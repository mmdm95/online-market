<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Utils\CaptchaUtil;
use Sim\Captcha\Exceptions\CaptchaException;
use Sim\Captcha\Interfaces\ICaptchaException;

class CaptchaController extends AbstractHomeController
{
    /**
     * @throws CaptchaException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function generateCaptcha()
    {
        $name = '';
        $inp = input()->get('name', '');
        if (!is_array($inp) && !empty($inp)) {
            $name = input()->get('name', '')->getValue();
        }
        $captcha = CaptchaUtil::get($name);

        $data = new ResourceHandler();
        $data->data($captcha);
        response()->json($data->getReturnData());
    }

    /**
     * @throws ICaptchaException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function verifyCaptcha()
    {
        $name = '';
        $inp = input()->post('name', '');
        if (!is_array($inp) && !empty($inp)) {
            $name = input()->post('name', '')->getValue();
        }
        $captcha = '';
        $inp = input()->post('inp-captcha-name', '');
        if (!is_array($inp) && !empty($inp)) {
            $captcha = input()->post('inp-captcha-name', '')->getValue();
        }
        $res = CaptchaUtil::verify($captcha, $name);

        $data = new ResourceHandler();
        if ($res) {
            response()->httpCode(204);
        } else {
            $data->errorMessage('کد وارد شده در تصویر نادرست است.');
        }
        response()->json($data->getReturnData());
    }
}