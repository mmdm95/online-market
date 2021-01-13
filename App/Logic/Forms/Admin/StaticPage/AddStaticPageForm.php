<?php

namespace App\Logic\Forms\Admin\StaticPage;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\StaticPageModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use voku\helper\AntiXSS;

class AddStaticPageForm implements IPageForm
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
                'inp-add-static-page-title' => 'عنوان',
                'inp-add-static-page-url' => 'آدرس',
                'inp-add-static-page-desc' => 'توضیحات',
            ]);

        /**
         * @var StaticPageModel $pageModel
         */
        $pageModel = container()->get(StaticPageModel::class);

        // title and description
        $validator
            ->setFields([
                'inp-add-static-page-title',
                'inp-add-static-page-desc'
            ])
            ->required();
        // url
        $validator
            ->setFields('inp-add-static-page-url')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) use ($pageModel) {
                if ($pageModel->count('url=:url', ['url' => trim($value->getValue())]) === 0) return true;
                return false;
            }, '{alias} ' . 'وارد شده تکراری می‌باشد.');

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
         * @var StaticPageModel $pageModel
         */
        $pageModel = container()->get(StaticPageModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $title = input()->post('inp-add-static-page-title', '')->getValue();
            $url = input()->post('inp-add-static-page-url', '')->getValue();
            $pub = input()->post('inp-add-static-page-status', '')->getValue();
            $keywords = input()->post('inp-add-static-page-keywords', '')->getValue();
            $desc = input()->post('inp-add-static-page-desc', '')->getValue();

            return $pageModel->insert([
                'title' => $xss->xss_clean(trim($title)),
                'url' => $xss->xss_clean(trim($url)),
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