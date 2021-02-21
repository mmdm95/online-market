<?php

use App\Logic\Utils\Jdf;
use Sim\Utils\StringUtil;

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
        <div class="container">
            <div class="row" id="__theia_sticky_sidebar_container">
                <!-- START DASHBOARD MENU -->
                <?php load_partial('main/user/dashboard-menu', ['user' => $user]); ?>
                <!-- END DASHBOARD MENU -->

                <div class="col-lg-9 col-md-8">
                    <div class="dashboard_content">
                        <div class="card">
                            <div class="card-header">
                                <h3>نظرات</h3>
                            </div>
                            <div class="card-body">
                                <?php if (count($comments)): ?>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>محصول</th>
                                                <th>تاریخ</th>
                                                <th>وضعیت</th>
                                                <th>اقدامات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($comments as $comment): ?>
                                                <tr>
                                                    <td>
                                                        <img width="90px" height="auto" class="mr-2"
                                                             src="<?= url('image.show')->getRelativeUrl() . $comment['product_image']; ?>"
                                                             alt="<?= $comment['product_title']; ?>">
                                                        <?= $comment['product_title']; ?>
                                                    </td>
                                                    <td><?= Jdf::jdate(DEFAULT_TIME_FORMAT, $comment['sent_at']); ?></td>
                                                    <td>
                                                        <?php load_partial('admin/parser/comment-condition', ['condition' => $comment['the_condition']]); ?>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                                class="btn btn-link"
                                                                data-remove-url="<?= url('ajax.user.comment.remove')->getRelativeUrlTrimmed(); ?>"
                                                                data-remove-id="<?= $comment['id']; ?>">
                                                            حذف
                                                        </button>
                                                        <a href="<?= url('user.comment.edit', ['id' => $comment['id']])->getRelativeUrl(); ?>"
                                                           class="btn btn-fill-out btn-sm">
                                                            تغییر
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <?php load_partial('main/not-found-rows', ['show_border' => false]); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP -->
</div>