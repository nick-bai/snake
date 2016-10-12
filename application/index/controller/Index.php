<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        echo "<h2 style='margin-top: 50px'> :) 欢迎您使用 snake 通用后台</h2>";
        echo "<h2>访问后台请在地址后面输入 /admin</h2><br/>";
        echo "默认用户名: admin  默认密码: admin";
    }
}
