<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Node;
use app\admin\model\NodeModel;
use app\admin\model\RoleModel;
use app\admin\model\UserType;

class Role extends Base
{
    // 角色列表
    public function index()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['role_name'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $user = new RoleModel();
            $selectResult = $user->getRoleByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                // 不允许操作超级管理员
                if(1 == $vo['id']){
                    $selectResult[$key]['operate'] = '';
                    continue;
                }

                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));

            }

            $return['total'] = $user->getAllRole($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加角色
    public function roleAdd()
    {
        if(request()->isPost()){

            $param = input('post.');

            $role = new RoleModel();
            $flag = $role->insertRole($param);
            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    // 编辑角色
    public function roleEdit()
    {
        $role = new RoleModel();

        if(request()->isPost()){

            $param = input('post.');
            $flag = $role->editRole($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'role' => $role->getOneRole($id)
        ]);
        return $this->fetch();
    }

    // 删除角色
    public function roleDel()
    {
        $id = input('param.id');

        $role = new RoleModel();
        $flag = $role->delRole($id);
        $this->removRoleCache();
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }

    // 分配权限
    public function giveAccess()
    {
        $param = input('param.');
        $node = new NodeModel();
        // 获取现在的权限
        if('get' == $param['type']){

            $nodeStr = $node->getNodeInfo($param['id']);
            return json(msg(1, $nodeStr, 'success'));
        }

        // 分配新权限
        if('give' == $param['type']){

            $doparam = [
                'id' => $param['id'],
                'rule' => $param['rule']
            ];
            $user = new RoleModel();
            $flag = $user->editAccess($doparam);

            $this->removRoleCache();
            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }
    }

    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'role/roleedit',
                'href' => url('role/roleEdit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'role/roledel',
                'href' => "javascript:roleDel(" .$id .")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ],
            '分配权限' => [
                'auth' => 'role/giveaccess',
                'href' => "javascript:giveQx(" .$id .")",
                'btnStyle' => 'info',
                'icon' => 'fa fa-institution'
            ],
        ];
    }
}
