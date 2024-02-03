<?php

namespace App\Logic\Forms\User\ReturnOrder;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\OrderModel;
use App\Logic\Models\ReturnOrderModel;
use App\Logic\Utils\ReturnOrderUtil;
use App\Logic\Validations\ExtendedValidator;
use Pecee\Http\Input\InputItem;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ReturnOrderForm implements IPageForm
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
                'inp-return-order-desc' => 'علت مرجوعی',
                'inp-order-item-id.*' => 'شناسه آیتم سفارش',
                'inp-order-item-check.*' => 'گزینه انتخاب جهت مرجوعی',
                'inp-return-item-quantity.*' => 'تعداد آیتم مرجوعی',
            ])->setOptionalFields([
                'inp-order-item-check.*',
            ]);

        // description
        $validator
            ->setFields('inp-return-order-desc')
            ->lessThanEqualLength(500);

        $itemIds = input()->post('inp-order-item-id');
        $quantities = input()->post('inp-return-item-quantity');
        $checkedItems = input()->post('inp-order-item-check');

        $warnMsg = 'هیچ آیتمی جهت مرجوع انتخاب نشده است.';

        if (!is_array($itemIds) || !is_array($checkedItems) || !is_array($quantities)) {
            $validator
                ->setStatus(false)
                ->setError('inp-return-item-quantity.*', $warnMsg);
        }

        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);

        // assemble returning order items
        $assembled = [];
        /**
         * @var InputItem $checkedItem
         */
        foreach ($checkedItems as $k => $checkedItem) {
            $id = is_array($itemIds) && isset($itemIds[$k]) ? $itemIds[$k]->getValue() : -1;
            $qnt = is_array($quantities) && isset($quantities[$k]) ? $quantities[$k]->getValue() : 0;

            if ($id != -1 && $qnt > 0 && $orderModel->getOrderItemsCount('oi.id=:id AND oi.is_returnable=:ir', ['id' => $id, 'ir' => DB_YES])) {
                $assembled[] = [
                    'order_item_id' => $id,
                    'product_count' => $qnt
                ];
            }
        }

        if (!count($assembled)) {
            $validator
                ->setStatus(false)
                ->setError('inp-return-item-quantity.*', $warnMsg);
        }

        session()->setFlash('assembled-return-order-items', $assembled);

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
        $auth = auth_home();

        try {
            $orderCode = session()->getFlash('return-order-submit-code', null);
            $desc = input()->post('inp-return-order-desc', '')->getValue();

            if (is_null($orderCode)) return false;

            $items = session()->getFlash('assembled-return-order-items');

            return $returnOrderModel->modifyReturnOrderItems([
                'user_id' => $auth->getCurrentUser()['id'],
                'order_code' => $orderCode,
                'code' => ReturnOrderUtil::getUniqueReturnOrderCode(20),
                'desc' => $xss->xss_clean($desc),
                'status' => RETURN_ORDER_STATUS_CHECKING,
                'requested_at' => time(),
            ], $items);
        } catch (\Exception $e) {
            return false;
        }
    }
}
