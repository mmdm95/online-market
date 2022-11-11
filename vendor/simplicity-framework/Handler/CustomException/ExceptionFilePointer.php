<?php

namespace Sim\Handler\CustomException;

use ReflectionClass;
use SplFileObject;

class ExceptionFilePointer
{
    /**
     * @param $filename
     * @param null $line
     * @return array
     */
    public static function getFilePointer($filename, $line = null)
    {
        $contentArr = [];
        $file = new SplFileObject(\trim($filename));
        $line = ($line ?: 10) - 10;
        if($line > 0) {
            $file->seek($line);
            while ($file->key() < ($line + 20) && !$file->eof()) {
                $contentArr[$file->key() + 1] = $file->current();
                $file->next();
            }
        }
        return $contentArr;
    }

    /**
     * @param $class
     * @param $line
     * @return array
     * @throws \ReflectionException
     */
    public static function getFilePointerFromClass($class, $line = null)
    {
        $ref = new ReflectionClass(\trim($class));
        $file = new SplFileObject($ref->getFileName());
        $contentArr = [];
        $line = ($line ?: $ref->getStartLine()) - 10;
        if($line > 0) {
            $file->seek($line);
            while ($file->key() < (min($ref->getEndLine(), $line + 20))) {
                $contentArr[$file->key() + 1] = $file->current();
                $file->next();
            }
        }
        return $contentArr;
    }
}
