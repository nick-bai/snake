<?php
# @Date:   2018/03/22
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: Video.php
# @Last modified time: 2018/03/28
# @License: MIT
namespace app\live\model;

use think\Model;
use traits\model\SoftDelete;

/**
 *
 */
class Video extends Model
{
    #引入软删除
    use SoftDelete;

    const GENERAL_FIELD = 'id as video_id, title, video_class_id, cover, path, type, recommend, open_time, over_time';
    public $error_msg = '';

    #自动写入时间戳
    protected $autoWriteTimestamp = true;
    #软删除字段
    protected $deleteTime = 'delete_time';

    /**
     * 获取视频列表
     * @param  int $offset      页号
     * @param  int $limit       每页显示条数
     * @param  int $class_id    分类id
     * @param  bool              $all_list 是否获取全部
     * @return Object           视频列表数据对象
     */
    public function getVideoList($offset, $limit, $video_class_id = false, $all_list = true)
    {
        if (false === $video_class_id && true === $all_list) {
            return $this->field($this::GENERAL_FIELD)
                ->limit($offset, $limit)
                ->select();
        }else {
            if (false === $all_list) {
                $video_data = $this->field($this::GENERAL_FIELD)
                    ->where(['recommend' => 1])
                    ->limit($offset, $limit)
                    ->select();
            }else {
                $video_data = $this->field($this::GENERAL_FIELD)
                    ->where(['video_class_id' => (int)$video_class_id])
                    ->limit($offset, $limit)
                    ->select();
            }
        }

        if (0 === count($video_data)) {
            return null;
        }
        return $this->detachData($video_data);
    }

    /**
     * 获取视频信息
     * @param  int $video_id 视频id
     * @return Object        视频数据对象
     */
    public function getVideo($video_id)
    {
        return $this::get(function($query) use ($video_id){
            $query->where(['id' => (int)$video_id])
                ->field($this::GENERAL_FIELD);
        });
    }

    /**
     * 添加视频
     * @param  array $param 视频数据
     * @return int          视频主键
     */
    public function insertVideo($param)
    {
        try {
            $this->allowField(true)->save($param);
            return $this->id;
        } catch (\PDOException $e) {
            $this->error_msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 修改视频
     * @param  array $param 视频数据
     * @return bool        是否成功
     */
    public function editVideo($param)
    {
        try {
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            return true;
        } catch (\PDOException $e) {
            $this->error_msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 删除视频
     * @param  int $video_id 视频id
     * @return bool           是否成功
     */
    public function delVideo($video_id)
    {
        try {
            $this::destroy((int)$video_id);
            return true;
        } catch (\PDOException $e) {
            $this->error_msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 分离视频数据
     * @param  array||Object $video_data 视频数据
     * @return array             分离的数据
     */
    protected function detachData($video_data)
    {
        $video_data = json_decode(json_encode($video_data), true);

        $time = time();
        $new_data = [];
        foreach ($video_data as $list_key => $list_value) {
            if ($time <= $list_value['open_time']) {
                $new_data['live'][] = &$list_value;
            }else {
                if ($time >= $list_value['over_time']) {
                    $new_data['replay'][] = &$list_value;
                }else {
                    $new_data['liveing'][] = &$list_value;
                }
            }
            $this->timeToDate($list_value);
        }
        return $new_data;
    }

    /**
     * 将开始时间和结束时间格式化
     * @param  array $video_data 视频数据
     * @return array
     */
    protected function timeToDate(&$list_value)
    {
        if (!empty($list_value['open_time'])) {
            $list_value['open_time'] = date('Y年m月d日', $list_value['open_time']);
        }
        if (!empty($list_value['over_time'])) {
            $list_value['over_time'] = date('Y年m月d日', $list_value['over_time']);
        }
    }
}
