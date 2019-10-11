<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/3/17
 * Time: 4:48 PM
 */
namespace app\admin\model;

use think\facade\Cache;
use think\facade\Log;
use think\Model;

class Node extends Model
{
    protected $table = 'bsa_node';

    /**
     * 获取节点数据
     * @return array
     */
    public function getNodesList()
    {
        try {

            $res = $this->field('node_id as id,node_name as title,node_pid as pid,node_path,node_icon,add_time,is_menu')
                ->select()->toArray();
        }catch (\Exception $e) {

            return modelReMsg(-1, [], $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 获取节点数据
     * @param $roleId
     * @return array
     */
    public function getNodesTree($roleId)
    {
        try {

            $result = $this->field('node_id,node_name,node_pid')->select();
            $str = '';

            $roleModel = new Role();
            $rule = $roleModel->getRoleInfoById($roleId);

            $nodeArr = [];
            if(!empty($rule['data'])){
                $nodeArr = explode(',', $rule['data']['role_node']);
            }

            foreach($result as $key => $vo){
                $str .= '{ "id": "' . $vo['node_id'] . '", "pId":"' . $vo['node_pid'] . '", "name":"' . $vo['node_name'].'"';

                if(!empty($nodeArr) && in_array($vo['node_id'], $nodeArr)){
                    $str .= ' ,"checked":1';
                }

                $str .= '},';

            }

            $res = '[' . rtrim($str, ',') . ']';

        }catch (\Exception $e) {

            return modelReMsg(-1, [], $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 根据节点id获取节点信息
     * @param $ids
     * @return array
     */
    public function getNodeInfoByIds($ids)
    {
        try {

            $res = $this->whereIn('node_id', $ids)->select()->toArray();
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 添加节点
     * @param $param
     * @return array
     */
    public function addNode($param)
    {
        try {

            // 检测唯一
            $has = $this->field('node_id')->where('node_name', $param['node_name'])
                ->where('node_pid', $param['node_pid'])->find();

            if (!empty($has)) {

                return modelReMsg(-2, '', '该节点名称已经存在');
            }

            $param['add_time'] = date('Y-m-d H:i:s');

            $this->insert($param);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '添加节点成功');
    }

    /**
     * 编辑节点
     * @param $param
     * @return array
     */
    public function editNode($param)
    {
        try {

            // 检测唯一
            $has = $this->field('node_id')->where('node_name', $param['node_name'])
                ->where('node_pid', $param['node_pid'])->where('node_id', '<>', $param['node_id'])->find();

            if (!empty($has)) {

                return modelReMsg(-2, '', '该节点名称已经存在');
            }

            $this->where('node_id', $param['node_id'])->update($param);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '编辑节点成功');
    }

    /**
     * 根据id 获取节点信息
     * @param $id
     * @return array
     */
    public function getNodeInfoById($id)
    {
        try {

            $res = $this->where('node_id', $id)->find();

        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return modelReMsg(-1, ['node_name' => ''], $e->getMessage());
        }

        return modelReMsg(0, $res, '编辑节点成功');
    }

    /**
     * 根据id 删除节点
     * @param $id
     * @return array
     */
    public function deleteNodeById($id)
    {
        try {

            // 检测节点下是否有其他的节点
            $has = $this->where('node_pid', $id)->count();
            if ($has > 0) {
                return modelReMsg(-2, '', '该节点下尚有其他节点，不可删除');
            }

            $this->where('node_id', $id)->delete();

        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '删除节点成功');
    }

    /**
     * 获取节点的id
     * @param $path
     * @return array
     */
    public function getNodeIdByPath($path)
    {
        try {

            $res = $this->field('node_id')->where('node_path', $path)->find();

        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return modelReMsg(-1, [], $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 获取角色菜单集合
     * @param $roleId
     * @return array
     */
    public function getRoleMenuMap($roleId)
    {
        try {
            $res = [];

            if (1 == $roleId) {

                $res = $this->field('node_id as id,node_name as title,node_pid as pid,node_path,node_icon')
                    ->where('is_menu', 2)->select()->toArray();
            } else {

                $roleModel = new Role();
                $roleInfo = $roleModel->getRoleInfoById($roleId)['data'];

                if (!empty($roleInfo)) {

                    $res = $this->field('node_id as id,node_name as title,node_pid as pid,node_path,node_icon')
                        ->whereIn('node_id', $roleInfo['role_node'])->where('is_menu', 2)->select()->toArray();
                }
            }
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return modelReMsg(-1, [], $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }
}