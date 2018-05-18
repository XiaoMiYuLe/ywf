$(document).ready(function () {
    var flag = 1;
    $("#sendCode").click(function () {
        var isMobile = /^1[34578][0-9]{1}[0-9]{8}$/;

        var phone = $("#phone").val();
        if (!isMobile.test(phone)) {
            alert('请输入正确的手机号码')
            return false;
        }
        if (flag == 1) {
            flag = 2;
            $("#sendCode").attr("disabled", "disabled");
            /* 不管成功与否都发送信息*/
            send();
            $.ajax({
                type: 'post',
                url: '/cas/resetpassword/sendcode',
                data: {"send_to": phone},
                dataType: 'json',
                timeout: 60000,
                success: function (response) {  
                    if (response.status == 0) {
                        alert('验证码发送成功，请注意查收！')
                    } else {
                        alert(response.error)
                    }
                }
            });

            setTimeout(function () {
                flag = 1;
            }, 60000);
        }
    });

    function send() {
        var nums = 60;  
        var numnow;
        $("#sendCode").html("重发" + 60 + "s").css('background', '#ccc');
        var ss = setInterval(function () {
            nums--;
            numnow = nums;
            $("#sendCode").html("重发" + numnow + "s").css('background', '#ccc');
            if (nums == 0) {
                clearInterval(ss);
                $("#sendCode").html("重新发送").css('background', '#ffaa00');
                nums = 6;
                $("#sendCode").removeAttr("disabled")
            }
        }, 1000)
    }

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
    var code = $("input[name=code]").val();
    var pay_pwd = $("input[name=pay_pwd]").val();
    var repeatpay_pwd = $("input[name=repeatpay_pwd]").val();
    var test_idcard = $("input[name=test_idcard]").val();
    
    if (code == '') {
        alert('验证码不能为空！');
        return false;
    }
    
    if (pay_pwd == '' || repeatpay_pwd == '') {
        alert('输入密码不能为空！');
        return false;
    }
    
    if (isNaN(pay_pwd)) {
        alert('交易密码只能是纯数字');
        return false;
    }
    
    if (isNaN(repeatpay_pwd)) {
        alert('交易密码只能是纯数字');
        return false;
    }
    
    if (pay_pwd.length != 6) {
        alert("请检查交易密码是否为6位纯数字!");
        return false;
    }
    
    if (repeatpay_pwd.length != 6) {
        alert("请检查交易密码是否为6位纯数字!");
        return false;   
    }

    if (pay_pwd != repeatpay_pwd) {
        alert('请输入相同密码！');
        return false;
    }

    var options = {
        url: '/cas/resetpassword/forgotPaypwd', // 修改密码
        type: 'POST',
        data: {"pay_pwd": md5(pay_pwd), "repeatpay_pwd": md5(repeatpay_pwd),"code":code},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                alert('交易密码修改成功！');
                window.location.href = '/cas/setting/index';
            } else {
                alert(data.error);
            }
        }
    };

    $("#form-submit").ajaxSubmit(options);
    return false;
}
