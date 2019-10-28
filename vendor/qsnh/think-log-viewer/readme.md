# ThinkLogViewer

基于 Thinkphp5 开发的日志浏览组件。该组件为我们提供了一键浏览当前项目日志的功能。

## 安装

```
composer require qsnh/think-log-viewer
```

## 使用

在命令行输入：

```
php think run
```

之后打开浏览器，输入：

```
http://127.0.0.1:8000/log
```

可以看到：

![_ _20180407152242](https://user-images.githubusercontent.com/12671205/38452527-71b70b98-3a78-11e8-8d66-ba40f032c1fa.png)

![_ _20180407152301](https://user-images.githubusercontent.com/12671205/38452528-7ca5244a-3a78-11e8-8f76-d5feba7e3225.png)


## 配置

该扩展包默认注册了 `/logs` 路由，对应的控制器是 `Qsnh\Think\Log\Controllers\LogViewerController@index` 如果您因为权限原因或者路由路径问题等原因的话，请自己在 `/route/route.php` 文件中覆盖该路径。具体可以这样：


```
Route::get('/logs', function () {});
Route::get('/backend/logs', 'Qsnh\Think\Log\Controllers\LogViewerController@index');
```

> **请注意控制好权限！** 上面代码并不推荐在生产环境下使用，因为闭包无法进行路由缓存。

## Author

[Qsnh](https://github.com/Qsnh)