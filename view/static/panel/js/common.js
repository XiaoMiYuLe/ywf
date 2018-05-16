/**
 * 定义全局变量
 */
var img_done = "<img src='" + STATIC_CDN + "/panel/img/ico-done.gif' border='0' />";
var img_delete = "<img src='" + STATIC_CDN + "/panel/img/ico-delete.gif' border='0' />";
var img_warning = "<img src='" + STATIC_CDN + "/panel/img/ico-warning.gif' border='0' />";
var img_loading_small = "<img src='" + STATIC_CDN + "/panel/img/loading-small.gif' border='0' />";
var img_loading_icon = "<i class='fa fa-spin fa-spinner'></i>";
var back_listing = false; // 编辑表单提交处理完毕后，是否返回列表管理
var cookieName_navigation = 'YZ_LastNavigationHid'; // 定义记录导航轨迹的 cookie 名称

$(document).ready(function() {
    /**
     * 载入导航轨迹
     */
    loadNavigation();
    
    /**
     * 基于 html5 的 ajax + pushState 实现无刷新加载内容，并同时修改 URL 地址栏
     */
    $.pjax({
        selector: 'a.load-content',
        container: '#content', //内容替换的容器
        show: 'fade',  //展现的动画，支持默认和fade, 可以自定义动画方式，这里为自定义的function即可。
        cache: false,  //是否使用缓存
        storage: false,  //是否使用本地存储
        titleSuffix: '', //标题后缀
        filter: function(){},
        beforeSend: function(){},
        callback: function(){
            /* 更新导航轨迹 */
            updateNavigation($(this).attr('hid'));
            
            return false;
        }
    });
    
    /**
     * 整页刷新
     */
    $('.action-refresh-allpage').click(function(){
        window.location.reload();
    });
    
    /**
     * 切换导航
     */
    $('li.nav-one').click(function(){
        if (! $(this).hasClass('active')) {
            $('li.nav-one').removeClass('active');
            $(this).addClass('active');
            
            $('.nav-content').removeClass('active in');
            $($(this).find('a').attr('href')).addClass('active in');
            
            $('#nav_one_now').text($(this).children('a:first').text());
        }
    });
    
    /**
     * 初始化地区选单
     */
    $("select.region").each(function(k){
        var pid = $(this).attr('data-init');
        if (k == 0) {
            pid = pid ? pid : 3743;
        }
        if (pid != '') {
            getRegionByPid(pid, $(this));
        }
    });
    
    /**
     * 选择地区，动态加载子级地区
     */
    $('body').delegate("select.region", 'change', function(){
        var $obj = $(this);
        var i = $obj.index();
        var pid = $(this).val();
        
        /* 到最后一级时，不再动态加载子级地区 */
        if (i == $obj.parent().find('select.region').size() - 1) {
            return false;
        }
        
        /* 清空后面地区选单元素的选项 */
        $obj.parent().find('select:gt(' + i + ')').find("option:gt(0)").remove();
        
        /* 动态加载子级地区 */
        getRegionByPid(pid, $obj.next('select.region'));
    });
    
    /**
     * 登出 - 销毁导航轨迹的 cookie
     */
    $('.sign-out').click(function(){
    	$.cookie(cookieName_navigation, '', {expires: 36000000, path: '/'});
    });
});


/**
 * 根据父级 ID 获取子级地区
 */
function getRegionByPid(pid, $obj) {
    $.ajax({
        type:'post',
        url:'/trend/region/getRegionByPid',
        data:'pid=' + pid,
        dataType:'json',
        timeout:60000,
        success:function(data){
            if (data.status == 0) {
                /* 若已加载，则无需重复加载 */
                if ($obj.find('option').size() > 1) {
                    return false;
                }
                
                /* 加载地区列表 */
                var selected_id = parseInt($obj.attr('data-selected'));
                $(data.data.regions).each(function() {
                    if (selected_id > 0 && selected_id == this.region_id) {
                        $obj.append('<option value="' + this.region_id + '" selected="selected">' + this.region_name + '</option>');
                    } else {
                        $obj.append('<option value="' + this.region_id + '">' + this.region_name + '</option>');
                    }
                });
            }
        }
    });
}

/**
 * 将数字金额转为大写
 */
function digit_uppercase(n) {
    var fraction = ['角', '分'];
    var digit = [
        '零', '壹', '贰', '叁', '肆',
        '伍', '陆', '柒', '捌', '玖'
    ];
    var unit = [
        ['元', '万', '亿'],
        ['', '拾', '佰', '仟']
    ];
    var head = n < 0? '欠': '';
    n = Math.abs(n);
    var s = '';
    for (var i = 0; i < fraction.length; i++) {
        s += (digit[Math.floor(n * 10 * Math.pow(10, i)) % 10] + fraction[i]).replace(/零./, '');
    }
    s = s || '整';
    n = Math.floor(n);
    for (var i = 0; i < unit[0].length && n > 0; i++) {
        var p = '';
        for (var j = 0; j < unit[1].length && n > 0; j++) {
            p = digit[n % 10] + unit[1][j] + p;
            n = Math.floor(n / 10);
        }
        s = p.replace(/(零.)*零$/, '')
             .replace(/^$/, '零')
          + unit[0][i] + s;
    }
    return head + s.replace(/(零.)*零元/, '元')
                   .replace(/(零.)+/g, '零')
                   .replace(/^整$/, '零元整');
}

/**
 * 载入导航轨迹
 */
function loadNavigation() {
    var cookie_nav = $.cookie(cookieName_navigation);
    if (cookie_nav) {
        $('#left_nav').find('.load-content').each(function(){
            if ($(this).attr('hid') == cookie_nav) {
                $(this).parent().addClass('active');
                $(this).parent().parent().show();
                $(this).parent().parent().parent().addClass('active');
            }
        });
    }
}

/**
 * 更新导航轨迹
 */
function updateNavigation(hid) {
    if (hid) {
        $.cookie(cookieName_navigation, hid, {expires: 36000000, path: '/'});
    }
}

/**
 * Ajax 加载页面 - 适用于局部，或者使用 js 进行页面跳转
 */
function loadingAjax(href, obj) {
    if (href != '' && href != '#' && href != './') {
        if (! obj) {
            obj = $("#content");
        }
        obj.load(href, function() {
            history.pushState({}, null, href);
        });
    }
}

/**
 * 提示信息处理，可定时清除
 * 
 * @param string obj_id 提示信息对象 ID
 * @param string msg 提示信息内容
 * @param boolean clear 是否自动清除
 * @param integer delayTime 延迟时间
 */
function notice(obj_id, msg, clear, delayTime) {
    $("#" + obj_id).html(msg);
    if (clear) {
        if (! delayTime) {
            delayTime = 5000;
        }
        setTimeout(function() {
            $("#" + obj_id).empty();
        }, delayTime);
    }
}

/**
 * 消息通知 - pnotify
 * 
 * @param string $msg 必填。提示信息
 * @param string $type 选填。提示类型，一共四种：warning、info、success、error
 * @param string $title 选填。提示标题
 */
function pnotify($msg, $type, $title) {
    new PNotify({
        title: $title ? $title : '提醒',
        text: $msg,
        type: $type ? $type : 'warning',
        styling: 'bootstrap3',
    });
}

/**
 * 更新 CKEDITOR 的状态，即值
 * 适用版本：4.0以上
 */
function CKupdate() {
    for (instance in CKEDITOR.instances)
        CKEDITOR.instances[instance].updateElement();
}

/**
 * 判断 js 数组包是否包含某个元素，类似 PHP 的数组函数 in_array()
 * 使用方法：
 * var arr = ["a","b"];
 * alert(arr.in_array("a"));
 */
Array.prototype.S = String.fromCharCode(2);
Array.prototype.in_array = function(e) {
    var r = new RegExp(this.S + e + this.S);
    return (r.test(this.S + this.join(this.S) + this.S));
}

/**
 * ajaxError
 */
function ajaxError(XMLHttpRequest, textStatus, errorThrown) {
    alert('Ooops!Encountered error while connecting to the server.There might be something wrong with your network.Please check your network connection!');
    $('#edit_notice').empty();
    $(".input-submit").removeAttr('disabled');
}