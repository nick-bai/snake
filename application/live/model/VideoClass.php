<?php
# @Date:   2018/03/22
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: Live.php
# @Last modified time: 2018/03/28
# @License: MIT

namespace app\live\model;

use think\Model;

/**
 *
 */
class VideoClass extends Model
{
    public $error_msg = '';

    #自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取分类列表
     * @param  int $offset 页号
     * @param  int $limit  每页显示条数
     * @return Object       分类数据对象
     */
    public function getClassList($offset = false, $limit = false)
    {
        if ($offset || $limit) {
            return $this->order('sorting desc')
                ->field('id as video_class_id, name, sorting')
                ->select();
        }
        return $this->limit($offset, $limit)
            ->order('sorting desc')
            ->field('id as video_class_id, name, sorting')
            ->select();
    }

    /**
     * 获取分类信息
     * @param  int $class_id 分类id
     * @return Object        分类数据对象
     */
    public function getClass($class_id)
    {
        return $this::get(function($query) use ($class_id){
            $query->where(['id' => (int)$class_id])
                ->field('id as video_class_id, name, sorting');
        });
    }

    /**
     * 添加分类
     * @param array $param 分类数据
     * @return int/bool     主键id
     */
    public function insertClass($param)
    {
        try {
            $this->save($param);
            return $this->id;
        } catch (\PDOException $e) {
            $this->error_msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 编辑分类
     * @param  array $param 分类数据
     * @return bool         是否成功
     */
    public function editClass($param)
    {
        try {
            $this->allowField(true)->save($param, ['id' => $param['video_class_id']]);
            return true;
        } catch (\PDOException $e) {
            $this->error_msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 删除分类
     * @param  int $class_id 分类id
     * @return bool         是否成功
     */
    public function delClass($class_id)
    {
        try {
            $this::destroy((int)$class_id);
            return true;
        } catch (\PDOException $e) {
            $this->error_msg = $e->getMessage();
            return false;
        }

    }
}
