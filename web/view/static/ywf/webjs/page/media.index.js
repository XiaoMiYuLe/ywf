/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

    //左侧导航
    $('.about').click(function () {
        var about = $(this).attr('id');
        var title = $(this).children('span').text();
        getDetail(about,title);
    });
    
    //媒体报道
    getApi();
});

//获取详情
function getDetail(about,title){
    $.post('/page/index/detail',{'about':about},function(response){
        if (response['status'] == 0) {
            var html = '';
            if (response.data.platform_introduce){
                $('#title').html(title);
                html += response.data.platform_introduce;
            }else if(response.data.company_introduce){
                 $('#title').html(title);
                html += response.data.company_introduce;
            }else if(response.data.security_assurance){
                 $('#title').html(title);
                html += response.data.security_assurance;
            }else if (response.data.contact_us){
                 $('#title').html(title);
                html += response.data.contact_us;
            }
            
            $("#body").empty();
            $("#body").html(html);
        }
    });
}

function getApi() {
    $("#paging").page({
        pageSize: 8,
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
            url: '/page/media/getMedia', //请求地址
            params: {}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.list, function (index, content) {
                    //时间截取
                    if (content.mtime) {
                        content.mtime = content.mtime.substring(0,10);
                    }else {
                        content.mtime = '';
                    }
                    var href_str = '/page/media/detail?id='+content.content_id;
                    html += '<li>'+
                                '<span class="mtbd1" onclick="location.href=\''+href_str+'\'">'+content.title+'</span>'+
                                '<span class="mtbd2">'+content.mtime+'</span>'+
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

//媒体报道
function getApi1(){
    
}
