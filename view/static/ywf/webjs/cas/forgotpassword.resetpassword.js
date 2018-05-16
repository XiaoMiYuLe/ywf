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
    var oldpassword = $("#oldpassword").val();
    var newpassword = $("#newpassword").val();
    
    if (oldpassword == '' || newpassword == '') {
        alert(输入密码不能为空);
    }
    
    if (oldpassword != newpassword) {
        alert('两次密码输入不一致');
        return false;
    }

    var options = {
        url: '/cas/forgotpassword/resetpassword', //下一步
        type: 'POST',
        data: {"newpassword":newpassword},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                window.location.href = '/cas/forgotpassword/forgotPrompt';
            } else {
                alert(data.error);
            }
        }
    };

    $("#form-submit").ajaxSubmit(options);
    return false;
}