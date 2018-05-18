/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

    //左侧导航
    //var first = $('.help:first').attr('id');

    $url = GetRequest();
    if($url.tag==null){
        $('#title').html('新手必读');
        getApi(16);
    }else if($url.tag=='1'){
        $('#title').html('新手必读');
        getApi(16);
    }else if($url.tag=='2'){
        $('#title').html('注册绑卡');
        getApi(17);
    }else if($url.tag=='3'){
        $('#title').html('投资操作');
        getApi(18);
    }else if($url.tag=='4'){
        $('#title').html('产品转让');
        getApi(19);
    }else if($url.tag=='5'){
        $('#title').html('收益保障');
        getApi(20);
    }else if($url.tag=='6'){
        $('#title').html('常见问题');
        getApi(22);
    }
    getHidden();

  
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
        
        var category_id = $(this).attr('id');
         
        if(category_id=='16'){
            $('#title').html('新手必读');
        }else if(category_id=='17'){
            $('#title').html('注册绑卡');
        }else if(category_id=='18'){
            $('#title').html('投资操作');
        }else if(category_id=='19'){
            $('#title').html('产品转让');
        }else if(category_id=='20'){
            $('#title').html('收益保障');
        }else if(category_id=='22'){
            $('#title').html('常见问题');
        }

        getApi(category_id);

        $('#paging').page('remote', {"category_id": category_id});
        
    });

});

    function GetRequest() {
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for(var i = 0; i < strs.length; i ++) {
        theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
        }
        }
        return theRequest;
    }
function getApi(category_id) {
    $("#paging").page({
        pageSize: 10,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
//        showPageSizes: true,
        infoFormat: '共{total}条', //{start} ~ {end}条，
        remote: {
            url: '/page/help/getHelpContent', //请求地址
            params: {"category_id": category_id}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.list, function (index, content) {
                    html += '<li>' +
                            '<i class="helpcenter_i">' +
                            '<span>' + content.title + '</span></i>' +
                            '<p style="font-family:仿宋; font-size:12pt;color:#000000;">' + content.body + '</p>' +
                            '</li>';
                });

                $("#list").empty();
                $("#list").html(html);

                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
}


function getHidden() {
   $('.container').delegate("li .helpcenter_i", "click", function () {
        //alert($(this).siblings("p").css("display"));
        if ($(this).siblings("p").css("display") == "none" || $(this).siblings("p").css("display") == "") {
            $(this).siblings("p").show();
            $(this).css("background", "url('/static/ywf/img/jian.png') no-repeat");
        } else if($(this).siblings("p").css("display") == "block"){
         $(this).siblings("p").hide();
         $(this).css("background", "url('/static/ywf/img/jia.png') no-repeat");
        }
        return false;

    });
}