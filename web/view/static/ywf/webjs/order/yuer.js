$(document).ready(function () {
	$("#button2").click(function(){
		var choose = $("#choose1").attr("class");
		var order= $("#order").val();
		var pay_pwd = $("#pay_pwd").val();
		
		var voucher_id = $("#voucher_y").val();
		if(! pay_pwd){
			showAlert({
                   alertTitle: "温馨提示",
                   alertContent: '请输入交易密码!',
                   alertType: 1,
                   singleBtnText: "确定"
                   });
			return false;
		}
		if(! choose){
			showAlert({
                   alertTitle: "温馨提示",
                   alertContent: '您需要同意协议条款才能交易!',
                   alertType: 1,
                   singleBtnText: "确定"
                   });
			return false;
		}
		var pay_pwd = md5(pay_pwd);
		$.post("/bts/order/balancePay",{'order_no':order,'pay_pwd':pay_pwd,voucher_id:voucher_id},function(result1){
			if(result1.status==0){
				window.location.href='/bts/order/prompt?order_no='+order;
			}else{
				showAlert({
                   alertTitle: "温馨提示",
                   alertContent: result1.error,
                   alertType: 1,
                   singleBtnText: "确定"
                   });
			}
		},'json')
		
	})
	
});

