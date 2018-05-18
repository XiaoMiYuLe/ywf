
$(document).ready(function () {
    var flag = 1;
    $("#sendCode").click(function () {
        var isMobile = /^1[34578][0-9]{1}[0-9]{8}$/;
        var imgcode = $("input[name=imgcode]").val();
        var phone = $("#phone").val();
        if (!isMobile.test(phone)) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入正确的手机号码',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }

        //请输入正确的图形验证码
        if (!imgcode) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入正确的图形验证码',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }

        if (flag == 1) {
            /* 先检查图形验证码是否正确*/
            var imgcode = $("input[name=imgcode]").val();

            $.post('/cas/sign/checkImg', {"imgcode": imgcode}, function (response) {
                if (response.status == 1) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: response.error,
                        alertType: 1,
                        singleBtnText: "确定",
                    })
                    $("#vcodeimg").trigger("click");
                    return false;
                } else if (response.status == 0) {
                    $("#sendCode").attr("disabled", "disabled");

                    /* 不管成功与否都发送信息*/
                    send();

                    $.ajax({
                        type: 'post',
                        url: '/cas/forgotpassword/sendcode',
                        data: {"send_to": phone},
                        dataType: 'json',
                        timeout: 60000,
                        success: function (response) {
                            if (response.status == 0) {

                            } else {
                                showAlert({
                                    alertTitle: "温馨提示",
                                    alertContent: response.error,
                                    alertType: 1,
                                    singleBtnText: "确定",
                                })
                                return false;
                            }
                        }
                    });

                    setTimeout(function () {
                        flag = 1;
                    }, 60000);
                }

            }, 'json');
        }
        
    });

    function send() {
        var nums = 60;
        var numnow;
        $("#sendCode").val("重发" + 60 + "s").css('background', '#ccc');
        var ss = setInterval(function () {
            nums--;
            numnow = nums;
            $("#sendCode").val("重发" + numnow + "s").css('background', '#ccc');
            if (nums == 0) {
                $("#vcodeimg").trigger("click");
                clearInterval(ss);
                $("#sendCode").val("重新发送").css('background', '#ffaa00');
                nums = 6;
                $("#sendCode").removeAttr("disabled")
            }
        }, 1000)
    }

    /* 提交点击事件*/
    $("#form-submit").click(function () {

        var isMobile = /^1[34578][0-9]{1}[0-9]{8}$/;

        var phone = $("#phone").val();
        if (!isMobile.test(phone)) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入正确的手机号码',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }

        var code = $("#code").val();
        if (!code) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入短信验证码',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }

        getApi();
    });
});

/**
 * 接口交互部分，以及页面提示等
 * @returns {Boolean}
 */
function getApi() {
    var phone = $("#phone").val();
    var code = $("#code").val();

    var options = {
        url: '/cas/forgotpassword/NextPassword', //下一步
        type: 'POST',
        data: {"phone": phone, "code": code},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                window.location.href = '/cas/forgotpassword/resetpassword';
            } else {
                showAlert({
                    alertTitle: "温馨提示",
                    alertContent: data.error,
                    alertType: 1,
                    singleBtnText: "确定",
                })
                return false;
            }
        }
    };

    $("#form-submit").ajaxSubmit(options);
    return false;
}