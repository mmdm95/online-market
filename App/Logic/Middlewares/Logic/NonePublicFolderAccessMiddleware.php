<?php

namespace App\Logic\Middlewares\Logic;

use App\Logic\Models\UserModel;
use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;
use Sim\Auth\DBAuth;

class NonePublicFolderAccessMiddleware extends AbstractMiddleware
{
    /**
     * @param mixed ...$_
     * @return bool
     */
    public function handle(...$_): bool
    {
//        [$filename] = $_;
//
//        $filename = str_replace(['\\', '//'], '/', $filename);
//        $filename = explode('/', trim($filename, '\\/'))[0];
//
//        /**
//         * @var DBAuth $auth
//         */
//        $auth = \container()->get('auth_home');
//
//        /**
//         * @var DBAuth $authAdmin
//         */
//        $authAdmin = \container()->get('auth_admin');
//
//        /**
//         * @var UserModel $userModel
//         */
//        $userModel = \container()->get(UserModel::class);
//        $username = $userModel->getUsernameFromID($auth->getCurrentUser()['id'] ?? null);
//
//        if (
//            $filename !== '' &&
//            !$authAdmin->isLoggedIn() &&
//            (
//                !$auth->isLoggedIn() ||
//                (
//                    $auth->isLoggedIn() &&
//                    PUBLIC_ACCESS_DIR !== $filename &&
//                    $filename !== $username
//                )
//            )) {
//            return false;
//        }

        return parent::handle(...$_);
    }
}