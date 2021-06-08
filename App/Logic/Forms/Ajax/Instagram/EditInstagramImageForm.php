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

class EditInstagramImageForm implements IPageForm
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
        $validator->setFieldsAlias([
            'inp-edit-ins-img' => 'تصویر',
            'inp-edit-ins-link' => 'لینک',
        ]);

        // image
        $validator
            ->setFields('inp-edit-ins-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists('{alias} ' . 'وجود ندارد!');
        // link
        $validator
            ->setFields('inp-edit-ins-link')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->url();

        $id = session()->getFlash('instagram-image-edit-id', null, false);
        if (!empty($id)) {
            /**
             * @var InstagramImagesModel $instagramModel
             */
            $instagramModel = container()->get(InstagramImagesModel::class);

            if (0 === $instagramModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-ins-link', 'شناسه تصویر مورد نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-ins-link', 'شناسه تصویر مورد نظر نامعتبر است.');
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
            $id = session()->getFlash('instagram-image-edit-id', null);
            $image = input()->post('inp-edit-ins-img', '')->getValue();
            $link = input()->post('inp-edit-ins-link', '')->getValue();

            $res = $instagramModel->update([
                'image' => $xss->xss_clean(get_image_name($image)),
                'link' => $xss->xss_clean($link),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}