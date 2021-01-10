<?php

namespace App\Logic\Forms\Admin\Brand;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BlogCategoryModel;
use App\Logic\Models\BlogModel;
use App\Logic\Models\BrandModel;
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

class EditBrandForm implements IPageForm
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
                'inp-edit-brand-img' => 'تصویر',
                'inp-edit-brand-fa-title' => 'عنوان فارسی',
                'inp-edit-brand-en-title' => 'عنوان انگلیسی',
            ])
            ->setOptionalFields([
                'inp-edit-brand-keywords',
                'inp-edit-brand-desc',
            ]);

        // image
        $validator
            ->setFields('inp-edit-brand-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists('{alias} ' . 'وجود ندارد!');
        // title
        $validator
            ->setFields('inp-edit-brand-fa-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha();
        // duplicate check
        $validator
            ->setFields([
                'inp-edit-brand-fa-title',
                'inp-edit-brand-en-title',
            ])
            ->custom(function (FormValue $value) {
                /**
                 * @var BrandModel $brandModel
                 */
                $brandModel = container()->get(BrandModel::class);
                if ($value->getName() == 'inp-edit-brand-en-title') {
                    $name = session()->getFlash('brand-prev-latin_name', null);
                    if ($name !== trim($value->getValue())) {
                        if ($brandModel->count('name=:name', ['name' => trim($value->getValue())]) === 0) return true;
                    }
                } else {
                    $name = session()->getFlash('brand-prev-name', null);
                    if ($name !== trim($value->getValue())) {
                        if ($brandModel->count('latin_name=:name', ['name' => trim($value->getValue())]) === 0) return true;
                    }
                }
                return false;
            }, 'برند با این عنوان (فارسی/انگلیسی) وجود دارد.');

        $id = session()->getFlash('brand-curr-id', null, false);
        if (!empty($id)) {
            /**
             * @var BrandModel $brandModel
             */
            $brandModel = container()->get(BrandModel::class);

            if (0 === $brandModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-brand-fa-title', 'شناسه برند نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-brand-fa-title', 'شناسه برند نامعتبر است.');
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
         * @var BrandModel $brandModel
         */
        $brandModel = container()->get(BrandModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $image = input()->post('inp-edit-brand-img', '')->getValue();
            $pub = input()->post('inp-edit-brand-status', '')->getValue();
            $name = input()->post('inp-edit-brand-fa-title', '')->getValue();
            $enName = input()->post('inp-edit-brand-en-title', '')->getValue();
            $keywords = input()->post('inp-edit-brand-keywords', '')->getValue();
            $desc = input()->post('inp-edit-brand-desc', '')->getValue();

            $id = session()->getFlash('brand-curr-id', null);
            if (is_null($id)) return false;

            return $brandModel->update([
                'name' => $xss->xss_clean($name),
                'latin_name' => $xss->xss_clean($enName),
                'fa_name' => $xss->xss_clean(StringUtil::toPersian($name)),
                'slug' => $xss->xss_clean(StringUtil::slugify($name)),
                'image' => $xss->xss_clean(get_image_name($image)),
                'body' => $xss->xss_clean($desc),
                'keywords' => $xss->xss_clean($keywords),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}