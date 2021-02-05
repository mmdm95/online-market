<?php

namespace App\Logic\Handlers;

use App\Logic\Models\Model;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Interfaces\IHandler;

class GeneralAjaxMultiStatusHandler implements IHandler
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
    private $statusDefaultMessage = 'وضعیت آیتم بروزرسانی شد.';

    /**
     * @var array
     */
    private $statusMessageMap = [];

    /**
     * @var array
     */
    private $statusCheckArr = [];

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
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
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
            $canContinue = emitter()->dispatch('multi.status.general.ajax:auth', [&$this->resourceHandler])->getReturnValue();
        }

        if (!empty($this->statusCheckArr) && !in_array($status, $this->statusCheckArr)) {
            $this->resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('وضعیت ارسال شده نامعتبر است.');
            emitter()->dispatch('multi.status.general.ajax:invalid_status', [&$this->resourceHandler]);
        } else {
            if ($canContinue) {
                if (is_null($id)) {
                    $this->resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('شناسه آیتم نامعتبر است.');
                    emitter()->dispatch('multi.status.general.ajax:invalid_id', [&$this->resourceHandler]);
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
                        emitter()->dispatch('multi.status.general.ajax:not_exists', [&$this->resourceHandler]);
                    } else {
                        $emRes = emitter()->dispatch('multi.status.general.ajax:before_update', [&$this->resourceHandler]);
                        if (is_null($emRes) || (bool)$emRes->getReturnValue()) {
                            $update = $model->update();
                            $update
                                ->table($table)
                                ->cols([
                                    $column => $status,
                                ])
                                ->where('id=:id')
                                ->bindValue('id', $id);
                            $res = $model->execute($update);
                            if ($res) {
                                $this->resourceHandler
                                    ->type(RESPONSE_TYPE_SUCCESS)
                                    ->data($this->getStatusMessage($status));
                                emitter()->dispatch('multi.status.general.ajax:success', [&$this->resourceHandler]);
                            } else {
                                $this->resourceHandler
                                    ->type(RESPONSE_TYPE_ERROR)
                                    ->errorMessage('امکان تغییر وضعیت این آیتم وجود ندارد.');
                                emitter()->dispatch('multi.status.general.ajax:failed', [&$this->resourceHandler]);
                            }
                        }
                    }
                }
            }
        }

        return $this->resourceHandler;
    }

    /**
     * @param array $status_array
     * @return static
     */
    public function setStatusArray(array $status_array)
    {
        $this->statusCheckArr = $status_array;
        return $this;
    }

    /**
     * @param array $map_status_message
     * @return static
     */
    public function setStatusMessage(array $map_status_message)
    {
        $this->statusMessageMap = $map_status_message;
        return $this;
    }

    /**
     * @param $status
     * @return string
     */
    private function getStatusMessage($status): string
    {
        foreach ($this->statusCheckArr as $s => $msg) {
            if ($s === $status) {
                $message = (string)$msg;
                if (!empty($message)) return $message;
                break;
            }
        }
        return $this->statusDefaultMessage;
    }
}