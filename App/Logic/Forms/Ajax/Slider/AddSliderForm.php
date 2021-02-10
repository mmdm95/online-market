<?php

namespace App\Logic\Forms\Ajax\Slider;

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

class AddSliderForm implements IPageForm
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
                'inp-add-slide-img' => 'تصویر اسلاید',
                'inp-add-slide-title' => 'عوان',
                'inp-add-slide-sub-title' => 'زیر عنوان',
                'inp-add-slide-sub-link' => 'لینک',
                'inp-add-slide-priority' => 'اولویت',
            ])
            ->setOptionalFields([
                'inp-add-slide-title',
                'inp-add-slide-sub-title',
                'inp-add-slide-sub-link',
            ]);

        // image
        $validator
            ->setFields('inp-add-slide-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists('{alias} ' . 'انتخاب شده وجود ندارد!');
        // title
        // sub title
        $validator
            ->setFields([
                'inp-add-slide-title',
                'inp-add-slide-sub-title'
            ])
            ->lessThanEqualLength(250);
        // link
        $validator
            ->setFields('inp-add-slide-sub-link')
            ->url();
        // priority
        $validator
            ->setFields('inp-add-slide-priority')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->isInteger();

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
            $image = input()->post('inp-add-slide-img', '')->getValue();
            $title = input()->post('inp-add-slide-title', '')->getValue();
            $subTitle = input()->post('inp-add-slide-sub-title', '')->getValue();
            $link = input()->post('inp-add-slide-sub-link', '')->getValue();
            $priority = input()->post('inp-add-slide-priority', '')->getValue();

            return $slideModel->insert([
                'title' => $xss->xss_clean(trim($title)),
                'note' => $xss->xss_clean(trim($subTitle)),
                'image' => $xss->xss_clean(get_image_name($image)),
                'link' => $xss->xss_clean($link),
                'priority' => $xss->xss_clean($priority),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}