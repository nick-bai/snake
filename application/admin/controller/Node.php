<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/9/3
 * Time:  14:30
 */
namespace app\admin\controller;

use app\admin\model\Node as NodeModel;
use app\admin\validate\NodeValidate;
use tool\Log;

class Node extends Base
{
    // 节点列表
    public function index()
    {
        $node = new NodeModel();
        $list = $node->getNodesList();

        $this->assign([
            'tree' => makeTree($list['data'])
        ]);

        return $this->fetch();
    }

    // 添加节点
    public function add()
    {
        if (request()->isAjax()) {

            $param = input('post.');

            $validate = new NodeValidate();
            if(!$validate->check($param)) {
                return ['code' => -1, 'data' => '', 'msg' => $validate->getError()];
            }

            $nodeModel = new NodeModel();
            $res = $nodeModel->addNode($param);

            Log::write("添加节点：" . $param['node_name']);

            return json($res);
        }

        $this->assign([
            'pname' => input('param.pname'),
            'pid' => input('param.pid')
        ]);

        return $this->fetch();
    }

    // 编辑节点
    public function edit()
    {
        if (request()->isAjax()) {

            $param = input('post.');

            $validate = new NodeValidate();
            if(!$validate->check($param)) {
                return ['code' => -1, 'data' => '', 'msg' => $validate->getError()];
            }

            $nodeModel = new NodeModel();
            $res = $nodeModel->editNode($param);

            Log::write("编辑节点：" . $param['node_name']);

            return json($res);
        }

        $id = input('param.id');
        $pid = input('param.pid');

        $nodeModel = new NodeModel();

        if (0 == $pid) {
            $pNode = '顶级节点';
        } else {
            $pNode = $nodeModel->getNodeInfoById($pid)['data']['node_name'];
        }

        $this->assign([
            'node_info' => $nodeModel->getNodeInfoById($id)['data'],
            'p_node' => $pNode
        ]);

        return $this->fetch();
    }

    // 删除节点
    public function delete()
    {
        if (request()->isAjax()) {

            $id = input('param.id');

            $nodeModel = new NodeModel();
            $res = $nodeModel->deleteNodeById($id);

            Log::write("删除节点：" . $id);

            return json($res);
        }
    }
}