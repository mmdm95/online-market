<?php

namespace App\Logic\Forms\Admin\ReturnOrder;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ReturnOrderModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ReturnOrderRespondForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
     * @throws ConfigNotRegisteredException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
                'inp-return-order-respond' => 'پاسخ مرجوعی',
            ]);

        /**
         * @var ReturnOrderModel $returnModel
         */
        $returnModel = container()->get(ReturnOrderModel::class);

        $id = session()->getFlash('curr_return_order_detail_id', null, false);
        if (!empty($id) && $returnModel->getReturnOrdersCount('ro.id=:id', ['id' => $id]) > 0) {
            // description
            $validator
                ->setFields('inp-return-order-respond')
                ->lessThanEqualLength(500);
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-return-order-respond', 'شناسه سفارش مرجوعی نامعتبر است.');
        }

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getError(),
            $validator->getUniqueErrors(),
            $validator->getFormattedError('<p class="m-0">'),
            $validator->getFormattedUniqueErrors('<p class="m-0">'),
            $validator->getRawErrors(),
        ];
    }

    /**
     * {@inheritdoc}
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function store(): bool
    {
        /**
         * @var ReturnOrderModel $returnOrderModel
         */
        $returnOrderModel = container()->get(ReturnOrderModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('curr_return_order_detail_id', null);
            $respond = input()->post('inp-return-order-respond', '')->getValue();

            if (is_null($id)) return false;

            return $returnOrderModel->update([
                'respond' => $xss->xss_clean($respond),
                'respond_at' => time(),
                'respond_by' => $auth->getCurrentUser()['id'] ?? null,
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
