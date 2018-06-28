$(document).ready(function () {
    //获取标的投资记录
    $("#record").click(function () {
        var borrow_id = $("#borrowID").val();//标的ID
        $("#paging").page({
            pageSize: 7,
            firstBtnText: '首页',
            lastBtnText: '尾页',
            prevBtnText: '上一页',
            nextBtnText: '下一页',
            showInfo: true,
            showJump: true,
            jumpBtnText: '跳转',
            infoFormat: '共{total}条', 

            remote: {
                url: '/borrow/list/record', //请求地址
                params: {
                    borrow_id: borrow_id
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
        $("#question").click(function () {
            $("#paging").hide();
        });
        $("#record").click(function () {
            $("#paging").show();
        });
    });

    //投资
    var borrow_status = parseInt($("#borrowStatus").val());

    if (borrow_status == 1) {
        $(".product_btn").click(function () {           
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
        });
    }
    
    //价格加减
    $(function(){
        var Dnum = $("#increasingAmount").val(); //递增金额
        var low_pay = $("#lowPay").val(); //起投金额
        var remainder_money = $("#remainderMoney").val(); //剩余可投金额
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

            if (DinputNum > low_pay) {
                DinputNum = DinputNum - Dnum;
                $('.text_box').val(DinputNum);
                // shouyi  
                var inV = $(".text_box").val();
                inV = parseInt(inV);
                var yield = $("#yieldRate").val();
                yield = parseInt(yield);
                var qx = $("#qx").val();
                qx = parseInt(qx);
                // 计算
                var z = (inV / 100) * (yield);
                var shouyi = (z / 360) * qx;
                shouyi = parseInt(shouyi);
                $("#yq").html(shouyi);
            } else {
                alert('最低投资金额，不能低于' + low_pay + "元");
            }

        });
        $('.plus_btn').click(function () {
            // 动态获取
            DinputNum = $('.text_box').val();
            DinputNum = parseInt(DinputNum);
           
            if ((DinputNum + Dnum) <= remainder_money) {
                DinputNum = DinputNum + Dnum;
                $('.text_box').val(DinputNum);
                //shouyi
                var inV = $(".text_box").val();
                inV = parseInt(inV);
                var yield = $("#yieldRate").val(); //年化收益率
                yield = parseInt(yield);
                var qx = $("#qx").val();
                qx = parseInt(qx);
                // 计算
                var z = (inV / 100) * (yield);
                var shouyi = (z / 360) * qx;
                shouyi = parseInt(shouyi);
                $("#yq").html(shouyi);
            } else {
                alert('最高投资金额，不能高于' + remainder_money + "元");
            }

        });
    });
    
    //页面加载自动呈现收益值
    getShouyi();

    //投资倒计时
    //getRaise_time();
});

function getShouyi(){
    var low_pay = $("#lowPay").val(); //起投金额
    var inV = $(".text_box").val();
    inV = parseInt(inV);
    $('.text_box').val(low_pay);
    inV = low_pay;
    var yield = $("#yieldRate").val();
    yield = parseInt(yield);
    var qx = $("#qx").val();
    qx = parseInt(qx);
    // 计算
    var z = (inV / 100) * (yield);
    var shouyi = (z / 360) * qx;
    shouyi = parseInt(shouyi);
    $("#yq").html(shouyi);

    // 输入框事件
    $(".text_box").blur(function () {
        //  low high
        var low_pay = $("#lowPay").val(); //起投金额        
        var remainder_money = $("#remainderMoney").val(); //剩余可投金额 
        low_pay = parseInt(low_pay);
        remainder_money = parseInt(remainder_money);
        var inV = $(".text_box").val();
        inV = parseInt(inV);
        // 最低
        if (parseInt(low_pay) > inV) {
            alert('最低投资金额，不能低于' + low_pay + "元");
            $('.text_box').val(low_pay);
            inV = low_pay;
            var yield = $("#yieldRate").val();
            yield = parseInt(yield);
            var qx = $("#qx").val();
            qx = parseInt(qx);
            // 计算
            var z = (inV / 100) * (yield);
            var shouyi = (z / 360) * qx;
            shouyi = parseInt(shouyi);
            $("#yq").html(shouyi);
        } else {
            var yield = $("#yieldRate").val();
            yield = parseInt(yield);
            var qx = $("#qx").val();
            qx = parseInt(qx);
            // 计算
            var z = (inV / 100) * (yield);
            var shouyi = (z / 360) * qx;
            shouyi = parseInt(shouyi);
            $("#yq").html(shouyi);
        }

        //最高
        if(parseInt(remainder_money) != 0){
            if (parseInt(remainder_money) < inV) {
                alert('最高投资金额，不能高于' + remainder_money + "元");
                $('.text_box').val(remainder_money);
                inV = remainder_money;
                var yield = $("#yieldRate").val();
                yield = parseInt(yield);
                var qx = $("#qx").val();
                qx = parseInt(qx);
                // 计算
                var z = (inV / 100) * (yield);
                var shouyi = (z / 360) * qx;
                shouyi = parseInt(shouyi);
                $("#yq").html(shouyi);
            } else {
                var yield = $("#yieldRate").val();
                yield = parseInt(yield);
                var qx = $("#qx").val();
                qx = parseInt(qx);
                // 计算
                var z = (inV / 100) * (yield);
                var shouyi = (z / 360) * qx;
                shouyi = parseInt(shouyi);
                $("#yq").html(shouyi);
            }
        }
    });   
}

// function getRaise_time() {
//     //获取发布时间  
//     var show_time = $("#showTime").val();
//     var nowDate = new Date(show_time);  
//     var now = nowDate.getTime();

//     //设置截止时间  
//     var raise_time = $("#raiseTime").val();
//     var endDate = new Date(raise_time); 
//     var end = endDate.getTime();  

//     //时间差  
//     var leftTime = end-now; 
//     //递归每秒调用getRaise_time方法，显示动态时间效果  
//     setTimeout("go()", 1000); 
//     function go(){
//         //定义变量 d,h,m,s保存倒计时的时间  
//         var d,h,m,s;  
//         if (leftTime >= 0) {  
//             d = Math.floor(leftTime / 1000 / 60 / 60/ 24);  
//             h = Math.floor(leftTime / 1000 / 60/ 60 % 24);  
//             m = Math.floor(leftTime / 1000 / 60 % 60);  
//             s = Math.floor(leftTime / 1000 % 60);                     
//         }  
//         alert(s)
//         //将倒计时赋值到div中  
//         $("#djs").html("剩余" + d + "天" + h + "时" + m + "分" + s + "秒");
//     }    
// }

// function getRaise_time(){
//     var showTime = $("#showTime").val();
//     var raiseTime = $("#raiseTime").val();
    
//     var time_now_server,time_now_client,time_end,time_server_client,timerID;

//         time_end=new Date(raiseTime);//结束的时间
//         time_end=time_end.getTime();
        
//         time_now_server=new Date(showTime);//开始的时间
//         time_now_server=time_now_server.getTime();

//         time_now_client=new Date();
//         time_now_client=time_now_client.getTime();

//         time_server_client = time_now_server - time_now_client;
//         var timer = document.getElementById("timer");
//         if(!timer){return;}

//         timer.innerHTML =time_server_client;
//         var time_now,time_distance, str_time;
//         var int_day, int_hour, int_minute, int_second;

//         var time_now = new Date();
//         time_now = time_now.getTime() + time_server_client;

//         time_distance = time_end-time_now;
        
//         function show_time(){
//             console.log(time_server_client)
//             if(time_distance > 0){
//                 int_day = Math.floor(time_distance/86400000)
//                 time_distance -= int_day*86400000;
//                 int_hour = Math.floor(time_distance/3600000)
//                 time_distance -= int_hour*3600000;
//                 int_minute = Math.floor(time_distance/60000)
//                 time_distance -= int_minute*60000;
//                 int_second = Math.floor(time_distance/1000)
//                 if(int_hour<10)
//                 int_hour="0" + int_hour;
//                 if(int_minute<10)
//                 int_minute = "0" + int_minute;
//                 if(int_second < 10)
//                 int_second = "0" + int_second;
//                 str_time = int_day+"天"+int_hour+"时"+int_minute+"分"+int_second+"秒";
//                 timer.innerHTML = str_time;
//             }else{
//                 timer.innerHTML = timer.innerHTML;
//                 clearTimeout(timerID)
//             }
//         }
//         timerID = self.setTimeout(show_time(),1000);
// }