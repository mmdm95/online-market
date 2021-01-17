<?php

namespace App\Logic\Forms\Admin\PaymentMethod;

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

class EditPaymentMethodForm implements IPageForm
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
                'inp-edit-static-page-title' => 'عنوان',
                'inp-edit-static-page-url' => 'آدرس',
                'inp-edit-static-page-desc' => 'توضیحات',
            ]);

        /**
         * @var StaticPageModel $pageModel
         */
        $pageModel = container()->get(StaticPageModel::class);

        // title and description
        $validator
            ->setFields([
                'inp-edit-static-page-title',
                'inp-edit-static-page-desc'
            ])
            ->required();
        // url
        $validator
            ->setFields('inp-edit-static-page-url')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->custom(function (FormValue $value) use ($pageModel) {
                $url = session()->getFlash('static-page-prev-url', null);
                if (
                    $url != trim($value->getValue()) &&
                    $pageModel->count('url=:url', ['url' => trim($value->getValue())]) !== 0
                ) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده تکراری می‌باشد.');

        $id = session()->getFlash('static-page-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $pageModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-static-page-title', 'شناسه صفحه نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-static-page-title', 'شناسه صفحه نامعتبر است.');
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
            $title = input()->post('inp-edit-static-page-title', '')->getValue();
            $url = input()->post('inp-edit-static-page-url', '')->getValue();
            $pub = input()->post('inp-edit-static-page-status', '')->getValue();
            $keywords = input()->post('inp-edit-static-page-keywords', '')->getValue();
            $desc = input()->post('inp-edit-static-page-desc', '')->getValue();
            $id = session()->getFlash('static-page-curr-id', null);
            if (is_null($id)) return false;

            return $pageModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'url' => $xss->xss_clean(trim($url)),
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