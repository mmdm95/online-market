<?php

namespace App\Logic\Forms\Ajax\FAQ;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SliderModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Form\Exceptions\FormException;
use voku\helper\AntiXSS;

class EditSliderForm implements IPageForm
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
                'inp-edit-slide-img' => 'تصویر اسلاید',
                'inp-edit-slide-title' => 'عوان',
                'inp-edit-slide-sub-title' => 'زیر عنوان',
                'inp-edit-slide-sub-link' => 'لینک',
                'inp-edit-slide-priority' => 'اولویت',
            ])
            ->setOptionalFields([
                'inp-edit-slide-title',
                'inp-edit-slide-sub-title',
                'inp-edit-slide-sub-link',
            ]);

        // image
        $validator
            ->setFields('inp-edit-slide-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists('{alias} ' . 'انتخاب شده وجود ندارد!');
        // title
        // sub title
        $validator
            ->setFields([
                'inp-edit-slide-title',
                'inp-edit-slide-sub-title'
            ])
            ->lessThanEqualLength(250);
        // link
        $validator
            ->setFields('inp-edit-slide-sub-link')
            ->url();
        // priority
        $validator
            ->setFields('inp-edit-slide-priority')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();

        $id = session()->getFlash('slider-edit-id', null, false);
        if (!empty($id)) {
            /**
             * @var SliderModel $sliderModel
             */
            $sliderModel = container()->get(SliderModel::class);

            if (0 === $sliderModel->count('id=:id', ['id' => $id])) {
                $validator->setError('inp-edit-slide-title', 'شناسه اسلاید مورد نظر نامعتبر است.');
            }
        } else {
            $validator
                ->setStatus(false)
                ->setError('inp-edit-slide-title', 'شناسه اسلاید مورد نظر نامعتبر است.');
        }

        // to reset form values and not set them again
        if ($validator->getStatus()) {
            $validator->resetBagValues();
        }

        return [
            $validator->getStatus(),
            $validator->getFormattedUniqueErrors('<p class="m-0">'),
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
         * @var SliderModel $slideModel
         */
        $slideModel = container()->get(SliderModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $id = session()->getFlash('slider-edit-id', null);
            $image = input()->post('inp-edit-slide-img', '')->getValue();
            $title = input()->post('inp-edit-slide-title', '')->getValue();
            $subTitle = input()->post('inp-edit-slide-sub-title', '')->getValue();
            $link = input()->post('inp-edit-slide-sub-link', '')->getValue();
            $priority = input()->post('inp-edit-slide-priority', '')->getValue();

            return $slideModel->update([
                'title' => $xss->xss_clean($title),
                'note' => $xss->xss_clean($subTitle),
                'image' => $xss->xss_clean(get_image_name($image)),
                'link' => $xss->xss_clean($link),
                'priority' => $xss->xss_clean($priority),
                'updated_by' => $auth->getCurrentUser()['id'] ?? null,
                'updated_at' => time(),
            ], 'id=:id', ['id' => $id]);
        } catch (\Exception $e) {
            return false;
        }
    }
}