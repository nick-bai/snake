<?php
# @Date:   2018/03/21
# @Email:  runstp@163.com
# @Project: 阿正
# @Filename: config.php
# @Last modified time: 2018/03/30
# @License: MIT



// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

return [
    // 默认模块名
    'default_module'        => 'live',
    // 默认控制器名
    'default_controller'    => 'index',
    // 默认操作名
    'default_action'        => 'index',
    'url_html_suffix' => '',
    'url_route_on' => true,
    'trace' => [
        'type' => 'html', // 支持 socket trace file
    ],
    //各模块公用配置
    'extra_config_list' => ['database', 'route', 'validate'],
    //临时关闭日志写入
    'log' => [
        'type' => 'test',
    ],

    'app_debug' => true,

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------
    'cache' => [
        // 驱动方式
        'type' => 'file',
        // 缓存保存目录
        'path' => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
        'host' => '192.168.6.55',
        'port' => 11211,
    ],

    //加密串
    'salt' => 'HvIwIZFovS7oO5CAFVX52omS6Yj5NW9e',

    //备份数据地址
    'back_path' => APP_PATH .'../back/'

];
