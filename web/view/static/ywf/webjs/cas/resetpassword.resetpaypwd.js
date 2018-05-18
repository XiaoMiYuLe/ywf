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
    
    //交易密码只能是数字判断
    
    if (oldpassword == '' || newpassword == '' || newpasswordOne == '') {
        alert('输入密码不能为空');
        return false;
    }
    
    if (isNaN(newpassword)) {
        alert('交易密码只能是纯数字');
        return false;
    }
    
    if (isNaN(newpasswordOne)) {
        alert('交易密码只能是纯数字');
        return false;
    }
    
    if (oldpassword.length != 6) {
        alert("请检查交易密码是否为6位纯数字!");
        return false;
    }
    
    if (newpassword.length != 6) {
        alert("请检查交易密码是否为6位纯数字!");
        return false;
    }
    
    if (newpasswordOne.length != 6) {
        alert("请检查交易密码是否为6位纯数字!");
        return false;
    }
    
    if (newpassword != newpasswordOne) {
        alert('请输入相同密码');
        return false;
    }

    var options = {
        url: '/cas/resetpassword/resetpaypwd', // 修改密码
        type: 'POST',
        data: {"oldpassword":md5(oldpassword),"newpassword":md5(newpassword),"newpasswordOne":md5(newpasswordOne)},
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
