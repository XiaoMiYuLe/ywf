$(function(){
	//选项卡切换
	//定义一个索引值变量
	var btnNum;
	//点击不同选项卡按钮时所触发的事件
	$('.tab button').click(function(){
		btnNum = $(this).index()-1;
		//给选项卡按钮添加样式
		$(this).addClass("tab-btn").siblings().removeClass("tab-btn");
		//选项卡下内容的改变
		$("table").eq(btnNum).show().siblings().hide();
  });
  
  //左边导航栏
    $('ul li span').click(function(){
    	//改变字体颜色
    	$(this).addClass("span_blue").parent().siblings().children().removeClass("span_blue");
    	//定义一个变量来接受新增的span
    	var span1 = "<span class='span_add' style='height:18px;width:4px;background-color:#00aaee;position:absolute;top:22px;left:48px'></span>"
        //将其添加到所点击的对象前
        $(this).before(span1);
        //在不要时删除
    	$(this).parent().siblings().children(".span_add").remove();
    });

});


