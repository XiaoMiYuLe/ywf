$(document).ready(function(){
	var phone = trim($("input[name='phone']").val());
	var order_no = trim($("input[name='order_no']").val());

	telNum=phone.substring(0,3)+'****'+phone.substring(7,11);
	showAlert({
		alertTitle:"付款确认",
		alertContent:"本次交易需要短信验证，验证码将发送到你的手机"+telNum,
		alertType:3,
		doubleBtnText1:"取消",
		doubleBtnText2:"确认",
		goUrl:"javascript:;"
	});
	setTime($('#getCodeBtn'));
	
	function trim(str){
		return str.replace(/(^\s*)|(\s*$)/g,"");
	}
	
	$('#getCodeBtn').click(function(){
		$.post("/cas/bank/sms",{'order_no':order_no},function(result1){
			if(result1.status==0){
			}else{
				showAlert({
                    alertTitle: "温馨提示",
                    alertContent: result1.msg,
                    alertType: 1,
                    singleBtnText: "确定"
                });
                return false;
			}
		},'json')
	})
	
	$('.btnRight').click(function(){
		myCode = $('#myCode').val();
		$.post("/cas/bank/pay",{'order_no':order_no,'check_code':myCode},function(result2){
			if(result2.status==0){
				$.post("/cas/bank/search",{'order_no':order_no},function(result3){
					if(result3.status==0){
						showAlert({
                               alertTitle: "温馨提示",
                               alertContent: '恭喜您，绑卡成功！',
                               alertType: 1,
                               singleBtnText: "确定"
                              });
						window.location.href='/cas/user/index';
					}else if(result3.status==1){
						showAlert({
                             alertTitle: "温馨提示",
                             alertContent: result3.msg,
                             alertType: 1,
                             singleBtnText: "确定"
                         });
                         return false;
					}else{
						showAlert({
                             alertTitle: "温馨提示",
                             alertContent: result3.error,
                             alertType: 1,
                             singleBtnText: "确定"
                         });
                         return false;
					}
				},'json')			
			}else{
				showAlert({
                             alertTitle: "温馨提示",
                             alertContent: result2.msg,
                             alertType: 1,
                             singleBtnText: "确定"
                         });
                return false;
			}
		},'json')
	})
});