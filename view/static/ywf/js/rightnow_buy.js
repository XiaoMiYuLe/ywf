$(function(){
	//快捷支付select下拉选择
	var lock = false;
	$('#discount').click(function(event) {
		event.stopPropagation();
		if (!lock) {
			$(this).siblings('.discount').css({
				display: 'block'
			});

			
			lock = false;
		};
		
	});
	$('.discount').children('li').click(function(){
		var liText = $(this).text();
		$('#discount').text(liText);
		$(this).children("input[name='danxuan']").attr('checked','true');
		voucher = $(this).children("input[name='danxuan']").val();
		type = $(this).children("input[name='voucher_type_kj']").val();
		use_money = $(this).children("input[name='use_money']").val();
		buy_money_voucher = $("input[name='buy_money_voucher']").val();
		if(type==1){
			voucher_money = $(this).children("input[name='voucher_money']").val();
			if(buy_money_voucher<use_money){
				alert('起投金额需大于'+use_money+'元才能使用该代金券')
				//$(this).children("input[name='danxuan']").attr('checked','false');
				$('#voucher_tishi').html('')
				liText = $('.discount').children('li').eq(0).text();
				$('#discount').text(liText);
			}else{
				$('#voucher_tishi').html('(优惠'+voucher_money+'元)')
			}
		}
		$("#voucher_k").val(voucher);
		$(this).parent('.discount').css({
			display: 'none'
		});
		
	});
	$(window).click(function(){
		if(!lock){
			$(".discount").css('display', 'none')
		}
 })


	
 //余额支付select下拉选择
	var lock = false;
	$('.ydiscount').click(function(event) {
		event.stopPropagation();
		if (!lock) {
			$(this).siblings('.discount').css({
				display: 'block'
			});

			
			lock = false;
		};
		
	});
	$('.yydiscount').children('li').click(function(){
		var liText = $(this).text();
		$('.ydiscount').text(liText);
		$(this).children("input[name='danxuan']").attr('checked','true');
		voucher = $(this).children("input[name='danxuan']").val();
		type_yr = $(this).children("input[name='voucher_type_yr']").val();
		use_money_yr = $(this).children("input[name='use_money_yr']").val();
		buy_money_voucher = $("input[name='buy_money_voucher']").val();
		if(type_yr==1){
			voucher_money_yr = $(this).children("input[name='voucher_money_yr']").val();
			if(buy_money_voucher<use_money_yr){
				alert('起投金额需大于'+use_money_yr+'元才能使用该代金券')
				//$(this).children("input[name='danxuan']").attr('checked','false');
				$('#voucher_tishi_yr').html('')
				liText = $('.yydiscount').children('li').eq(0).text();
				$('.ydiscount').text(liText);
			}else{
				$('#voucher_tishi_yr').html('(优惠'+voucher_money_yr+'元)')
			}
		}
		$("#voucher_y").val(voucher);
		$(this).parent('.yydiscount').css({
			display: 'none'
		});
		
	});

	$(window).click(function(){
		if(!lock){
			$(".discount").css('display', 'none')
		}
 })


//input单选
	var choose= true;
	$('.ytext1 b').click(function(){
		if (choose) {
			$(this).removeClass('choose');
			choose=false;
		}else{
			$(this).addClass('choose');
			choose=true;
		};
	});
	
//点击图片切换
	$('.pay>span').click(function () {	 
		 var num=$(this).index();
			$(this).addClass('cur');
			$(this).siblings().removeClass('cur'); 
		  $('.buy_box').eq(num-1).show().siblings('.buy_box').hide();
	});

//添加图片上的右图标i
	 var iHtml = "<i></i>";
		    $('.pay').delegate('span', 'click', function(event) {
		      $(this).append(iHtml).siblings('span').children('i').remove();
		      $(this).css({
		        border: '1px solid #2197d7',
		        height: 50,
		        width:130
		      }).siblings('span').css({
		        border: 0,
		        height: 52,
		        width:132
		      });;
		    });
		    $(this).attr('choose','yes')
});

