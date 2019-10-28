function LayTool () {
    let version = 'v1.0';
}

LayTool.prototype.layerIndex = null;

// iframe 弹层
LayTool.prototype.open = function (url, title, width, height) {
    layui.use('layer', function () {
        let layer = layui.layer;

        if (!width) {
            width = '90%';
        }

        if (!height) {
            height = '90%';
        }

        layer.ready(function () {
            layer.open({
                type: 2,
                title: title,
                shade: 0.2,
                area: [width, height],
                content: url,
            });
        });
    });
};

// alert 弹层
LayTool.prototype.alert = function (content, title, icon, closeBtn) {

    layui.use('layer', function () {
        let layer = layui.layer;

        if (!title) {
            title = '友情提示';
        }

        if (!icon) {
            icon = 1;
        }

        if (0 != closeBtn) {
            closeBtn = 1;
        }

        layer.alert(content, {
            title: title,
            icon: icon,
            closeBtn: closeBtn
        });
    });
};

// msg 弹层
LayTool.prototype.msg = function (content, conf) {

    layui.use('layer', function () {
        let layer = layui.layer;

        layer.msg(content, conf);
    });
};

// 加载中
LayTool.prototype.loading = function (type) {

    if (typeof type == 'undefined') {
        type = 0;
    }

    layui.use('layer', function () {
        let layer = layui.layer;

        layTool.layerIndex = layer.load(type, {shade: false});
    });
};

// 隐藏加载中
LayTool.prototype.hideLoading = function () {

    setTimeout(function () {
        layui.use('layer', function () {
            let layer = layui.layer;

            layer.close(layTool.layerIndex);
        });
    }, 100);
};

// 日历
LayTool.prototype.layDate = function (dom, type, range) {

    if (!type) {
        type = 'date';
    }

    if (!range) {
        range = false;
    }

    layui.use('laydate', function(){
        let laydate = layui.laydate;

        laydate.render({
            elem: dom,
            type: type,
            range: range
        });
    });
};

// 数据表格
LayTool.prototype.table = function (dom, url, cols, limit) {

    layui.use('table', function(){
        let table = layui.table;

        if (!limit) {
            limit = 10;
        }

        table.render({
            elem: dom
            ,limit: limit
            ,height: 'full-200'
            ,url: url
            ,page: true
            ,cols: cols
        });
    });
};

window.layTool = new LayTool();


