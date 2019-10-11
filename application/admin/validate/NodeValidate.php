<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/10/8
 * Time:  15:54
 */
namespace app\admin\validate;

use think\Validate;

class NodeValidate extends Validate
{
    protected $rule =   [
        'node_name'  => 'require',
        'node_path'   => 'require'
    ];

    protected $message  =   [
        'node_name.require' => '节点名称不能为空',
        'node_path.require'   => '节点路径不能为空'
    ];
}