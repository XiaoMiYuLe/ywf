
$(document).ready(function () {

    getApi();

    //weishiyong
    $("#weishiyong1").click(function () {
        $("#page").hide();
        $("#page1").hide();
        $("#paging").show();
        $("#paging").page('remote');
    });

    //yishiyong
    $("#yishiyong1").click(function () {
        $("#paging").hide();
        $("#page1").hide();
        $("#page").show();
        getApi1();
        $("#page").page('remote');
    });

    //yiguoqi
    $("#yiguoqi1").click(function () {
        $("#paging").hide();
        $("#page").hide();
        $("#page1").show();
        getApi2();
        $("#page1").page('remote');
    });

    //使用
    $("body").delegate(".use", 'click', function () {
        var voucher = $(this).parent().siblings().find('.voucher').val();
        useuse = voucher;
        showAlert({
            alertTitle: "温馨提示",
            alertContent: "您确定使用推广金吗？",
            alertType: 2,
            doubleBtnText1: "取消",
            doubleBtnText2: "确定",
            goUrl: "javascript:;"
        });

        $('.btnRight').click(function(){
                $.post("/cas/user/managerUse", {"voucher": voucher}, function (response) {
                if (response.status == 0) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: "使用成功",
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                } else {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: response.error,
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                }
            }, 'json');
        });

    });

    //use1 shiyong
    $("body").delegate('.use1', 'click', function () {
        
        $.post('/cas/user/getTuiInfo',{},function(response){
            
            if (response.status == 0) {
                var product_id = response .data.goods_id;
                window.location.href = "/goods/goods/detail?goods_id="+product_id+"";
                
            }else {
                showAlert({
                    alertTitle:"温馨提示",
                    alertContent:response.error,
                    alertType:1,
                    singleBtnText:"确定"
                });
            }
            
        });

    });

});



function getApi() {
    $("#paging").page({
        pageSize: 7,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
//        showPageSizes: true,
        infoFormat: '共{total}条', //{start} ~ {end}条，

        remote: {
            url: '/cas/user/couponList', //请求地址
            params: {status: 1}, //自定义请求参数
            success: function (result, pageIndex) {
                var coupon = '';
                $.each(result.voucher, function (index, content) {
                    //推广金使用
                    var flag = '';
                    if (content.type == 2 && content.is_manager == 1) {
                        flag = "<button class='use'>使用</button>";
                    } else if (content.type == 2 && content.is_manager == 0) { //体验金
                        flag = "<button class='use1'>使用</button>";
                    } else {
                        flag = '';
                    }
                    
                    //begin time
                    var begin_time = content.start_data.substring(0,10);
                    //end time
                    var end_time = content.valid_data.substring(0,10);
                    
                    //分类
                    var title = '';
                    if (content.left_title) {
                        title = content.left_title;
                    } else if (content.right_title) {
                        title = content.right_title
                    } else {
                        title = '';
                    }

                    coupon += '<tr>' +
                            '<td>' + title + '</td>' +
                            '<td>' + content.content_money + '</td>' +
                            '<td>' + begin_time + '</td>' +
                            '<td>' + end_time + '</td>' +
                            '<td>' + content.remarks + '</td>' +
                            '<td>' + flag + '</td>' +
                            '<td style="width:0px;display:none;">' + "<input type='hidden' class='voucher' value='" + content.id + "'/>" + '</td>' +
                            '</tr>';

                });
                
                if (coupon == '') {
                    coupon += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    

                $("#weishiyong").empty();
                $("#weishiyong").html(coupon);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}


function getApi1() {
    $("#page").page({
        pageSize: 7,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
//        showPageSizes: true,
        infoFormat: '共{total}条', //{start} ~ {end}条，

        remote: {
            url: '/cas/user/couponList', //请求地址
            params: {status: 2}, //自定义请求参数
            success: function (result, pageIndex) {
                var coupon = '';
                $.each(result.voucher, function (index, content) {
                    
                    //分类
                    var title = '';
                    if (content.left_title) {
                        title = content.left_title;
                    } else if (content.right_title) {
                        title = content.right_title
                    } else {
                        title = '';
                    }
                    
                    //begin time
                    var begin_time = content.start_data.substring(0,10);
                    //end time
                    var end_time = content.valid_data.substring(0,10);

                    coupon += '<tr>' +
                            '<td>' + title + '</td>' +
                            '<td>' + content.content_money + '</td>' +
                            '<td>' + begin_time + '</td>' +
                            '<td>' + end_time + '</td>' +
                            '<td>' + content.remarks + '</td>' +
                            '</tr>';

                });

                if (coupon == '') {
                    coupon += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#yishiyong").empty();
                $("#yishiyong").html(coupon);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

function getApi2() {
    $("#page1").page({
        pageSize: 7,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
//        showPageSizes: true,
        infoFormat: '共{total}条', //{start} ~ {end}条，

        remote: {
            url: '/cas/user/couponList', //请求地址
            params: {status: 3}, //自定义请求参数
            success: function (result, pageIndex) {
                var coupon = '';
                $.each(result.voucher, function (index, content) {

                    //begin time
                    var begin_time = content.start_data.substring(0,10);
                    //end time
                    var end_time = content.valid_data.substring(0,10);

                    //分类
                    var title = '';
                    if (content.left_title) {
                        title = content.left_title;
                    } else if (content.right_title) {
                        title = content.right_title
                    } else {
                        title = '';
                    }

                    coupon += '<tr>' +
                            '<td>' + title + '</td>' +
                            '<td>' + content.content_money + '</td>' +
                            '<td>' + begin_time + '</td>' +
                            '<td>' + end_time + '</td>' +
                            '<td>' + content.remarks + '</td>' +
                            '</tr>';

                });

                if (coupon == '') {
                    coupon += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    

                $("#yiguoqi").empty();
                $("#yiguoqi").html(coupon);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}
