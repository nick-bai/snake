<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;

class Hello extends Controller
{
    function index()
    {
        $this->response()->write('Hello easySwoole!');
    }
}
