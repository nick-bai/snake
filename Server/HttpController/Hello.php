<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;

class Hello extends Controller
{
    function index()
    {
        echo "dadsadssadadsad";
        var_dump($this);
        $this->response()->write('Hello easySwoole!');
    }
}
