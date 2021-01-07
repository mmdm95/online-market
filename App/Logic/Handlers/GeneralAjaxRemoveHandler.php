<?php

namespace App\Logic\Handlers;

use App\Logic\Models\Model;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Interfaces\IHandler;

class GeneralAjaxRemoveHandler implements IHandler
{
    const TYPE_ERROR_INVALID_ID = 1;
    const TYPE_ERROR_NOT_EXISTS = 2;
    const TYPE_ERROR_FAILED = 3;

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
     *   [3. Extra where parameter: string]
     *   [4. Bind values for extra where parameter: array]
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
        if (2 > count($_)) {
            throw new \InvalidArgumentException("Number of arguments is invalid. Expected 2, giving " . count($_));
        }

        [$table, $id] = $_;

        $where = $_[2] ?? '';
        $bindValues = $_[3] ?? [];

        $canContinue = true;
        if ($this->authCallback instanceof \Closure) {
            $canContinue = emitter()->dispatch('remove.general.ajax:auth', [&$this->resourceHandler])->getReturnValue();
        }

        if ($canContinue) {
            if (is_null($id)) {
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
                    emitter()->dispatch('remove.general.ajax:not_exists', [&$this->resourceHandler]);
                } else {
                    $delete = $model->delete();
                    $delete
                        ->from($table)
                        ->where('id=:id')
                        ->bindValue('id', $id);
                    $res = $model->execute($delete);
                    if ($res) {
                        $this->resourceHandler
                            ->type(RESPONSE_TYPE_SUCCESS)
                            ->errorMessage('آیتم با موفقیت حذف شد.');
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

        return $this->resourceHandler;
    }
}