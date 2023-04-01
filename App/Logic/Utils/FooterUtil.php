<?php

namespace App\Logic\Utils;

use Pecee\Http\Input\InputItem;

class FooterUtil
{
    /**
     * @param $title
     * @param array $names
     * @param array $links
     * @param array $priorities
     * @return array
     */
    public function assembleFooterLinks($title, array $names, array $links, array $priorities): array
    {
        try {
            $assembled = [];
            $assembledLinks = [];
            $counter = 0;
            /**
             * @var InputItem $name
             */
            foreach ($names as $k => $name) {
                $t = $name->getValue();
                if ('' != trim($t)) {
                    $link = $links[$k] ? $links[$k]->getValue() : '';
                    $priority = $priorities[$k] ? $priorities[$k]->getValue() : '';

                    if (is_numeric($priority)) {
                        $counter = (int)$priority;
                    }
                    $assembledLinks[$counter] = [
                        'name' => $t,
                        'link' => $link,
                    ];
                    $counter++;
                }
            }

            $assembled['title'] = $title;
            $assembled['links'] = $assembledLinks;

            return $assembled;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param array $namads
     * @return array
     */
    public function assembleFooterNamads(array $namads): array
    {
        try {
            $newNamad = [];
            /**
             * @var InputItem $namad
             */
            foreach ($namads as $namad) {
                $x = htmlentities($namad->getValue());
                if (trim($x) != '') {
                    $newNamad[] = $x;
                }
            }
            return $newNamad;
        } catch (\Exception $e) {
            return [];
        }
    }
}
