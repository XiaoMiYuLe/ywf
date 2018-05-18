$(document).ready(function () {
//    $("#form").attr("action", "/csa/sign/in");

    /**
    * 关键字搜索 - 支持回车
    */
    $("input[name=code]").on('keypress', function (event) {
        if (event.which == '13') {
            $("#form-submit").trigger("click");
            return false;
        }
    });

    /**
     * 页面点击事件
     */
    $("#form-submit").click(function () {
        
        //手机号 11为 js 正则匹配
        /*var isMobile = /^((\+?86)|(\(\+86\)))?(13[012356789][0-9]{8}|15[012356789][0-9]{8}|17[0123456789][0-9]{8}|18[012356789][0-9]{8}|147[0-9]{8}|1349[0-9]{7})$/;

        var phone = $("input[name=phone]").val();

        if (!isMobile.test(phone)) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入手机号码',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }*/
        
        // 引入获取接口方法
        getApi();
    });
});


/**
 * 接口交互部分，以及页面提示等
 * @returns {Boolean}
 */
function getApi() {
    var phone = $("#phone").val();
    var password = $("#password").val();
    var code = $("input[name=code]").val();

    if (phone == "" || password == "") {
        showAlert({
            alertTitle: "温馨提示",
            alertContent: '请检测手机号或登录密码是否填写错误',
            alertType: 1,
            singleBtnText: "确定",
        })
        return false;
    }
    
    if (code == "") {
        showAlert({
            alertTitle: "温馨提示",
            alertContent: '请填写登录验证码',
            alertType: 1,
            singleBtnText: "确定",
        })
        return false;
    }

    var options = {
        type: 'POST',
        data: {"phone": phone, "password": password,"code":code},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
//                alert('登录成功！');
                window.location.href='/index/index';
            } else {
                 showAlert({
                                alertTitle: "温馨提示",
                                alertContent: data.error,
                                alertType: 1,
                                singleBtnText: "确定"
                            });
                $("#vcodeimg").trigger("click");
            }
        }
    };

    $("#form-submit").ajaxSubmit(options);
    return false;
}

