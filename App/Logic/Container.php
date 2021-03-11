<?php

namespace App\Logic;

use App\Logic\Controllers\CartController;
use DI\ContainerBuilder;
use Sim\Auth\Interfaces\IAuth;
use Sim\Captcha\CaptchaFactory;
use Sim\Form\FormValidator;
use Sim\I18n\Translate as Translate;
use Sim\Interfaces\IInitialize;
use Sim\Container\Container as Resolver;
use Sim\Auth\DBAuth as Auth;
use Sim\Auth\APIAuth as APIAuth;
use Sim\SMS\Factories\NiazPardaz;
use Sim\SMS\SMSFactory;

class Container implements IInitialize
{
    /**
     * Container initializer
     */
    public function init()
    {
        // home authentication class
        \container()->set('auth_home', function () {
            $authConfig = \config()->get('auth');

            $auth = new Auth(\connector()->getPDO(), 'home', [
                'main' => \config()->get('env.APP_MAIN_KEY'),
                'assured' => \config()->get('env.APP_ASSURED_KEY'),
            ], PASSWORD_BCRYPT, IAuth::STORAGE_DB, $authConfig['structure']);

            $auth->setExpiration($authConfig['namespaces']['home']['expire_time'])
                ->setSuspendTime($authConfig['namespaces']['home']['suspend_time']);

            return $auth;
        });

        // admin authentication class
        \container()->set('auth_admin', function () {
            $authConfig = \config()->get('auth');

            $auth = new Auth(\connector()->getPDO(), 'admin', [
                'main' => \config()->get('env.APP_MAIN_KEY'),
                'assured' => \config()->get('env.APP_ASSURED_KEY'),
            ], PASSWORD_BCRYPT, IAuth::STORAGE_DB, $authConfig['structure']);

            $auth->setExpiration($authConfig['namespaces']['admin']['expire_time'])
                ->setSuspendTime($authConfig['namespaces']['admin']['suspend_time']);

            return $auth;
        });

        // api authentication class
        \container()->set('auth_api', function () {
            return new APIAuth(\connector()->getPDO(), \config()->get('auth.structure'));
        });

        // form validator class
        \container()->set(FormValidator::class, function (Resolver $resolver) {
            /**
             * @var Translate $translate
             */
            $translate = $resolver->get(Translate::class);

            $settings = $translate->translate('form_translation');
            $settings = is_array($settings) ? $settings : [];

            $formValidator = new FormValidator();
            $formValidator->setLang($translate->getLanguage())->setLangSettings($settings);

            return $formValidator;
        });

        // simple captcha class
        \container()->set('captcha-simple', function () {
            $captchaConfig = config()->get('captcha');
            $cp = \captcha();
            $cp->setExpiration($captchaConfig['expiration'] ?? 600)
                ->setLength($captchaConfig['length'] ?: 6)
                ->setDifficulty($captchaConfig['difficulty'] ?? CaptchaFactory::DIFFICULTY_NORMAL)
                ->addNoise($captchaConfig['noise'] ?? false);
            if (!empty($captchaConfig['font'])) {
                $cp->setFont($captchaConfig['font']);
            }
            return $cp;
        });

        // sms panel
        \container()->set('sms_panel', function () {
            $config = config()->get('sms.niaz');

            /**
             * @var NiazPardaz $panel
             */
            $panel = new NiazPardaz($config['username'], $config['password']);
            $panel->fromNumber($config['from']);

            return $panel;
        });

//        /**
//         * @var Auth $t
//         */
//        $t = \container()->get('auth_admin');
//        $t->runConfig();
//
//        /**
//         * @var HitCounter $tt
//         */
//        $tt = \container()->get(HitCounter::class);
//        $tt->runConfig();
//
//        /**
//         * @var Cart $ttt
//         */
//        $ttt = \container()->get(Cart::class);
//        $ttt->utils()->runConfig();
    }
}