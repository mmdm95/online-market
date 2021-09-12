<?php

namespace App\Logic\Forms\User\Info;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SecurityQuestionModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ChangeUserOtherForm implements IPageForm
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
                'inp-recover-type' => 'نوع بازگردانی کلمه عبور',
                'inp-recover-sec-question' => 'سؤال امنیتی',
                'inp-recover-sec-question-answer' => 'پاسخ سؤال امنیتی',
            ])
            ->setOptionalFields([
                'inp-recover-sec-question',
                'inp-recover-sec-question-answer',
            ]);

        // recover type
        $validator
            ->setFields('inp-recover-type')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isIn([RECOVER_PASS_TYPE_SMS, RECOVER_PASS_TYPE_SECURITY_QUESTION], '{alias} ' . 'انتخاب شده نامعتبر است.');
        if (
            $validator->getStatus() &&
            $validator->getFieldValue('inp-recover-type') == RECOVER_PASS_TYPE_SECURITY_QUESTION
        ) {
            // sec question select
            $validator
                ->setFields('inp-recover-sec-question')
                ->stopValidationAfterFirstError(false)
                ->required()
                ->stopValidationAfterFirstError(true)
                ->custom(function (FormValue $value) {
                    /**
                     * @var SecurityQuestionModel $secModel
                     */
                    $secModel = container()->get(SecurityQuestionModel::class);
                    if (!$secModel->count('id=:id', ['id' => trim($value->getValue())]) !== 0) {
                        return false;
                    }
                    return true;
                }, $validator->getFieldAlias('inp-recover-sec-question') . ' انتخاب شده نامعتبر است.');
            // sec question answer
            $validator
                ->setFields('inp-recover-sec-question-answer')
                ->required();
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
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $recoverType = input()->post('inp-recover-type', '')->getValue();
            $id = session()->getFlash('the-current-user-id');

            if (empty($id)) return false;

            return $userModel->update([
                'recover_password_type' => $xss->xss_clean(trim($recoverType)),
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}