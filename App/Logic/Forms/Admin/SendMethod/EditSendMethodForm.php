<?php

namespace App\Logic\Forms\Admin\SendMethod;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SendMethodModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditSendMethodForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
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
                'inp-edit-send-method-img' => 'تصویر',
                'inp-edit-send-method-title' => 'عنوان',
                'inp-edit-send-method-desc' => 'توضیحات',
            ])
            ->setOptionalFields([
                'inp-edit-send-method-desc',
            ]);

        // title
        $validator
            ->setFields('inp-edit-send-method-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // image
        $validator
            ->setFields('inp-edit-send-method-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists();
        // desc
        $validator
            ->setFields('inp-edit-send-method-desc')
            ->lessThanEqualLength(250);

        /**
         * @var SendMethodModel $sendModel
         */
        $sendModel = container()->get(SendMethodModel::class);

        $id = session()->getFlash('send-method-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $sendModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-send-method-title', 'شناسه روش ارسال نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-send-method-title', 'شناسه روش ارسال نامعتبر است.');
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
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     */
    public function store(): bool
    {
        /**
         * @var SendMethodModel $sendModel
         */
        $sendModel = container()->get(SendMethodModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $image = input()->post('inp-edit-send-method-img', '')->getValue();
            $title = input()->post('inp-edit-send-method-title', '')->getValue();
            $desc = input()->post('inp-edit-send-method-desc', '')->getValue();
            $pub = input()->post('inp-edit-send-method-status', '')->getValue();
            $id = session()->getFlash('send-method-curr-id', null);
            if (is_null($id)) return false;

            return $sendModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'desc' => $xss->xss_clean(trim($desc)),
                'image' => $xss->xss_clean(get_image_name($image)),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}