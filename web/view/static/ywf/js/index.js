
$(function(){
    for(var y=0;y<$(".banner").find("li").length;y++){
        $(".banner_dian ul").append("<li></li>")
    }

    var t=$(".banner_dian ul").width()/2;
    $(".banner_dian").css("margin-left",-t);
    jQuery(".h350_banner").slide({mainCell:".banner ul",effect:"fold",autoPlay:true,interTime:5000});

});
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


