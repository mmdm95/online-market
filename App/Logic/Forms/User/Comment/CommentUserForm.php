<?php

namespace App\Logic\Forms\User\Comment;

use App\Logic\Interfaces\IPageForm;
use App\Logic\Models\CommentModel;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use voku\helper\AntiXSS;

class CommentUserForm implements IPageForm
{
    /**
     * {@inheritdoc}
     * @throws FormException
     * @throws MethodNotFoundException
     * @throws ParameterHasNoDefaultValueException
     * @throws ServiceNotFoundException
     * @throws ServiceNotInstantiableException
     * @throws \ReflectionException
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
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
            'inp-comment-message' => 'متن نظر',
        ]);

        // message
        $validator
            ->setFields('inp-comment-message')
            ->required();

        /**
         * @var CommentModel $commentModel
         */
        $commentModel = container()->get(CommentModel::class);
        if (0 === $commentModel->count('id=:id', [
                'id' => session()->getFlash('the-current-comment-id', 0, false)
            ])) {
            $validator
                ->setStatus(false)
                ->setError('inp-comment-message', 'کالای مورد نظر وجود ندارد!');
        }

        /**
         * @var UserModel $userModel
         */
        $userModel = container()->get(UserModel::class);

        $user = $userModel->getFirst(['first_name']);
        $user = count($user) ? $user : [];
        //-----
        // insert name if it's empty in user's first name
        if (!isset($user['first_name']) || empty($user['first_name'])) {
            $validator
                ->setStatus(false)
                ->setError('inp-comment-message', 'نام خود را در بخش اطلاعات حساب خود، وارد کنید.');
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
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_home');
        /**
         * @var CommentModel $commentModel
         */
        $commentModel = container()->get(CommentModel::class);
        /**
         * @var AntiXSS $xss
         */
        $xss = container()->get(AntiXSS::class);

        try {
            $message = input()->post('inp-comment-message', '')->getValue();
            $userId = $auth->getCurrentUser()['id'] ?? 0;
            $id = session()->getFlash('the-current-comment-id', 0);

            // insert to database
            return $commentModel->update([
                'body' => $xss->xss_clean($message),
                'status' => COMMENT_STATUS_NOT_READ,
                'condition' => COMMENT_CONDITION_NOT_SET,
                'sent_at' => time(),
            ], 'id=:id AND user_id=:uId', [
                'id' => $id,
                'uId' => $userId
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}