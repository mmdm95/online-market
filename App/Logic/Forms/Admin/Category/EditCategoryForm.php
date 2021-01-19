<?php

namespace App\Logic\Forms\Admin\Blog;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Models\BlogModel;
use App\Logic\Models\CategoryModel;
use App\Logic\Models\UserModel;
use App\Logic\Utils\Jdf;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class EditCategoryForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
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
                'inp-edit-category-name' => 'نام دسته',
                'inp-edit-category-parent' => 'والد',
                'inp-edit-category-priority' => 'اولویت',
            ])
            ->setOptionalFields([
                'inp-edit-category-keywords',
                'inp-edit-category-priority',
            ]);

        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);

        // name
        $validator
            ->setFields('inp-edit-category-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(100)
            ->custom(function (FormValue $value) use ($categoryModel) {
                $name = session()->getFlash('category-prev-name', null);
                if (
                    $name != trim($value->getValue()) &&
                    $categoryModel->count('name=:name', ['name' => trim($value->getValue())]) !== 0
                ) {
                    return false;
                }
                return true;
            }, 'دسته با این نام وجود دارد!');
        // parent
        $validator
            ->setFields('inp-edit-category-parent')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) use ($categoryModel) {
                if (
                    $value->getValue() == DEFAULT_OPTION_VALUE ||
                    $categoryModel->count('id=:id', ['id' => trim($value->getValue())]) === 0) {
                    return true;
                }
                return false;
            }, 'دسته والد انتخاب شده نامعتبر است.');
        // priority
        $validator
            ->setFields('inp-edit-category-priority')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('/[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.');

        $id = session()->getFlash('category-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $categoryModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-category-name', 'شناسه مطلب نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-category-name', 'شناسه مطلب نامعتبر است.');
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
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $pub = input()->post('inp-add-category-status', '')->getValue();
            $name = input()->post('inp-add-category-name', '')->getValue();
            $parent = input()->post('inp-add-category-parent', '')->getValue();
            $keywords = input()->post('inp-add-category-keywords', '')->getValue();
            $priority = input()->post('inp-add-category-priority', '')->getValue();

            $parentInfo = $categoryModel->getFirst(['level'], 'id=:id', ['id' => $parent]);
            if ($parent == DEFAULT_OPTION_VALUE) {
                $level = 1;
            } else {
                $level = ((int)$parentInfo['level']) + 1;
            }

            $id = session()->getFlash('category-curr-id', null);
            if (is_null($id)) return false;

            return $categoryModel->update([
                'name' => $xss->xss_clean($name),
                'parent_id' => $parent,
                'keywords' => $xss->xss_clean($keywords),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'priority' => $xss->xss_clean($priority),
                'level' => $xss->xss_clean($level),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}