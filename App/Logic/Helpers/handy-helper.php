<?php

use App\Logic\Handlers\ResourceHandler;
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
 * Not implemented response with resource
 */
function not_implemented_yet()
{
    $resourceHandler = new ResourceHandler();
    $resourceHandler
        ->type(RESPONSE_TYPE_ERROR)
        ->errorMessage('Not implemented yet!');
    response()->json($resourceHandler->getReturnData());
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
 * @param $val
 * @return bool
 */
function is_value_checked($val): bool
{
    return in_array($val, ['yes', 'on', 1, '1', true]);
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

/**
 * @param string $filename
 * @return string
 */
function get_image_name(string $filename): string
{
    return str_replace(url('image.show', '')->getRelativeUrl(), '', str_replace(['//', '\\'], '/', $filename));
}

/**
 * @param string $bgColor
 * @param string $lightColor
 * @param string $darkColor
 * @return string
 */
function get_color_from_bg(string $bgColor, string $lightColor, string $darkColor): string
{
    $color = ($bgColor[0] === '#') ? substr($bgColor, 1, 7) : $bgColor;
    $r = (int)substr($color, 0, 2); // hexToR
    $g = (int)substr($color, 2, 4); // hexToG
    $b = (int)substr($color, 4, 6); // hexToB
    $uiColors = [$r / 255, $g / 255, $b / 255];
    $c = array_map(function ($col) {
        if ($col <= 0.03928) {
            return $col / 12.92;
        }
        return pow(($col + 0.055) / 1.055, 2.4);
    }, $uiColors);
    $L = (0.2126 * $c[0]) + (0.7152 * $c[1]) + (0.0722 * $c[2]);
    return ($L > 0.179) ? $darkColor : $lightColor;
}
