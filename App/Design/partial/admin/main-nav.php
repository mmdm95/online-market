<?php

use App\Logic\Utils\Jdf;

$authAdmin = auth_admin();

?>

<div class="navbar navbar-expand-md navbar-dark bg-indigo-800 fixed-top">
    <div class="navbar-brand p-0 text-center">
        <a href="<?= url('home.index'); ?>" target="_blank" class="d-inline-block">
            <img src="" data-src="<?= url('image.show') . config()->get('settings.logo_light.value'); ?>"
                 alt="<?= config()->get('settings.title.value'); ?>" class="lazy">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <span class="navbar-text ml-md-3 mr-md-auto">
				<span class="badge bg-success">آنلاین</span>
			</span>

        <ul class="navbar-nav">
            <?php if ($unread_product_comments['count'] > 0): ?>
                <li class="nav-item dropdown">
                    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-comment-discussion mr-2"></i>
                        نظرات محصول
                    </a>

                    <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
                        <div class="dropdown-content-header bg-blue">
                            <span class="font-size-sm line-height-sm text-uppercase font-weight-semibold">نظرات مشاهده نشده</span>
                            <span>
                                (
                                <?= local_number($unread_product_comments['count']); ?>
                                عدد
                                )
                            </span>
                        </div>

                        <div class="dropdown-content-body dropdown-scrollable">
                            <ul class="media-list">
                                <?php foreach ($unread_product_comments['comments'] as $comment): ?>
                                    <li class="media">
                                        <div class="mr-3">
                                            <a href="<?= url('admin.product.detail', ['id' => $comment['product_id']])->getRelativeUrl(); ?>">
                                                <img src=""
                                                     data-src="<?= url('image.show', ['filename' => $comment['image']]); ?>"
                                                     class="rounded-circle lazy border" alt="" width="70px">
                                            </a>
                                        </div>

                                        <div class="media-body">
                                            <div>
                                                <?= $comment['body']; ?>
                                            </div>
                                            <a href="<?= url('admin.user.view', ['id' => $comment['user_id']])->getRelativeUrl(); ?>">
                                                <?php if (!empty($comment['first_name']) || !empty($comment['last_name'])): ?>
                                                    <?= trim(($comment['first_name'] ?? '') . ' ' . ($comment['last_name'] ?? '')) ?: $comment['username']; ?>
                                                <?php else: ?>
                                                    <?= local_number($comment['username']); ?>
                                                <?php endif; ?>
                                            </a>
                                            <div class="font-size-sm text-muted mt-1">
                                                <?= Jdf::jdate(DEFAULT_TIME_FORMAT, $comment['sent_at']); ?>
                                            </div>
                                            <div class="text-right">
                                                <a href="<?= url('admin.comment.detail', [
                                                    'p_id' => $comment['product_id'],
                                                    'id' => $comment['id'],
                                                ])->getRelativeUrl(); ?>"
                                                   class="btn btn-light btn-block mt-1">
                                                    مشاهده
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            <li class="nav-item dropdown dropdown-user">
                <a href="javascript:void(0);" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src=""
                         data-src="<?= asset_path('image/avatars/' . $main_user_info['info']['image'], false); ?>"
                         class="rounded-circle lazy" alt="">
                    <span>
                        <?php if (!empty($main_user_info['info']['first_name'])): ?>
                            سلام،
                            <?= trim($main_user_info['info']['first_name']); ?>
                        <?php else: ?>
                            <span>کاربر</span>
                        <?php endif; ?>
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="<?= url('admin.user.view', ['id' => $authAdmin->getCurrentUser()['id']])->getRelativeUrl(); ?>"
                       class="dropdown-item">
                        <i class="icon-user-plus"></i>
                        اطلاعات من
                    </a>
                    <div class="dropdown-divider"></div>

                    <?php if ($authAdmin->isAllow(RESOURCE_SETTING, OWN_PERMISSIONS)): ?>
                        <a href="<?= url('admin.setting.main')->getRelativeUrl(); ?>" class="dropdown-item">
                            <i class="icon-cog5"></i>
                            تنظیمات
                        </a>
                    <?php endif; ?>

                    <a href="<?= url('admin.logout')->getRelativeUrl(); ?>" class="dropdown-item">
                        <i class="icon-switch2"></i>
                        خروج
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>