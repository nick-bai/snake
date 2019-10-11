<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/3/17
 * Time: 4:48 PM
 */
namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    protected $table = 'bsa_admin';

    /**
     * 获取管理员
     * @param $limit
     * @param $where
     * @return array
     */
    public function getAdmins($limit, $where)
    {
        $prefix = config('database.prefix');

        try {

            $res = $this->field($prefix . 'admin.*,' . $prefix . 'role.role_name')->where($where)
                ->leftJoin($prefix . 'role', $prefix . 'admin.role_id = ' . $prefix . 'role.role_id')
                ->order('admin_id', 'desc')->paginate($limit);

        }catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $res, 'ok');
    }

    /**
     * 增加管理员
     * @param $admin
     * @return array
     */
    public function addAdmin($admin)
    {
        try {

            $has = $this->where('admin_name', $admin['admin_name'])->findOrEmpty()->toArray();
            if(!empty($has)) {
                return modelReMsg(-2, '', '管理员名已经存在');
            }

            $this->insert($admin);
        }catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '添加管理员成功');
    }

    /**
     * 获取管理员信息
     * @param $adminId
     * @return array
     */
    public function getAdminById($adminId)
    {
        try {

            $info = $this->where('admin_id', $adminId)->findOrEmpty()->toArray();
        }catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $info, 'ok');
    }

    /**
     * 编辑管理员
     * @param $admin
     * @return array
     */
    public function editAdmin($admin)
    {
        try {

            $has = $this->where('admin_name', $admin['admin_name'])->where('admin_id', '<>', $admin['admin_id'])
                ->findOrEmpty()->toArray();
            if(!empty($has)) {
                return modelReMsg(-2, '', '管理名已经存在');
            }

            $this->save($admin, ['admin_id' => $admin['admin_id']]);
        }catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '编辑管理员成功');
    }

    /**
     * 删除管理员
     * @param $adminId
     * @return array
     */
    public function delAdmin($adminId)
    {
        try {
            if (1 == $adminId) {
                return modelReMsg(-2, '', 'admin管理员不可删除');
            }

            $this->where('admin_id', $adminId)->delete();
        } catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, '', '删除成功');
    }

    /**
     * 获取管理员信息
     * @param $name
     * @return array
     */
    public function getAdminByName($name)
    {
        try {

            $info = $this->where('admin_name', $name)->findOrEmpty()->toArray();
        } catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $info, 'ok');
    }

    /**
     * 获取管理员信息
     * @param $id
     * @return array
     */
    public function getAdminInfo($id)
    {
        try {

            $info = $this->where('admin_id', $id)->findOrEmpty()->toArray();
        } catch (\Exception $e) {

            return modelReMsg(-1, '', $e->getMessage());
        }

        return modelReMsg(0, $info, 'ok');
    }

    /**
     * 更新登录时间
     * @param $id
     */
    public function updateAdminInfoById($id, $param)
    {
        try {

            $this->where('admin_id', $id)->update($param);
        } catch (\Exception $e) {

        }
    }

    /**
     * 根据角色id 获取管理员信息
     * @param $roleId
     * @return array
     */
    public function getAdminInfoByRoleId($roleId)
    {
        try {

            $info = $this->where('role_id', $roleId)->select()->toArray();
        } catch (\Exception $e) {

            return modelReMsg(-1, [], $e->getMessage());
        }

        return modelReMsg(0, $info, 'ok');
    }
}