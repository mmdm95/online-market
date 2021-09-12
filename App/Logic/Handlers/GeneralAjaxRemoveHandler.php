<?php

namespace App\Logic\Handlers;

use App\Logic\Models\Model;
use Sim\Interfaces\IHandler;

class GeneralAjaxRemoveHandler implements IHandler
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
     *   3. Extra where parameter: string [Default: '']
     *   4. Bind values for extra where parameter: array [Default: []]
     *   5. Ignore parameter to ignore id: bool [Default: false]
     *   6. Use softDelete: bool [Default: false]
     *   7. softDelete column: string [Default: 'is_deleted']
     *
     * @param mixed ...$_
     * @return ResourceHandler
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function handle(...$_): ResourceHandler
    {
        if (2 > count($_)) {
            throw new \InvalidArgumentException("Number of arguments is invalid. Expected 2, giving " . count($_));
        }

        [$table, $id] = $_;

        $where = $_[2] ?? '';
        $bindValues = $_[3] ?? [];
        $ignoreId = $_[4] ?? false;
        $useSoftDelete = $_[5] ?? false;
        $softDeleteColumn = $_[6] ?? 'is_deleted';

        $canContinue = true;
        if ($this->authCallback instanceof \Closure) {
            $canContinue = emitter()->dispatch('remove.general.ajax:auth', [&$this->resourceHandler])->getReturnValue();
        }

        if ($canContinue) {
            if (is_null($id) && !$ignoreId) {
                $this->resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage('شناسه آیتم نامعتبر است.');
                emitter()->dispatch('remove.general.ajax:invalid_id', [&$this->resourceHandler]);
            } else {
                /**
                 * @var Model $model
                 */
                $model = container()->get(Model::class);
                $select = $model->select();
                $select
                    ->from($table)
                    ->cols(['COUNT(*) AS count']);

                if (!$ignoreId) {
                    $select
                        ->where('id=:id')
                        ->bindValue('id', $id);
                }

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
                    emitter()->dispatch('remove.general.ajax:not_exists', [&$this->resourceHandler]);
                } else {
                    $emRes = emitter()->dispatch('remove.general.ajax:custom_handler', [&$this->resourceHandler]);

                    if (is_null($emRes->getReturnValue()) || (bool)$emRes->getReturnValue()) {
                        if (!$useSoftDelete) {
                            $query = $model->delete();
                            $query
                                ->from($table);
                        } else {
                            $query = $model->update();
                            $query
                                ->table($table)
                                ->set($softDeleteColumn, DB_YES);
                        }

                        if (!$ignoreId) {
                            $query
                                ->where('id=:id')
                                ->bindValue('id', $id);
                        }

                        if (!empty($where)) {
                            $query
                                ->where($where)
                                ->bindValues($bindValues);
                        }

                        $res = $model->execute($query);

                        if ($res) {
                            $this->resourceHandler
                                ->type(RESPONSE_TYPE_SUCCESS)
                                ->data('آیتم با موفقیت حذف شد.');
                            emitter()->dispatch('remove.general.ajax:success', [&$this->resourceHandler]);
                        } else {
                            $this->resourceHandler
                                ->type(RESPONSE_TYPE_ERROR)
                                ->errorMessage('امکان حذف این آیتم وجود ندارد.');
                            emitter()->dispatch('remove.general.ajax:failed', [&$this->resourceHandler]);
                        }
                    }
                }
            }
        }

        return $this->resourceHandler;
    }
}