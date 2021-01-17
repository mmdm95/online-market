<?php

namespace App\Logic\Forms\Ajax\SecurityQuestion;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\UnitModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use voku\helper\AntiXSS;

class EditSecurityQuestionForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
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
        $validator->setFieldsAlias([
            'inp-edit-unit-title' => 'نام گیرنده',
            'inp-edit-unit-sign' => 'موبایل',
        ])->setOptionalFields([
            'inp-edit-unit-sign'
        ]);

        // title
        $validator
            ->setFields('inp-edit-unit-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // sign
        $validator
            ->setFields('inp-edit-unit-sign')
            ->required();

        $id = session()->getFlash('unit-edit-id', null, false);
        if (!empty($id)) {
            /**
             * @var UnitModel $unitModel
             */
            $unitModel = container()->get(UnitModel::class);

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
            $validator->getFormattedUniqueErrors('<p class="m-0">'),
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