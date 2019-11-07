<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/2/17
 * Time: 11:33 AM
 */
namespace app\admin\controller;

use app\admin\model\Admin;
use think\App;
use tool\Auth;

class Index extends Base
{
    public function index()
    {
        $authModel = new Auth();
        $menu = $authModel->getAuthMenu(session('admin_role_id'));

        $this->assign([
            'menu' => $menu
        ]);

        return $this->fetch();
    }

    public function home()
    {
        $this->assign([
            'tp_version' => App::VERSION
        ]);

        return $this->fetch();
    }

    // 修改密码
    public function editPwd()
    {
        if (request()->isPost()) {

            $param = input('post.');

            if ($param['new_password'] != $param['rep_password']) {
                return json(['code' => -1, 'data' => '', 'msg' => '两次密码输入不一致']);
            }

            // 检测旧密码
            $admin = new Admin();
            $adminInfo = $admin->getAdminInfo(session('admin_user_id'));

            if(0 != $adminInfo['code'] || empty($adminInfo['data'])){
                return json(['code' => -2, 'data' => '', 'msg' => '管理员不存在']);
            }

            if(!checkPassword($param['password'], $adminInfo['data']['admin_password'])){
                return json(['code' => -3, 'data' => '', 'msg' => '旧密码错误']);
            }

            $admin->updateAdminInfoById(session('admin_user_id'), [
                'admin_password' => makePassword($param['new_password'])
            ]);

            return json(['code' => 0, 'data' => '', 'msg' => '修改密码成功']);
        }

        return $this->fetch('pwd');
    }
}
