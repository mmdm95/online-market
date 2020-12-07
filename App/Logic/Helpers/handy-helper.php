<?php

use App\Logic\Validations\ExtendedValidator;
use Sim\Container\Exceptions\MethodNotFoundException;
use Sim\Container\Exceptions\ParameterHasNoDefaultValueException;
use Sim\Container\Exceptions\ServiceNotFoundException;
use Sim\Container\Exceptions\ServiceNotInstantiableException;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Form\FormValidator;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

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

/**
 * @return FormValidator
 * @throws ReflectionException
 * @throws MethodNotFoundException
 * @throws ParameterHasNoDefaultValueException
 * @throws ServiceNotFoundException
 * @throws ServiceNotInstantiableException
 */
function form_validator(): FormValidator
{
    return container()->get(ExtendedValidator::class);
}

/**
 * @param $type
 * @param array $placeholders
 * @return string
 */
function replaced_sms_body($type, array $placeholders = []): string
{
    /**
     * @param string $message
     * @param array $placeholders
     * @return string
     */
    function message_replacer(string $message, array $placeholders): string
    {
        if (!empty($message)) {
            foreach ($placeholders as $placeholder => $value) {
                if (is_scalar($value)) {
                    $message = str_replace($placeholder, $value, $message);
                }
            }
        }
        return $message;
    }

    try {
        $body = config()->get('settings.' . $type . '.value');
    } catch (ConfigNotRegisteredException|IFileNotExistsException|IInvalidVariableNameException $e) {
        return '';
    }

    return message_replacer((string)$body, $placeholders);
}
