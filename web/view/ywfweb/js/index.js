$(function(){
	//jq初始化CSS
	$('.imgList li:first').show();
	$('.btnList li:last').css('margin-right', 0);
	//设置一个变量，用来模拟那个不断在改变的序号
	var num=0;
	var timer;
	//下一张走的功能
	function nextFn(event) {	
		//num在加加之前，代表当前这个角标
		$('.btnList li').eq(num).removeClass('current');
		//当前图片淡出去
		$('.imgList li').eq(num).stop().fadeOut(1000);
		num++;
		if(num>5){
			num=0;
		}
		//下一张图片淡入来
		$('.imgList li').eq(num).stop().fadeIn(1000);
		//num在加加之后，代表下一个角标
		$('.btnList li').eq(num).addClass('current');
	}
	//单击按钮切换某一张
	$('.btnList li').click(function(event) {	
		//让this具备current，让this的小伙伴们...
		$(this).addClass('current').siblings().removeClass('current');
		//现在内存中的num代表当前这张图片的索引值
		//当前这张图片淡出去
		$('.imgList li').eq(num).stop().fadeOut(1000);
		num=$(this).index();
		//重新赋值以后的num代表与当前按钮下标匹配的某一张图片
		//让与当前按钮下标匹配的某一张图片淡入进来
		$('.imgList li').eq(num).stop().fadeIn(1000);
	});

	//自动走
	timer=setInterval(nextFn, 3000);

	$('.banner').hover(function() {
		clearInterval(timer);
	}, function() {
		clearInterval(timer);
		timer=setInterval(nextFn, 3000);
	});
})


//公告的切换
	
	$(function() {
		
		$('.four_tab_title li').click(function(event) {
			//给对应的LI进行排序
			var i=$(this).index();

			$(this).addClass('current');

			$(this).siblings().removeClass('current');

			//相应的事件进行同步
			$('.four_tab_content').parent().find('.four_tab_content').hide().eq(i).show();

		});
	});


