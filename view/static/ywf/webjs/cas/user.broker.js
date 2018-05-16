$(document).ready(function () {
    // 加载即加载
    getYongJin();
    
    //已结
    $("#yijie1").click(function(){
        getYongJin1();
        $("#paging").hide();
        $("#page").page('remote');
    });
    
    //未接
    $("#weijie1").click(function(){
        $("#paging").show();
        $("#paging").page('remote');
    });
});

function getYongJin() {
    //请求格式： .../GetPageData?query = test & pageIndex = 0 & pageSize = 10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
    $("#paging").page({
        // brokerage_status 
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
            url: '/cas/user/brokerList', //请求地址
            params: {brokerage_status:1}, //自定义请求参数
            success: function (result, pageIndex) {
                var yongjin = '';
                $.each(result.content, function (index, content) {
                    if (content.username == null) {
                        content.username = '';
                    }
                    
                    if (content.goods_name == null) {
                        content.goods_name = '';
                    }
                    
                    //佣金状态
                    var tmp = parseInt(content.brokerage_status);
                    switch (tmp) {
                        case 1:
                            content.brokerage_status = '待结佣金';
                            break;
                        case 2:
                            content.brokerage_status = '已结佣金';
                            break;
                        default:
                            content.brokerage_status = '已拒绝';
                            break;

                    }

                    yongjin += '<tr>' +
                            '<td>' + content.username + '</td>' +
                            '<td>' + content.goods_name + '</td>' +
                            '<td>' + content.user_grade + '</td>' +
                            '<td>' + content.investment_amount + '</td>' +
                            '<td>' + content.brokerage_ratio + '</td>' +
                            '<td>' + content.expected_money + '</td>' +
                            '<td>' + content.brokerage_status + '</td>' +
                            '<td>' + content.order_time + '</td>' +
                            '</tr>';
                });

                if (yongjin == '') {
                    yongjin += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#daijie").empty();
                $("#daijie").html(yongjin);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

function getYongJin1() {
    //请求格式： .../GetPageData?query = test & pageIndex = 0 & pageSize = 10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
    $("#page").page({
        // brokerage_status 
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
            url: '/cas/user/brokerList', //请求地址
            params: {brokerage_status:2}, //自定义请求参数
            success: function (result, pageIndex) {
                var yongjin = '';
                $.each(result.content, function (index, content) {
                    if (content.username == null) {
                        content.username = '';
                    }
                    
                    //佣金状态
                    var tmp = parseInt(content.brokerage_status);
                    switch (tmp) {
                        case 1:
                            content.brokerage_status = '待结佣金';
                            break;
                        case 2:
                            content.brokerage_status = '已结佣金';
                            break;
                        default:
                            content.brokerage_status = '已拒绝';
                            break;

                    }

                    yongjin += '<tr>' +
                            '<td>' + content.username + '</td>' +
                            '<td>' + content.goods_name + '</td>' +
                            '<td>' + content.user_grade + '</td>' +
                            '<td>' + content.investment_amount + '</td>' +
                            '<td>' + content.brokerage_ratio + '</td>' +
                            '<td>' + content.expected_money + '</td>' +
                            '<td>' + content.brokerage_status + '</td>' +
                            '<td>' + content.order_time + '</td>' +
                            '</tr>';
                });
                
                $("#paging").hide();
                
                if (yongjin == '') {
                    yongjin += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#yijie").empty();
                $("#yijie").html(yongjin);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}