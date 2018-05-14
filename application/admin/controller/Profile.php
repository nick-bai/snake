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
    private $public_path;
    //相对路径，用于返回前端
    private $head_return_path;
    //绝对路径，用于存储地址
    private $head_save_path;

    public function _initialize()
    {
        parent::_initialize();
        $this->public_path = ROOT_PATH.'public';
        $this->head_return_path = '/upload/head';
        $this->head_save_path = ROOT_PATH.'public/upload/head';
    }

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

            if ($user_data['password'] === md5($param['new_password']. config('salt'))) {
                return json(msg(-4, '', '新密码不能和旧密码相同'));
            }

            $param['password'] = md5($param['new_password']. config('salt'));
            $flag = $user_model->updateStatus($param, session('id'));
            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    /**
     * 头像编辑
     * @return View
     */
    public function headEdit()
    {

        if ($this->request->isAJax()) {
            $param = $this->request->param();
            if (empty($param)) {
                return json(msg(-1, url('index/indexpage'), 'not found user'));
            }

            if (!empty($param['user_name'])) {
                unset($param['user_name']);
            }

            $user_model = new UserModel();
            $flag = $user_model->save($param, ['id' => session('id')]);
            if ($flag) {
                $this->removRoleCache();
                return json(msg(1, url('index/indexpage'), 'ok'));
            }else {
                return json(msg(-1, '', '修改失败'));
            }

        }

        //访问
        $user_model = new UserModel();
        $user_data = $user_model->getOneUser(session('id'));
        if (is_null($user_data)) {
            return json(msg(-1, url('login/index'), 'not found user'));
        }

        $this->assign('user_data', $user_data);
        return $this->fetch();
    }

    /**
     * 上传头像
     * 这里是croppic插件的处理逻辑
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
        $info = $file->move($this->head_save_path, $save_name);

        if (false === $info) {
            return json(['status' => 'error', 'message' => $file->error]);
        }else {
            //返回图像信息
            $image = Image::open($this->head_save_path. '/'. $save_name);
            return json([
                'status' => 'success',
                'url' => $this->head_return_path. '/'. $save_name,
                "width" => $image->width(),
				"height" => $image->height()
            ]);
        }
    }

    /**
     * 裁剪头像
     * 这里是croppic插件的处理逻辑
     * @return json
     */
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
            $image = Image::open($this->public_path. $param['imgUrl']);
            $save_name = $this->getImageName($image->type());

            //预处理裁剪
            //这步相当于将图像缩放
            $image->crop(
                (int)$param['imgInitW'],    //裁剪区域宽度
                (int)$param['imgInitH'],    //裁剪区域高度
                (int)0,                     //裁剪区域x坐标
                (int)0,                     //裁剪区域y坐标
                (int)$param['imgW'],        //图像保存宽度
                (int)$param['imgH']         //图像保存高度
            );

            //如果存在旋转参数
            if(!empty($param['rotation'])){
                //这里旋转生成的新图像会被GD库自动填充黑边
                $image->rotate((int)$param['rotation']);

                //获取裁剪坐标差
                $dx = $image->width() - $param['imgW'];
                $dy = $image->height() - $param['imgH'];

                //裁剪出预选定区域
                $image->crop(
                    (int)$param['imgW'],    //裁剪区域宽度
                    (int)$param['imgH'],    //裁剪区域高度
                    (int)$dx / 2,           //裁剪区域x坐标
                    (int)$dy / 2           //裁剪区域y坐标
                );
            }

            //裁剪图像
            $image->crop(
                (int)$param['cropW'],    //裁剪区域宽度
                (int)$param['cropH'],    //裁剪区域高度
                (int)$param['imgX1'],    //裁剪区域x坐标
                (int)$param['imgY1']    //裁剪区域y坐标
            );

            //保存图像
            $image->save($this->head_save_path. '/'. $save_name);
            return json(['status' => 'success', 'url' => $this->head_return_path. '/'. $save_name]);
        } catch (\think\image\Exception $e) {
            return json(['status' => 'error', 'message' => $e->getMessage()]);
        }
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
