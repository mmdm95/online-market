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

class EditFestivalForm implements IPageForm
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
                'inp-edit-festival-title' => 'عنوان',
                'inp-edit-festival-start-date' => 'تاریخ شروع',
                'inp-edit-festival-end-date' => 'تاریخ پایان',
            ]);

        /**
         * @var FestivalModel $festivalModel
         */
        $festivalModel = container()->get(FestivalModel::class);

        // title
        $validator
            ->setFields('inp-edit-festival-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250)
            ->custom(function (FormValue $value) use ($festivalModel) {
                $title = session()->getFlash('festival-prev-title', null);
                if (
                    $title != trim($value->getValue()) &&
                    $festivalModel->count('title=:title', ['title' => trim($value->getValue())]) !== 0
                ) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'وارد شده تکراری می‌باشد.');
        // start & end date
        $validator
            ->setFields([
                'inp-edit-festival-start-date',
                'inp-edit-festival-end-date'
            ])
            ->timestamp()
            ->custom(function (FormValue $value) {
                if (false === date(DEFAULT_TIME_FORMAT, $value->getValue())) {
                    return false;
                }
                return true;
            }, '{alias} ' . 'یک زمان وارد شده نامعتبر است.');
        // start date
        if ($validator->getFieldValue('inp-edit-festival-end-date', 0) > 0) {
            $validator
                ->setFields('inp-edit-festival-start-date')
                ->lessThan(
                    $validator->getFieldValue('inp-edit-festival-end-date'),
                    '{alias} ' . 'باید از ' . $validator->getFieldAlias('inp-edit-festival-end-date') . ' کمتر باشد.'
                );
        }

        $id = session()->getFlash('festival-curr-id', null, false);
        if (!empty($id)) {
            if (0 === $festivalModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-festival-title', 'شناسه جشنواره نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-festival-title', 'شناسه جشنواره نامعتبر است.');
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
            $pub = input()->post('inp-edit-festival-status', '')->getValue();
            $title = input()->post('inp-edit-festival-title', '')->getValue();
            $startAt = input()->post('inp-edit-festival-start-date', '')->getValue();
            $endAt = input()->post('inp-edit-festival-end-date', '')->getValue();
            $id = session()->getFlash('festival-curr-id', null);
            if (is_null($id)) return false;

            return $festivalModel->update([
                'title' => $xss->xss_clean(trim($title)),
                'slug' => $xss->xss_clean(StringUtil::slugify(trim($title))),
                'start_at' => $xss->xss_clean($startAt) ?: null,
                'expire_at' => $xss->xss_clean($endAt) ?: null,
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}