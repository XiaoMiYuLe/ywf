$(document).ready(function(){
	var flag = 1;
	$("#sendCode").click(function(){
		var isMobile = /^1[34578][0-9]{1}[0-9]{8}$/;

		var phone = $("#phone").val();
		if (! isMobile.test(phone)) {
			alert('请输入正确的手机号码')
			return false;
		}
		if(flag == 1){
			flag = 2;
		    $("#sendCode").attr("disabled","disabled");
		    /* 不管成功与否都发送信息*/
		    send();
		    $.ajax({
		    	type:'post',
		        url:'/cas/signup/sendCode',
		        data:{"phone":phone},
		        dataType:'json',
		        timeout:60000,
		        success:function(response){
		    		  if(response.status==0){
		    			  alert('发送成功')
		    		  }else{
		    			  alert(response.error)
		    		  }
		        }
		    });
		    
		    setTimeout(function(){
		    	flag = 1 ;
		    },60000);
		}
	});
	
	function send(){
	    var nums = 60;
	    var numnow;
	    $("#sendCode").val("重发" + 60 + "s").css('background','#ccc');
	    var ss = setInterval(function () {
	        nums--;
	        numnow = nums;
	        $("#sendCode").val("重发" + numnow + "s").css('background','#ccc');
	        if (nums == 0) {
	            clearInterval(ss);
	            $("#sendCode").val("重新发送").css('background','#ffaa00');
	            nums = 6;
	            $("#sendCode").removeAttr("disabled")
	        }
	    }, 1000)
	}
	
	$("#sub").click(function(){
		var phone = $("#phone").val();
		var code = $("#code").val();
		var pwd = $("#pwd").val();
		var repwd = $("#repwd").val();
		var recommender = $("#recommender").val();
		if(phone=="" || phone==null || code==null || code=="" || code==null || pwd=="" || pwd==null || repwd=="" || repwd==null){
			alert('请将信息填写完整')
			return false;
		}
		$.post("/cas/signup/add",{"phone":phone,"code":code,"pwd":pwd,"repwd":repwd,"recommender":recommender},function(data){
			if(data.status==0){
				alert('注册成功！');
				location.href=data.guide_url;
			}else{
				alert(data.error);
			}
			},'json');
	});
	
	
	
});
	
	
 

	