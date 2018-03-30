<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

// 此函数文件来自OneThink

/**
 * 系统环境检测
 * @return array 系统环境数据
 */
function check_env(){
    $items = array(
        'os'      => array('操作系统', '不限制', '类Unix', PHP_OS, 'check'),
        'php'     => array('PHP版本', '5.5', '5.5+', PHP_VERSION, 'check'),
        'upload'  => array('附件上传', '不限制', '2M+', '未知', 'check'),
        'gd'      => array('GD库', '2.0', '2.0+', '未知', 'check'),
        'disk'    => array('磁盘空间', '100M', '不限制', '未知', 'check'),
    );

    // PHP环境检测
    if($items['php'][3] < $items['php'][1]){
        $items['php'][4] = 'times text-warning';
        session('error', true);
    }

    // 附件上传检测
    if(@ini_get('file_uploads'))
        $items['upload'][3] = ini_get('upload_max_filesize');

    // GD库检测
    $tmp = function_exists('gd_info') ? gd_info() : array();
    if(empty($tmp['GD Version'])){
        $items['gd'][3] = '未安装';
        $items['gd'][4] = 'times text-warning';
        session('error', true);
    } else {
        $items['gd'][3] = $tmp['GD Version'];
    }
    unset($tmp);

    // 磁盘空间检测
    if(function_exists('disk_free_space')) {
        $disk_size = floor(disk_free_space(INSTALL_APP_PATH) / (1024*1024));
        $items['disk'][3] = $disk_size.'M';
        if ($disk_size < 100) {
            $items['disk'][4] = 'times text-warning';
            session('error', true);
        }
    }

    return $items;
}

/**
 * 目录，文件读写检测
 * @return array 检测数据
 */
function check_dirfile(){
    $items = array(
        array('dir',  '可写', 'check', './application'),
        array('dir',  '可写', 'check', './data'),
        array('dir',  '可写', 'check', './public/static'),
        array('dir',  '可写', 'check', './public/upload'),
        array('dir',  '可写', 'check', './runtime')
    );

    foreach ($items as &$val) {
        $item =	INSTALL_APP_PATH . $val[3];
        if('dir' == $val[0]){
            if(!is_writable($item)) {
                if(is_dir($item)) {
                    $val[1] = '可读';
                    $val[2] = 'times text-warning';
                    session('error', true);
                } else {
                    $val[1] = '不存在';
                    $val[2] = 'times text-warning';
                    session('error', true);
                }
            }
        } else {
            if(file_exists($item)) {
                if(!is_writable($item)) {
                    $val[1] = '不可写';
                    $val[2] = 'times text-warning';
                    session('error', true);
                }
            } else {
                if(!is_writable(dirname($item))) {
                    $val[1] = '不存在';
                    $val[2] = 'times text-warning';
                    session('error', true);
                }
            }
        }
    }

    return $items;
}

/**
 * 函数检测
 * @return array 检测数据
 */
function check_func(){
    $items = array(
        array('pdo','支持','check','类'),
        array('pdo_mysql','支持','check','模块'),
        array('fileinfo','支持','check','模块'),
        array('curl','支持','check','模块'),
        array('file_get_contents', '支持', 'check','函数'),
        array('mb_strlen', '支持', 'check','函数'),
    );

    foreach ($items as &$val) {
        if(('类'==$val[3] && !class_exists($val[0]))
            || ('模块'==$val[3] && !extension_loaded($val[0]))
            || ('函数'==$val[3] && !function_exists($val[0]))
        ){
            $val[1] = '不支持';
            $val[2] = 'times text-warning';
            session('error', true);
        }
    }

    return $items;
}

/**
 * 写入配置文件
 * @param $config
 * @return array 配置信息
 */
function write_config($config){
    if(is_array($config)){
        //读取配置内容
        $conf = file_get_contents(APP_PATH . 'install/data/database.tpl');
        // 替换配置项
        foreach ($config as $name => $value) {
            $conf = str_replace("[{$name}]", $value, $conf);
        }

        //写入应用配置文件
        if(file_put_contents(APP_PATH . 'database.php', $conf)){
            show_msg('配置文件写入成功');
        } else {
            show_msg('配置文件写入失败！', 'error');
            session('error', true);
        }
        return '';
    }
}

/**
 * 创建数据表
 * @param $db 数据库连接资源
 * @param string $prefix 表前缀
 */
function create_tables($db, $prefix = ''){
    // 读取SQL文件
    $sql = file_get_contents(APP_PATH . 'install/data/snake.sql');

    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);

    // 替换表前缀
    $orginal = config('original_table_prefix');
    $sql = str_replace(" `{$orginal}", " `{$prefix}", $sql);

    // 开始安装
    show_progress('0%');
    $all_table = config('install_table_total');
    $i = 1;
    foreach ($sql as $value) {
        $value = trim($value);
        if(empty($value)) continue;
        $msg  = (int)($i/$all_table*100) . '%';
        if(false !== $db->execute($value)){
            show_progress($msg);
        } else {
            show_progress($msg, 'error');
            session('error', true);
        }
        $i++;
    }
}

/**
 * 更新数据表
 * @param $db 数据库连接资源
 * @param string $prefix 表前缀
 */
function update_tables($db, $prefix = ''){
    //读取SQL文件
    $sql = file_get_contents(APP_PATH . 'install/data/update.sql');
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);

    // 替换表前缀
    $sql = str_replace(" `dp_", " `{$prefix}", $sql);

    //开始安装
    show_progress('0%');
    $all_table = config('update_data_total');
    $i = 1;
    $msg = '';
    foreach ($sql as $value) {
        $value = trim($value);
        if(empty($value)) continue;
        if(substr($value, 0, 12) == 'CREATE TABLE') {
            $msg  = (int)($i/$all_table*100) . '%';
            if(($db->execute($value)) === false){
                session('error', true);
            }
        } else {
            if(substr($value, 0, 8) == 'UPDATE `') {
                $msg  = (int)($i/$all_table*100) . '%';
            } else if(substr($value, 0, 11) == 'ALTER TABLE'){
                $msg  = (int)($i/$all_table*100) . '%';
            } else if(substr($value, 0, 11) == 'INSERT INTO'){
                $msg  = (int)($i/$all_table*100) . '%';
            }
            if(($db->execute($value)) === false){
                session('error', true);
            }
        }

        if ($msg != '') {
            show_progress($msg);
            $i++;
        }
    }
}

/**
 * 及时显示提示信息
 * @param  string $msg 提示信息
 */
function show_msg($msg, $class = ''){
    echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
    flush();
    ob_flush();
}

/**
 * 显示进度
 * @param $msg
 * @param string $class
 * @author 蔡伟明 <314013107@qq.com>
 */
function show_progress($msg, $class = ''){
    echo "<script type=\"text/javascript\">show_progress(\"{$msg}\", \"{$class}\")</script>";
    flush();
    ob_flush();
}