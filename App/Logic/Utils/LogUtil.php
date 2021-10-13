<?php

namespace App\Logic\Utils;

class LogUtil
{
    public static function logException(\Exception $e, string $line, string $file)
    {
        $format = '{level}: {date} - line: {line} file: {file} - {message}';

        logger()
            ->format($format)
            ->extraParameters([
                'line' => $line,
                'file' => $file,
            ])
            ->error($e->getMessage());
    }
}
