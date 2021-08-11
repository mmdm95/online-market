<?php

namespace App\Logic\Forms\Admin\Category;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CategoryModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class AddCategoryForm implements IPageForm
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
                'inp-add-category-name' => 'نام دسته',
                'inp-add-category-parent' => 'والد',
                'inp-add-category-priority' => 'اولویت',
            ])
            ->setOptionalFields([
                'inp-add-category-keywords',
                'inp-add-category-priority',
            ]);

        /**
         * @var CategoryModel $categoryModel
         */
        $categoryModel = container()->get(CategoryModel::class);

        // name
        $validator
            ->setFields('inp-add-category-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(100)
            ->custom(function (FormValue $value) use ($categoryModel) {
                if ($categoryModel->count('name=:name', ['name' => trim($value->getValue())]) === 0) return true;
                return false;
            }, 'دسته با این نام وجود دارد!');
        // parent
        $validator
            ->setFields('inp-add-category-parent')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) use ($categoryModel) {
                if (
                    $value->getValue() == DEFAULT_OPTION_VALUE ||
                    $categoryModel->count('id=:id', ['id' => trim($value->getValue())]) !== 0) {
                    return true;
                }
                return false;
            }, 'دسته والد انتخاب شده نامعتبر است.');
        // priority
        $validator
            ->setFields('inp-add-category-priority')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('/\-?[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.');

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
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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

            $parentInfo = $categoryModel->getFirst(['all_parents_id', 'level'], 'id=:id', ['id' => $parent]);
            if ($parent == DEFAULT_OPTION_VALUE) {
                $level = 1;
            } else {
                $level = ((int)$parentInfo['level']) + 1;
            }

            return $categoryModel->insert([
                'name' => $xss->xss_clean($name),
                'parent_id' => $parent ?: null,
                'all_parents_id' => isset($parentInfo['all_parents_id'])
                    ? (trim(((string)$parentInfo['all_parents_id']) . ',' . $parent, ','))
                    : '',
                'keywords' => $xss->xss_clean($keywords),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'priority' => $xss->xss_clean($priority),
                'level' => $xss->xss_clean($level),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}