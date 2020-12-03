<?php

/**
 * @return bool
 */
function is_post(): bool
{
    return request()->getMethod() === 'post';
}

/**
 * @param mixed ...$_
 * @return string
 */
function title_concat(...$_): string
{
    $_ = array_filter($_, function ($val) {
        if (is_scalar($val)) return true;
        return false;
    });
    return implode(TITLE_DELIMITER, $_);
}

/**
 * @param int $time
 * @return int
 */
function get_today_start_of_time(int $time): int
{
    return strtotime("today", $time);
}

/**
 * @param int $time
 * @return int
 */
function get_today_end_of_time(int $time): int
{
    return strtotime("tomorrow, -1 second", $time);
}

/**
 * @param $num
 * @param $total
 * @param bool $low
 * @return int
 */
function get_percentage($num, $total, bool $low = true): int
{
    $num = (int)$num;
    $total = (int)$total;

    if ($num > $total) return 0;

    $percentage = (($total - $num) / $total) * 100;

    if ($percentage < 0 || $percentage > 100) return 0;

    if (!$low) return ceil($percentage);
    return floor($percentage);
}
