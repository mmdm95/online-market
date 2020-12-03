<?php

namespace App\Logic\Utils;

use App\Logic\Models\Model;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;

class ConfigUtil
{
    /**
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws IInvalidVariableNameException
     */
    public function pullConfig()
    {
        /**
         * @var Model $model
         */
        $model = \container()->get(Model::class);
        $select = $model->select();
        $select->from('settings')->cols(['*']);
        $config = $model->get($select);
        $config = ArrayUtil::arrayGroupBy('setting_name', $config, ['setting_name'], true);
        $config = array_map(function ($value) {
            $arr = $value[0];
            $arr['value'] = !empty(trim($arr['setting_value'])) ? $arr['setting_value'] : $arr['default_value'];

            // decode setting if it is encoded json
            $decoded = json_decode($arr['value'], true);
            if (is_string($arr['value']) && is_array($decoded)) {
                $arr['value'] = $decoded;
            }

            unset($arr['setting_value']);
            return $arr;
        }, $config);
        \config()->setAsConfig('settings', $config);
    }
}