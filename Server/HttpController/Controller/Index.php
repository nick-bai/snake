<?php
namespace App\HttpController\Controller;

use EasySwoole\Core\Http\AbstractInterface\Controller;

use App\HttpController\Model\User;
/**
 *
 */
class Index extends Controller
{
    public function test()
    {
        $a = User::get(1);
        $this->response()->write(json_encode($a));
    }
}
