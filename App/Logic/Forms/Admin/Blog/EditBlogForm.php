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

class EditBlogForm implements IPageForm
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
                'inp-edit-blog-img' => 'تصویر',
                'inp-edit-blog-title' => 'عنوان',
                'inp-edit-blog-category' => 'دسته‌بندی',
                'inp-edit-blog-abs' => 'خلاصه مطلب',
                'inp-edit-blog-desc' => 'توضیحات',
            ])
            ->setOptionalFields([
                'inp-edit-blog-keywords',
            ]);

        // image
        $validator
            ->setFields('inp-edit-blog-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists('{alias} ' . 'وجود ندارد!');
        // title
        $validator
            ->setFields('inp-edit-blog-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) {
                /**
                 * @var BlogModel $blogModel
                 */
                $blogModel = container()->get(BlogModel::class);
                $title = session()->getFlash('blog-prev-title', null);
                if (
                    $title != trim($value->getValue()) &&
                    $blogModel->count('title=:title', ['title' => trim($value->getValue())]) !== 0
                ) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده تکراری می‌باشد.');
        // category
        $validator
            ->setFields('inp-edit-blog-category')
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
            ->setFields('inp-edit-blog-abs')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(200);
        // description
        $validator
            ->setFields('inp-edit-blog-desc')
            ->required();

        $id = session()->getFlash('blog-curr-id', null, false);
        if (!empty($id)) {
            /**
             * @var BlogModel $blogModel
             */
            $blogModel = container()->get(BlogModel::class);

            if (0 === $blogModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-blog-title', 'شناسه مطلب نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-blog-title', 'شناسه مطلب نامعتبر است.');
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
            $image = input()->post('inp-edit-blog-img', '')->getValue();
            $pub = input()->post('inp-edit-blog-status', '')->getValue();
            $title = input()->post('inp-edit-blog-title', '')->getValue();
            $category = input()->post('inp-edit-blog-category', '')->getValue();
            $abstract = input()->post('inp-edit-blog-abs', '')->getValue();
            $keywords = input()->post('inp-edit-blog-keywords', '')->getValue();
            $desc = input()->post('inp-edit-blog-desc', '')->getValue();
            $writer = null;

            $id = session()->getFlash('blog-curr-id', null);
            if (is_null($id)) return false;

            if ($userModel->count('id=:id', ['id' => $auth->getCurrentUser()['id'] ?? 0])) {
                $user = $userModel->getFirst(['first_name', 'last_name'], 'id=:id', ['id' => $auth->getCurrentUser()['id']]);
                $writer = $user['first_name'] . ' ' . $user['last_name'];
            }

            return $blogModel->update([
                'title' => $title,
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
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}