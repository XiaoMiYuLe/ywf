//回到顶部
$(function () {
    $(".sidebar_nav>.app").removeAttr('href');
    var erwm=$("<img>");
    erwm.attr("src","/static/ywf/img/icon/webchart.jpg");  // 微信图，手机图以后自行更换
    $(".sidebar_nav>.app").hover(function(){
        $(this).append(erwm)
    },function(){
        $(this).find("img").remove()
    })
    // var viewHeight=$(window).height();
    $(window).scroll(function (event) {

        //如果大于一屏，显示小火箭
        if ($(window).scrollTop() >= 500) {
            $('.sidebar_nav').show();
        } else {
            //如果小于一屏，隐藏小火箭
            $('.sidebar_nav').hide();
        }
    });

    //单击火箭按钮，回到页面的顶部
    $('.gotop').click(function (event) {
        //直接回0点，不做动画
        // $(window).scrollTop(0);

        $('html,body').stop().animate({
            'scrollTop': 0
        }, 500);
    });
});