$(function(){
	//选项卡切换
	//定义一个索引值变量
	var btnNum;
	//点击不同选项卡按钮时所触发的事件
	$('.tab button').click(function(){
		btnNum = $(this).index();
		//给选项卡按钮添加样式
		$(this).addClass("tab-btn").siblings().removeClass("tab-btn");
		//选项卡下内容的改变
		$("table").eq(btnNum).show().siblings().hide();
  });
  
  //左边导航栏
    $('ul li span').click(function(){
    	$(this).addClass("span_blue").parent().siblings().children().removeClass("span_blue");
       // $(this).before("<span style="height:22px;width:6px;background-color:#00aaee;margin-top:38px"></span>");
    })

});


