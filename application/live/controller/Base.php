<?php
# @Date:   2018/03/22
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: Live.php
# @Last modified time: 2018/03/22
# @License: MIT
namespace app\live\controller;

use think\Controller;

use app\service\api\Auth;
use app\service\logic\Sign;
/**
 *
 */
class Base extends Controller
{
    //$user = array('status'=>1,'userInfo'=>array('userId'=>'1234556','mobile'=>'18516274516'));
    protected $userInfo;
    protected $sourceType;

    public function _initialize()
    {

    }

    protected function checkSource()
    {
        $param = $this->request->param();
        if (empty($param['status']) && empty($param['openid'])) {
            return false;
        }
        $this->sourceType = empty($param['status']) ? 'app' : 'wechat';
        return true;
    }

    protected function auth()
    {
        if ('app' === $this->sourceType) {
            
        }elseif ('wechat' === $this->sourceType) {

        }else {
            return false;
        }
    }
}
