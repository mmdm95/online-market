<?php

namespace App\Logic\Forms\Admin\Brand;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\BrandModel;
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

class AddBrandForm implements IPageForm
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
                'inp-add-brand-img' => 'تصویر',
                'inp-add-brand-fa-title' => 'عنوان فارسی',
                'inp-add-brand-en-title' => 'عنوان انگلیسی',
            ])
            ->setOptionalFields([
                'inp-add-brand-keywords',
                'inp-add-brand-desc',
            ]);

        // image
        $validator
            ->setFields('inp-add-brand-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists('{alias} ' . 'وجود ندارد!');
        // title
        $validator
            ->setFields('inp-add-brand-fa-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha();
        // duplicate check
        $validator
            ->setFields([
                'inp-add-brand-fa-title',
                'inp-add-brand-en-title',
            ])
            ->custom(function (FormValue $value) {
                /**
                 * @var BrandModel $brandModel
                 */
                $brandModel = container()->get(BrandModel::class);
                if ($value->getName() == 'inp-add-brand-en-title') {
                    if ($brandModel->count('name=:name', ['name' => trim($value->getValue())]) === 0) return true;
                } else {
                    if ($brandModel->count('latin_name=:name', ['name' => trim($value->getValue())]) === 0) return true;
                }
                return false;
            }, 'برند با این عنوان (فارسی/انگلیسی) وجود دارد.');

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
            $image = input()->post('inp-add-brand-img', '')->getValue();
            $pub = input()->post('inp-add-brand-status', '')->getValue();
            $name = input()->post('inp-add-brand-fa-title', '')->getValue();
            $enName = input()->post('inp-add-brand-en-title', '')->getValue();
            $keywords = input()->post('inp-add-brand-keywords', '')->getValue();
            $desc = input()->post('inp-add-brand-desc', '')->getValue();

            return $brandModel->insert([
                'name' => $xss->xss_clean($name),
                'latin_name' => $xss->xss_clean($enName),
                'fa_name' => $xss->xss_clean(StringUtil::toPersian($name)),
                'slug' => $xss->xss_clean(StringUtil::slugify($name)),
                'image' => $xss->xss_clean(get_image_name($image)),
                'body' => $xss->xss_clean($desc),
                'keywords' => $xss->xss_clean($keywords),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}