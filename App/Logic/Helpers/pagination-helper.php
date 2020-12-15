<?php

/**
 * @param array $overwrites
 * @return array
 */
function blog_query_string(array $overwrites = []): array
{
    $arr = [];
    $searchParameters = ['q', 'time', 'page'];
    foreach ($searchParameters as $parameter) {
        if (!empty($_GET[$parameter] ?? null)) {
            $arr[$parameter] = $_GET[$parameter];
        }
    }
    return array_replace_recursive($arr, $overwrites);
}
