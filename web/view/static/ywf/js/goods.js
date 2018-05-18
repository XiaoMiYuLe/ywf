//点击切换
$(function (argument) {

	$('.goods_product_second li').click(function (argument) {	 
		 var num=$(this).index();
			$(this).addClass('current');
			$(this).siblings().removeClass('current'); 
		  $('.goods_box_l').parent().find('.goods_box_l').hide().eq(num).show();

	});
	$("div.z_scroll").myScroll({
		speed:40, 
		rowHeight:60 
	});

});

(function($){
	$.fn.myScroll = function(options){
	
	var defaults = {
		speed:40,  
		rowHeight:24 
	};
	
	var opts = $.extend({}, defaults, options),intId = [];
	
	function marquee(obj, step){
	
		obj.find("ul").animate({
			marginTop: '-=1'
		},0,function(){
				var s = Math.abs(parseInt($(this).css("margin-top")));
				if(s >= step){
					$(this).find("li").slice(0, 1).appendTo($(this));
					$(this).css("margin-top", 0);
				}
			});
		}
		
		this.each(function(i){
			var sh = opts["rowHeight"],speed = opts["speed"],_this = $(this);
			intId[i] = setInterval(function(){
				if(_this.find("ul").height()<=_this.height()){
					clearInterval(intId[i]);
				}else{
					marquee(_this, sh);
				}
			}, speed);

			_this.hover(function(){
				clearInterval(intId[i]);
			},function(){
				intId[i] = setInterval(function(){
					if(_this.find("ul").height()<=_this.height()){
						clearInterval(intId[i]);
					}else{
						marquee(_this, sh);
					}
				}, speed);
			});
		
		});

	}

})(jQuery);