<?php

namespace App\Logic\Forms\Ajax\Unit;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UnitModel;
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

class EditUnitForm implements IPageForm
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
        $validator->setFieldsAlias([
            'inp-add-unit-title' => 'عنوان واحد',
            'inp-add-unit-sign' => 'علامت',
        ])->setOptionalFields([
            'inp-edit-unit-sign'
        ]);

        /**
         * @var UnitModel $unitModel
         */
        $unitModel = container()->get(UnitModel::class);
        $id = session()->getFlash('unit-edit-id', null, false);

        // title
        $validator
            ->setFields('inp-edit-unit-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250)
            ->custom(function (FormValue $value) use ($id, $unitModel) {
                if (empty($id)) return false;
                $prevTitle = $unitModel->getFirst(['title'], 'id=:id', ['id' => $id])['title'];
                if (
                    $prevTitle != trim($value->getValue()) &&
                    0 !== $unitModel->count('title=:title', ['title' => trim($value->getValue())])
                ) {
                    return false;
                }
                return true;
            }, 'واحد با این عنوان وجود دارد.');
        // sign
        $validator
            ->setFields('inp-edit-unit-sign')
            ->required();

        if (!empty($id)) {
            if (0 === $unitModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-unit-title', 'شناسه واحد مورد نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-unit-title', 'شناسه واحد مورد نظر نامعتبر است.');
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
         * @var UnitModel $unitModel
         */
        $unitModel = container()->get(UnitModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('unit-edit-id', null);
            $title = input()->post('inp-edit-unit-title', '')->getValue();
            $sign = input()->post('inp-edit-unit-sign', '')->getValue();

            $res = $unitModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'sign' => $xss->xss_clean($sign),
                'updated_at' => time(),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
            ], 'id=:id', ['id' => $id]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}