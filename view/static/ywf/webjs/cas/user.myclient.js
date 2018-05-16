$(document).ready(function () {
    getClientList();

    //quanbu
    $("#quanbu1").click(function () {
        $("#page").hide();
        $("#page1").hide();
        $("#paging").show();
        $("#paging").page('remote');
    });

    //yijiaru
    $("#yijiaru1").click(function () {
        getClientList1();
        $("#paging").hide();
        $("#page1").hide();
        $("#page").show();
        $("#page").page('remote');
    });

    //weijiaru
    $("#weijiaru1").click(function () {
        getClientList2();
        $("#paging").hide();
        $("#page").hide();
        $("#page1").show();
        $("#page1").page('remote');
    });

    $("body").delegate('.invitation', 'click', function () {
        var user = $(this).parent().siblings().find('.user').val();
        var tiecard = $(this).parent().siblings().find('.tiecard').val();

        if (tiecard == 0) {
            showAlert({
                    alertTitle:"温馨提示",
                    alertContent:"该用户未绑卡，无法邀请加入经纪人",
                    alertType:1,
                    singleBtnText:"确定"
            });
           
            return false;
        } else if (tiecard == 1) {
            $.post("/cas/user/invitation", {"user": user}, function (response) {
                if (response.status == 0) {
                    showAlert({
                        alertTitle:"温馨提示",
                        alertContent:"已邀请",
                        alertType:1,
                        singleBtnText:"确定"
                    });
                    window.location.href = '/cas/user/myClient';
                } else {
                    showAlert({
                        alertTitle:"温馨提示",
                        alertContent:data.error,
                        alertType:1,
                        singleBtnText:"确定"
                    });
                }
            }, 'json');
        }

    });

});

/* 获取客户列表*/
function getClientList() {
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
            url: '/cas/user/getClientList', //请求地址
            params: {status: 0}, //自定义请求参数
            success: function (result, pageIndex) {
                var kehu = '';
                $.each(result.content, function (index, content) {
                    //是否帮卡

                    //是否邀请
                    if (content.is_invitaiton == 0) {
                        content.is_invitaiton = '<span class="invitation" style="cursor:pointer;color: #00aaee;border: 1px solid #00aaee;width: 48px;height: 28px;border-radius: 3px;line-height: 28px;font-size: 14px;display: inline-block;">邀请</span>';
                    }

                    if (content.is_invitaiton == 1) {
                        content.is_invitaiton = '已邀请';
                    }

                    if (content.is_invitaiton == 2 || content.is_invitaiton == 3) {
                        content.is_invitaiton = '审核中';
                    }

                    if (content.is_invitaiton == 4) {
                        content.is_invitaiton = '未通过';
                    }

                    if (content.is_invitaiton == 5) {
                        content.is_invitaiton = '已加入';
                    }

                    kehu += '<tr>' +
                            '<td>' + content.username + '</td>' +
                            '<td>' + content.phone + '</td>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.is_invitaiton + '</td>' +
                            '<td style="width:0px;display:none;">' + "<input type='hidden' class='user' value='" + content.userid + "'/>" + '</td>' +
                            '<td style="width:0px;display:none;">' + "<input type='hidden' class='tiecard' value='" + content.is_tiecard + "'/>" + '</td>' +
                            '</tr>';
                });

                if (kehu == '') {
                    kehu += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }   
                
                $("#quanbu").empty();
                $("#quanbu").html(kehu);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

function getClientList1() {
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
            url: '/cas/user/getClientList', //请求地址
            params: {status: 1}, //自定义请求参数
            success: function (result, pageIndex) {
                var kehu = '';
                $.each(result.content, function (index, content) {

                    //是否邀请
                    if (content.is_invitaiton == 0) {
                        content.is_invitaiton = '<span class="invitation" style="cursor:pointer;color: #00aaee;border: 1px solid #00aaee;width: 48px;height: 28px;border-radius: 3px;line-height: 28px;font-size: 14px;display: inline-block;">邀请</span>';
                    }

                    if (content.is_invitaiton == 1) {
                        content.is_invitaiton = '已邀请';
                    }

                    if (content.is_invitaiton == 2 || content.is_invitaiton == 3) {
                        content.is_invitaiton = '审核中';
                    }

                    if (content.is_invitaiton == 4) {
                        content.is_invitaiton = '未通过';
                    }

                    if (content.is_invitaiton == 5) {
                        content.is_invitaiton = '已加入';
                    }

                    kehu += '<tr>' +
                            '<td>' + content.username + '</td>' +
                            '<td>' + content.phone + '</td>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.is_invitaiton + '</td>' +
                            '<td style="width:0px;display:none;">' + "<input type='hidden' class='user' value='" + content.userid + "'/>" + '</td>' +
                            '<td style="width:0px;display:none;">' + "<input type='hidden' class='tiecard' value='" + content.is_tiecard + "'/>" + '</td>' +
                            '</tr>';
                });

                if (kehu == '') {
                    kehu += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }   
                
                $("#yijiaru").empty();
                $("#yijiaru").html(kehu);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}

function getClientList2() {
    $("#page1").page({
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
            url: '/cas/user/getClientList', //请求地址
            params: {status: 2}, //自定义请求参数
            success: function (result, pageIndex) {
                var kehu = '';
                $.each(result.content, function (index, content) {

                    //是否邀请
                    if (content.is_invitaiton == 0) {
                        content.is_invitaiton = '<span class="invitation" style="cursor:pointer;color: #00aaee;border: 1px solid #00aaee;width: 48px;height: 28px;border-radius: 3px;line-height: 28px;font-size: 14px;display: inline-block;">邀请</span>';
                    }

                    if (content.is_invitaiton == 1) {
                        content.is_invitaiton = '已邀请';
                    }

                    if (content.is_invitaiton == 2 || content.is_invitaiton == 3) {
                        content.is_invitaiton = '审核中';
                    }

                    if (content.is_invitaiton == 4) {
                        content.is_invitaiton = '未通过';
                    }

                    if (content.is_invitaiton == 5) {
                        content.is_invitaiton = '已加入';
                    }

                    kehu += '<tr>' +
                            '<td>' + content.username + '</td>' +
                            '<td>' + content.phone + '</td>' +
                            '<td>' + content.ctime + '</td>' +
                            '<td>' + content.is_invitaiton + '</td>' +
                            '<td style="width:0px;display:none;">' + "<input type='hidden' class='user' value='" + content.userid + "'/>" + '</td>' +
                            '<td style="width:0px;display:none;">' + "<input type='hidden' class='tiecard' value='" + content.is_tiecard + "'/>" + '</td>' +
                            '</tr>';

                });

                if (kehu == '') {
                    kehu += '<tr><td colspan="10"><img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;"</td></tr>';
                }   
                
                $("#weijiaru").empty();
                $("#weijiaru").html(kehu);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}


