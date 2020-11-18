<?php

namespace App\Logic\Controllers;

use App\Logic\Handlers\ResourceHandler;
use Sim\Abstracts\Mvc\Controller\AbstractController;

class CsrfController extends AbstractController
{
    /**
     * @param string|null $name
     */
    public function getToken($name = null)
    {
        $data = new ResourceHandler();
        try {
            $token = csrf()->getToken($name);
            $data->resetData()->data($token);
            \response()->json($data->getReturnData());
        } catch (\Exception $e) {
            $data->resetData()->statusCode(412)->errorMessage('Error generating csrf token');
            \response()->json($data->getReturnData());
        }
    }
}