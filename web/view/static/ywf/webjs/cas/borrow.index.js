$(document).ready(function () {
    getApi();
    $("#jixizhong").click(function () {
        $("#page").page('remote', {"tag": 1});
    });

    $("#yiduifu").click(function () {
        $("#page").page('remote', {"tag": 2});
    });
});

//jixi
function getApi() {
    $("#page").page({
        pageSize: 6,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
        infoFormat: '共{total}条', //{start} ~ {end}条，
        remote: {
            url: '/cas/borrow/getOrderList', //请求地址
            type: 'GET',
            params: {tag: 1}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';
                $.each(result.info, function (index, content) {
                    html += '<tr><td>' + content.borrow_name + '</td>';
                    var show_str = '<a class="hand" onclick="showIncomes(' + content.bil_id + ')">详情</a>';
                    if (content.last_payment_time == null) {
                        content.last_payment_time = '--';
                        show_str = '--';
                    }
                    html += '<td>' + content.invest_money + '</td>' +
                        '<td>' + content.yield_rate + '%</td>' +
                        '<td>' + content.invest_time + '</td>' +
                        '<td>' + content.last_payment_time + '</td>' +
                        '<td>' + content.borrow_time_limit + '天</td>' +
                        '<td>' + show_str + '</td>';
                });

                if (html == '') {
                    html = '<tr><td colspan="7"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"></td></tr>';
                }
                $("#t_data").empty();
                $("#t_data").html(html);
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}


function showIncomes(bil_id) {
    var showIncome = document.getElementById('showIncome');
    var over = document.getElementById('over');
    showIncome.style.display = "block";
    over.style.display = "block";
    $.ajax({
        type: 'get',
        url: '/cas/borrow/getIncomeList',
        data: 'bil_id=' + bil_id,
        dataType: 'json',
        timeout: 60000,
        success: function (data) {
            var html = '';
            $.each(data, function (index, content) {
                var status = '';
                if (content.income_status == 0) {
                    status = '未兑付';
                } else {
                    status = '已兑付';
                }
                html += '<tr><td>' + content.sum_money + '</td>' +
                    '<td>' + content.time_limit + '</td>' +
                    '<td>' + content.interest_limit_num + '/' + content.interest_limit + '</td>' +
                    '<td>' + content.expect_payment_time + '</td>' +
                    '<td>' + status + '</td>';
            });
            $("#bil_data").empty();
            $("#bil_data").html(html);
        }
    });
}

function hide() {
    var showIncome = document.getElementById('showIncome');
    var over = document.getElementById('over');
    showIncome.style.display = "none";
    over.style.display = "none";
}

