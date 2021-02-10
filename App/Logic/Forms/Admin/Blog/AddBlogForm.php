<?php

namespace App\Logic\Forms\Admin\Blog;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Models\BlogModel;
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

class AddBlogForm implements IPageForm
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
                'inp-add-blog-img' => 'تصویر',
                'inp-add-blog-title' => 'عنوان',
                'inp-add-blog-category' => 'دسته‌بندی',
                'inp-add-blog-abs' => 'خلاصه مطلب',
                'inp-add-blog-desc' => 'توضیحات',
            ])
            ->setOptionalFields([
                'inp-add-blog-keywords',
            ]);

        // image
        $validator
            ->setFields('inp-add-blog-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists('{alias} ' . 'وجود ندارد!');
        // title
        $validator
            ->setFields('inp-add-blog-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var BlogModel $blogModel
                 */
                $blogModel = container()->get(BlogModel::class);
                if ($blogModel->count('title=:title', ['title' => trim($value->getValue())]) === 0) return true;
                return false;
            }, '{alias} ' . 'وارد شده تکراری می‌باشد.');
        // category
        $validator
            ->setFields('inp-add-blog-category')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var BlogCategoryModel $categoryModel
                 */
                $categoryModel = container()->get(BlogCategoryModel::class);
                if ($categoryModel->count('id=:id', ['id' => $value->getValue()]) === 0) return true;
                return false;
            }, '{alias} ' . 'وارد شده وجود ندارد.');
        // abstract
        $validator
            ->setFields('inp-add-blog-abs')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(200);
        // description
        $validator
            ->setFields('inp-add-blog-desc')
            ->required();

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
         * @var BlogModel $blogModel
         */
        $blogModel = container()->get(BlogModel::class);
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $image = input()->post('inp-add-blog-img', '')->getValue();
            $pub = input()->post('inp-add-blog-status', '')->getValue();
            $title = input()->post('inp-add-blog-title', '')->getValue();
            $category = input()->post('inp-add-blog-category', '')->getValue();
            $abstract = input()->post('inp-add-blog-abs', '')->getValue();
            $keywords = input()->post('inp-add-blog-keywords', '')->getValue();
            $desc = input()->post('inp-add-blog-desc', '')->getValue();
            $writer = null;

            if ($userModel->count('id=:id', ['id' => $auth->getCurrentUser()['id'] ?? 0])) {
                $user = $userModel->getFirst(['first_name', 'last_name'], 'id=:id', ['id' => $auth->getCurrentUser()['id']]);
                $writer = $user['first_name'] . ' ' . $user['last_name'];
            }

            return $blogModel->insert([
                'title' => $xss->xss_clean(trim($title)),
                'fa_title' => $xss->xss_clean(StringUtil::toPersian($title)),
                'slug' => $xss->xss_clean(StringUtil::slugify($title)),
                'image' => $xss->xss_clean(get_image_name($image)),
                'abstract' => $xss->xss_clean($abstract),
                'body' => $xss->xss_clean($desc),
                'category_id' => $category,
                'keywords' => $xss->xss_clean($keywords),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'writer' => $writer,
                'archive_tag' => Jdf::jdate(ARCHIVE_TAGS_TIME_FORMAT),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}