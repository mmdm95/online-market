<?php

namespace App\Logic\Forms\Ajax\DepositType;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\DepositTypeModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddDepositTypeForm implements IPageForm
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
                'inp-add-deposit-type-title' => 'عنوان',
                'inp-add-deposit-type-desc' => 'توضیح',
            ]);

        // title
        // desc
        $validator
            ->setFields([
                'inp-add-deposit-type-title',
                'inp-add-deposit-type-desc',
            ])
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);

        // check for duplicate
        $validator
            ->setFields('inp-add-deposit-type-title')
            ->custom(function (FormValue $value) {
                /**
                 * @var DepositTypeModel $depositModel
                 */
                $depositModel = container()->get(DepositTypeModel::class);

                if (0 === $depositModel->count('title=:title', ['title' => trim($value->getValue())])) {
                    return true;
                }
                return false;
            }, '{alias} ' . 'وارد شده تکراری است.');

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getUniqueErrors(),
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
         * @var DepositTypeModel $depositModel
         */
        $depositModel = container()->get(DepositTypeModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $title = input()->post('inp-add-deposit-type-title', '')->getValue();
            $desc = input()->post('inp-add-deposit-type-desc', '')->getValue();

            return $depositModel->insert([
                'code' => StringUtil::uniqidReal(12),
                'title' => $xss->xss_clean(trim($title)),
                'desc' => $xss->xss_clean(trim($desc)),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}