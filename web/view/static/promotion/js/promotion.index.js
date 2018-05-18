/**
 * htm5 页面js
 */
$(function(){
	//活动
	$(".active").on("click",function(event){
		event.preventDefault();
		location.href = 'http://platform.jkl.com.cn/promotion#{"param":"618"}';
	});
	//领取优惠券
	$(".coupon").on("click",function(event){
		event.preventDefault();
		location.href = 'http://platform.jkl.com.cn/promotion#{"param":"coupon"}';
	});
	//品牌墙
	$(".brand").on("click",function(event){
		event.preventDefault();
		location.href = 'http://platform.jkl.com.cn/promotion#{"param":"brand"}';
	});
	//进口食品
	$(".import").on("click",function(event){

		event.preventDefault();
		location.href = 'http://platform.jkl.com.cn/promotion#{"param":"import","category":20}';
	});
});
