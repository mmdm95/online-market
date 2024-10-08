<?php

namespace App\Logic\Forms\Admin\SendMethod;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\SendMethodModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\StringUtil;
use voku\helper\AntiXSS;

class AddSendMethodForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @return array
     * @throws ConfigNotRegisteredException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
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
                'inp-add-send-method-img' => 'تصویر',
                'inp-add-send-method-title' => 'عنوان',
                'inp-add-send-method-desc' => 'توضیحات',
                'inp-add-send-method-price' => 'هزینه ارسال',
                'inp-add-send-method-priority' => 'اولویت',
            ])
            ->setOptionalFields([
                'inp-add-send-method-desc',
                'inp-add-send-method-priority',
            ]);

        // title
        $validator
            ->setFields('inp-add-send-method-title')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->lessThanEqualLength(250);
        // image
        $validator
            ->setFields('inp-add-send-method-img')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->imageExists();
        // desc
        $validator
            ->setFields('inp-add-send-method-desc')
            ->lessThanEqualLength(250);
        // price
        $validator
            ->setFields('inp-add-send-method-price')
            ->greaterThanEqual(0, '{alias} ' . 'باید عددی بزرگتر یا مساوی صفر باشد.')
            ->regex('/\d+/', '{alias} ' . 'باید از نوع عددی باشد.');
        // priority
        $validator
            ->setFields('inp-add-send-method-priority')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->regex('/\-?[0-9]+/', '{alias} ' . 'باید از نوع عددی باشد.');

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
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function store(): bool
    {
        /**
         * @var SendMethodModel $sendModel
         */
        $sendModel = container()->get(SendMethodModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');

        try {
            $image = input()->post('inp-add-send-method-img', '')->getValue();
            $title = input()->post('inp-add-send-method-title', '')->getValue();
            $pub = input()->post('inp-add-send-method-status', '')->getValue();
            $desc = input()->post('inp-add-send-method-desc', '')->getValue();
            $price = input()->post('inp-add-send-method-price', '')->getValue();
            $determineLoc = input()->post('inp-add-send-method-determine-location', '')->getValue();
            $forShopLoc = input()->post('inp-add-send-method-for-shop-location', '')->getValue();
            $priority = input()->post('inp-add-send-method-priority', '')->getValue();

            return $sendModel->insert([
                'code' => StringUtil::uniqidReal(12),
                'title' => $xss->xss_clean(trim($title)),
                'desc' => $xss->xss_clean(trim($desc)),
                'image' => $xss->xss_clean(get_image_name($image)),
                'publish' => is_value_checked($pub) ? DB_YES : DB_NO,
                'price' => $xss->xss_clean($price),
                'determine_price_by_location' => is_value_checked($determineLoc) ? DB_YES : DB_NO,
                'only_for_shop_location' => is_value_checked($forShopLoc) ? DB_YES : DB_NO,
                'priority' => $xss->xss_clean($priority),
                'created_by' => $auth->getCurrentUser()['id'] ?? null,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
