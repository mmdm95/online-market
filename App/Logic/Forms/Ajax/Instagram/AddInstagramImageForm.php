<?php

namespace App\Logic\Forms\Ajax\Instagram;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\InstagramImagesModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class AddInstagramImageForm implements IPageForm
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
                'inp-add-ins-img' => 'تصویر',
                'inp-add-ins-link' => 'لینک',
            ]);

        // image
        $validator
            ->setFields('inp-add-ins-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists('{alias} ' . 'وجود ندارد!');
        // link
        $validator
            ->setFields('inp-add-ins-link')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->url();

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
         * @var InstagramImagesModel $instagramModel
         */
        $instagramModel = container()->get(InstagramImagesModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $image = input()->post('inp-add-ins-img', '')->getValue();
            $link = input()->post('inp-add-ins-link', '')->getValue();

            $res = $instagramModel->insert([
                'image' => $xss->xss_clean(get_image_name($image)),
                'link' => $xss->xss_clean($link),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}