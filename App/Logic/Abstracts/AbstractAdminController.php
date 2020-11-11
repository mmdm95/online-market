<?php

namespace App\Logic\Abstracts;
use Sim\Abstracts\Mvc\Controller\AbstractController;

abstract class AbstractAdminController extends AbstractController
{
    /**
     * @var string
     */
    protected $main_layout = 'admin';
}