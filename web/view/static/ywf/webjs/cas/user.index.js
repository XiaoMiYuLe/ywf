$(document).ready(function () {

    /**
     * 初始化日期插件
     */
    $('.datepicker-input').datepicker();

    getApi();
    
    $("#quanbu1").click(function(){
        var start_time = $('input[name=start_time]').val();
        var end_time = $('input[name=end_time]').val();
        $("#page,#page1,#page2,#page3,#page4").hide();
        $("#paging").show();
        $('#paging').page('remote',{"start_time":start_time,"end_time":end_time});
    });
    
    $("#goumai1").click(function(){
        var start_time = $('input[name=start_time]').val();
        var end_time = $('input[name=end_time]').val();
        $("#paging,#page1,#page2,#page3,#page4").hide();
        getApi1();
        $("#page").show();
        $('#page').page('remote',{"start_time":start_time,"end_time":end_time});
    });
    
    $("#shouyi1").click(function(){
        var start_time = $('input[name=start_time]').val();
        var end_time = $('input[name=end_time]').val();
        $("#paging,#page,#page2,#page3,#page4").hide();
        getApi2();
        $("#page1").show();
        $('#page1').page('remote',{"start_time":start_time,"end_time":end_time});
    });
    
    $("#chongzhi1").click(function(){
        var start_time = $('input[name=start_time]').val();
        var end_time = $('input[name=end_time]').val();
        $("#paging,#page,#page1,#page3,#page4").hide();
        getApi3();
        $("#page2").show();
        $('#page2').page('remote',{"start_time":start_time,"end_time":end_time});
    });
    
    $("#tixian1").click(function(){
        var start_time = $('input[name=start_time]').val();
        var end_time = $('input[name=end_time]').val();
        $("#paging,#page,#page1,#page2,#page4").hide();
        getApi4();
        $("#page3").show();
        $('#page3').page('remote',{"start_time":start_time,"end_time":end_time});
    });

    $("#yongjin1").click(function(){
        var start_time = $('input[name=start_time]').val();
        var end_time = $('input[name=end_time]').val();
        $("#paging,#page,#page1,#page2,#page3").hide();
        getApi5();
        $("#page4").show();
        $('#page4').page('remote',{"start_time":start_time,"end_time":end_time});
    });
    
    $("#queding").click(function(){
        $("#paging,#page,#page1,#page2,#page3,#page4").hide();
        var start_time = $('input[name=start_time]').val();
        var end_time = $('input[name=end_time]').val();
        var clickV = $('.tab-btn').attr('id');
        var flag = "";
        var status=3;
        var getApii="";
        switch (clickV){
            case 'quanbu1':
                flag="#paging";
                flagg="#quanbu";
                getApii=getApi;
                status=0;
                break;
            case 'goumai1':
                flag="#page";
                flagg="#goumai";
                getApii=getApi1;
                status=3;
                break;
            case 'shouyi1':
                flag="#page1";
                flagg="#quanbu";
                getApii=getApi2;
                status=4;
                break;
            case 'chongzhi1':
                flag="#page2";
                flagg="#chongzhi";
                getApii=getApi3;
                status=1;
                break;
            case 'tixian1':
                flag="#page3"; 
                flagg="#tixian";
                getApii=getApi4;
                status=2;
                break;
            case 'yongjin1':
                flag="#page4"; 
                flagg="#yongjin";
                getApii=getApi5;
                status=5;
                break;
            default:
                break;
        }
        
        getApii(start_time,end_time);
        $(flag).show();
        $(flag).page('remote',{"start_time":start_time,"end_time":end_time});
        
    });
});

//quanbu
function getApi(start_time,end_time) {
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
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
            url: '/cas/user/getRecordLog', //请求地址
            params: {"status":0}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.content, function (index, content) {
                    //timesub
                    if (content.ctime) {
                        content.ctime = (content.ctime).substring(0,10);
                    }else {
                        content.ctime = '';
                    }
                    
                    // 类型判断
                    switch (content.type) {
                        case 1:
                            content.type = '充值';
                            break;
                        case 2:
                            content.type = '提现';
                            break;
                        case 3:
                            content.type = '购买理财产品';
                            break;
                        case 4:
                            content.type = '理财产品收益';
                            break;
                        case 5:
                            content.type = '产品佣金';
                            break;
                        case 6:
                            content.type = '兑付本金';
                            break;
                        case 7:
                            content.type = '转让手续费';
                            break;
                        case 8:
                            content.type = '转让结算';
                            break;
                        case 9:
                            content.type = '冻结操作';
                            break;
                        case 10:
                            content.type = '解冻操作';
                            break;
                        case 11:
                            content.type = '满标增资';
                            break;
                        case 12:
                            content.type = '还款减资';
                            break;
                    }

                    html += '<tr>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.type + '</td>' +
                            '<td>' + content.status + content.money + '</td>' +
                            '<td>' + content.flow_asset + '</td>' +
                            '</tr>';
                });

                if (html == '') {
                    html += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#quanbu").empty();
                $("#quanbu").html(html);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

//goumai
function getApi1(start_time,end_time) {
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
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
            url: '/cas/user/getRecordLog', //请求地址
            params: {"status":3,"start_time":start_time,"end_time":end_time}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.content, function (index, content) {
                    //timesub
                    if (content.ctime) {
                        content.ctime = (content.ctime).substring(0,10);
                    }else {
                        content.ctime = '';
                    }
                    
                    // 类型判断
                    switch (content.type) {
                        case 1:
                            content.type = '充值';
                            break;
                        case 2:
                            content.type = '提现';
                            break;
                        case 3:
                            content.type = '购买理财产品';
                            break;
                        case 4:
                            content.type = '理财产品收益';
                            break;
                        case 5:
                            content.type = '产品佣金';
                            break;
                        case 6:
                            content.type = '兑付本金';
                            break;
                        case 7:
                            content.type = '转让手续费';
                            break;
                        case 8:
                            content.type = '转让结算';
                            break;
                        case 9:
                            content.type = '冻结操作';
                            break;
                        case 10:
                            content.type = '解冻操作';
                            break;
                        case 11:
                            content.type = '满标增资';
                            break;
                        case 12:
                            content.type = '还款减资';
                            break;
                    }

                    html += '<tr>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.type + '</td>' +
                            '<td>' + content.status + content.money + '</td>' +
                            '<td>' + content.flow_asset + '</td>' +
                            '</tr>';
                });

                if (html == '') {
                    html += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#goumai").empty();
                $("#goumai").html(html);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

// shouyi
function getApi2(start_time,end_time) {
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
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
            url: '/cas/user/getRecordLog', //请求地址
            params: {"status":4,"start_time":start_time,"end_time":end_time}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.content, function (index, content) {
                    //timesub
                    if (content.ctime) {
                        content.ctime = (content.ctime).substring(0,10);
                    }else {
                        content.ctime = '';
                    } 

                    // 类型判断
                    switch (content.type) {
                        case 1:
                            content.type = '充值';
                            break;
                        case 2:
                            content.type = '提现';
                            break;
                        case 3:
                            content.type = '购买理财产品';
                            break;
                        case 4:
                            content.type = '理财产品收益';
                            break;
                        case 5:
                            content.type = '产品佣金';
                            break;
                        case 6:
                            content.type = '兑付本金';
                            break;
                        case 7:
                            content.type = '转让手续费';
                            break;
                        case 8:
                            content.type = '转让结算';
                            break;
                        case 9:
                            content.type = '冻结操作';
                            break;
                        case 10:
                            content.type = '解冻操作';
                            break;
                        case 11:
                            content.type = '满标增资';
                            break;
                        case 12:
                            content.type = '还款减资';
                            break;
                    }

                    html += '<tr>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.type + '</td>' +
                            '<td>' + content.status + content.money + '</td>' +
                            '<td>' + content.flow_asset + '</td>' +
                            '</tr>';
                });

                if (html == '') {
                    html += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#shouyi").empty();
                $("#shouyi").html(html);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

// chongzhi
function getApi3(start_time,end_time) {
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
    $("#page2").page({
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
            url: '/cas/user/getRecordLog', //请求地址
            params: {"status":1,"start_time":start_time,"end_time":end_time}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.content, function (index, content) {
                    //timesub
                    if (content.ctime) {
                        content.ctime = (content.ctime).substring(0,10);
                    }else {
                        content.ctime = '';
                    } 
                    
                    // 类型判断
                    switch (content.type) {
                        case 1:
                            content.type = '充值';
                            break;
                        case 2:
                            content.type = '提现';
                            break;
                        case 3:
                            content.type = '购买理财产品';
                            break;
                        case 4:
                            content.type = '理财产品收益';
                            break;
                        case 5:
                            content.type = '产品佣金';
                            break;
                        case 6:
                            content.type = '兑付本金';
                            break;
                        case 7:
                            content.type = '转让手续费';
                            break;
                        case 8:
                            content.type = '转让结算';
                            break;
                        case 9:
                            content.type = '冻结操作';
                            break;
                        case 10:
                            content.type = '解冻操作';
                            break;
                        case 11:
                            content.type = '满标增资';
                            break;
                        case 12:
                            content.type = '还款减资';
                            break;
                    }

                    html += '<tr>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.type + '</td>' +
                            '<td>' + content.status + content.money + '</td>' +
                            '<td>' + content.flow_asset + '</td>' +
                            '</tr>';
                });

                if (html == '') {
                    html += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#chongzhi").empty();
                $("#chongzhi").html(html);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

//tixian
function getApi4(start_time,end_time) {
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
    $("#page3").page({
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
            url: '/cas/user/getRecordLog', //请求地址
            params: {"status":2,"start_time":start_time,"end_time":end_time}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.content, function (index, content) {
                    //timesub
                    if (content.ctime) {
                        content.ctime = (content.ctime).substring(0,10);
                    }else {
                        content.ctime = '';
                    } 
                    // 类型判断
                    switch (content.type) {
                        case 1:
                            content.type = '充值';
                            break;
                        case 2:
                            content.type = '提现';
                            break;
                        case 3:
                            content.type = '购买理财产品';
                            break;
                        case 4:
                            content.type = '理财产品收益';
                            break;
                        case 5:
                            content.type = '产品佣金';
                            break;
                        case 6:
                            content.type = '兑付本金';
                            break;
                        case 7:
                            content.type = '转让手续费';
                            break;
                        case 8:
                            content.type = '转让结算';
                            break;
                        case 9:
                            content.type = '冻结操作';
                            break;
                        case 10:
                            content.type = '解冻操作';
                            break;
                        case 11:
                            content.type = '满标增资';
                            break;
                        case 12:
                            content.type = '还款减资';
                            break;
                    }
                    html += '<tr>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.type + '</td>' +
                            '<td>' + content.status + content.money + '</td>' +
                            '<td>' + content.flow_asset + '</td>' +
                            '</tr>';
                });
                
                if (html == '') {
                    html += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#tixian").empty();
                $("#tixian").html(html);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

//yongjin
function getApi5(start_time,end_time) {
    //请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
    //返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
    $("#page4").page({
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
            url: '/cas/user/getRecordLog', //请求地址
            params: {"status":5,"start_time":start_time,"end_time":end_time}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.content, function (index, content) {
                    //timesub
                    if (content.ctime) {
                        content.ctime = (content.ctime).substring(0,10);
                    }else {
                        content.ctime = '';
                    } 
                    // 类型判断
                    switch (content.type) {
                        case 1:
                            content.type = '充值';
                            break;
                        case 2:
                            content.type = '提现';
                            break;
                        case 3:
                            content.type = '购买理财产品';
                            break;
                        case 4:
                            content.type = '理财产品收益';
                            break;
                        case 5:
                            content.type = '产品佣金';
                            break;
                        case 6:
                            content.type = '兑付本金';
                            break;
                        case 7:
                            content.type = '转让手续费';
                            break;
                        case 8:
                            content.type = '转让结算';
                            break;
                        case 9:
                            content.type = '冻结操作';
                            break;
                        case 10:
                            content.type = '解冻操作';
                            break;
                        case 11:
                            content.type = '满标增资';
                            break;
                        case 12:
                            content.type = '还款减资';
                            break;
                    }

                    html += '<tr>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.type + '</td>' +
                            '<td>' + content.status + content.money + '</td>' +
                            '<td>' + content.flow_asset + '</td>' +
                            '</tr>';
                });
                
                if (html == '') {
                    html += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }    
                
                $("#yongjin").empty();
                $("#yongjin").html(html);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}


