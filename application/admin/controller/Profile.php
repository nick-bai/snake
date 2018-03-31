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

use app\admin\model\UserModel;
/**
 *
 */
class Profile extends Base
{
    /**
     * 修改个人信息
     * @return json||View
     */
    public function index()
    {

        //提交修改
        if ($this->request->isAjax()) {
            $param = $this->request->param();
            if (empty($param)) {
                return json(msg(-1, '', 'not found user'));
            }

            if ($param['new_password'] !== $param['re_new_password']) {
                return json(msg(-2, '', '两次输入的密码不相同'));
            }

            $user_model = new UserModel();
            $user_data = $user_model->getOneUser(session('id'));

            if (is_null($user_data)) {
                return json(msg(-1, '', 'not found user'));
            }

            if ($user_data['password'] !== md5($param['old_password']. config('salt'))) {
                return json(msg(-3, '', '原始密码错误'));
            }

            $flag = $user_model->updateStatus($param, session('id'));
            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        //访问
        $user_model = new UserModel();
        $user_data = $user_model->getOneUser(session('id'));
        if (is_null($user_data)) {
            return json(msg(-1, '', 'not found user'));
        }

        $this->assign('user_data', $user_data);
        return $this->fetch();
    }


    public function headEdit()
    {
        return $this->fetch();
    }

    public function uploadHeade()
    {
        dump($this->request->param('post.'));
        dump($this->request->file());die;
    }

    public function cropHeade()
    {
        dump($this->request->param('post.'));
        dump($this->request->file());die;
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
