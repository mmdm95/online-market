<?php

namespace App\Logic\Middlewares\Logic;

use Sim\Abstracts\Mvc\Controller\Middleware\AbstractMiddleware;

class PublicFolderModifyMiddleware extends AbstractMiddleware
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
//        $filename = trim($filename, '\\/');
//        $dir = explode('/', $filename)[0];
//
//        /**
//         * @var DBAuth $auth
//         */
//        $auth = \container()->get('auth_home');
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
//            PUBLIC_ACCESS_DIR === $filename ||
//            (
//                !$authAdmin->isLoggedIn() &&
//                (
//                    !$auth->isLoggedIn() ||
//                    (
//                        $auth->isLoggedIn() &&
//                        $dir !== $username
//                    )
//                )
//            )
//        ) {
//            return false;
//        }

        return parent::handle(...$_);
    }
}