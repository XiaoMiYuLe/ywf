$(document).ready(function () {
    var flag = 1;
    $("#sendCode").click(function () {
        var isMobile = /^1[34578][0-9]{1}[0-9]{8}$/;

        var phone = $("#phone").val();
        var imgcode = $("input[name=imgcode]").val();

        if (!phone) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入手机号码',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }
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
                    /* 不管成功与否都发送信息*/
                    $.ajax({
                        type: 'post',
                        url: '/cas/sign/sendcode',
                        data: {"send_to": phone},
                        dataType: 'json',
                        timeout: 60000,
                        success: function (response) {
                            if (response.status == 0) {
                                flag = 2;
                                $("#sendCode").attr("disabled", "disabled");
                                send();
                            } else {
                                showAlert({
                                    alertTitle: "温馨提示",
                                    alertContent: response.error,
                                    alertType: 1,
                                    singleBtnText: "确定",
                                })
                            }
                        }

                    });
                }
            }, 'json');

            setTimeout(function () {
                flag = 1;
            }, 60000);
        }
    });

    function send() {
        //发送成功立即触发重发验证码
//        $("#vcodeimg").trigger("click");
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
                // 更新验证码
                $("#sendCode").val("重新发送").css('background', '#ffaa00');
                nums = 6;
                $("#sendCode").removeAttr("disabled")
            }
        }, 1000)
    }

    $("#sub").click(function () {
        var phone = $("#phone").val();
        var code = $("#code").val();
        var pwd = $("#pwd").val();
        var repwd = $("#repwd").val();
        var recommender = $("#recommender").val();
        var imgcode = $("input[name=imgcode]").val();
        var code = $("#code").val();
        if (phone == "" || phone == null || code == null || code == "" || code == null || pwd == "" || pwd == null || repwd == "" || repwd == null || imgcode == null) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请将信息填写完整',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }
        $.post("/cas/sign/signup", {"phone": phone, "code": code, "pwd": pwd, "repwd": repwd, "recommender": recommender, "code":code,"imgcode":imgcode}, function (data) {
            if (data.status == 0) {
                showAlert({
                    alertTitle: "温馨提示",
                    alertContent: '注册成功！',
                    alertType: 1,
                    singleBtnText: "确定",
                })
                $('.btnSubmit').click(function(){   
                    location.href = '/index/index';
                });
            } else {
                showAlert({
                    alertTitle: "温馨提示",
                    alertContent: data.error,
                    alertType: 1,
                    singleBtnText: "确定",
                })
            }
        }, 'json');
    });
});