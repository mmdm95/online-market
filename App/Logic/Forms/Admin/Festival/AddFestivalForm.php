<?php

namespace App\Logic\Forms\Admin\Festival;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\FestivalModel;
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

class AddFestivalForm implements IPageForm
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
                'inp-add-festival-title' => 'عنوان',
                'inp-add-festival-start-date' => 'تاریخ شروع',
                'inp-add-festival-end-date' => 'تاریخ پایان',
            ]);

        // title
        $validator
            ->setFields('inp-add-festival-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // start & end date
        $validator
            ->setFields([
                'inp-add-festival-start-date',
                'inp-add-festival-end-date'
            ])
            ->timestamp()
            ->custom(function (FormValue $value) {
                if (false === date(DEFAULT_TIME_FORMAT, $value->getValue())) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'یک زمان وارد شده نامعتبر است.');
        // start date
        if ($validator->getFieldValue('inp-add-festival-end-date', 0) > 0) {
            $validator
                ->setFields('inp-add-festival-start-date')
                ->lessThan(
                    $validator->getFieldValue('inp-add-festival-end-date'),
                    '{alias} ' . 'باید از ' . $validator->getFieldAlias('inp-add-festival-end-date') . ' کمتر باشد.'
                );
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
         * @var FestivalModel $festivalModel
         */
        $festivalModel = container()->get(FestivalModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $pub = input()->post('inp-add-festival-status', '')->getValue();
            $title = input()->post('inp-add-festival-title', '')->getValue();
            $startAt = input()->post('inp-add-festival-start-date', '')->getValue();
            $endAt = input()->post('inp-add-festival-end-date', '')->getValue();

            return $festivalModel->insert([
                'title' => $xss->xss_clean(trim($title)),
                'slug' => $xss->xss_clean(StringUtil::slugify(trim($title))),
                'start_at' => $xss->xss_clean($startAt) ?: null,
                'expire_at' => $xss->xss_clean($endAt) ?: null,
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}