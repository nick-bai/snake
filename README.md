# snake
thinkphp5做的通用系统改后台

目前完成的功能有:

后台管理员的增删改查

角色的增删改查

权限的分配

数据库的备份与还原

# QQ交流群
## 159640205

# 使用方法
配置好虚拟域名，输入域名，点击下一步安装即可

管理员是: admin
密码是: admin

可以自己进行修改  

# 更新日志：  
2016.10.12 19:34分,已经讲系统升级到了thinkphp5.0.1,若有发现问题，请积极反馈。谢谢  

2017.7.19 对代码进行了重大修改  
1、升级 thinkphp5 的版本到 5.0.10  
2、添加了节点管理  
3、优化了权限控制(能控制到所有的按钮)  
4、改变了操作按钮的样式，并添加了生成按钮的方法  
5、统一采用model去查询数据  

2017.9.16 更新如下  
1、添加了文章的 增删改查 更能   
2、展示了图片异步上传和展示的功能  
3、展示了 tags 插件的使用  
4、展示了 ueditor和 tp5结合使用

2017.9.18 更新如下  
1、添加安装系统

# 一些二开的指导 

### 关于框架主要文件夹讲解  

```
|-- application     // 项目业务逻辑主要目录
    |-- admin       // 后台所在目录
       |-- controller       // 后台控制器
       |-- model        // 后台模型
       |-- validate         // 后台验证器
       |-- view          // 后台视图
       |-- common.php    // 后台公用方法
       |-- config.php    // 后台项目配置
       |-- database.php  // 后台数据库配置
    |-- api     // 规划 api 目录
    |-- index   // 前台所在目录
        |-- controller       // 前台控制器
        |-- model        // 前台模型
        |-- validate         // 前台验证器
        |-- view          // 前台视图
        |-- common.php    // 前台公用方法
        |-- config.php    // 前台项目配置
        |-- database.php  // 前台数据库配置
    |-- command.php     // console命令文件
    |-- common.php      // 系统公用方法
    |-- config.php      // 系统配置文件
    |-- database.php    // 系统数据库文件
    |-- route.php       // 系统路由文件
|-- back    // sql备份文件目录
|-- extend      // 自由扩展包目录 
|-- public      // 系统入口、资源所在目录
    |-- static      // 静态资源
        |-- admin   // 后台资源目录
```

# 开发步骤
1. 开发后台功能时，在 application\admin\controller 下新建控制器，比如 index.php  
```
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
        return $this->fetch('index');
    }
}
```

> ==所有控制器均需要继承    Base.php==

2. 在 model 文件夹下建立对应的 model，名称规范 以 Model结尾，例如 UserModel.php  

```
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
namespace app\admin\model;

use think\Model;

class UserModel extends Model
{
    // 确定链接表名
    protected $table = 'gk_user';
}    
```

> 在类前声明要链接的表 前缀 + 表名  

3. 在后台的 用户管理-->节点管理 里面添加响应的控制器 和 方法节点 所有的 public 的方法，均需要录入，以达到权限完全控制的目的。新建完成之后，退出系统，以 admin 身份重新登录。  

==填写的控制器和方法名，规定全部以小写录入==

4. 表单提交的内容，需要用过 validate 过滤，validate 名称的命名规范是 以Validate 结尾，例如：AdminValidate.php  
```
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
namespace app\admin\validate;

use think\Validate;

class AdminValidate extends Validate
{
    protected $rule = [
        ['userName', 'require', '用户名不能为空'],
        ['password', 'require', '密码不能为空'],
        ['code', 'require', '验证码不能为空']
    ];

}
```
validate 细节用法，参照 tp 手册

5.新建对应的视图，渲染页面  


# 开发注意事项：

## 控制器方面
controller 的编写   
1、必须继承 base.php  
2、由于页面是异步渲染的，所以按钮需要在后台统一拼装完成，又为了达到权限控制的目的，现封装了按钮生成方法  
```
/**
 * 生成操作按钮
 * @param array $operate 操作按钮数组
 */
function showOperate($operate = [])
{
    if(empty($operate)){
        return '';
    }

    $option = '';
    foreach($operate as $key=>$vo){
        if(authCheck($vo['auth'])){
            $option .= ' <a href="' . $vo['href'] . '"><button type="button" class="btn btn-' . $vo['btnStyle'] . ' btn-sm">'.
                '<i class="' . $vo['icon'] . '"></i> ' . $key . '</button></a>';
        }
    }

    return $option;
}
```

每个按钮是否显示 通过 authCheck 方法进行了判断
```
/**
 * 权限检测
 * @param $rule
 */
function authCheck($rule)
{
    $control = explode('/', $rule)['0'];
    if(in_array($control, ['login', 'index'])){
        return true;
    }

    if(in_array($rule, session('action'))){
        return true;
    }

    return false;
}
```

你需要显示那些按钮，只要配置相应的数组然后调用 该方法即可。
```
 /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'user/useredit',
                'href' => url('user/userEdit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'user/userdel',
                'href' => "javascript:userDel(" .$id .")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
```

auth 部分的路由，全部为小写。  
href 为按钮跳转地址  
btnStyle 为按钮的颜色 class主要是bootstrap 的几种样式  
icon  为按钮前置小图标 参考 H+

异步消息返回，调用 msg 方法
```
/**
 * 统一返回信息
 * @param $code
 * @param $data
 * @param $msge
 */
function msg($code, $data, $msg)
{
    return compact('code', 'data', 'msg');
}
```

为统一规范，现规定返回码  
成功 返回 1，其余各种状态按照编号自由返回 < 1 的状态码，例如：
```
return json(msg(-1, '', '密码错误'));
return json(msg(-2, '', '用户名错误'));

return json(msg(1, '', '添加用户成功'));
```

由于 tp 通过model 查询的数据，如果是 find 查询的可以通过 toArray()方法转换成数组处理，而 通过select 查询的结果集无法直接转换成数组，因此，特封装了一个方法 objToArray
```
/**
 * 对象转换成数组
 * @param $obj
 */
function objToArray($obj)
{
    return json_decode(json_encode($obj), true);
}
```

列表部分 采用的是 bootstrap 的table 插件，因此返回格式必须如下：
```
$return['total'] = $user->getAllUsers($where);  //总数据
$return['rows'] = $selectResult; // 结果集

return json($return);
```

分页的写法可以自行计算
```
$limit = $param['pageSize']; // 每页多少条
$offset = ($param['pageNumber'] - 1) * $limit; // 这次分页从哪个开始
```


## 视图方面

视图大部分采用的是异步渲染，而且完全收录了 H+ 的页面，因此在特殊布局上，可以去H+ ，直接右键 拿出H+ 的源码，即可完成页面布局。  

系统资源统一路径配置：
```
    // 模板参数替换
    'view_replace_str'       => array(
        '__CSS__'    => '/static/admin/css',
        '__JS__'     => '/static/admin/js',
        '__IMG__' => '/static/admin/images',
    ),
```



# 线上预览地址:
http://snake.baiyf.com

