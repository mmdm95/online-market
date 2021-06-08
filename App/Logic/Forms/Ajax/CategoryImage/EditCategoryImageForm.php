<?php

namespace App\Logic\Forms\Ajax\CategoryImage;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CategoryImageModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditCategoryImageForm implements IPageForm
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
            'inp-edit-cat-img-img' => 'تصویر',
            'inp-edit-cat-img-link' => 'لینک',
        ])
            ->setOptionalFields([
                'inp-edit-cat-img-link',
            ]);

        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var CategoryImageModel $categoryImageModel
         */
        $categoryImageModel = container()->get(CategoryImageModel::class);

        // category
        $validator
            ->setFields('inp-edit-cat-img-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists();
        // link
        $validator
            ->setFields('inp-edit-cat-img-link')
            ->url();

        $id = session()->getFlash('cat-img-edit-id', null, false);
        if (!empty($id)) {
            if (0 === $categoryModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-cat-img-img', 'شناسه تصویر دسته مورد نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-cat-img-img', 'شناسه تصویر دسته مورد نظر نامعتبر است.');
        }

        $category = session()->getFlash('cat-img-edit-cat-id', null, false);
        if (!empty($category)) {
            if (0 === $categoryImageModel->count('id=:id', ['id' => $category])) {
                $validator->setError('inp-edit-cat-img-img', 'شناسه دسته مورد نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-cat-img-img', 'شناسه دسته مورد نظر نامعتبر است.');
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
         * @var CategoryImageModel $categoryModel
         */
        $categoryModel = container()->get(CategoryImageModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('cat-img-edit-id', null);
            $category = session()->getFlash('cat-img-add-cat-id', null);
            $image = input()->post('inp-add-cat-img-img', '')->getValue();
            $link = input()->post('inp-add-cat-img-link', '')->getValue();

            $res = $categoryModel->update([
                'image' => $xss->xss_clean(get_image_name($image)),
                'link' => $xss->xss_clean($link) ?: null,
                'updated_at' => time(),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
            ], 'id=:id AND category_id=:cId', ['id' => $id, 'cId' => $category]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}