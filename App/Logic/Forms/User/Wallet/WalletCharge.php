<?php

namespace App\Logic\Forms\User\Wallet;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\DepositTypeModel;
use App\Logic\Models\UserModel;
use App\Logic\Models\WalletFlowModel;
use App\Logic\Models\WalletModel;
use App\Logic\Utils\WalletChargeUtil;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class WalletCharge implements IPageForm
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
                'inp-wallet-price' => 'قیمت شارژ',
            ])
            ->toEnglishValueFields([
                'inp-wallet-price'
            ], true);

        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);

        // price
        $validator
            ->setFields('inp-wallet-price')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();

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
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        /**
         * @var WalletFlowModel $walletFlowModel
         */
        $walletFlowModel = container()->get(WalletFlowModel::class);
        /**
         * @var DepositTypeModel $depositTypes
         */
        $depositTypes = container()->get(DepositTypeModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $userId = $auth->getCurrentUser()['id'] ?? null;

            if(is_null($userId)) return false;

            $username = $userModel->getFirst(['username'], 'id=:id', ['id' => $userId])['username'] ?? null;
            $price = input()->post('inp-wallet-price', 0)->getValue();
            //
            $desc = $depositTypes->getFirst(['title'], 'code=:code', ['code' => DEPOSIT_TYPE_CHARGE]);

            if (0 >= $price || count($desc) == 0 || is_null($username)) {
                return false;
            }

            $code = WalletChargeUtil::getUniqueWalletOrderCode();

            $arr = [
                'order_code' => $code,
                'username' => $username,
                'deposit_price' => $xss->xss_clean(trim($price)),
                'deposit_type_code' => DEPOSIT_TYPE_CHARGE,
                'deposit_type_title' => $xss->xss_clean(trim($desc['title'])),
                'payer_id' => $userId,
                'deposit_at' => time(),
            ];

            session()->set(SESSION_WALLET_CHARGE_ARR_INFO, $arr);

            return $walletFlowModel->insert($arr);
        } catch (\Exception $e) {
            return false;
        }
    }
}
