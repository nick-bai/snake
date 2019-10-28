<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/10/11
 * Time:  13:54
 */
namespace app\admin\behavior;

use app\admin\model\Operate;
use think\Request;

class OperateLog
{
    public function run(Request $request, $params)
    {
        $controller = lcfirst($request->controller());
        $action = $request->action();
        $checkInput = $controller . '/' . $action;

        $logModel = new Operate();
        $logModel->writeLog([
            'operator' => session('admin_user_name'),
            'operator_ip' => $request->ip(),
            'operate_method' => $checkInput,
            'operate_desc' => $params['info']['0'],
            'operate_time' => date('Y-m-d H:i:s')
        ]);
    }
}