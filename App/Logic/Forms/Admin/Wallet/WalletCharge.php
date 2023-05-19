<?php

namespace App\Logic\Forms\Admin\Wallet;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\DepositTypeModel;
use App\Logic\Models\WalletModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
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
                'inp-charge-wallet-price' => 'قیمت شارژ',
                'inp-charge-wallet-desc' => 'توضیح شارژ',
            ])
            ->toEnglishValueFields([
                'inp-charge-wallet-price'
            ], true);

        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);

        // price
        $validator
            ->setFields('inp-charge-wallet-price')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();
        // desc
        $validator
            ->setFields('inp-charge-wallet-desc')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var DepositTypeModel $depositModel
                 */
                $depositModel = container()->get(DepositTypeModel::class);

                if (0 !== $depositModel->count('id=:id', ['id' => $value->getValue()])) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'نامعتبر است.');

        // check for id is not necessary here, but you can do it when needed
        // ...
        $id = session()->getFlash('wallet_charge_curr_id', null, false);
        if (!empty($id)) {
            $count = $walletModel->count('id=:id', ['id' => $id]);
            if (0 === $count) {
                $validator->setError('inp-charge-wallet-price', 'شناسه کیف پول نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-charge-wallet-price', 'شناسه کیف پول نامعتبر است.');
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
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function store(): bool
    {
        /**
         * @var WalletModel $walletModel
         */
        $walletModel = container()->get(WalletModel::class);
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
            $id = session()->getFlash('wallet_charge_curr_id', null);
            $username = session()->getFlash('wallet_charge_curr_username', null);
            $price = input()->post('inp-charge-wallet-price', 0)->getValue();
            //
            $descId = input()->post('inp-charge-wallet-desc', 0)->getValue();
            $desc = $depositTypes->getFirst(['code', 'title'], 'id=:id', ['id' => $descId]);

            if (count($desc) == 0) {
                return false;
            }

            $price = (int)$xss->xss_clean(trim($price));
            $balancePhrase = $price > 0 ? 'balance+' . $price : 'balance-' . abs($price);

            return $walletModel->chargeWalletWithWalletId($id, [
                'username' => $xss->xss_clean(trim($username)),
            ], [
                'order_code' => StringUtil::uniqidReal(12),
                'username' => $xss->xss_clean(trim($username)),
                'deposit_price' => $xss->xss_clean(trim($price)),
                'deposit_type_code' => $xss->xss_clean(trim($desc['code'])),
                'deposit_type_title' => $xss->xss_clean(trim($desc['title'])),
                'payer_id' => $auth->getCurrentUser()['id'] ?? null,
                'deposit_at' => time(),
            ], [
                'balance' => $balancePhrase,
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
