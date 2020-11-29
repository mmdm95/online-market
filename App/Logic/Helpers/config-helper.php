<?php

use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Utils\ArrayUtil;

/**
 * @param string $group
 * @return array
 * @throws ConfigNotRegisteredException
 * @throws IFileNotExistsException
 * @throws IInvalidVariableNameException
 */
function getSettingByGroup(string $group): array
{
    $all = \config()->get('settings');
    $group = ArrayUtil::arrayGroupBy($group, $all);
    return $group;
}
