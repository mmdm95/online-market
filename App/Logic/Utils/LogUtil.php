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

    /**
     * @param $v
     * @return void
     */
    public static function dump($v)
    {
        if (is_array($v)) {
            echo "<pre style='direction: ltr; text-align: left'>";
            var_dump($v);
            echo "</pre>";
        } else {
            var_dump($v);
        }
    }
}
