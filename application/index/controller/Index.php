<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        echo makePassword('admin');
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
