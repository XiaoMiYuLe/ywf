$(document).ready(function () {
    customRecord();
    
    //jixizhong
    $("#jixizhong1").click(function(){
      $("#page").hide();
      $("#paging").show();
      $("#paging").page('remote');
    });
    
    //yiduifu
    $("#yiduifu1").click(function(){
        customRecord1();
        $("#paging").hide();
        $("#page").show();
        $("#page").page('remote');
    });
    
});

/* wodekehu*/
function customRecord() {
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
            url: '/cas/user/getCustomRecord', //请求地址
            params: {tag: 1}, //自定义请求参数
            success: function (result, pageIndex) {
                var jiaoyi = '';
                $.each(result.content, function (index, content) {
                    //客户交易
                   if (content.username == null) {
                       content.username = "";
                   } 
                   if (content.goods_name == null) {
                       content.goods_name = "";
                   } 
                   if (content.cash_time == null) {
                       content.cash_time = "";
                   }
                    jiaoyi += '<tr>' +
                            '<td>' + content.username + '</td>' +
                            '<td>' + content.goods_name + '</td>' +
                            '<td style="color:#00aaee;">' + content.buy_money + '</td>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.cash_time + '</td>' +
                            '</tr>';

                });

                if (jiaoyi == '') {
                    jiaoyi += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#jixizhong").empty();
                $("#jixizhong").html(jiaoyi);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

function customRecord1() {
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
            url: '/cas/user/getCustomRecord', //请求地址
            params: {tag: 2}, //自定义请求参数
            success: function (result, pageIndex) {
                var jiaoyi = '';
                $.each(result.content, function (index, content) {
                   //客户交易
                   if (content.username == null) {
                       content.username = "";
                   } 
                   if (content.goods_name == null) {
                       content.goods_name = "";
                   } 
                   if (content.cash_time == null) {
                       content.cash_time = "";
                   }
                   
                   jiaoyi += '<tr>' +
                            '<td>' + content.username + '</td>' +
                            '<td>' + content.goods_name + '</td>' +
                            '<td style="color:#00aaee;">' + content.buy_money + '</td>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.cash_time + '</td>' +
                            '</tr>';

                });

                if (jiaoyi == '') {
                    jiaoyi += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }   
                
                $("#yiduifu").empty();
                $("#yiduifu").html(jiaoyi);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}