$(document).ready(function () {
    //jixi
    getApi();
        
    $("#jixizhong").click(function () {
        $("#page").show();
        $("#paging").hide();
        getApi();
        $("#page").page('remote',{"tag": 2});
    });

    $("#yiduifu").click(function () {
        $("#paging").show();
        $("#page").hide();
        getApi1();
        $("#paging").page('remote',{"tag": 4});
    });

    $("#yuyuezhong").click(function () {
        $("#paging").show();
        $("#page").hide();
        getApi1();
        $("#paging").page('remote',{"tag": 1});
    });

    $('.zr').click(function () {
        var order_id = $(this).parent().siblings('.order_id').attr('order_id');
        var counter_money = $(this).parent().siblings('.counter_money').attr('counter_money');
        var rate_min_money = $(this).parent().siblings('.rate_min_money').attr('rate_min_money');
        var rate_max_money = $(this).parent().siblings('.rate_max_money').attr('rate_max_money');
        var content = '本次转让成功需要收取<span style="color:red;">' + counter_money + '</span>元转让费，转让价格区间为<span style="color:red;">' + rate_min_money + '</span>-<span style="color:red;">' + rate_max_money + '</span>元(仅整数)';
        showAlert({
            alertTitle: "转让确认",
            alertContent: content,
            alertType: 5,
            doubleBtnText1: "取消",
            doubleBtnText2: "确认",
        });

        $('.btnRight').click(function () {
            var transfer_price = $('#myPassword').val();
            $.post('/cas/order/publish', {'transfer_price': transfer_price, 'order_id': order_id, 'counter_money': counter_money}, function (data) {
                if (data.status == 0) {
                    alert('操作成功')
                    window.location.reload();
                } else {
                    alert(data.error)
                }
            })
        })


    })

    $('.cx').click(function () {
        var order = $(this).parent().siblings('.order_id').attr('order_id');
        showAlert({
            alertTitle: "温馨提示",
            alertContent: "您确定要撤销吗",
            alertType: 4,
            doubleBtnText1: "取消",
            doubleBtnText2: "同意",
        });

        $('.btnRight').click(function () {
            $.post('/cas/order/RevokeTransfer', {'order_id': order}, function (data) {
                if (data.status == 0) {
                    alert('操作成功')
                    window.location.reload();
                } else {
                    alert(data.error)
                }
            })
        })

    })

});

//jixi
function getApi() {
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
//返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
//'订单状态【 1：预约中 2：计息中 3：已结息 4：已兑付 5:已取消】
    $("#page").page({
        pageSize: 6,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
        //showPageSizes: true,
        infoFormat: '共{total}条', //{start} ~ {end}条，

        remote: {
            url: '/cas/order/getOrderList', //请求地址
            params: {tag: 2}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.info, function (index, content) {
                    // 开放转让
                    var transferStr = '';
                    if (content.is_transfer == 1) {
                        transferStr = "截止日期</br>" + content.transfer_maxdate;
                    } else {
                        transferStr = '不支持转让';
                    }

                    html += '<tr>' +
                            '<td class="order_id" order_id="' + content.order_id + '">' + content.order_no + '</td>' +
                            '<td class="counter_money" counter_money="' + content.counter_money + '">' + content.goods_name + '</td>';
                    if (content.order_status == 2) {
                        html += '<td>' + '计息中' + '</td>';
                    }

                    if (content.order_status == 1) {
                        html += '<td>' + '预约中' + '</td>';
                    }

                    html += '<td class="rate_max_money"  rate_max_money="' + content.rate_max_money + '">' + content.buy_money + '</td>' +
                            '<td class="rate_min_money" rate_min_money="' + content.rate_min_money + '">' + content.yield + '%</td>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.cash_time + '</td>' +
                            '<td>' + "<a href='/cas/order/detail?order_no=" + content.order_no + "'>查看</a>" + '</td>';

                    /*if (content.is_transfer == 1) {
                        if (content.transfer_status == 0) {
                            html += '<td><button class="zr">转让</button></td>';
                        } else {
                            html += '<td><button class="cx">撤销</button></td>';
                        }
                    } else {
                        html += '<td></td>';
                    }

                    html += '<td>' + transferStr + '</td></tr>';*/

                });
                
                if (html == '') {
                    html += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    

                // jixi
                $("#jixi").empty();
                $("#jixi").html(html);

                //duifu
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

//yiduifu
//jixi
function getApi1() {
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
//返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
//'订单状态【 1：预约中 2：计息中 3：已结息 4：已兑付 5:已取消】
    $("#paging").page({
        pageSize: 6,
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
            url: '/cas/order/getOrderList', //请求地址
            params: {tag:1}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';
                $.each(result.info, function (index, content) {
                    if (content.order_status == 1) {
                        content.order_status = '预约中';
                        tag_type = '1';
                    }else if (content.order_status == 2) {
                        tag_type = '3';
                        content.order_status = '计息中';
                    }else if (content.order_status == 4) {
                        tag_type = '4';
                        content.order_status = '已兑付';
                    }
                    
                    html += '<tr>' +
                            '<td>' + content.order_no + '</td>' +
                            '<td>' + content.goods_name + '</td>' +
                            '<td>' + content.order_status + '</td>' +
                            '<td>' + content.buy_money + '</td>' +
                            '<td>' + content.yield + '%</td>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.cash_time + '</td>' +
                            '<td>' + "<a href='/cas/order/detail?order_no=" + content.order_no + "'>查看</a>" + '</td>' +
                            '</tr>';
                });

                if (html == '') {
                    html += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                var tag_type = $('.tab-btn').attr('id');
                
                if (tag_type == 'yuyuezhong') {
                    $("#yuyue").empty();
                    $("#yuyue").html(html);
                }else if (tag_type == 'yiduifu') {
                    $("#duifu").empty();
                    $("#duifu").html(html);
                }
               
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}