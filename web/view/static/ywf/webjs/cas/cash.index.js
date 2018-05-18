$(document).ready(function () {
    /* 提交点击事件*/
    $(".form-submit").click(function () {
        getApi();
    });
});

/**
 * 接口交互部分，以及页面提示等
 * @returns {Boolean}
 */
function getApi() {
    // 提现金额,只能输入数字
    var tixian = $("#tixian").val();
    tixian = parseInt(tixian);
    var asset = $("#asset").val();
    asset = asset;

    //未绑卡去帮卡
    var bank_status = $("#bank_status").val();
    //未绑卡
    if(bank_status==4){
            showAlert({
                    alertTitle:"温馨提示",
                    alertContent:"您尚未绑定银行卡",
                    alertType:2,
                    doubleBtnText1:"取消",
                    doubleBtnText2:"去绑卡",
                    goUrl:"/cas/bank/addbank"
            });
            return false;
    }
    //未设置密码去设置密码
    var status = $("#status").val();
        if(status==2){
            showAlert({
                    alertTitle:"温馨提示",
                    alertContent:"您尚未设置交易密码",
                    alertType:2,
                    doubleBtnText1:"取消",
                    doubleBtnText2:"去设置",
                    goUrl:"/cas/setting/tradepwd"
            });
            return false;
    }

    // 空值判断
    if (isNaN(tixian)) {
        alert('请输入正确的提现金额!');
        $("#tixian").val("");
        return false;
    }

    // 余额不足
    if (asset < tixian) {
        alert('余额不足');
        $("#tixian").val("");
        return false;
    }

    //最高100万
    if (tixian > 10000000) {
        alert('提现金额不能超过10000000元');
        $("#tixian").val("");
        return false;
    }

    //password
    var password = $("#password").val();
    password = parseInt(password);
    if (password == "") {
        alert("请输入6位数字密码!");
        return false;
    }
    
    if (isNaN(password)) {
        alert("请输入6位数字密码！");
        return false;
    }

    var options = {
        url: '/cas/cash/index',
        type: 'POST',
        data: {"tixian": tixian, "password": md5(password)},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                //提现成功页
                window.location.href='/cas/cash/prompt';
            } else {
                showAlert({
                            alertTitle:"温馨提示",
                            alertContent:data.error,
                            alertType:1,
                            singleBtnText:"确定"
    			});
            }
        }
    };

    $(".form-submit").ajaxSubmit(options);
    return false;
}