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
use think\Model;

class Role extends Model
{
    protected $table = 'bsa_role';

    /**
     * 获取角色列表
     * @param $limit
     * @param $where
     * @return array
     */
    public function getRolesList($limit, $where)
    {
        try {

            $res = $this->where($where)->order('role_id', 'desc')->paginate($limit);

        }catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 增加角色
     * @param $param
     * @return array
     */
    public function addRole($param)
    {
        try {

            $has = $this->where('role_name', $param['role_name'])->find();
            if(!empty($has)) {
                return modelReMsg(-2, '', '角色名称已经存在');
            }

            $param['role_node'] = '1,2,3'; // 默认权限

            $this->insert($param);
        }catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '添加角色成功');
    }

    /**
     * 获取角色信息
     * @param $id
     * @return array
     */
    public function getRoleInfoById($id)
    {
        try {

            $info = $this->where('role_id', $id)->findOrEmpty()->toArray();
        }catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $info, 'ok');
    }

    /**
     * 编辑角色
     * @param $param
     * @return array
     */
    public function editRole($param)
    {
        try {

            $has = $this->where('role_name', $param['role_name'])->where('role_id', '<>', $param['role_id'])
                ->findOrEmpty()->toArray();
            if(!empty($has)) {
                return modelReMsg(-2, '', '角色名已经存在');
            }

            $this->save($param, ['role_id' => $param['role_id']]);
        }catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '编辑角色成功');
    }

    /**
     * 删除角色
     * @param $id
     * @return array
     */
    public function delRoleById($id)
    {
        try {
            if (1 == $id) {
                return modelReMsg(-2, '', '超级管理员不可删除');
            }

            // 检测角色下是否有管理员
            $adminModel = new Admin();
            $has = $adminModel->getAdminInfoByRoleId($id);

            if (!empty($has['data'])) {
                return modelReMsg(-2, '', '该角色下有管理员，不可删除');
            }

            $this->where('role_id', $id)->delete();
        } catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '删除成功');
    }

    /**
     * 获取所有的角色
     * @return array
     */
    public function getAllRoles()
    {
        try {

            $res = $this->where('role_status', 1)->select()->toArray();
        } catch (\Exception $e) {

            return modelReMsg(-1, [], $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 通过id更新角色信息
     * @param $roleId
     * @param $param
     * @return array
     */
    public function updateRoleInfoById($roleId, $param)
    {
        try {

            $res = $this->where('role_id', $roleId)->update($param);
        } catch (\Exception $e) {

            return modelReMsg(-1, [], $e->getMessage());
        }

        return modelReMsg(0, $res, '成功');
    }

    /**
     * 获取角色的权限节点数组
     * @param $roleId
     * @return array
     */
    public function getRoleAuthNodeMap($roleId)
    {
        $map = Cache::get("role_" . $roleId . "_map");

        if (empty($map)) {

            try {

                $res = $this->where('role_id', $roleId)->find();
                if (!empty($res)) {

                    $map = $this->cacheRoleNodeMap($res['role_node'], $roleId);
                }

            }catch (\Exception $e) {

                return modelReMsg(-1, $map, $e->getMessage());
            }
        }

        return modelReMsg(0, $map, 'ok');
    }

    /**
     * 缓存角色节点信息
     * @param $roleNode
     * @param $roleId
     * @return array
     */
    public function cacheRoleNodeMap($roleNode, $roleId)
    {
        $nodeModel = new Node();
        $nodeInfo = $nodeModel->getNodeInfoByIds($roleNode);

        $map = [];
        if (!empty($nodeInfo['data'])) {

            foreach ($nodeInfo['data'] as $vo) {
                if (empty($vo['node_path']) || '#' == $vo['node_path']) continue;

                $map[$vo['node_path']] = $vo['node_id'];
            }

            Cache::set("role_" . $roleId . "_map", $map);
        }

        return $map;
    }
}