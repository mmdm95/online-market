<?php

namespace App\Logic\Controllers;

use App\Logic\Abstracts\AbstractHomeController;
use App\Logic\Forms\User\Comment\CommentUserForm;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Utils\CommentUtil;
use Jenssegers\Agent\Agent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Form\Exceptions\FormException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class CommentController extends AbstractHomeController
{
    /**
     * @param $product_id
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function paginate($product_id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var CommentUtil $commentUtil
             */
            $commentUtil = container()->get(CommentUtil::class);
            $comments = $commentUtil->paginatedComment($product_id);

            $resourceHandler
                ->type(RESPONSE_TYPE_SUCCESS)
                ->data($this->setTemplate('partial/main/product/comments')->render([
                    'comments' => $comments['items'],
                    'pagination' => $comments['pagination'],
                ]));
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }

    /**
     * @param $product_id
     * @throws ConfigNotRegisteredException
     * @throws FormException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     * @throws \Sim\Container\Exceptions\MethodNotFoundException
     * @throws \Sim\Container\Exceptions\ParameterHasNoDefaultValueException
     * @throws \Sim\Container\Exceptions\ServiceNotFoundException
     * @throws \Sim\Container\Exceptions\ServiceNotInstantiableException
     */
    public function saveComment($product_id)
    {
        session()->setFlash('comment_product_id', $product_id);

        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            /**
             * @var CommentUserForm $commentForm
             */
            $commentForm = container()->get(CommentUserForm::class);
            [$status, $formattedErrors] = $commentForm->validate();
            if ($status) {
                $res = $commentForm->store();
                // success or warning message
                if ($res) {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_SUCCESS)
                        ->data('نظر شما با موفقیت ثبت شد و پس از تایید در سایت نمایش داده می‌شود.');
                } else {
                    $resourceHandler
                        ->type(RESPONSE_TYPE_ERROR)
                        ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
                }
            } else {
                $resourceHandler
                    ->type(RESPONSE_TYPE_ERROR)
                    ->errorMessage(encode_html($formattedErrors));
            }
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }
        response()->json($resourceHandler->getReturnData());
    }
}