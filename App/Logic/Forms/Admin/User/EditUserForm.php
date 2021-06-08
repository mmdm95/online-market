<?php

namespace App\Logic\Forms\Admin\User;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\RoleModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Pecee\Http\Input\InputItem;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Form\FormValue;
use Sim\Form\Validations\PasswordValidation;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class EditUserForm implements IPageForm
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

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        // aliases
        $validator
            ->setFieldsAlias([
                'inp-user-active-status' => 'وضعیت کاربر',
                'inp-user-login-status' => 'وضعیت ورود',
                'inp-user-ban-status' => 'وضعیت فعالیت',
                'inp-user-ban-desc' => 'علت منع فعالیت',
                'inp-user-mobile' => 'موبایل',
                'inp-user-email' => 'ایمیل',
                'inp-user-role' => 'نقش',
                'inp-user-role.*' => 'نقش',
                'inp-user-password' => 'کلمه عبور',
                'inp-user-first-name' => 'نام',
                'inp-user-last-name' => 'نام خانوادگی',
            ])
            ->setDefaultValue([
                'inp-user-change-password' => '',
            ])
            ->setOptionalFields([
                'inp-user-ban-desc',
                'inp-user-password',
                'inp-user-email',
                'inp-user-shaba',
            ]);

        // ban desc
        $validator
            ->setFields('inp-user-ban-desc')
            ->requiredWith('inp-user-ban-status', '{alias} ' . 'اجباری می‌باشد.');
        // mobile
        $validator
            ->setFields('inp-user-mobile')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianMobile('{alias} ' . 'نامعتبر است.')
            ->custom(function (FormValue $formValue) use ($userModel) {
                $mobile = $formValue->getValue();
                if (0 === $userModel->count('username=:username', ['username' => $mobile])) {
                    return true;
                }
                return false;
            }, 'این' . ' {alias} ' . 'قبلا ثبت شده، لطفا دوباره تلاش کنید.');
        // email
        $validator
            ->setFields('inp-user-email')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->email('{alias} ' . 'وارد شده نامعتبر است.');
        // password
        $validator
            ->setFields('inp-user-password')
            ->stopValidationAfterFirstError(false)
            ->requiredWith('inp-user-change-password', '{alias} ' . 'اجباری می‌باشد.')
            ->stopValidationAfterFirstError(true)
            ->password(PasswordValidation::STRENGTH_NORMAL, '{alias} ' . 'باید شامل حروف و اعداد باشد.')
            ->greaterThanEqualLength(8, '{alias} ' . 'باید بیشتر از' . ' {min} ' . 'کاراکتر باشد.');
        // check confirm password
        if (!$validator->isFieldValueEmpty('inp-user-password')) {
            $validator->match(
                ['کلمه عبور' => 'inp-user-password'],
                ['تایید کلمه عبور' => 'inp-user-re-password'],
                '{first} ' . 'با' . ' {second} ' . 'یکسان نمی‌باشد.'
            );
        }
        // role
        $validator
            ->setFields('inp-user-role')
            ->required();
        // role (continue.)
        $validator
            ->setFields('inp-user-role.*')
            ->isIn(ROLES_ARRAY_ACCEPTABLE, '{alias} ' . 'انتخاب شده نامعتبر است!');
        // name
        $validator
            ->setFields('inp-user-first-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');
        // last name
        $validator
            ->setFields('inp-user-last-name')
            ->stopValidationAfterFirstError(false)
            ->required()
            ->stopValidationAfterFirstError(true)
            ->persianAlpha('{alias} ' . 'باید از حروف فارسی باشد.')
            ->lessThanEqualLength(30, '{alias} ' . 'باید کمتر از' . ' {max} ' . 'کاراکتر باشد.');

        // check for username(mobile) duplicate
        $validator
            ->setFields('inp-user-mobile')
            ->custom(function (FormValue $value) {
                $prevMobile = session()->getFlash('prev-username', '', false);
                if ($prevMobile === $value->getValue()) return true;

                /**
                 * @var UserModel $userModel
                 */
                $userModel = container()->get(UserModel::class);
                return $userModel->count('username=:username', ['username' => $prevMobile]) === 0;
            }, 'این شماره موبایل فبلا ثبت شده است! دوباره امتحان کنید.');

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
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        /**
         * @var RoleModel $roleModel
         */
        $roleModel = container()->get(RoleModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $prevMobile = session()->getFlash('prev-username', '');
            //-----
            $status = input()->post('inp-user-active-status', '')->getValue();
            $loginStatus = input()->post('inp-user-login-status', '')->getValue();
            $banStatus = input()->post('inp-user-ban-status', '')->getValue();
            $banDesc = input()->post('inp-user-ban-desc', '')->getValue();
            $shaba = input()->post('inp-user-shaba', '')->getValue();
            $activatedAt = is_value_checked($status) ? time() : null;
            $mobile = input()->post('inp-user-mobile', '')->getValue();
            $email = input()->post('inp-user-email', '')->getValue();
            $password = input()->post('inp-user-password', '')->getValue();
            $roles = input()->post('inp-user-role', '');
            $firsName = input()->post('inp-user-first-name', '')->getValue();
            $lastName = input()->post('inp-user-last-name', '')->getValue();

            // get filled roles
            if (!is_array($roles)) return false;
            $roles = array_filter($roles, function (InputItem $val) {
                if (!empty($val->getValue())) return true;
                return false;
            });
            if (!count($roles)) return false;

            $updateArr = [
                'username' => $xss->xss_clean($mobile),
                'first_name' => $xss->xss_clean($firsName ?: null),
                'last_name' => $xss->xss_clean($lastName ?: null),
                'shaba_number' => $xss->xss_clean($shaba ?: null),
                'email' => $xss->xss_clean($email ?: null),
                'image' => PLACEHOLDER_USER_IMAGE,
                'is_activated' => is_value_checked($status) ? DB_YES : DB_NO,
                'is_login_locked' => is_value_checked($loginStatus) ? DB_YES : DB_NO,
                'ban' => is_value_checked($banStatus) ? DB_YES : DB_NO,
                'ban_desc' => $xss->xss_clean($banDesc ?: null),
                'activated_at' => $activatedAt,
                'updated_at' => time(),
            ];

            if (!empty(trim($password))) {
                $updateArr['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            $res = $roleModel->deleteUserRoles($mobile);
            $res2 = false;
            $res3 = false;
            if ($res) {
                $res2 = $userModel->update($updateArr, 'username=:username', ['username' => $prevMobile]);

                if ($res2) {
                    $res3 = $roleModel->addRolesToUser($mobile, $roles);
                }
            }

            return $res && $res2 && $res3;
        } catch (\Exception $e) {
            return false;
        }
    }
}