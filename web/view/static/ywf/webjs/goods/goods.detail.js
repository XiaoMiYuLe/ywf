$(document).ready(function () {
    $("#record").click(function () {
        var goods_id = $("#g").val();
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
                url: '/goods/goods/record', //请求地址
                params: {
                    goods_id: goods_id
                }, //自定义请求参数
                success: function (result, pageIndex) {
                        var html = '';
                        $.each(result.content, function (index, content) {
                            html += '<tr>' +
                                '<td>' + content.phone + '</td>' +
                                '<td>' + content.buy_money + '</td>' +
                                '<td>' + content.ctime + '</td>' +
                                '<td>' + '成功' + '</td>' +
                                '</tr>';
                        });
                        if (html == '') {
                            html += '<img src="/static/ywf/img/pic/zanwu.png" style="display: block;margin: 50px auto;width:30%;">'
                        }

                        $("#tbody").empty();
                        $("#tbody").html(html);
                        //回调函数
                        //result 为 请求返回的数据，呈现数据
                    },
                    pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
                pageSizeName: 'pageSize', //请求参数，每页数量
                totalName: 'totalnum', //指定返回数据的总数据量的字段名
            }
        });
        // click other remove
        $("#safe").click(function () {
            $("#paging").hide();
        });
        $("#detail").click(function () {
            $("#paging").hide();
        });
        $("#record").click(function () {
            $("#paging").show();
        });
    });
    
    //投资
    var goods_status = parseInt($("#goods_status").val());

    if (goods_status == 2) {
        
    }else if (goods_status == 3){
        
    }else {
        var goods_pattern = $('#goods_pattern').val();
        if (goods_pattern == 3) {
                    
        $(".product_btn").click(function () {
            
        showAlert({
            alertTitle: "温馨提示",
            alertContent: '您确认要预约吗?',
            alertType: 4,
            doubleBtnText1: "取消",
            doubleBtnText2: "确认",
        });
        
        $('.btnRight').click(function () {
            hideAlert();
            getYuyue();
        });
        
         });
        }else if (goods_pattern == 1) {
            $(".product_btn").click(function () {           
                getTouzi();
            });
        }else if (goods_pattern == 2) {
            $(".product_btn").click(function () {           
                getTouzi();
            });
        }else {
            $(".product_btn").click(function () {         
                getTiyan();
            });
        }
    }
    
    //预约有弹框
    function getYuyue(){
         var g = $('#g').val();
            var buy_money = $('#buy_money').val();
            var transfer_order_id = $('#order_id').val();
            var goods_pattern = $('#goods_pattern').val();

            $.post("/goods/goods/Investment", {
                'g': g,
                'buy_money': buy_money,
                'order_id': transfer_order_id
            }, function (data) {
                //未登入跳登入页
                if (data.status == 2) {
                    window.location.href = "/cas/sign/in";
                    return false;
                }
                //未绑卡
                if (data.status == 3) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: "您尚未绑定银行卡",
                        alertType: 2,
                        doubleBtnText1: "取消",
                        doubleBtnText2: "去绑卡",
                        goUrl: "/cas/bank/addbank"
                    });
                }

                if (data.status == 4) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: "您尚未设置交易密码",
                        alertType: 2,
                        doubleBtnText1: "取消",
                        doubleBtnText2: "去设置",
                        goUrl: "/cas/setting/tradepwd"
                    });
                }

                if (data.status == 5) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: data.error,
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                }

                if (data.status == 6) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: '预约成功',
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                    window.location.href = "/cas/order/index";
                }

                if (data.status == 1) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: data.error,
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                }

                if (data.status == 0) {
                    if (transfer_order_id) {
                        order_no = data.data.order_no
                        window.location.href = "/bts/order/shortcutpay?order_no=" + order_no + "&type=zr";
                    } else {
                        order_no = data.data.order_id
                        window.location.href = "/bts/order/shortcutpay?order_no=" + order_no;
                    }

                }

            }, 'json');
            return false;
    }

    //体验金
    function getTiyan(){
         var g = $('#g').val();
            var buy_money = $('#buy_money').val();
            var transfer_order_id = $('#order_id').val();
            var goods_pattern = $('#goods_pattern').val();
            var voucher_money = $('#voucher_money').val();
            if (voucher_money == '') {
                voucher_money = 0
            }
            if (voucher_money == -1) {
                window.location.href = "/cas/sign/in";
                return false;
            }
            else if (voucher_money == 0) {
                showAlert({
                        alertTitle: "温馨提示",
                        alertContent: "您无体验金可使用！",
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                return false;
            }
            var content = '您有' + voucher_money + '元的体验金，是否立即使用';
            if (goods_pattern == 4) {
                showAlert({
                    alertTitle: "温馨提示",
                    alertContent: content,
                    alertType: 4,
                    doubleBtnText1: "取消",
                    doubleBtnText2: "同意",
                });
                $('.btnRight').click(function () {
                    $.post('/goods/goods/tiyan', {
                        'goods_id': g
                    }, function (tiyan) {
                        //未登入跳登入页
                        if (tiyan.status == 2) {
                            window.location.href = "/cas/sign/in";
                            return;
                        }

                        if (tiyan.status == 3) {
                            showAlert({
                                alertTitle: "温馨提示",
                                alertContent: "您尚未绑定银行卡",
                                alertType: 2,
                                doubleBtnText1: "取消",
                                doubleBtnText2: "去绑卡",
                                goUrl: "/cas/bank/addbank"
                            });
                        }

                        if (tiyan.status == 4) {
                            showAlert({
                                alertTitle: "温馨提示",
                                alertContent: "您尚未设置交易密码",
                                alertType: 2,
                                doubleBtnText1: "取消",
                                doubleBtnText2: "去设置",
                                goUrl: "/cas/setting/tradepwd"
                            });
                        }

                        if (tiyan.status == 5) {
                            showAlert({
                                alertTitle: "温馨提示",
                                alertContent: data.error,
                                alertType: 1,
                                singleBtnText: "确定"
                            });
                        }

                        if (tiyan.status == 0) {
                            window.location.href = '/bts/order/prompt';
                        } else {
                            showAlert({
                                alertTitle: "温馨提示",
                                alertContent: tiyan.error,
                                alertType: 1,
                                singleBtnText: "确定"
                            });
                        }
                    })
                })
                return false;
              
            }
    }
    
    //touzi
    function getTouzi(){
         var g = $('#g').val();
            var buy_money = $('#buy_money').val();
            var transfer_order_id = $('#order_id').val();
            var goods_pattern = $('#goods_pattern').val();

            $.post("/goods/goods/Investment", {
                'g': g,
                'buy_money': buy_money,
                'order_id': transfer_order_id
            }, function (data) {
                //未登入跳登入页
                if (data.status == 2) {
                    window.location.href = "/cas/sign/in";
                    return false;
                }
                //未绑卡
                if (data.status == 3) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: "您尚未绑定银行卡",
                        alertType: 2,
                        doubleBtnText1: "取消",
                        doubleBtnText2: "去绑卡",
                        goUrl: "/cas/bank/addbank"
                    });
                }

                if (data.status == 4) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: "您尚未设置交易密码",
                        alertType: 2,
                        doubleBtnText1: "取消",
                        doubleBtnText2: "去设置",
                        goUrl: "/cas/setting/tradepwd"
                    });
                }

                if (data.status == 5) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: data.error,
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                }

                if (data.status == 6) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: '预约成功',
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                    window.location.href = "/cas/user/index";
                }

                if (data.status == 1) {
                    showAlert({
                        alertTitle: "温馨提示",
                        alertContent: data.error,
                        alertType: 1,
                        singleBtnText: "确定"
                    });
                }

                if (data.status == 0) {
                    if (transfer_order_id) {
                        order_no = data.data.order_no
                        window.location.href = "/bts/order/shortcutpay?order_no=" + order_no + "&type=zr";
                    } else {
                        order_no = data.data.order_no
                        window.location.href = "/bts/order/shortcutpay?order_no=" + order_no;
                    }

                }

            }, 'json');
            return false;
    }
    
    //价格加减
    $(function () {
        var Dnum = $("#increasing_pay").val(); //自增
        var low_pay = $("#low_pay").val();
        var high_pay = $("#high_pay").val();
        //zd
        low_pay = parseInt(low_pay);
        Dnum = parseInt(Dnum);
        //input
        var DinputNum = low_pay;
        DinputNum = parseInt(DinputNum);
        $('.reduce_btn').click(function () {
            //动态获取
            DinputNum = $('.text_box').val();
            DinputNum = parseInt(DinputNum);
            var order_id = $("#order_id").val();
            if (order_id) {
                DinputNum = DinputNum - Dnum;
                $('.text_box').val(DinputNum);
                // shouyi  
                var inV = $(".text_box").val();
                inV = parseInt(inV);
                var yield = $("#yield").val();
                yield = parseInt(yield);
                var qx = $("#qx").val();
                qx = parseInt(qx);
                // 计算
                var z = (inV / 100) * (yield);
                var shouyi = (z / 365) * qx;
                shouyi = shouyi.toFixed(2);
                $("#yq").html(shouyi);
            } else {
                if (DinputNum > low_pay) {
                    DinputNum = DinputNum - Dnum;
                    $('.text_box').val(DinputNum);
                    // shouyi  
                    var inV = $(".text_box").val();
                    inV = parseInt(inV);
                    var yield = $("#yield").val();
                    yield = parseInt(yield);
                    var qx = $("#qx").val();
                    qx = parseInt(qx);
                    // 计算
                    var z = (inV / 100) * (yield);
                    var shouyi = (z / 365) * qx;
                    shouyi = shouyi.toFixed(2);
                    $("#yq").html(shouyi);
                } else {
                    alert('最低投资金额，不能低于' + low_pay);
                }
            }
        });
        $('.plus_btn').click(function () {
            // 动态获取
            DinputNum = $('.text_box').val();
            DinputNum = parseInt(DinputNum);
            var order_id = $("#order_id").val();
            if (order_id) {
                DinputNum = DinputNum + Dnum;
                $('.text_box').val(DinputNum);
                //shouyi
                var inV = $(".text_box").val();
                inV = parseInt(inV);
                var yield = $("#yield").val();
                yield = parseInt(yield);
                var qx = $("#qx").val();
                qx = parseInt(qx);
                // 计算
                var z = (inV / 100) * (yield);
                var shouyi = (z / 365) * qx;
                shouyi = shouyi.toFixed(2);
                $("#yq").html(shouyi);
            } else {
                if ((DinputNum + Dnum) <= high_pay) {
                    DinputNum = DinputNum + Dnum;
                    $('.text_box').val(DinputNum);
                    //shouyi
                    var inV = $(".text_box").val();
                    inV = parseInt(inV);
                    var yield = $("#yield").val();
                    yield = parseInt(yield);
                    var qx = $("#qx").val();
                    qx = parseInt(qx);
                    // 计算
                    var z = (inV / 100) * (yield);
                    var shouyi = (z / 365) * qx;
                    shouyi = shouyi.toFixed(2);
                    $("#yq").html(shouyi);
                } else {
                    alert('最高投资金额，不能高于' + high_pay);
                }
            }
        });
    });
    getShouyi();
});

function getShouyi() {
    /*
     *页面加载自动呈现
     */
    //  low high
    var low_pay = $("#low_pay").val();
    var order_id = $("#order_id").val();
    var high_pay = $("#high_pay").val();
    var buy_money = $("#buy_money_order").val();
    var inV = $(".text_box").val();
    inV = parseInt(inV);
    if (order_id) {
        $('.text_box').val(buy_money);
        inV = buy_money;
    } else {
        $('.text_box').val(low_pay);
        inV = low_pay;
    }



    var yield = $("#yield").val();
    yield = parseInt(yield);
    if (order_id) {
        var qx = $("#qx_order").val();
    } else {
        var qx = $("#qx").val();
    }

    qx = parseInt(qx);
    // 计算
    var z = (inV / 100) * (yield);
    var shouyi = (z / 365) * qx;
    shouyi = shouyi.toFixed(2);
    $("#yq").html(shouyi);
    // 输入框事件
    $(".text_box").blur(function () {
        //  low high
        var low_pay = $("#low_pay").val();
        var high_pay = $("#high_pay").val();
        var order_id = $("#order_id").val();
        var inV = $(".text_box").val();
        inV = parseInt(inV);
        // 最低
        if (parseInt(low_pay) > inV) {
            alert('最低投资金额，不能低于' + low_pay);
            $('.text_box').val(low_pay);
            inV = low_pay;
            var yield = $("#yield").val();
            yield = parseInt(yield);
            if (order_id) {
                var qx = $("#qx_order").val();
            } else {
                var qx = $("#qx").val();
            }

            qx = parseInt(qx);
            // 计算
            var z = (inV / 100) * (yield);
            var shouyi = (z / 365) * qx;
            shouyi = shouyi.toFixed(2);
            $("#yq").html(shouyi);
        } else {
            var yield = $("#yield").val();
            yield = parseInt(yield);
            var qx = $("#qx").val();
            qx = parseInt(qx);
            // 计算
            var z = (inV / 100) * (yield);
            var shouyi = (z / 365) * qx;
            shouyi = shouyi.toFixed(2);
            $("#yq").html(shouyi);
        }

        //最高
        if (parseInt(high_pay) < inV) {
            alert('最高投资金额，不能高于' + high_pay);
            $('.text_box').val(high_pay);
            inV = high_pay;
            var yield = $("#yield").val();
            yield = parseInt(yield);
            var qx = $("#qx").val();
            qx = parseInt(qx);
            // 计算
            var z = (inV / 100) * (yield);
            var shouyi = (z / 365) * qx;
            shouyi = shouyi.toFixed(2);
            $("#yq").html(shouyi);
        } else {
            var yield = $("#yield").val();
            yield = parseInt(yield);
            var qx = $("#qx").val();
            qx = parseInt(qx);
            // 计算
            var z = (inV / 100) * (yield);
            var shouyi = (z / 365) * qx;
            shouyi = shouyi.toFixed(2);
            $("#yq").html(shouyi);
        }

    });
}