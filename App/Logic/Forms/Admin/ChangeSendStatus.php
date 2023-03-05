<?php

namespace App\Logic\Forms\Admin;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\OrderBadgeModel;
use App\Logic\Models\OrderModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ChangeSendStatus implements IPageForm
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
                'inp-change-order-send-status' => 'وضعیت ارسال',
            ]);

        // status
        $validator
            ->setFields('inp-change-order-send-status')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var OrderBadgeModel $badgeModel
                 */
                $badgeModel = container()->get(OrderBadgeModel::class);

                if (0 !== $badgeModel->count('code=:code', ['code' => $value->getValue()])) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'نامعتبر است.');

        // check for id is not necessary here, but you can do it when needed
        // ...

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
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function store(): bool
    {
        /**
         * @var OrderModel $orderModel
         */
        $orderModel = container()->get(OrderModel::class);
        /**
         * @var OrderBadgeModel $badgeModel
         */
        $badgeModel = container()->get(OrderBadgeModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('curr_order_detail_id', null);
            $code = input()->post('inp-change-order-send-status', '')->getValue();
            $badge = $badgeModel->getFirst(['title', 'color', 'should_return_order_product'], 'code=:code', ['code' => $code]);
            $order = $orderModel->getFirst(['code', 'mobile'], 'id=:id', ['id' => $id]) ?? null;

            if (is_null($order)) return false;

            session()->setFlash('send.status.title', $badge['title']);
            session()->setFlash('send.status.username', $order['mobile']);
            session()->setFlash('send.status.order_code', $order['code']);

            return $orderModel->updateSendStatusForOrder([
                'send_status_code' => $xss->xss_clean(trim($code)),
                'send_status_title' => $xss->xss_clean(trim($badge['title'])),
                'send_status_color' => $xss->xss_clean(trim($badge['color'])),
                'send_status_changed_by' => $auth->getCurrentUser()['id'] ?? null,
                'send_status_changed_at' => time(),
            ], $id, $badge['should_return_order_product'] == DB_YES);
        } catch (\Exception $e) {
            return false;
        }
    }
}
