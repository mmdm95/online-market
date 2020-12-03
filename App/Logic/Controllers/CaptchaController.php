<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Utils\CaptchaUtil;
use Sim\Captcha\Exceptions\CaptchaException;
use Sim\Captcha\Interfaces\ICaptchaException;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;

class CaptchaController extends AbstractHomeController
{
    /**
     * @throws \ReflectionException
     * @throws CaptchaException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     */
    public function generateCaptcha()
    {
        $name = '';
        if (!empty(input()->get('name', ''))) {
            $name = input()->get('name', '');
        }
        $captcha = CaptchaUtil::get($name);

        $data = new ResourceHandler();
        $data->data($captcha);
        response()->json($data->getReturnData());
    }

    /**
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws ICaptchaException
     */
    public function verifyCaptcha()
    {
        $name = '';
        if (!empty(input()->post('name', ''))) {
            $name = input()->post('name', '');
        }
        $res = CaptchaUtil::verify(input()->post('captcha', ''), $name);

        $data = new ResourceHandler();
        if ($res) {
            response()->httpCode(204);
        } else {
            $data->errorMessage('کد وارد شده در تصویر نادرست است.');
        }
        response()->json($data->getReturnData());
    }
}