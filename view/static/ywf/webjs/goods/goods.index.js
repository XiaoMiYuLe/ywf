$(document).ready(function () {
    //$("#licai").click(function(){
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
    getApi();

    $("#licai").click(function () {
        getApi();
        $("#page").page('remote');
    });

    $("#yuyue").click(function () {
        getApi2();
        $("#page2").page('remote');
    });

    $("#zhuanrang").click(function () {
        getApi1();
        $("#page1").page('remote');
    });
    
});

//licai
function getApi() {
    $("#page").page({
        pageSize: 5,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
//        showPageSizes: true,
        infoFormat: '共{total}条',
        remote: {
            url: '/goods/goods/getGoodsContent', //请求地址
            params: {tag: "1"}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';
                $.each(result.info, function (index, content) {

                    html += '<div class="goods_list goods_list12">';
                    //新手产品展示三角标志
                    if (content.goods_pattern == 1) {
                        html += '<b></b>';
                    }
                    html += '<div class="goods_list_l">';

                    //代金券，加息券
                    if (content.voucher_name && content.interest_name) {
                        html += '<h3><a href="">' + content.goods_name + '</a><span>' + content.voucher_name + '</span><span>' + content.interest_name + '</span>';
                    }

                    if (content.voucher_name && content.interest_name == '') {
                        html += '<h3><a href="/goods/goods/detail?goods_id=' + content.goods_id + '">' + content.goods_name + '</a><span>' + content.voucher_name + '</span>';
                    }

                    if ((content.voucher_name == '') && content.interest_name) {
                        html += '<h3><a href="/goods/goods/detail?goods_id=' + content.goods_id + '">' + content.goods_name + '</a><span>' + content.interest_name + '</span>';
                    }

                    if (content.voucher_name == '' && content.interest_name == '') {
                        html += '<h3><a href="/goods/goods/detail?goods_id=' + content.goods_id + '">' + content.goods_name + '</a>';
                    }
                    //新手标上限3.6万
                    if (content.goods_pattern == 1) {
                        html += '<em>上限3.6万</em>';
                    }

                    //体验标
                    if (content.goods_pattern == 4) {
                        html += '<em>体验标</em>';
                    }

                    html += '</h3>';

                    html += '<div class="goods_title"><span>' + content.comment + '</span></div>';
                    html += '<ul><li><div class="title title1"><span  class="shuzi">' + content.yield + '</span> <span>%</span> </div> <p>预期年化收益</p></li>';
                    html += '<li class="sep"><div class="title title2"> <span class="shuzi">' + content.financial_period + '</span><span>天</span></div><p>期限</p> </li>';
                    html += '<li><div class="title title3"><span class="shuzi">' + content.low_pay + '</span> <span>元</span></div> <p>起投金额</p></li></ul></div>';
                    html += '<div class="goods_list_r">';
                    //新手产品展示投资人数
                    if (content.goods_pattern == 1||content.goods_pattern == 4) {
                        html += '<p>投资人数：' + content.buy_num + '人</p>';
                    }

                    if (content.goods_pattern == 2) {
                        if (content.goods_status == 1) {
                            html += '<p>剩余金额：' + content.spare_fee_view + '元</p> <div class="pro_bar"><span class="progress" style="width:' + content.schedule + '%"></span><em class="progress_num">' + content.schedule + '%</em></div>';
                        }

                        if (content.goods_status == 2) {
                            html += '<p>累计投资：' + content.all_fee_view1 + '元</p>';
                        }

                    }

                    if (content.goods_status == 1) {
                        html += '<a href="/goods/goods/detail?goods_id=' + content.goods_id + '" class="btn">立即投资</a>';
                    } else if (content.goods_status == 2) {
                        html += '<a href="/goods/goods/detail?goods_id=' + content.goods_id + '" class="btn" style="background:#cccccc">售罄</a>';
                    } else if (content.goods_status == 3) {
                        html += '<a href="/goods/goods/trdetail?order_id=' + content.order_id + '" class="btn" style="background:#cccccc">已下架</a>';
                    }
                    html += '</div></div>';
                });

                if (html == '') {
                    html += '<img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;">'
                }
                
                $("#licai_content").empty();
                $('#licai_content').html(html);

                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum'              //指定返回数据的总数据量的字段名
        }
    });
}

//转让
function getApi1() {
    $("#page1").page({
        pageSize: 5,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
//        showPageSizes: true,
//{start} ~ {end}条，
        infoFormat: '共{total}条',
        remote: {
            url: '/goods/goods/GetTransferList', //请求地址
            params: {}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';
                $.each(result.info, function (index, content) {
                    html += '<div class="goods_list goods_list12">';
                    html += '<div class="goods_list_l">';
                    html += '<h3><a href="/goods/goods/trdetail?order_id=' + content.order_id + '">' + content.goods_name + '</a>';
                    html += '</h3>';
                    html += '<div class="goods_title"><span>' + content.comment + '</span></div>';
                    html += '<ul><li><div class="title title1"><span  class="shuzi">' + content.yield + '</span> <span>%</span> </div> <p>预期年化收益</p></li>';
                    html += '<li class="sep"><div class="title title2"> <span class="shuzi">' + content.distance_days + '</span><span>天</span></div><p>期限</p> </li>';
                    html += '<li><div class="title title3"><span class="shuzi">' + content.buy_money + '</span> <span>元</span></div> <p>起投金额</p></li></ul></div>';
                    html += '<div class="goods_list_r">';
                    html += '<p>折让：' + content.price_difference + '</p>';
                    html += '<a href="/goods/goods/trdetail?order_id=' + content.order_id + '" class="btn">立即投资</a>';
                    html += '</div></div>';
                });

                if (html == '') {
                    html += '<img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;">'
                }

                $('#zhuanrang_content').empty();
                $('#zhuanrang_content').html(html);

                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum'              //指定返回数据的总数据量的字段名
        }
    });
}

//yuyue
function getApi2() {
    $("#page2").page({
        pageSize: 5,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
//        showPageSizes: true,
        infoFormat: '共{total}条',
        remote: {
            url: '/goods/goods/getGoodsContent', //请求地址
            params: {tag: "2"}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';
                $.each(result.info, function (index, content) {
                    html += '<div class="goods_list goods_list12">';
                    //新手产品展示三角标志
                    if (content.goods_pattern == 1) {
                        html += '<b></b>';
                    }
                    html += '<div class="goods_list_l">';
                    html += '<h3><a href="/goods/goods/detail?goods_id=' + content.goods_id + '">' + content.goods_name + '</a>';
                    html += '</h3>';
                    html += '<div class="goods_title"><span>' + content.comment + '</span></div>';
                    html += '<ul><li><div class="title title1"><span  class="shuzi">' + content.yield + '</span> <span>%</span> </div> <p>预期年化收益</p></li>';
                    html += '<li class="sep"><div class="title title2"> <span class="shuzi">' + content.financial_period + '</span><span>天</span></div><p>期限</p> </li>';
                    html += '<li><div class="title title3"><span class="shuzi">' + content.low_pay + '</span> <span>元</span></div> <p>起投金额</p></li></ul></div>';
                    html += '<div class="goods_list_r">';
                    //新手产品展示投资人数
                    if (content.goods_pattern == 1) {
                        html += '<p>投资人数：' + content.buy_num + '人</p>';
                    } else {
                        if (content.goods_status == 1) {
                            html += '<p>剩余金额：' + content.spare_fee_view + '元</p> <div class="pro_bar"><span class="progress" style="width:' + content.schedule + '%"></span><em class="progress_num">' + content.schedule + '%</em></div>';
                        }

                        if (content.goods_status == 2) {
                            html += '<p>剩余金额：' + content.spare_fee_view + '元</p>';
                        }
                    }

                    if (content.goods_status == 1) {
                        html += '<a href="/goods/goods/detail?goods_id=' + content.goods_id + '" class="btn">立即投资</a>';
                    } else if (content.goods_status == 2) {
                        html += '<a href="/goods/goods/detail?goods_id=' + content.goods_id + '" class="btn" style="background:#cccccc">售罄</a>';
                    } else if (content.goods_status == 3) {
                        html += '<a href="/goods/goods/trdetail?order_id=' + content.order_id + '" class="btn">已下架</a>';
                    }
                    html += '</div></div>';
                });
                if (html == '') {
                    html += '<img src="/static/ywf/img/pic/zanwu.png" style="margin-left:55px;">'
                }
                if (html == '') {
                    html += '<img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;">'
                }
                
                $('#yuyue_content').empty();
                $('#yuyue_content').html(html);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum'              //指定返回数据的总数据量的字段名
        }
    });
}
