<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/10/30
 * Time: 8:19 PM
 */
namespace tool;

use app\admin\model\Operate;

class Log
{
    public static function write($content)
    {
        $controller = lcfirst(request()->controller());
        $action = request()->action();
        $checkInput = $controller . '/' . $action;

        $logModel = new Operate();
        $logModel->writeLog([
            'operator' => session('admin_user_name'),
            'operator_ip' => request()->ip(),
            'operate_method' => $checkInput,
            'operate_desc' => $content,
            'operate_time' => date('Y-m-d H:i:s')
        ]);
    }
}