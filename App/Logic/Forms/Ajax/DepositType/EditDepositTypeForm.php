<?php

namespace App\Logic\Forms\Ajax\DepositType;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\DepositTypeModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditDepositTypeForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws FormException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
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
                'inp-edit-deposit-type-title' => 'عنوان',
            ]);

        /**
         * @var DepositTypeModel $depositModel
         */
        $depositModel = container()->get(DepositTypeModel::class);

        // title
        // desc
        $validator
            ->setFields('inp-edit-deposit-type-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);

        $id = session()->getFlash('deposit-type-edit-id', null, false);
        if (!empty($id)) {
            if (0 === $depositModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-deposit-type-title', 'شناسه نوع تراکنش مورد نظر نامعتبر است.');
            } else {
                // check for duplicate
                $validator
                    ->custom(function (FormValue $value) use ($depositModel, $id) {
                        $title = $depositModel->getFirst(['title'], 'id=:id', ['id' => $id])['title'];
                        if (
                            $title != trim($value->getValue()) &&
                            0 === $depositModel->count('title=:title', ['title' => trim($value->getValue())])
                        ) {
                            return true;
                        }
                        return false;
                    }, '{alias} ' . 'وارد شده تکراری است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-deposit-type-title', 'شناسه نوع تراکنش مورد نظر نامعتبر است.');
        }

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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
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
            $id = session()->getFlash('deposit-type-edit-id', null);
            $title = input()->post('inp-edit-deposit-type-title', '')->getValue();

            return $depositModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}