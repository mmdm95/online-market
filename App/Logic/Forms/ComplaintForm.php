<?php

namespace App\Logic\Forms;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\ComplaintModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class ComplaintForm implements IPageForm
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
            'inp-complaint-name' => 'نام',
            'inp-complaint-email' => 'ایمیل',
            'inp-complaint-mobile' => 'موبایل',
            'inp-complaint-subject' => 'موضوع',
            'inp-complaint-message' => 'متن پیام',
            'inp-complaint-captcha' => 'کد تصویر',
        ]);
        // captcha
        $validator
            ->setFields('inp-complaint-captcha')
            ->captcha('{alias} ' . 'به درستی وارد نشده است.');
        // name
        $validator
            ->setFields('inp-complaint-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // email
        $validator
            ->setFields('inp-complaint-email')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->email('{alias} ' . 'وارد شده نامعتبر است.');
        // mobile
        $validator
            ->setFields('inp-complaint-mobile')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.');
        // subject
        $validator
            ->setFields('inp-complaint-subject')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(250, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // message
        $validator
            ->setFields('inp-complaint-message')
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
         * @var ComplaintModel $complaintModel
         */
        $complaintModel = container()->get(ComplaintModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $name = input()->post('inp-complaint-name', '')->getValue();
            $email = input()->post('inp-complaint-email', '')->getValue();
            $mobile = input()->post('inp-complaint-mobile', '')->getValue();
            $subject = input()->post('inp-complaint-subject', '')->getValue();
            $message = input()->post('inp-complaint-message', '')->getValue();
            // if user is logged in, fetch his info
            if ($auth->isLoggedIn()) {
                $userId = $auth->getCurrentUser()[0]['id'] ?? 0;
                $user = $userModel->getFirst(['first_name', 'username AS mobile', 'email'], 'id=:id', ['id' => $userId]);
                $user = count($user) ? $user : [];
                //-----
                $name = isset($user['first_name']) && !empty($user['first_name']) ? $user['first_name'] : $name;
                $email = isset($user['email']) && !empty($user['email']) ? $user['email'] : $email;
                $mobile = isset($user['mobile']) && !empty($user['mobile']) ? $user['mobile'] : $mobile;
            }
            // insert to database
            $res = $complaintModel->insert([
                'title' => $xss->xss_clean($subject),
                'name' => $xss->xss_clean($name),
                'mobile' => $xss->xss_clean($mobile),
                'email' => $xss->xss_clean($email),
                'body' => $xss->xss_clean($message),
                'status' => COMPLAINT_STATUS_UNREAD,
                'created_at' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }
}