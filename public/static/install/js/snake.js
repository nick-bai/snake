/*
 *  Document   : dolphin.js
 *  Author     : CaiWeiMing <314013107@qq.com>
 */

var Dolphin = function () {
    /**
     * 处理ajax方式的post提交
     * @author CaiWeiMing <314013107@qq.com>
     */
    var ajaxPost = function () {
        jQuery(document).delegate('.ajax-post', 'click', function () {
            var msg, self   = jQuery(this), ajax_url = self.attr("href") || self.data("url");
            var target_form = self.attr("target-form");
            var text        = self.data('tips');
            var title       = self.data('title') || '确定要执行该操作吗？';
            var confirm_btn = self.data('confirm') || '确定';
            var cancel_btn  = self.data('cancel') || '取消';
            var form        = jQuery('form[name=' + target_form + ']');
            if (form.length === 0) {
                form = jQuery('.' + target_form);
            }
            var form_data   = form.serialize();

            if ("submit" === self.attr("type") || ajax_url) {
                // 不存在“.target-form”元素则返回false
                if (undefined === form.get(0)) return false;
                // 节点标签名为FORM表单
                if ("FORM" === form.get(0).nodeName) {
                    ajax_url = ajax_url || form.get(0).action;

                    // 提交确认
                    if (self.hasClass('confirm')) {
                        swal({
                            title: title,
                            text: text || '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d26a5c',
                            confirmButtonText: confirm_btn,
                            cancelButtonText: cancel_btn,
                            closeOnConfirm: true,
                            html: false
                        }, function () {
                            pageLoader();
                            self.attr("autocomplete", "off").prop("disabled", true);

                            // 发送ajax请求
                            jQuery.post(ajax_url, form_data, {}, 'json').success(function(res) {
                                pageLoader('hide');
                                msg = res.msg;
                                if (res.code) {
                                    if (res.url && !self.hasClass("no-refresh")) {
                                        msg += " 页面即将自动跳转~";
                                    }
                                    tips(msg, 'success');
                                    setTimeout(function () {
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);return false;
                                        }
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();return false;
                                        }
                                        return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                                    }, 1500);
                                } else {
                                    jQuery(".reload-verify").length > 0 && jQuery(".reload-verify").click();
                                    tips(msg, 'danger');
                                    setTimeout(function () {
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);return false;
                                        }
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();return false;
                                        }
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                    }, 2000);
                                }
                            }).fail(function () {
                                pageLoader('hide');
                                tips('服务器发生错误~', 'danger');
                                self.attr("autocomplete", "on").prop("disabled", false);
                            });
                        });
                        return false;
                    } else {
                        self.attr("autocomplete", "off").prop("disabled", true);
                    }
                } else if ("INPUT" === form.get(0).nodeName || "SELECT" === form.get(0).nodeName || "TEXTAREA" === form.get(0).nodeName) {
                    // 如果是多选，则检查是否选择
                    if (form.get(0).type === 'checkbox' && form_data === '') {
                        Dolphin.notify('请选择要操作的数据', 'warning');
                        return false;
                    }

                    // 提交确认
                    if (self.hasClass('confirm')) {
                        swal({
                            title: title,
                            text: text || '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d26a5c',
                            confirmButtonText: confirm_btn,
                            cancelButtonText: cancel_btn,
                            closeOnConfirm: true,
                            html: false
                        }, function () {
                            pageLoader();
                            self.attr("autocomplete", "off").prop("disabled", true);

                            // 发送ajax请求
                            jQuery.post(ajax_url, form_data, {}, 'json').success(function(res) {
                                pageLoader('hide');
                                msg = res.msg;
                                if (res.code) {
                                    if (res.url && !self.hasClass("no-refresh")) {
                                        msg += " 页面即将自动跳转~";
                                    }
                                    tips(msg, 'success');
                                    setTimeout(function () {
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);return false;
                                        }
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();return false;
                                        }
                                        return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                                    }, 1500);
                                } else {
                                    jQuery(".reload-verify").length > 0 && jQuery(".reload-verify").click();
                                    tips(msg, 'danger');
                                    setTimeout(function () {
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);return false;
                                        }
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();return false;
                                        }
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                    }, 2000);
                                }
                            }).fail(function () {
                                pageLoader('hide');
                                tips('服务器发生错误~', 'danger');
                                self.attr("autocomplete", "on").prop("disabled", false);
                            });
                        });
                        return false;
                    } else {
                        self.attr("autocomplete", "off").prop("disabled", true);
                    }
                } else {
                    if (self.hasClass("confirm")) {
                        swal({
                            title: title,
                            text: text || '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d26a5c',
                            confirmButtonText: confirm_btn,
                            cancelButtonText: cancel_btn,
                            closeOnConfirm: true,
                            html: false
                        }, function () {
                            pageLoader();
                            self.attr("autocomplete", "off").prop("disabled", true);
                            form_data = form.find("input,select,textarea").serialize();

                            // 发送ajax请求
                            jQuery.post(ajax_url, form_data, {}, 'json').success(function(res) {
                                pageLoader('hide');
                                msg = res.msg;
                                if (res.code) {
                                    if (res.url && !self.hasClass("no-refresh")) {
                                        msg += " 页面即将自动跳转~";
                                    }
                                    tips(msg, 'success');
                                    setTimeout(function () {
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);return false;
                                        }
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();return false;
                                        }
                                        return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                                    }, 1500);
                                } else {
                                    jQuery(".reload-verify").length > 0 && jQuery(".reload-verify").click();
                                    tips(msg, 'danger');
                                    setTimeout(function () {
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);return false;
                                        }
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();return false;
                                        }
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                    }, 2000);
                                }
                            }).fail(function () {
                                pageLoader('hide');
                                tips('服务器发生错误~', 'danger');
                                self.attr("autocomplete", "on").prop("disabled", false);
                            });
                        });
                        return false;
                    } else {
                        form_data = form.find("input,select,textarea").serialize();
                        self.attr("autocomplete", "off").prop("disabled", true);
                    }
                }

                // 直接发送ajax请求
                pageLoader();
                jQuery.post(ajax_url, form_data, {}, 'json').success(function(res) {
                    pageLoader('hide');
                    msg = res.msg;

                    if (res.code) {
                        if (res.url && !self.hasClass("no-refresh")) {
                            msg += "， 页面即将自动跳转~";
                        }
                        tips(msg, 'success');
                        setTimeout(function () {
                            self.attr("autocomplete", "on").prop("disabled", false);
                            // 关闭弹出框
                            if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);return false;
                            }
                            // 刷新父窗口
                            if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                parent.location.reload();return false;
                            }
                            return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                        }, 1500);
                    } else {
                        jQuery(".reload-verify").length > 0 && jQuery(".reload-verify").click();
                        tips(msg, 'danger');
                        setTimeout(function () {
                            // 关闭弹出框
                            if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);return false;
                            }
                            // 刷新父窗口
                            if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                parent.location.reload();return false;
                            }
                            self.attr("autocomplete", "on").prop("disabled", false);
                        }, 2000);
                    }
                }).fail(function () {
                    pageLoader('hide');
                    tips('服务器发生错误~', 'danger');
                    self.attr("autocomplete", "on").prop("disabled", false);
                });
            }

            return false;
        });
    };

    /**
     * 处理ajax方式的get提交
     * @author CaiWeiMing <314013107@qq.com>
     */
    var ajaxGet = function () {
        jQuery(document).delegate('.ajax-get', 'click', function () {
            var msg, self = $(this), text = self.data('tips'), ajax_url = self.attr("href") || self.data("url");
            var title       = self.data('title') || '确定要执行该操作吗？';
            var confirm_btn = self.data('confirm') || '确定';
            var cancel_btn  = self.data('cancel') || '取消';
            // 执行确认
            if (self.hasClass('confirm')) {
                swal({
                    title: title,
                    text: text || '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d26a5c',
                    confirmButtonText: confirm_btn,
                    cancelButtonText: cancel_btn,
                    closeOnConfirm: true,
                    html: false
                }, function () {
                    pageLoader();
                    self.attr("autocomplete", "off").prop("disabled", true);

                    // 发送ajax请求
                    jQuery.get(ajax_url, {}, {}, 'json').success(function(res) {
                        pageLoader('hide');
                        msg = res.msg;
                        if (res.code) {
                            if (res.url && !self.hasClass("no-refresh")) {
                                msg += " 页面即将自动跳转~";
                            }
                            tips(msg, 'success');
                            setTimeout(function () {
                                self.attr("autocomplete", "on").prop("disabled", false);
                                // 关闭弹出框
                                if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                    parent.layer.close(index);return false;
                                }
                                // 刷新父窗口
                                if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                    parent.location.reload();return false;
                                }
                                return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                            }, 1500);
                        } else {
                            tips(msg, 'danger');
                            setTimeout(function () {
                                // 关闭弹出框
                                if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                    parent.layer.close(index);return false;
                                }
                                // 刷新父窗口
                                if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                    parent.location.reload();return false;
                                }
                                self.attr("autocomplete", "on").prop("disabled", false);
                            }, 2000);
                        }
                    }).fail(function () {
                        pageLoader('hide');
                        tips('服务器发生错误~', 'danger');
                        self.attr("autocomplete", "on").prop("disabled", false);
                    });
                });
            } else {
                pageLoader();
                self.attr("autocomplete", "off").prop("disabled", true);

                // 发送ajax请求
                jQuery.get(ajax_url, {}, {}, 'json').success(function(res) {
                    pageLoader('hide');
                    msg = res.msg;
                    if (res.code) {
                        if (res.url && !self.hasClass("no-refresh")) {
                            msg += " 页面即将自动跳转~";
                        }
                        tips(msg, 'success');
                        setTimeout(function () {
                            self.attr("autocomplete", "on").prop("disabled", false);
                            // 关闭弹出框
                            if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);return false;
                            }
                            // 刷新父窗口
                            if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                parent.location.reload();return false;
                            }
                            return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                        }, 1500);
                    } else {
                        tips(msg, 'danger');
                        setTimeout(function () {
                            // 关闭弹出框
                            if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                parent.layer.close(index);return false;
                            }
                            // 刷新父窗口
                            if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                parent.location.reload();return false;
                            }
                            self.attr("autocomplete", "on").prop("disabled", false);
                        }, 2000);
                    }
                }).fail(function () {
                    pageLoader('hide');
                    tips('服务器发生错误~', 'danger');
                    self.attr("autocomplete", "on").prop("disabled", false);
                });
            }

            return false;
        });
    };

    /**
     * 处理普通方式的get提交
     * @author CaiWeiMing <314013107@qq.com>
     */
    var jsGet = function () {
        jQuery(document).delegate('.js-get', 'click', function () {
            var self = $(this), text = self.data('tips'), url = self.attr("href") || self.data("url");
            var target_form = self.attr("target-form");
            var form        = jQuery('form[name=' + target_form + ']');
            var form_data   = form.serialize() || [];
            var title       = self.data('title') || '确定要执行该操作吗？';
            var confirm_btn = self.data('confirm') || '确定';
            var cancel_btn  = self.data('cancel') || '取消';

            if (form.length === 0) {
                form = jQuery('.' + target_form + '[type=checkbox]:checked');
                form.each(function () {
                    form_data.push($(this).val());
                });
                form_data = form_data.join(',');
            }

            if (form_data === '') {
                Dolphin.notify('请选择要操作的数据', 'warning');
                return false;
            }

            if (url.indexOf('?') !== -1) {
                url += '&' + target_form + '=' + form_data;
            } else {
                url += '?' + target_form + '=' + form_data;
            }

            // 执行确认
            if (self.hasClass('confirm')) {
                swal({
                    title: title,
                    text: text || '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d26a5c',
                    confirmButtonText: confirm_btn,
                    cancelButtonText: cancel_btn,
                    closeOnConfirm: true,
                    html: false
                }, function () {
                    location.href = url;
                });
            } else {
                location.href = url;
            }

            return false;
        });
    };

    /**
     * 顶部菜单
     * @author CaiWeiMing <314013107@qq.com>
     */
    var topMenu = function () {
        $('.top-menu').click(function () {
            var $target = $(this).attr('target');
            var data = {
                module_id: $(this).data('module-id') || '',
                module: $(this).data('module') || '',
                controller: $(this).data('controller') || ''
            };

            if ($('#nav-' + data.module_id).length) {
                location.href = $('#nav-' + data.module_id).find('a').not('.nav-submenu').first().attr('href');
            } else {
                $.post(dolphin.top_menu_url, data, function (res) {
                    if (res !== '') {
                        if ($target === '_self') {
                            location.href = res;
                        } else {
                            window.open(res);
                        }
                    } else {
                        tips('无任何节点权限', 'danger');
                    }
                }).fail(function () {
                    tips('服务器发生错误~', 'danger');
                });
            }
            return false;
        });
    };

    /**
     * 页面小提示
     * @param $msg 提示信息
     * @param $type 提示类型:'info', 'success', 'warning', 'danger'
     * @param $icon 图标，例如：'fa fa-user' 或 'glyphicon glyphicon-warning-sign'
     * @param $from 'top' 或 'bottom'
     * @param $align 'left', 'right', 'center'
     * @author CaiWeiMing <314013107@qq.com>
     */
    var tips = function ($msg, $type, $icon, $from, $align) {
        $type  = $type || 'info';
        $from  = $from || 'top';
        $align = $align || 'center';
        $enter = $type === 'success' ? 'animated fadeInUp' : 'animated shake';

        jQuery.notify({
            icon: $icon,
            message: $msg
        },
        {
            element: 'body',
            type: $type,
            allow_dismiss: true,
            newest_on_top: true,
            showProgressbar: false,
            placement: {
                from: $from,
                align: $align
            },
            offset: 20,
            spacing: 10,
            z_index: 10800,
            delay: 3000,
            timer: 1000,
            animate: {
                enter: $enter,
                exit: 'animated fadeOutDown'
            }
        });
    };

    /**
     * 页面加载提示
     * @param $mode 'show', 'hide'
     * @author CaiWeiMing <314013107@qq.com>
     */
    var pageLoader = function ($mode) {
        var $loadingEl = jQuery('#loading');
        $mode          = $mode || 'show';

        if ($mode === 'show') {
            if ($loadingEl.length) {
                $loadingEl.fadeIn(250);
            } else {
                jQuery('body').prepend('<div id="loading"><div class="loading-box"><i class="fa fa-2x fa-cog fa-spin"></i> <span class="loding-text">请稍等...</span></div></div>');
            }
        } else if ($mode === 'hide') {
            if ($loadingEl.length) {
                $loadingEl.fadeOut(250);
            }
        }

        return false;
    };

    /**
     * 启用图标搜索
     * @author CaiWeiMing <314013107@qq.com>
     */
    var iconSearchLoader = function () {
        // Set variables
        var $searchItems = jQuery('.js-icon-list > li');
        var $searchValue = '';

        // When user types
        jQuery('.js-icon-search').on('keyup', function(){
            $searchValue = jQuery(this).val().toLowerCase();

            if ($searchValue.length > 2) { // If more than 2 characters, search the icons
                $searchItems.hide();

                jQuery('code', $searchItems)
                    .each(function(){
                        if (jQuery(this).text().match($searchValue)) {
                            jQuery(this).parent('li').show();
                        }
                    });
            } else if ($searchValue.length === 0) { // If text deleted show all icons
                $searchItems.show();
            }
        });
    };

    return {
        // 初始化
        init: function () {
            ajaxPost();
            ajaxGet();
            jsGet();
            topMenu();
        },
        // 页面加载提示
        loading: function ($mode) {
            pageLoader($mode);
        },
        // 页面小提示
        notify: function ($msg, $type, $icon, $from, $align) {
            tips($msg, $type, $icon, $from, $align);
        },
        // 启用图标搜索
        iconSearch: function () {
            iconSearchLoader();
        }
    };
}();

// Initialize app when page loads
jQuery(function () {
    Dolphin.init();
});