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
    var recommender = $("input[name=recommender]").val();

    // 空值判断
    if (recommender == '') {
        alert('推荐人未提供');
        return false;
    }

    var options = {
        url: '/cas/setting/recommender', // 修改推荐人
        type: 'POST',
        data: {"recommender": recommender},
        dataType: 'json',
        timeout: 60000,
        success: function (data, textStatus) {
            if (data.status == 0) {
                alert('推荐人修改成功!');
                window.location.href = '/cas/setting/index';
            } else {
                alert(data.error);
            }
        }
    };

    $("#form-submit").ajaxSubmit(options);
    return false;
}
