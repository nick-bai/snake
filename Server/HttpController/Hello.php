<?php
# @Date:   2018/03/30
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: Index.php
# @Last modified time: 2018/03/30
# @License: MIT
namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;

class Hello extends Controller
{
    function index()
    {
        $this->response()->write('Hello easySwoole!');
    }
}
