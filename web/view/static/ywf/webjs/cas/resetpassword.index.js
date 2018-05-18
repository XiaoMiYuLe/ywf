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
    var oldpassword = $("input[name=oldpassword]").val();
    var newpassword = $("input[name=newpassword]").val();
    var newpasswordOne = $("input[name=newpasswordOne]").val();
    
    if (oldpassword == '' || newpassword == '' || newpasswordOne == '') {
        alert('输入密码不能为空');
        return false;
    }
    
    if (newpassword != newpasswordOne) {
        alert('两次密码输入不一致');
        return false;
    }

    var options = {
        url: '/cas/resetpassword/index', // 修改密码
        type: 'POST',
        data: {"oldpassword":oldpassword,"newpassword":newpassword},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                alert('登录密码修改成功！');
                window.location.href = '/cas/setting/index';
            } else {
                alert(data.error);
            }
        }
    };

    $("#form-submit").ajaxSubmit(options);
    return false;
}