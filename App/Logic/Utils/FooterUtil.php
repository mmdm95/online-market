<?php

namespace App\Logic\Utils;

use Pecee\Http\Input\InputItem;
use voku\helper\AntiXSS;

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
                $title = $name->getValue();
                if ('' != trim($title)) {
                    $link = $links[$k] ? $links[$k]->getValue() : '';
                    $priority = $priorities[$k] ? $priorities[$k]->getValue() : '';

                    if (is_numeric($priority)) {
                        $counter = (int)$priority;
                    }
                    $assembledLinks[$counter] = [
                        'name' => $title,
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
            /**
             * @var AntiXSS $xss
             */
            $xss = container()->make(AntiXSS::class);
            $xss->removeNeverAllowedOnEventsAfterwards([
                'onClick',
            ]);
            $newNamad = [];
            /**
             * @var InputItem $namad
             */
            foreach ($namads as $namad) {
                $x = $xss->xss_clean($namad->getValue());
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