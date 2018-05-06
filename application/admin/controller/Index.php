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
use app\admin\model\NodeModel;

class Index extends Base
{
    public function index()
    {
        // 获取权限菜单
        $node = new NodeModel();

        $this->assign([
            'menu' => $node->getMenu(session('rule'))
        ]);

        return $this->fetch('/index');
    }

    /**
     * 后台默认首页
     * @return mixed
     */
    public function indexPage()
    {
        // 生成从 8点 到 22点的时间数组
        $dateLine = array_map(function($vo){
            if($vo < 10){
                return '0' . $vo;
            }else{
                return $vo;
            }
        }, range(8, 22));

        // 初始化数据
        $line = [];
        foreach($dateLine as $key=>$vo){
            $line[$vo] = [
                'is_talking' => intval(rand(20, 120)),
                'in_queue' => intval(rand(0, 20)),
                'success_in' => intval(rand(50, 200)),
                'total_in' => intval(rand(150, 300))
            ];
        }

        $showData = [];
        foreach($line as $key=>$vo){
            $showData['is_talking'][] = $vo['is_talking'];
            $showData['in_queue'][] = $vo['in_queue'];
            $showData['success_in'][] = $vo['success_in'];
            $showData['total_in'][] = $vo['total_in'];
        }

        $this->assign([
            'show_data' => json_encode($showData)
        ]);

        return $this->fetch('index');
    }
}
