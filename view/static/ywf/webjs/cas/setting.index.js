$(document).ready(function () {
    /* 提交点击事件*/
//    $("#form-submit").click(function () {
        getApi();
//    });
});

/**
 * 接口交互部分，以及页面提示等
 * @returns {Boolean}
 */
function getApi() {
    var options = {
        url: '/cas/setting/index',
        type: 'POST',
        data: {},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                
            } else {
                alert(data.error);
            }
        }
    };

    $.ajax(options);
    return false;
}