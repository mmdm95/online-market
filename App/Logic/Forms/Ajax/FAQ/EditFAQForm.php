<?php

namespace App\Logic\Forms\Ajax\FAQ;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\FAQModel;
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

class EditFAQForm implements IPageForm
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
                'inp-edit-faq-q' => 'سؤال',
                'inp-edit-faq-a' => 'پاسخ',
                'inp-edit-faq-tags' => 'برچسب',
            ]);

        // question
        // answer
        // tags
        $validator
            ->setFields([
                'inp-edit-faq-q',
                'inp-edit-faq-a',
                'inp-edit-faq-tags'
            ])
            ->required();

        $id = session()->getFlash('faq-edit-id', null, false);
        if (!empty($id)) {
            /**
             * @var FAQModel $faqModel
             */
            $faqModel = container()->get(FAQModel::class);

            if (0 === $faqModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-faq-q', 'شناسه سؤال مورد نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-faq-q', 'شناسه سؤال مورد نظر نامعتبر است.');
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
         * @var FAQModel $faqModel
         */
        $faqModel = container()->get(FAQModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('faq-edit-id', null);
            $q = input()->post('inp-edit-faq-q', '')->getValue();
            $a = input()->post('inp-edit-faq-a', '')->getValue();
            $tags = input()->post('inp-edit-faq-tags', '')->getValue();
            $pub = input()->post('inp-edit-faq-status', '')->getValue();

            return $faqModel->update([
                'question' => $xss->xss_clean($q),
                'answer' => $xss->xss_clean($a),
                'tags' => $xss->xss_clean($tags),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}