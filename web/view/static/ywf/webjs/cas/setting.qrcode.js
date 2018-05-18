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
        url: '/cas/setting/qrcode',
        type: 'POST',
        data: {},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                // 生成二维码
                $("#code").qrcode({
                    render: "table", //table方式 
                    width: 160, //宽度 
                    height: 160, //高度 
                    text: data.data.link_url, //任意内容 
                });
                
                // 设置user_code
                $("#sp").html(data.data.user_code);

            } else {
                alert(data.error);
            }
        }
    };

    $.ajax(options);
    return false;
}