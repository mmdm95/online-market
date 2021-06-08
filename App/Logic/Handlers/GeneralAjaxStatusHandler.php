<?php

namespace App\Logic\Handlers;

use App\Logic\Models\Model;
use Sim\Interfaces\IHandler;

class GeneralAjaxStatusHandler implements IHandler
{
    /**
     * @var \Closure|null
     */
    private $authCallback = null;

    /**
     * @var ResourceHandler
     */
    private $resourceHandler;

    /**
     * @var string
     */
    private $statusCheckedMessage = 'وضعیت آیتم فعال شد.';

    /**
     * @var string
     */
    private $statusUncheckedMessage = 'وضعیت آیتم غیر فعال شد.';

    /**
     * GeneralRemoveHandler constructor.
     */
    public function __construct()
    {
        $this->resourceHandler = new ResourceHandler();
    }

    /**
     * Handle all deletions in same manner
     * Steps:
     *   1. Send a [Table_Name] as first parameter.
     *   2. Send a [id] as second parameter and get
     *      a resource handler as return array.
     *   3. Column of status to change: string
     *   4. Status to change: int|string
     *   5. Extra where parameter: string
     *   6. Bind values for extra where parameter: array
     *
     * @param mixed ...$_
     * @return ResourceHandler
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function handle(...$_): ResourceHandler
    {
        if (4 > count($_)) {
            throw new \InvalidArgumentException("Number of arguments is invalid. Expected 4, giving " . count($_));
        }

        [$table, $id, $column, $status] = $_;

        $where = $_[4] ?? '';
        $bindValues = $_[5] ?? [];

        $canContinue = true;
        if ($this->authCallback instanceof \Closure) {
            $canContinue = emitter()->dispatch('status.general.ajax:auth', [&$this->resourceHandler])->getReturnValue();
        }

        if ($canContinue) {
            if (is_null($id)) {
                $this->resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('شناسه آیتم نامعتبر است.');
                emitter()->dispatch('status.general.ajax:invalid_id', [&$this->resourceHandler]);
            } else {
                /**
                 * @var Model $model
                 */
                $model = container()->get(Model::class);
                $select = $model->select();
                $select
                    ->from($table)
                    ->cols(['COUNT(*) AS count'])
                    ->where('id=:id')
                    ->bindValue('id', $id);

                if (!empty($where)) {
                    $select
                        ->where($where)
                        ->bindValues($bindValues);
                }

                $res = $model->get($select);

                if (!count($res) || 0 === (int)$res[0]['count']) {
                    $this->resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('آیتم مورد نظر وجود ندارد.');
                    emitter()->dispatch('status.general.ajax:not_exists', [&$this->resourceHandler]);
                } else {
                    $emRes = emitter()->dispatch('status.general.ajax:before_update', [&$this->resourceHandler]);

                    if (is_null($emRes->getReturnValue()) || (bool)$emRes->getReturnValue()) {
                        $update = $model->update();
                        $update
                            ->table($table)
                            ->cols([
                                $column => is_value_checked($status) ? DB_YES : DB_NO,
                            ])
                            ->where('id=:id')
                            ->bindValue('id', $id);
                        $res = $model->execute($update);

                        if ($res) {
                            if (is_value_checked($status)) {
                                $this->resourceHandler
                                    ->type(RESPONSE_TYPE_SUCCESS)
                                    ->data($this->statusCheckedMessage);
                            } else {
                                $this->resourceHandler
                                    ->type(RESPONSE_TYPE_WARNING)
                                    ->data($this->statusUncheckedMessage);
                            }
                            emitter()->dispatch('status.general.ajax:success', [&$this->resourceHandler]);
                        } else {
                            $this->resourceHandler
                                ->type(RESPONSE_TYPE_ERROR)
                                ->errorMessage('امکان تغییر وضعیت این آیتم وجود ندارد.');
                            emitter()->dispatch('status.general.ajax:failed', [&$this->resourceHandler]);
                        }
                    }
                }
            }
        }

        return $this->resourceHandler;
    }

    /**
     * @param string $message
     * @return static
     */
    public function setStatusCheckedMessage(string $message)
    {
        if (!empty($message)) {
            $this->statusCheckedMessage = $message;
        }
        return $this;
    }

    /**
     * @param string $message
     * @return static
     */
    public function setStatusUncheckedMessage(string $message)
    {
        if (!empty($message)) {
            $this->statusUncheckedMessage = $message;
        }
        return $this;
    }
}