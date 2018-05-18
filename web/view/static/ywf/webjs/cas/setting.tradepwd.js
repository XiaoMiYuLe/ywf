$(document).ready(function () {

    /* 提交点击事件*/
    $("#form-submit").click(function () {
        getApi();
    });

});

/**
 * 接口交互部分，以及页面提示等
 * @returns {Boolean}
 */
function getApi() {
    var pwd = $("input[name=pay_pwd]").val();
    var repwd = $("input[name=repeatpay_pwd]").val();
    //交易密码只能是数字判断
    if (pwd == '' || repwd == '') {
        alert('输入密码不能为空');
        return false;
    }

    if (isNaN(pwd)) {
        alert('请检查交易密码是否为6位纯数字!');
        return false;
    }
    
    if (isNaN(repwd)) {
        alert('请检查交易密码是否为6位纯数字!');
        return false;
    }
    
    if (pwd.length <6 || pwd.length >6) {
        alert('请检查交易密码是否为6位纯数字!');
        return false;
    }
    
    if (pwd != repwd) {
        alert('请输入相同密码');
        return false;
    }
    
    //请正确设置6位纯数字密码
    var options = {
        url: '/cas/setting/tradepwd', // 修改密码
        type: 'POST',
        data: {"pwd": md5(pwd), "repwd": md5(repwd)},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                alert(data.msg);
                window.location.href = '/cas/setting/index';
            } else {
                alert(data.error);
            }
        }
    };

    $("#form-submit").ajaxSubmit(options);
    return false;
}