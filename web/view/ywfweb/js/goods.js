//点击切换
$(function (argument) {

	$('.goods_product_second li').click(function (argument) {	 
		 var num=$(this).index();
			$(this).addClass('current');
			$(this).siblings().removeClass('current'); 
		  $('.goods_box').parent().find('.goods_box').hide().eq(num).show();

	});

});

