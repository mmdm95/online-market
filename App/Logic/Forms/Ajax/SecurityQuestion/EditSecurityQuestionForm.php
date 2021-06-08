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

class EditSecurityQuestionForm implements IPageForm
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
                'inp-edit-sec-question-q' => 'سؤال امنیتی',
            ]);

        // title
        $validator
            ->setFields('inp-edit-sec-question-q')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);

        $id = session()->getFlash('sec-q-edit-id', null, false);
        if (!empty($id)) {
            /**
             * @var SecurityQuestionModel $secModel
             */
            $secModel = container()->get(SecurityQuestionModel::class);

            if (0 === $secModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-sec-question-q', 'شناسه سؤال مورد نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-sec-question-q', 'شناسه سؤال مورد نظر نامعتبر است.');
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
            $id = session()->getFlash('sec-q-edit-id', null);
            $q = input()->post('inp-edit-sec-question-q', '')->getValue();
            $pub = input()->post('inp-edit-sec-question-status', '')->getValue();

            $res = $secModel->update([
                'question' => $xss->xss_clean(trim($q)),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_at' => time(),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
            ], 'id=:id', ['id' => $id]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}