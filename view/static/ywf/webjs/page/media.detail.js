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