<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: RunsTp <RunsTP@163.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;


/**
 *
 */
class Profile extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function headEdit()
    {
        return $this->fetch();
    }

    public function loginOut()
    {
        session('username', null);
        session('id', null);
        session('role', null);  // 角色名
        session('rule', null);  // 角色节点
        session('action', null);  // 角色权限

        $this->redirect(url('index'));
    }
}
