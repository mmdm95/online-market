<?php

use App\Logic\Handlers\ResourceHandler;
use App\Logic\Models\UserModel;
use App\Logic\Validations\ExtendedValidator;
use Sim\Auth\DBAuth;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\File\FileSystem;
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
 * Show 403 error somehow
 */
function show_403()
{
    if (request()->isAjax()) {
        $resourceHandler = new ResourceHandler();
        $resourceHandler
            ->type(RESPONSE_TYPE_ERROR)
            ->errorMessage('دسترسی غیر مجاز');
        response()->httpCode(403)->json($resourceHandler->getReturnData());
    } else {
        header_remove("Content-Type");
        response()->httpCode(403)->header('HTTP/1.1 403 Forbidden');
        echo 'دسترسی غیر مجاز';
        exit(0);
    }
}

/**
 * Show 500 error somehow
 * @param string|null $content
 */
function show_500(?string $content = null)
{
    if (request()->isAjax()) {
        $resourceHandler = new ResourceHandler();
        $resourceHandler
            ->type(RESPONSE_TYPE_ERROR)
            ->errorMessage($content ?: 'سرویس در دسترس نمی باشد.');
        response()->httpCode(500)->json($resourceHandler->getReturnData());
    } else {
        header_remove("Content-Type");
        response()->httpCode(500)->header('HTTP/1.1 500 Service Unavailable');
        echo ($content ?: 'سرویس در دسترس نمی باشد.');
        exit(0);
    }
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
        return is_scalar($val);
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
    return in_array($val, ['yes', 'on', 1, '1', true, "true"], true);
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
 * @param array $item
 * @return array
 */
function get_discount_price(array $item): array
{
    $hasDiscount = false;
    $discountPrice = (float)$item['price'];

    if (!empty($item['festival_expire']) && $item['festival_expire'] > time() && isset($item['festival_discount']) && 0 != $item['festival_discount']) {
        $hasDiscount = true;
        $discountPrice = (float)$item['price'] * (float)$item['festival_discount'];
    } elseif (isset($item['discount_until']) && $item['discount_until'] >= time()) {
        $hasDiscount = true;
        $discountPrice = (float)$item['discounted_price'];
    }

    return [$discountPrice, $hasDiscount];
}

/**
 * @param array $item
 * @return bool
 */
function get_product_availability(array $item): bool
{
    return DB_YES == $item['product_availability'] &&
        DB_YES == $item['is_available'] &&
        ((int)$item['stock_count'] > 0) &&
        ((int)$item['max_cart_count'] > 0);
}

/**
 * @return DBAuth
 * @throws \DI\DependencyException
 * @throws \DI\NotFoundException
 */
function auth_admin(): DBAuth
{
    /**
     * @var DBAuth $auth
     */
    return container()->get('auth_admin');
}

/**
 * @return DBAuth
 * @throws \DI\DependencyException
 * @throws \DI\NotFoundException
 */
function auth_home(): DBAuth
{
    /**
     * @var DBAuth $auth
     */
    return container()->get('auth_home');
}

/**
 * @param DBAuth $auth
 * @return array
 * @throws \DI\DependencyException
 * @throws \DI\NotFoundException
 */
function get_current_authenticated_user(DBAuth $auth)
{
    /**
     * @var UserModel $userModel
     */
    $userModel = container()->get(UserModel::class);

    // get current user info
    $user = $userModel->getFirst(['*'], 'id=:id', ['id' => $auth->getCurrentUser()['id'] ?? 0]);
    if(count($user)) {
        unset($user['password']);
        $user['roles'] = $userModel->getUserRoles($user['id'], null, [], ['r.*']);
    }
    return $user;
}

/**
 * @return FormValidator
 * @throws ConfigNotRegisteredException
 * @throws IFileNotExistsException
 * @throws IInvalidVariableNameException
 * @throws \DI\DependencyException
 * @throws \DI\NotFoundException
 */
function form_validator(): FormValidator
{
    /**
     * @var FormValidator $validator
     */
    $validator = container()->get(ExtendedValidator::class);
    $translateConfig = config()->get('i18n');
    if (!is_null($translateConfig)) {
        /** @var array $translateConfig */
        $t = translate()->getTranslateFromFile($translateConfig['language_dir'] . '/' . $translateConfig['language'] . '.php');
        $validator->setLang('fa')->setLangSettings($t['form_translation']);
    }
    return $validator;
}

/**
 * @param $type
 * @param array $placeholders
 * @return string
 */
function replaced_sms_body($type, array $placeholders = []): string
{
    if(!function_exists('message_replacer')) {
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
 * @param string $filename
 * @return bool
 */
function is_image_exists(string $filename): bool
{
    $filename = get_image_name($filename);
    $path = get_path('upload-root', $filename, false);

    $ext = FileSystem::getFileExtension($path);
    $validImageExt = ['png', 'jpg', 'jpeg', 'gif', 'svg'];

    return FileSystem::fileExists($path) && in_array($ext, $validImageExt);
}

/**
 * @see https://betterprogramming.pub/generate-contrasting-text-for-your-random-background-color-ac302dc87b4
 *
 * @param string $bgColor
 * @param string $lightColor
 * @param string $darkColor
 * @return string
 */
function get_color_from_bg(string $bgColor, string $lightColor = '#ffffff', string $darkColor = '#000000'): string
{
    $color = ($bgColor[0] === '#') ? substr($bgColor, 1) : $bgColor;
    if (strlen($color) === 3) {
        $colorArr = array_map(function ($value) {
            return $value . $value;
        }, str_split($color));
        $color = '';
        foreach ($colorArr as $c) {
            $color .= $c;
        }
    }
    $r = hexdec(substr($color, 0, 2)); // hexToR
    $g = hexdec(substr($color, 2, 2)); // hexToG
    $b = hexdec(substr($color, 4, 2)); // hexToB
    $brightness = round((($r * 299) + ($g * 587) + ($b * 114)) / 1000);
    return $brightness > 150 ? $darkColor : $lightColor;
}
