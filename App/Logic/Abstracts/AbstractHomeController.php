<?php

namespace App\Logic\Abstracts;

abstract class AbstractHomeController extends AbstractMainController
{
    /**
     * @var string
     */
    protected $main_layout = 'main';

    /**
     * @var string
     */
    protected $main_index_layout = 'main-index';
}