<?php
namespace app\install\controller;

use think\Controller;
use think\Db;

define('INSTALL_APP_PATH', realpath('./') . '/../');

/**
 * 安装控制器
 * @package app\install\controller
 */
class Index extends Controller
{
    /**
     * 获取入口目录
     */
    protected function _initialize() {
        $base_file = $this->request->baseFile();
        $base_dir  = rtrim($base_file, 'index.php');
        $this->assign('static_dir', $base_dir . 'static/');
    }

    /**
     * 安装首页
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function index()
    {
        if (is_file(APP_PATH . 'database.php')) {
            // 已经安装过了 执行更新程序
            session('reinstall', true);
            $this->assign('next', '重新安装');
        } else {
            session('reinstall', false);
            $this->assign('next', '下一步');
        }

        session('step', 1);
        session('error', false);
        return $this->fetch();
    }

    /**
     * 步骤二，检查环境
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function step2()
    {
        if (session('step') != 1 && session('step') != 3) $this->redirect($this->request->baseFile());
        if(session('reinstall')){
            session('step', 2);
            $this->redirect(url('install/index/step4'));
        }else{
            session('error', false);

            // 环境检测
            $env = check_env();

            // 目录文件读写检测
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);

            // 函数检测
            $func = check_func();

            session('step', 2);

            $this->assign('env', $env);
            $this->assign('func', $func);

            return $this->fetch();
        }
    }

    /**
     * 步骤三，设置数据库连接
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function step3()
    {
        // 检查上一步是否通过
        if ($this->request->isAjax()) {
            if (session('error')) {
                $this->error('环境检测没有通过，请调整环境后重试！');
            } else {
                $this->success('恭喜您环境检测通过', url('install/index/step3'));
            }
        }
        if (session('step') != 2) $this->redirect($this->request->baseFile());
        session('error', false);
        session('step', 3);
        return $this->fetch();
    }

    /**
     * 步骤四，创建数据库
     * @param null $db 数据库配置信息
     * @param int $cover 是否覆盖已存在数据库
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function step4($db = null, $cover = 0)
    {
        // 检查上一步是否通过
        if ($this->request->isPost()) {
            // 检测数据库配置
            if(!is_array($db) || empty($db['type'])
                || empty($db['hostname'])
                || empty($db['database'])
                || empty($db['username'])
                || empty($db['prefix'])){
                $this->error('请填写完整的数据库配置');
            }

            // 缓存数据库配置
            session('db_config', $db);

            // 防止不存在的数据库导致连接数据库失败
            $db_name = $db['database'];
            unset($db['database']);

            // 创建数据库连接
            $db_instance = Db::connect($db);

            // 检测数据库连接
            try{
                $db_instance->execute('select version()');
            }catch(\Exception $e){
                $this->error('数据库连接失败，请检查数据库配置！');
            }

            // 用户选择不覆盖情况下检测是否已存在数据库
            if (!$cover) {
                // 检测是否已存在数据库
                $result = $db_instance->execute('SELECT * FROM information_schema.schemata WHERE schema_name="' . $db_name . '"');
                if ($result) {
                    $this->error('该数据库已存在，请更换名称！如需覆盖，请选中覆盖按钮！');
                }
            }

            // 创建数据库
            $sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8";
            $db_instance->execute($sql) || $this->error($db_instance->getError());

            // 跳转到数据库安装页面
            $this->success('参数正确开始安装', url('install/index/step4'));
        } else {
            if (session('step') != 3 && !session('reinstall')) {
                $this->redirect($this->request->baseFile());
            }

            session('step', 4);
            return $this->fetch();
        }
    }

    /**
     * 完成安装
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function complete()
    {
        if (session('step') != 4) {
            $this->error('请按步骤安装系统', $this->request->baseFile());
        }

        if (session('error')) {
            $this->error('安装出错，请重新安装！', $this->request->baseFile());
        } else {
            // 写入安装锁定文件(只能在最后一步写入锁定文件，因为锁定文件写入后安装模块将无法访问)
            file_put_contents(APP_PATH . '../data/install.lock', 'lock');
            session('step', null);
            session('error', null);
            session('reinstall', null);
            return $this->fetch();
        }
    }
}