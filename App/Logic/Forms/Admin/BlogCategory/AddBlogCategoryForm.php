<?php

namespace App\Logic\Forms\Admin\BlogCategory;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddBlogCategoryForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws FormException
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
                'inp-add-blog-category-name' => 'نام دسته‌بندی',
                'inp-add-blog-category-status' => 'وضعیت نمایش',
            ]);

        // name
        $validator
            ->setFields('inp-add-blog-category-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var BlogCategoryModel $categoryModel
                 */
                $categoryModel = container()->get(BlogCategoryModel::class);
                if (0 === $categoryModel->count('name=:name', ['name' => trim($value->getValue())])) {
                    return true;
                }
                return false;
            }, 'دسته‌بندی با این نام وجود دارد!');

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getError(),
            $validator->getUniqueErrors(),
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
            $name = input()->post('inp-add-blog-category-name', '')->getValue();
            $pub = input()->post('inp-add-blog-category-status', '')->getValue();

            return $categoryModel->insert([
                'name' => $xss->xss_clean($name),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'slug' => $xss->xss_clean(StringUtil::slugify($name)),
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}