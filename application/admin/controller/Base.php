<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Node;
use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {
        if(empty(session('username'))){

            $this->redirect(url('login/index'));
        }

        //检测权限
        $control = request()->controller();
        $action = request()->action();

        //跳过登录系列的检测以及主页权限
        if(!in_array($control, ['login', 'index'])){

            if(!in_array($control . '/' . $action, session('action'))){
                $this->error('没有权限');
            }
        }

        //获取权限菜单
        $node = new Node();

        $this->assign([
            'username' => session('username'),
            'menu' => $node->getMenu(session('rule')),
            'rolename' => session('role')
        ]);

    }
}