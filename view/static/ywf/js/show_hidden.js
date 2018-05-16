$(function () {
 $("li .helpcenter_i").click(function(){
    if($(this).siblings("p").css("display")=="none"){

        $(this).siblings("p").show();
        $(this).css("background","url('/static/ywf/img/jian.png') no-repeat");
    }
    else{
         
        $(this).siblings("p").hide();
        $(this).css("background","url('/static/ywf/img/jia.png') no-repeat");
    }
 });
});