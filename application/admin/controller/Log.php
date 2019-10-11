<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/10/11
 * Time:  14:23
 */
namespace app\admin\controller;

use app\admin\model\Operate;
use app\admin\model\LoginLog;

class Log extends Base
{
    // 登录日志
    public function login()
    {
        if(request()->isAjax()) {

            $limit = input('param.limit');

            $log = new LoginLog();
            $list = $log->loginLogList($limit);

            if(0 == $list['code']) {

                return json(['code' => 0, 'msg' => 'ok', 'count' => $list['data']->total(), 'data' => $list['data']->all()]);
            }

            return json(['code' => 0, 'msg' => 'ok', 'count' => 0, 'data' => []]);
        }

        return $this->fetch();
    }

    // 操作日志
    public function operate()
    {
        if (request()->isAjax()) {

            $limit = input('param.limit');
            $operateTime = input('param.operate_time');

            $where = [];

            if (!empty($operateTime)) {
                $where[] = ['operate_time', 'between', [$operateTime, $operateTime. ' 23:59:59']];
            }

            $operateModel = new Operate();
            $list = $operateModel->getOperateLogList($limit, $where);

            if(0 == $list['code']) {

                return json(['code' => 0, 'msg' => 'ok', 'count' => $list['data']->total(), 'data' => $list['data']->all()]);
            }

            return json(['code' => 0, 'msg' => 'ok', 'count' => 0, 'data' => []]);
        }

        return $this->fetch();
    }
}