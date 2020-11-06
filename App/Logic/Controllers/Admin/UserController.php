<?php


namespace App\Logic\Controllers\Admin;

use Sim\Abstracts\Mvc\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @var string
     */
    private $main_layout = 'admin';

    public function view($id = null)
    {
        $this->setLayout($this->main_layout)->setTemplate('view/user/view');

        return $this->render();
    }

}