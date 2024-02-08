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

class AddCategoryImageForm implements IPageForm
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
                'inp-add-cat-img-img' => 'تصویر',
                'inp-add-cat-img-link' => 'لینک',
            ])
            ->setOptionalFields([
                'inp-add-cat-img-link',
            ]);

        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);

        // category
        $validator
            ->setFields('inp-add-cat-img-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists();
        // link
        $validator
            ->setFields('inp-add-cat-img-link')
            ->url();

        $category = session()->getFlash('cat-img-add-cat-id', null, false);
        if (!empty($category)) {
            if (0 === $categoryModel->count('id=:id', ['id' => $category])) {
                $validator
                    ->setStatus(false)
                    ->setError('inp-add-cat-img-img', 'شناسه دسته مورد نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-add-cat-img-img', 'شناسه دسته مورد نظر نامعتبر است.');
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
            $category = session()->getFlash('cat-img-add-cat-id', null);
            $image = input()->post('inp-add-cat-img-img', '')->getValue();
            $link = input()->post('inp-add-cat-img-link', '')->getValue();

            $res = $categoryModel->insert([
                'category_id' => $xss->xss_clean($category),
                'image' => $xss->xss_clean(get_image_name($image)),
                'link' => $xss->xss_clean($link) ?: null,
                'created_at' => time(),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
            ]);
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
}