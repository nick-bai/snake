<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;

use App\Model\User;

class Hello extends Controller
{
    function index()
    {
        $User = new User();
        $obj = $User->getTest();
        $this->response()->write(json_encode($obj));
    }
}
