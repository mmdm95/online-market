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

class AddUnitForm implements IPageForm
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
                'inp-add-unit-title' => 'عنوان واحد',
                'inp-add-unit-sign' => 'علامت',
            ])
            ->setOptionalFields([
                'inp-add-unit-sign',
            ]);

        // title
        $validator
            ->setFields('inp-add-unit-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250)
            ->custom(function (FormValue $value) {
                /**
                 * @var UnitModel $unitModel
                 */
                $unitModel = container()->get(UnitModel::class);
                if (
                    0 !== $unitModel->count('title=:title', ['title' => trim($value->getValue())])
                ) {
                    return false;
                }
                return true;
            }, 'واحد با این عنوان وجود دارد.');
        // sign
        $validator
            ->setFields('inp-add-unit-sign')
            ->lessThanEqualLength(250);

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
            $title = input()->post('inp-add-unit-title', '')->getValue();
            $sign = input()->post('inp-add-unit-sign', '')->getValue();

            $res = $unitModel->insert([
                'title' => $xss->xss_clean(trim($title)),
                'sign' => $xss->xss_clean(trim($sign)),
                'created_at' => time(),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}