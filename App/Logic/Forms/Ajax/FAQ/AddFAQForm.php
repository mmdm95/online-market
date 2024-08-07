<?php

namespace App\Logic\Forms\Ajax\FAQ;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\FAQModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class AddFAQForm implements IPageForm
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
                'inp-add-faq-q' => 'سؤال',
                'inp-add-faq-a' => 'پاسخ',
                'inp-add-faq-tags' => 'برچسب',
            ]);

        // question
        // answer
        // tags
        $validator
            ->setFields([
                'inp-add-faq-q',
                'inp-add-faq-a',
                'inp-add-faq-tags'
            ])
            ->required();

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
            $q = input()->post('inp-add-faq-q', '')->getValue();
            $a = input()->post('inp-add-faq-a', '')->getValue();
            $tags = input()->post('inp-add-faq-tags', '')->getValue();
            $pub = input()->post('inp-add-faq-status', '')->getValue();

            return $faqModel->insert([
                'question' => $xss->xss_clean($q),
                'answer' => $xss->xss_clean($a),
                'tags' => $xss->xss_clean($tags),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}