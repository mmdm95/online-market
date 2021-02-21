<?php switch ($condition):
    case COMMENT_CONDITION_REJECT: ?>
        <span class="badge badge-danger">عدم تایید</span>
        <?php break; ?>
    <?php case COMMENT_CONDITION_ACCEPT: ?>
        <span class="badge badge-success">تایید شده</span>
        <?php break; ?>
    <?php case COMMENT_CONDITION_NOT_SET: ?>
        <span class="badge badge-primary">در حال بررسی</span>
        <?php break; ?>
    <?php default: ?>
        <span class="badge badge-dark">نامشخص</span>
        <?php break; ?>
    <?php endswitch; ?>
