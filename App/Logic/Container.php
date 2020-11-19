<?php

namespace App\Logic;

use Sim\Auth\Interfaces\IAuth;
use Sim\Cart\Cart as Cart;
use Sim\Cookie\Cookie as Cookie;
use Sim\Crypt\Crypt as Crypt;
use Sim\Form\FormValidator;
use Sim\HitCounter\HitCounter;
use Sim\I18n\Translate as Translate;
use Sim\Interfaces\IInitialize;
use Sim\Container\Container as Resolver;
use Sim\Auth\DBAuth as Auth;
use Sim\Auth\APIAuth as APIAuth;
use Sim\Session\Session as Session;

class Container implements IInitialize
{
    /**
     * Container initializer
     */
    public function init()
    {
        // crypt class
        \container()->set(Crypt::class, function () {
            return new Crypt(config()->get('security.main_key'),
                config()->get('security.assured_key'));
        });

        // session class
        \container()->set(Session::class, function (Resolver $c) {
            return new Session($c->get(Crypt::class));
        });

        // cookie class
        \container()->set(Cookie::class, function (Resolver $c) {
            return new Cookie($c->get(Crypt::class));
        });

        // translator class
        \container()->set(Translate::class, function () {
            $translate = new Translate();
            $translateConfig = config()->get('i18n');
            if (!is_null($translateConfig)) {
                /** @var array $translateConfig */
                $translate->setTranslateDir($translateConfig['language_dir'] ?? '')
                    ->setLocale($translateConfig['language'] ?? '');

                if ((bool)config()->get('i18n.is_rtl') === true) {
                    $translate->itIsRTL();
                }
            }

            return $translate;
        });

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

        // cart class
        \container()->set(Cart::class, function (Resolver $resolver) {
            $cookie = $resolver->get(Cookie::class);
            return new Cart(\connector()->getPDO(), $cookie, 0, \config()->get('cart.structure'));
        });

        // hit counter class
        \container()->set(HitCounter::class, function () {
            return new HitCounter(\connector()->getPDO(), \config()->get('hit.structure'));
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