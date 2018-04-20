<?php
# @Author: Runstp
# @Date:   2018/03/22
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: Video.php
# @Last modified by:
# @Last modified time: 2018/03/28
# @License: MIT
namespace app\admin\controller;

use think\Response;
use think\Image;
use app\live\model\Video as VideoModel;
use app\live\model\VideoClass as VideoClassModel;

class Video extends Base
{
    const COVER_RETURN_PATH = '/upload/cover';
    const COVER_SAVE_PATH = ROOT_PATH.'public/upload/cover';

    /**
     * 视频列表
     * @return View
     */
    public function videoList()
    {
        if ($this->request->isAjax()) {
            $param = $this->request->param();

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $video_model = new VideoModel();
            $video_list = $video_model->getVideoList($offset, $limit);

            $i = 0;
            foreach ($video_list as $key => $value) {
                $video_list[$key]['cover'] = '<img src="' . $value['cover'] . '" width="auto" height="200px">';
                $video_list[$key]['open_time'] = date('Y年m月d日', $value['open_time']);
                $video_list[$key]['over_time'] = date('Y年m月d日', $value['over_time']);
                $video_list[$key]['recommend'] = $value['recommend'] > 0 ?'是' :'否';
                $video_list[$key]['operate'] = showOperate($this->makeButton($value['video_id'], 'video'));
                $i++;
            }

            $return['rows'] = $video_list;
            $return['total'] = $i;

            return json($return);
        }

        return $this->fetch();
    }

    /**
     * 视频添加
     * @return View
     */
    public function videoAdd()
    {
        if ($this->request->isAjax()) {
            $param = $this->request->param();
            if (empty($param) || empty($param['path']) || empty($param['open_time']) || empty($param['over_time'])) {
                return json(msg(500, false, '提交参数不完整'));
            }

            $this->dateInit($param);
            if (false === $param) {
                return json(msg(500, false, '时间格式错误'));
            }

            $video_model = new VideoModel();
            $flag = $video_model->insertVideo($param);
            if (false === $flag) {
                return json(msg(500, false, $video_model->error_msg));
            }else {
                return json(msg(200, url('video/videoList'), '添加成功'));
            }
        }

        $class_model = new VideoClassModel();
        $this->assign('class_list', $class_model->select());
        return $this->fetch();
    }

    /**
     * 视频编辑
     * @return View||json
     */
    public function videoEdit()
    {
        if ($this->request->isAjax()) {
            $param = $this->request->param();
            if (empty($param)) {
                return json(msg(500, false, '提交参数不完整'));
            }

            $this->dateInit($param);
            $video_model = new VideoModel();
            $flag = $video_model->editVideo($param);
            if (false === $flag) {
                return json(msg(500, false, $video_model->error_msg));
            }else {
                return json(msg(200, url('video/videoList'), '修改成功'));
            }
        }

        $video_id = $this->request->param('video_id');
        if (empty($video_id)) {
            return Response('not found video', 500);
        }

        $video_model = new VideoModel();
        $video_data = $video_model->getVideo($video_id);

        if (null === $video_data) {
            return json(msg(500, url('vieo/videoList'), '找不到该视频'));
        }else {
            $video_data['open_time'] = date('Y-m-d H:m', $video_data['open_time']);
            $video_data['over_time'] = date('Y-m-d H:m', $video_data['over_time']);

            $class_model = new VideoClassModel();

            $this->assign('class_list', $class_model->select());
            $this->assign('video_data', $video_data);
            return $this->fetch();
        }
    }

    /**
     * 删除视频
     * @return json
     */
    public function videoDel()
    {
        if (!$this->request->isAjax()) {
            return Response('not supported', 500);
        }

        $video_id = $this->request->param('video_id');
        if (empty($video_id)) {
            return json(msg(500, false, 'not found video_id'));
        }

        $video_model = new VideoModel();
        $flag = $video_model->delVideo($video_id);
        if (false === $flag) {
            return json(msg(500, false, $video_model->error_msg));
        }else {
            return json(msg(200, true, '删除成功'));
        }
    }

    /**
     * 分类列表
     * @return View||json 分类列表
     */
    public function classList()
    {
        if ($this->request->isAjax()) {
            $param = $this->request->param();
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $class_model = new VideoClassModel();
            $class_list = $class_model->getClassList($offset, $limit);

            $i = 0;
            foreach ($class_list as $key => $value) {
                $class_list[$key]['operate'] = showOperate($this->makeButton($value['video_class_id'], 'class'));
                $i++;
            }

            $return['rows'] = $class_list;
            $return['total'] = $i;

            return json($return);
        }
        return $this->fetch();
    }

    /**
     * 添加视频分类
     * @return View||Json 添加状态
     */
    public function classAdd()
    {
        if ($this->request->isAjax()) {
            $param = $this->request->param();
            if (empty($param)) {
                return json(msg(500, false, '未提交任何内容'));
            }

            $class_model = new VideoClassModel();
            $flag = $class_model->insertClass($param);
            if (false === $flag) {
                return json(msg(500, false, $class_model->error_msg));
            }else {
                return json(msg(200, url('video/classList'), '添加成功'));
            }
        }
        return $this->fetch();
    }

    /**
     * 编辑视频分类
     * @return View||Json
     */
    public function classEdit()
    {
        if ($this->request->isAjax()) {
            $param = $this->request->param();
            if (empty($param) || empty($param['video_class_id'])) {
                return json(msg(500, false, ''));
            }
            $class_model = new VideoClassModel();
            $flag = $class_model->editClass($param);

            if (false === $flag) {
                return json(msg(500, false, $class_model->error_msg));
            }else {
                return json(msg(200, url('video/classList'), '修改成功'));
            }
        }

        $video_class_id = $this->request->param('video_class_id');
        $class_model = new VideoClassModel();
        $class_data = $class_model->getClass($video_class_id);
        if (false === $class_data) {
            return $this->error('数据错误,请联系管理员');
        }
        $this->assign('class_data', $class_data);
        return $this->fetch();
    }

    /**
     * 删除分类
     * @return json 状态
     */
    public function classDel()
    {
        if (!$this->request->isAjax()) {
            return Response('not supported', 500);
        }

        $param = $this->request->param();
        if (empty($param || $param['video_class_id'])) {
            return json(msg(500, false, 'not found video_class_id'));
        }

        $class_model = new VideoClassModel();
        $flag = $class_model->delClass($param['video_class_id']);

        if (false === $flag) {
            return json(msg(500, false, $class_model->error_msg));
        }else {
            return json(msg(200, true, '添加成功'));
        }
    }

    /**
     * 上传图片
     * @return json
     */
    public function uploadImg()
    {
        if (!$this->request->isAjax()) {
            return json(msg(500, false, 'not supported'));
        }

        //获取上传文件并检查
        $file = $this->request->file('file');
        if (!$file->checkImg()) {
            return json(msg(500, false, 'not supported'));
        }
        //获取文件后缀并且保存
        $image_type = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);
        $save_name = $this->getImageName($image_type);
        $info = $file->move($this::COVER_SAVE_PATH, $save_name);
        if ($info) {
            $image_mode = new \app\service\model\Image();
            $save_path = $this::COVER_SAVE_PATH. '/'. $save_name;
            try {
                $image_mode->saveImage($save_path, $info->hash('md5'));
            } catch (\Exception $e) {
                return json(msg(500, false, $image_mode->error_msg));
            }
            return json(msg(200, ['path' => $this::COVER_RETURN_PATH. '/'. $save_name], '上传成功'));
        }
        return json(msg(500, false, '保存失败'));
    }

    /**
     * 获取随机图片名
     * @param  string $image_type 文件后缀
     * @return string             文件名
     */
    private function getImageName($image_type)
    {
        return getRandom(). '.'. $image_type;
    }

    /**
     * 时间格式化（date转时间戳）
     * @param  array $array 提交数据
     * @return array
     */
    private function dateInit(&$array)
    {
        foreach ($array as $k => $v) {
            if ('open_time' === $k || 'over_time' === $k) {
                $array[$k] = strtotime($v);
                if (false === $array[$k]) {
                    return false;
                }
            }
        }
    }

    /**
     * 生成操作按钮
     * @param  int $id      主键id
     * @param  string $type 类别
     * @return array        操作按钮
     */
    private function makeButton($id, $type)
    {
        if ('video' === $type) {
            $name = 'video_id';
        }else{
            $name = 'video_class_id';
        }
        return [
            '编辑' => [
                'auth' => 'video/'.$type. 'edit',
                'href' => url("video/". $type. "Edit", [$name => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'video/'.$type. 'del',
                'href' => "javascript:". $type. "Del(" .$id .")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
