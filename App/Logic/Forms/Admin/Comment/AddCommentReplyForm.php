<?php

namespace App\Logic\Forms\Admin\Comment;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CommentModel;
use App\Logic\Models\PaymentMethodModel;
use App\Logic\Models\ProductModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use voku\helper\AntiXSS;

class AddCommentReplyForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
        $validator
            ->setFieldsAlias([
                'inp-ans-comment-desc' => 'پاسخ',
            ]);

        // title
        $validator
            ->setFields('inp-ans-comment-desc')
            ->required();

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);
        /**
         * @var CommentModel $commentModel
         */
        $commentModel = container()->get(CommentModel::class);

        // check product id
        $pId = session()->getFlash('current-comment-product-id', null, false);
        if (!empty($pId)) {
            if (0 === $productModel->count('id=:id', ['id' => $pId])) {
                $validator->setError('inp-ans-comment-desc', 'شناسه محصول نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-ans-comment-desc', 'شناسه محصول نامعتبر است.');
        }

        // check comment id
        $id = session()->getFlash('current-comment-id', null, false);
        if (!empty($id)) {
            if (0 === $commentModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-ans-comment-desc', 'شناسه نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-ans-comment-desc', 'شناسه نظر نامعتبر است.');
        }

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getUniqueErrors(),
            $validator->getError(),
            $validator->getFormattedError('<p class="m-0">'),
            $validator->getFormattedUniqueErrors('<p class="m-0">'),
            $validator->getRawErrors(),
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
        /**
         * @var PaymentMethodModel $payModel
         */
        $payModel = container()->get(PaymentMethodModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $body = input()->post('inp-ans-comment-desc', '')->getValue();
            $pId = session()->getFlash('current-comment-product-id');
            $id = session()->getFlash('current-comment-id');

            return $payModel->update([
                'reply' => $xss->xss_clean(trim($body)),
                'reply_by' => $auth->getCurrentUser()['id'] ?? null,
                'reply_at' => time(),
            ], 'id=:id AND product_id=:pId', ['id' => $id, 'pId' => $pId]);
        } catch (\Exception $e) {
            return false;
        }
    }
}