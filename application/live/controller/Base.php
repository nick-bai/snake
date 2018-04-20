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
    public $error_msg;
    protected $userInfo;
    protected $sourceType;

    public function _initialize()
    {
        // if (!$this->checkSource()) {
        //     throw new \think\Exception($this->error_msg, 100006);
        // }
        //
        // if (!$this->auth()) {
        //     throw new \think\Exception($this->error_msg, 100006);
        // }

    }

    /**
     * 来源检查
     * @return bool
     */
    protected function checkSource()
    {
        $param = $this->request->param();
        if (empty($param['status']) && empty($param['openid'])) {
            $this->error_msg = 'undefined sourceType';
            return false;
        }
        $this->sourceType = empty($param['openid']) ? 'app' : 'wechat';
        return true;
    }

    /**
     * 签名检查
     * @return  void||bool
     */
    protected function auth()
    {
        if ('app' === $this->sourceType) {
            return $this->appAuth();
        }elseif ('wechat' === $this->sourceType) {
            return $this->wechatAuth();
        }else {
            return false;
        }
    }

    /**
     * app签名验证
     * @return bool
     */
    protected function appAuth()
    {
        $param = $this->request->param();
        try {
            $data['userid'] = $param['userid'];
    		$data['sessionToken'] = $param['sessionToken'];
    		$data['source'] = $param['source'];
        } catch (\Exception $e) {
            $this->error_msg = $e->getMessage();
            return false;
        }

        $re = json_decode(Auth::auth($data), true);

        if ($re['status'] != 1 || empty($re['userInfo']['mobile'])) {
            $this->error_msg = $e->getMessage();
            return false;
        }else {
            $this->userInfo = $re['userInfo'];
            return true;
        }
    }

    /**
     * 微信签名验证
     * @return bool
     */
    protected function wechatAuth()
    {
        $param = $this->request->param();

        try {
            $data['openid'] = $param['openid'];
    		$data['nickname'] = $param['nickname'];
    		$data['isbind'] = $param['isbind'];
            $data['sign'] = $param['sign'];
        } catch (\Exception $e) {
            $this->error_msg = $e->getMessage();
            return false;
        }

        $flag = Sign::cpicSign($data['sign'], $data);

        if (false === $flag) {
            $this->error_msg = 'invalid sign';
            return false;
        }else {
            $this->userInfo = $data;
            return true;
        }
    }
}
