<?php

namespace App\Logic\Forms\Ajax\SecurityQuestion;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SecurityQuestionModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class AddSecurityQuestionForm implements IPageForm
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
                'inp-add-sec-question-q' => 'سؤال امنیتی',
            ]);

        // title
        $validator
            ->setFields('inp-add-sec-question-q')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
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
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function store(): bool
    {
        /**
         * @var SecurityQuestionModel $secModel
         */
        $secModel = container()->get(SecurityQuestionModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $q = input()->post('inp-add-sec-question-q', '')->getValue();
            $pub = input()->post('inp-add-sec-question-status', '')->getValue();

            $res = $secModel->insert([
                'question' => $xss->xss_clean(trim($q)),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'created_at' => time(),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}