<?php

namespace App\Logic\Abstracts;

use App\Logic\Models\CommentModel;
use App\Logic\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Comment;
use Sim\Abstracts\Mvc\Controller\AbstractController;
use Sim\Auth\DBAuth;
use Sim\Auth\Interfaces\IDBException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

abstract class AbstractAdminController extends AbstractController
{
    /**
     * @var string
     */
    protected $main_layout = 'admin';

    /**
     * AbstractAdminController constructor.
     *
     * @throws ConfigNotRegisteredException
     * @throws IDBException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * @var DBAuth $auth
         */
        $auth = container()->get('auth_admin');
        $auth->resume()->isLoggedIn();

        $id = $auth->getCurrentUser()['id'] ?? null;
        $info = [
            'info' => [],
            'role' => [],
        ];
        if (!is_null($id)) {
            /**
             * @var UserModel $userModel
             */
            $userModel = container()->get(UserModel::class);
            $info['info'] = $userModel->getFirst(['*'], 'id=:id', ['id' => $id]);
            $info['role'] = $auth->getCurrentUserRole();
        }

        if ($auth->isAllow(RESOURCE_PRODUCT, OWN_PERMISSIONS)) {
            /**
             * @var CommentModel $commentModel
             */
            $commentModel = container()->get(CommentModel::class);
            $comments = $commentModel->getCommentsSummaryAllInfo(
                'c.status=:cond',
                ['cond' => COMMENT_STATUS_NOT_READ],
                null,
                0,
                ['c.id DESC'],
                null, [
                    'c.id',
                    'c.product_id',
                    'c.user_id',
                    'c.body',
                    'c.sent_at',
                    'p.image',
                    'u.first_name',
                    'u.last_name',
                    'u.username',
                ]
            );
            $commentsCount = $commentModel->count('status=:cond', ['cond' => COMMENT_STATUS_NOT_READ]);
        }

        $productComments = [
            'comments' => $comments ?? [],
            'count' => $commentsCount ?? 0,
        ];

        $this->setDefaultArguments(array_merge($this->getDefaultArguments(), [
            'the_options' => [
                'allow_rename' => true,
                'allow_upload' => true,
                'allow_create_folder' => true,
                'allow_direct_link' => true,
                'MAX_UPLOAD_SIZE' => max_upload_size(),
            ],
            'main_user_info' => $info,
            'unread_product_comments' => $productComments,
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function show404(array $arguments = [], ?string $layout = '', string $template = 'error/404'): string
    {
        return parent::show404($arguments, $layout, $template);
    }
}
