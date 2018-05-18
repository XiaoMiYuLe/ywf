$(document).ready(function () {
	$("#button2").click(function(){
		 if(($('#wbk').html()) =='未绑卡'){
			 showAlert({
                 alertTitle:"温馨提示",
                 alertContent:"您尚未绑定银行卡",
                 alertType:2,
                 doubleBtnText1:"取消",
                 doubleBtnText2:"去绑卡",
                 goUrl:"/cas/bank/addbank"
			 });
			 return false;
		 }
		myCode = $('#check_code').val();
		var money = $("#money").val();
		var iss_ins_cd = $(".checked").attr('id');
		 if(! money){
			showAlert({
				alertTitle:"温馨提示",
				alertContent:'请输入金额',
				alertType:1,
				singleBtnText:"确定",
				
			})
			return false;
	     }
		
		// 空值判断
	    if (isNaN(money)) {
	    	showAlert({
				alertTitle:"温馨提示",
				alertContent:'请输入正确的充值金额!',
				alertType:1,
				singleBtnText:"确定",
				
			})
	        $("#money").val("");
	        return false;
	    }
	    
	   //最高100万
	    if (money < 100) {
	        showAlert({
				alertTitle:"温馨提示",
				alertContent:'充值金额不能低于100元',
				alertType:1,
				singleBtnText:"确定",
				
			})
	        $("#money").val("");
	        return false;
	    }
	 
		//最高100万
	    if (money > 10000000) {
	        showAlert({
				alertTitle:"温馨提示",
				alertContent:'充值金额不能超过100万元',
				alertType:1,
				singleBtnText:"确定",
				
			})
	        $("#money").val("");
	        return false;
	    }
		
		$.post("/cas/recharge/payinfo",{'order_amt':money,'iss_ins_cd':iss_ins_cd},function(result1){
			if(result1.status==0){
				html = '';
				html+='充值金额 :<span style="color:red">'+result1.data.order_money+'</span>元'+
					'<form action="https://pay.fuiou.com/smpGate.do" method="post"  target="_blank">'+
					'<input type="hidden" name="md5" value="'+result1.data.md5+'" />'+
		            '<input type="hidden" name="mchnt_cd" value="'+result1.data.mchnt_cd+'" />'+
		            '<input type="hidden" name="order_id" value="'+result1.data.order_id+'" />'+
		            '<input type="hidden" name="order_amt" value="'+result1.data.order_amt+'" />'+
		            '<input type="hidden" name="order_pay_type" value="'+result1.data.order_pay_type+'" />'+
		            '<input type="hidden" name="page_notify_url" value="'+result1.data.page_notify_url+'" />'+
		            '<input type="hidden" name="back_notify_url" value="'+result1.data.back_notify_url+'" />'+
		            '<input type="hidden" name="order_valid_time" value="'+result1.data.order_valid_time+'" />'+
		            '<input type="hidden" name="iss_ins_cd" value="'+result1.data.iss_ins_cd+'" />'+
		            '<input type="hidden" name="goods_name" value="'+result1.data.goods_name+'" />'+
		            '<input type="hidden" name="goods_display_url" value="'+result1.data.goods_display_url+'" />'+
		            '<input type="hidden" name="rem" value="'+result1.data.rem+'" />'+
		            '<input type="hidden" name="ver" value="'+result1.data.ver+'" />'+
		            '<div class="btngroup"><input class="z-submit" id="z-submit" type="submit" style="width: 100%;height: 70px;color:#2197d7;background: transparent;border: 0;cursor:pointer" value="确认支付" /></div>'+
		            '</form>';
		           showAlert({
					alertTitle:"温馨提示",
					alertContent:html,
					alertType:1,
					singleBtnText:"取消",
					
				})		        
				return false;
			}else{
				showAlert({
					alertTitle:"温馨提示",
					alertContent:result1.error,
					alertType:1,
					singleBtnText:"确定",
					
				})
				return false;
			}
		},'json')

		$('body').delegate('.z-submit','mouseover',function(){
			$(this).css({
				background:"#2197d7",
				color:"#fff"
			})
		});
		
	})
});


