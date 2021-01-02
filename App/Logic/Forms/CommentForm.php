<?php

namespace App\Logic\Forms;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CommentModel;
use App\Logic\Models\ProductModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use voku\helper\AntiXSS;

class CommentForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws FormException
     */
    public function validate(): array
    {
        /**
         * @var ExtendedValidator $validator
         */
        $validator = form_validator();
        $validator->reset();

        // aliases
        $validator->setFieldsAlias([
            'inp-comment-name' => 'نام',
            'inp-comment-message' => 'متن نظر',
        ]);
        // name
        $validator
            ->setFields('inp-comment-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // message
        $validator
            ->setFields('inp-comment-message')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true);

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        $product = $productModel->get(
            ['allow_commenting'],
            'id=:id',
            ['id' => session()->getFlash('comment_product_id', 0, false)]
        );
        if (0 === count($product)) {
            $validator->setStatus(false)->setError('inp-comment-message', 'کالای مورد نظر وجود ندارد!');
        } elseif (DB_YES == $product[0]['allow_commenting']) {
            $validator->setStatus(false)->setError('inp-comment-message', 'امکان ارسال نظر برای این محصول، وجود ندارد.');
        }

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getFormattedUniqueErrors('<p class="m-0">'),
        ];
    }

    /**
     * {@inheritdoc}
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function store(): bool
    {
        $res = false;
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        /**
         * @var CommentModel $commentModel
         */
        $commentModel = container()->get(CommentModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $name = input()->post('inp-comment-name', '')->getValue();
            $message = input()->post('inp-comment-message', '')->getValue();
            $userId = $auth->getCurrentUser()['id'] ?? 0;
            $productId = session()->getFlash('comment_product_id', 0);
            // if user is logged in, fetch his info
            if ($auth->isLoggedIn()) {
                $user = $userModel->get(['first_name']);
                $user = count($user) ? $user[0] : [];
                //-----
                // insert name if it's empty in user's first name
                if (!isset($user['first_name']) || empty($user['first_name'])) {
                    $userModel->update([
                        'first_name' => $name,
                    ], 'id=:id', ['id' => $userId]);
                }

                // insert to database
                $res = $commentModel->insert([
                    'product_id' => $productId,
                    'user_id' => $userId,
                    'body' => $xss->xss_clean($message),
                    'status' => COMMENT_STATUS_NOT_READ,
                    'sent_at' => time(),
                ]);
            }
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }
}