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
    // 接收值
    var address = $("input[name=address]").val();
    var zip_code = $("input[name=zip_code]").val();
    var contacts_person = $("input[name=contacts_person]").val();
    var contacts_phone = $("input[name=contacts_phone]").val();

    // 空值判断
    if (address == '') {
        showAlert({
		alertTitle:"温馨提示",
		alertContent:"你还没有输入详细地址",
		alertType:1,
		singleBtnText:"确定"
	});
        return false;
    }
    if (zip_code == '') {
        showAlert({
		alertTitle:"温馨提示",
		alertContent:"你还没有输入邮编",
		alertType:1,
		singleBtnText:"确定"
	});
        return false;
    }
    if (contacts_person == '') {
        showAlert({
		alertTitle:"温馨提示",
		alertContent:"你还没有输入联系人姓名",
		alertType:1,
		singleBtnText:"确定"
	});
        return false;
    }
    if (contacts_phone == '') {
        showAlert({
		alertTitle:"温馨提示",
		alertContent:"你还没有输入联系电话",
		alertType:1,
		singleBtnText:"确定"
	});
        return false;
    }

    var options = {
        url: '/cas/setting/contact', // 修改地址
        type: 'POST',
        data: {"address": address, "zip_code": zip_code, "contacts_person": contacts_person, "contacts_phone": contacts_phone},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                showAlert({
                    alertTitle:"温馨提示",
                    alertContent:"联系方式修改成功",
                    alertType:1,
                    singleBtnText:"确定"
                });
                window.location.href = '/cas/setting/index';
            } else {
                alert(data.error);
            }
        }
    };

    $("#form-submit").ajaxSubmit(options);
    return false;
}
