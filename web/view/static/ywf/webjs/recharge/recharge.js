$(document).ready(function () {
    //$("#button1").attr('disabled', true);
    //$("#button1").attr('style', 'background:gray');
    $("#button1").click(function () {
        var order = $("#order").val();
        myCode = $('#check_code').val();
        var money = $("#money").val();
        if (!money) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入金额',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }

        // 空值判断
        if (isNaN(money)) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入正确的充值金额!',
                alertType: 1,
                singleBtnText: "确定",
            })
            $("#money").val("");
            return false;
        }

        //最高100万
        if (money < 100) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '充值金额不能低于100元',
                alertType: 1,
                singleBtnText: "确定",
            })
            $("#money").val("");
            return false;
        }

        //最高100万
        if (money > 10000000) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '充值金额不能超过100万元',
                alertType: 1,
                singleBtnText: "确定",
            })
            $("#money").val("");
            return false;
        }
        if (!myCode) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入短信验证码',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }

        $.post("/cas/recharge/pay", {'order_no': order, 'check_code': myCode}, function (result1) {
            if (result1.status == 0) {
                $.post("/cas/bank/search", {'order_no': order}, function (result3) {
                    if (result3.status == 0) {
                        window.location.href = '/cas/recharge/prompt';
                    } else if (result3.status == 1) {
                        showAlert({
                            alertTitle: "温馨提示",
                            alertContent: result3.msg,
                            alertType: 1,
                            singleBtnText: "确定",
                        })
                        return false;
                    } else {
                        showAlert({
                            alertTitle: "温馨提示",
                            alertContent: result3.error,
                            alertType: 1,
                            singleBtnText: "确定",
                        })
                        return false;
                    }

                }, 'json')
            } else {
                showAlert({
                    alertTitle: "温馨提示",
                    alertContent: result1.msg,
                    alertType: 1,
                    singleBtnText: "确定",
                })
                return false;
                //$('.btnSubmit').click(function(){
                    //window.location.href = '/cas/recharge/index';
                //});
            }
        }, 'json')

    })


    $("#code").click(function () {
        if (($('#wbk').html()) == '未绑卡') {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: "您尚未绑定银行卡",
                alertType: 2,
                doubleBtnText1: "取消",
                doubleBtnText2: "去绑卡",
                goUrl: "/cas/bank/addbank"
            });
            return false;
        }
        if ($(this).html() == '获取验证码') {
            bd();
        } else {
            sms();
        }
    });

    function bd() {
        var money = $("#money").val();
        if (!money) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入金额',
                alertType: 1,
                singleBtnText: "确定",
            })
            return false;
        }

        // 空值判断
        if (isNaN(money)) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '请输入正确的充值金额!',
                alertType: 1,
                singleBtnText: "确定",
            })
            $("#money").val("");
            return false;
        }

        //最高100万
        if (money < 100) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '充值金额不能低于100元',
                alertType: 1,
                singleBtnText: "确定",
            })
            $("#money").val("");
            return false;
        }

        //最高100万
        if (money > 10000000) {
            showAlert({
                alertTitle: "温馨提示",
                alertContent: '充值金额不能超过100万元',
                alertType: 1,
                singleBtnText: "确定",
            })
            $("#money").val("");
            return false;
        }

        $.post("/cas/recharge/bdCard", {money: money}, function (data) {
            if (data.status == 0) {
                $("#order").val(data.data.order_no);
                setTime($("#code"))
                $("#money").attr('readonly', true);
                $("#money").attr('style', 'background:lightgray');
                $("#button1").attr('disabled', false);
                $("#button1").attr('style', '');

            } else {
                showAlert({
                    alertTitle: "温馨提示",
                    alertContent: data.msg,
                    alertType: 1,
                    singleBtnText: "确定",
                })
                return false;
            }
        }, 'json')
    }

    function sms() {
        order = $("#order").val();
        $.post("/cas/bank/sms", {'order_no': order}, function (result1) {
            if (result1.status == 0) {
                setTime($("#code"))
            } else {
                showAlert({
                    alertTitle: "温馨提示",
                    alertContent: result1.msg,
                    alertType: 1,
                    singleBtnText: "确定",
                })
                return false;
            }
        }, 'json')
    }

    function setTime(btn) {
        if (!btn.hasClass('sended')) {
            btn.addClass('sended').attr('disabled', true);
            var sendCodeTime = 30;
            btn.text(sendCodeTime + 'S');
            var sendCodeInterval;
            sendCodeInterval = setInterval(function () {
                if (sendCodeTime > 1 && sendCodeTime <= 30) {
                    sendCodeTime--;
                    btn.text(sendCodeTime + 'S');
                } else {
                    btn.text('重新发送');
                    clearInterval(sendCodeInterval);
                    btn.removeClass('sended').attr('disabled', false);
                }
            }, 1000);
        }
        ;
    }
    ;
});


