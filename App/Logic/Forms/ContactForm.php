<?php

namespace App\Logic\Forms;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ContactUsModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ContactForm implements IPageForm
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
        $validator->setFieldsAlias([
            'inp-contact-name' => 'نام',
            'inp-contact-email' => 'ایمیل',
            'inp-contact-mobile' => 'موبایل',
            'inp-contact-subject' => 'موضوع',
            'inp-contact-message' => 'متن پیام',
            'inp-contact-captcha' => 'کد تصویر',
        ]);
        // captcha
        $validator
            ->setFields('inp-contact-captcha')
            ->captcha('{alias} ' . 'به درستی وارد نشده است.');
        // name
        $validator
            ->setFields('inp-contact-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // email
        $validator
            ->setFields('inp-contact-email')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->email('{alias} ' . 'وارد شده نامعتبر است.');
        // mobile
        $validator
            ->setFields('inp-contact-mobile')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');
        // subject
        $validator
            ->setFields('inp-contact-subject')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(250, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // message
        $validator
            ->setFields('inp-contact-message')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true);

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
     * @return bool
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function store(): bool
    {
        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);
        /**
         * @var ContactUsModel $contactModel
         */
        $contactModel = container()->get(ContactUsModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $name = input()->post('inp-contact-name', '')->getValue();
            $email = input()->post('inp-contact-email', '')->getValue();
            $mobile = input()->post('inp-contact-mobile', '')->getValue();
            $subject = input()->post('inp-contact-subject', '')->getValue();
            $message = input()->post('inp-contact-message', '')->getValue();
            // if user is logged in, fetch his info
            if ($auth->isLoggedIn()) {
                $userId = $auth->getCurrentUser()['id'] ?? 0;
                $user = $userModel->get(['first_name', 'mobile', 'email']);
                $user = count($user) ? $user[0] : [];
                //-----
                $name = isset($user['first_name']) && !empty($user['first_name']) ? $user['first_name'] : $name;
                $email = isset($user['email']) && !empty($user['email']) ? $user['email'] : $email;
                $mobile = isset($user['mobile']) && !empty($user['mobile']) ? $user['mobile'] : $mobile;
            }
            // insert to database
            $res = $contactModel->insert([
                'user_id' => $userId ?? null,
                'title' => $xss->xss_clean($subject),
                'name' => $xss->xss_clean($name),
                'mobile' => $xss->xss_clean($mobile),
                'email' => $xss->xss_clean($email),
                'body' => $xss->xss_clean($message),
                'status' => CONTACT_STATUS_UNREAD,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }
}