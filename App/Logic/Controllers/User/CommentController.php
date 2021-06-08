<?php

namespace App\Logic\Controllers\User;

use App\Logic\Abstracts\AbstractUserController;
use App\Logic\Forms\User\Comment\CommentUserForm;
use App\Logic\Handlers\GeneralAjaxRemoveHandler;
use App\Logic\Handlers\GeneralFormHandler;
use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\BaseModel;
use App\Logic\Models\CommentModel;
use App\Logic\Models\ProductModel;
use Jenssegers\Agent\Agent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Exceptions\Mvc\Controller\ControllerException;
use Sim\Exceptions\PathManager\PathNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class CommentController extends AbstractUserController
{
    /**
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function index()
    {
        /**
         * @var CommentModel $commentModel
         */
        $commentModel = container()->get(CommentModel::class);

        $user = $this->getDefaultArguments()['user'];

        $comments = $commentModel->getCommentsWithProductInfo(
            'user_id=:id',
            ['id' => $user['id']],
            null,
            0,
            ['c.id DESC'],
            [
                'c.id',
                'c.the_condition',
                'c.sent_at',
                'p.image AS product_image',
                'p.title AS product_title',
                // if need add to cart, this column is required
                'pp.code AS product_code',
            ]);

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/comment/index');
        return $this->render([
            'comments' => $comments,
        ]);
    }

    /**
     * @param $id
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function decider($id)
    {
        if (is_post()) {
            $user = $this->getDefaultArguments()['user'];

            /**
             * @var CommentModel $commentModel
             */
            $commentModel = container()->get(CommentModel::class);
            $comment = $commentModel->getComments('product_id=:pId AND user_id=:uId', ['pId' => $id, 'uId' => $user['id']]);

            if (count($comment)) {
                response()->redirect(url('user.comment.edit', ['id' => $id])->getRelativeUrl());
            } else {
                response()->redirect(url('home.product.show', ['id' => $id])->getRelativeUrlTrimmed() . '#Reviews');
            }
        }
        response()->redirect(url('user.comments')->getRelativeUrl());
    }

    /**
     * @param $id
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws ControllerException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws PathNotRegisteredException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    public function edit($id)
    {
        $user = $this->getDefaultArguments()['user'];

        /**
         * @var CommentModel $commentModel
         */
        $commentModel = container()->get(CommentModel::class);

        if (0 === $commentModel->count('id=:id AND user_id=:uId', ['id' => '', 'uId' => $user['id']])) {
            return $this->show404();
        }

        $data = [];
        if (is_post()) {
            session()->setFlash('the-current-user-id', $user['id']);
            session()->setFlash('the-current-comment-id', $id);

            $formHandler = new GeneralFormHandler();
            $data = $formHandler->handle(CommentUserForm::class, 'update_comment');
        }

        /**
         * @var ProductModel $productModel
         */
        $productModel = container()->get(ProductModel::class);

        $comment = $commentModel->getFirst(['product_id', 'body'], 'id=:id AND user_id=:uId', ['id' => $id, 'uId' => $user['id']]);
        $product = $productModel->getFirst(['title', 'slug', 'image', 'is_available']);
        $price = $productModel->getProductPropertyWithInfo(['MIN(price) AS min_price', 'MAX(price) AS max_price'], 'product_id=:pId AND is_available=:av', ['pId' => $comment['product_id'], 'av' => DB_YES]);

        $this->setLayout($this->main_layout)->setTemplate('view/main/user/comment/edit');
        return $this->render(array_merge($data, [
            'comment' => $comment,
            'product' => $product,
            'price' => $price,
            'comment_id' => $id,
        ]));
    }

    /**
     * @param $id
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function remove($id)
    {
        $resourceHandler = new ResourceHandler();

        /**
         * @var Agent $agent
         */
        $agent = container()->get(Agent::class);
        if (!$agent->isRobot()) {
            $user = $this->getDefaultArguments()['user'];

            $handler = new GeneralAjaxRemoveHandler();
            $resourceHandler = $handler->handle(BaseModel::TBL_COMMENTS, $id, 'user_id=:uId', ['uId' => $user['id']]);
        } else {
            response()->httpCode(403);
            $resourceHandler
                ->type(RESPONSE_TYPE_ERROR)
                ->errorMessage('خطا در ارتباط با سرور، لطفا دوباره تلاش کنید.');
        }

        response()->json($resourceHandler->getReturnData());
    }
}