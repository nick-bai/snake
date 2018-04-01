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

use think\Response;
use think\Image;
use app\admin\model\UserModel;
/**
 *
 */
class Profile extends Base
{
    //public 绝对路径， 用于用户提交相对路径时追加
    const PUBLIC_PATH = ROOT_PATH.'public';
    //相对路径，用于返回前端
    const HEAD_RETURN_PATH = '/upload/head';
    //绝对路径，用于存储地址
    const HEAD_SAVE_PATH = ROOT_PATH.'public/upload/head';

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

    /**
     * 上传头像
     * @return json
     */
    public function uploadHeade()
    {
        if (!$this->request->isAjax()) {
            return Response('not supported', 500);
        }

        //获取文件并检查，注意这里使用croppic插件的特定json返回。
        $file = $this->request->file('img');
        if (empty($file) && !$file->checkImg()) {
            return json(['status' => 'error', 'message' => 'not found image']);
        }

        //获取文件后缀名
        $image_type = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
        $save_name = $this->getImageName($image_type);
        $info = $file->move($this::HEAD_SAVE_PATH, $save_name);

        if (false === $info) {
            return json(['status' => 'error', 'message' => $file->error]);
        }else {
            //返回图像信息
            $image = Image::open($this::HEAD_SAVE_PATH. '/'. $save_name);
            return json([
                'status' => 'success',
                'url' => $this::HEAD_RETURN_PATH. '/'. $save_name,
                "width" => $image->width(),
				"height" => $image->height()
            ]);
        }
    }


    public function cropHeade()
    {
        if (!$this->request->isAjax()) {
            return Response('not supported', 500);
        }

        $param = $this->request->param();
        if (empty($param) || empty($param['imgUrl'])) {
            return json(['status' => 'error', 'message' => 'not found image']);
        }

        //抛出符合croppic插件规范的异常，防止前端js错误
        try {
            $image = Image::open($this::PUBLIC_PATH. $param['imgUrl']);
            //有旋转
            if(!empty($param['rotation'])){
                $image->rotate((int)$param['rotation']);
            }

            //裁剪
            $image->crop(
                $param['cropW'],    //裁剪区域宽度
                $param['cropH'],    //裁剪区域高度
                $param['imgX1'],    //裁剪区域x坐标
                $param['imgY1'],    //裁剪区域y坐标
                $param['imgW'],     //图像保存宽度
                $param['imgH']      //图像保存高度
                )
                ->save($this::PUBLIC_PATH. $param['imgUrl']);

            return json(['status' => 'success', 'url' => $this::PUBLIC_PATH. $param['imgUrl']]);
        } catch (\Exception $e) {
            return json(['status' => 'error', 'message' => $e->getMessage()]);
        }
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

    /**
     * 获取图像名称(固定长度32)
     * @param  string  $image_type 图像类型
     * @return string            随机图像名称
     */
    private function getImageName($image_type)
    {
        $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        $name = substr(str_shuffle($str), mt_rand(0, 30), 32);
        return $name. '.'. $image_type;
    }
}
