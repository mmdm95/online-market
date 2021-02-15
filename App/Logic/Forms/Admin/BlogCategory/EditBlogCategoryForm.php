<?php

namespace App\Logic\Forms\Admin\BlogCategory;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class EditBlogCategoryForm implements IPageForm
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
                'inp-edit-blog-category-name' => 'نام دسته‌بندی',
                'inp-edit-blog-category-status' => 'وضعیت نمایش',
            ]);

        // title
        $validator
            ->setFields('inp-edit-blog-category-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var BlogCategoryModel $categoryModel
                 */
                $categoryModel = container()->get(BlogCategoryModel::class);
                $title = session()->getFlash('blog-category-prev-name', null);
                if (
                    $title != trim($value->getValue()) &&
                    $categoryModel->count('name=:name', ['name' => trim($value->getValue())]) !== 0
                ) {
                    return false;
                }
                return true;
            }, 'دسته‌بندی با این نام وجود دارد!');

        $id = session()->getFlash('blog-category-curr-id', null, false);
        if (!empty($id)) {
            /**
             * @var BlogCategoryModel $categoryModel
             */
            $categoryModel = container()->get(BlogCategoryModel::class);

            if (0 === $categoryModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-blog-category-name', 'شناسه دسته نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-blog-category-name', 'شناسه دسته نامعتبر است.');
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
         * @var BlogCategoryModel $categoryModel
         */
        $categoryModel = container()->get(BlogCategoryModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $name = input()->post('inp-edit-blog-category-name', '')->getValue();
            $pub = input()->post('inp-edit-blog-category-status', '')->getValue();

            $id = session()->getFlash('blog-category-curr-id', null);
            if (is_null($id)) return false;

            return $categoryModel->update([
                'name' => $xss->xss_clean($name),
                'slug' => $xss->xss_clean(StringUtil::slugify($name)),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}