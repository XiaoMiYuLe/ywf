	//点击切换
	$(function() {
		
		$('.product_second li').click(function(event) {
			//给对应的LI进行排序
			var i=$(this).index();

			$(this).addClass('current');

			$(this).siblings().removeClass('current'); 
			//相应的事件进行同步
			$('.product_third').parent().find('.product_third').hide().eq(i).show();
			
		});
		 //进度条数字
        var shuzi=$('.progress_num').text();
        alert('heeh')
            Sshuzi=parseInt(shuzi)+'%';
            console.log(Sshuzi);
         $('.pro_bar .progress').css('width', Sshuzi);
});