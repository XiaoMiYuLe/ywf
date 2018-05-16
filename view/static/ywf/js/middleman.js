//切换
	
	$(function() {		
		$('.middledown ul>li').click(function(event) {
			//给对应的LI进行排序
				$(this).addClass('current').siblings().removeClass('current');
			  var fatherNum=$(this).index();
				$('.second_menu').eq(fatherNum).addClass('qie').siblings('.second_menu').removeClass('qie');
        
		});		
		$('.son >li').click(function(event) {
				//给对应的LI进行排序
				$(this).addClass('current1').siblings().removeClass('current1');
				var sonNum = $(this).index();
				$(this).parent().siblings('.menu_list').children('.down_tab ').eq(sonNum).addClass('cur').siblings('.down_tab ').removeClass('cur');	
		});	
});
