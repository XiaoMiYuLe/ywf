/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

    //左侧导航
    var first = $('.about:first').attr('id');
    var title = $('.about>span:first').html();
    //getDetail(first,title);

    $url = GetRequest();
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

      if($url.tag==null){
        getDetail(first,title);
      }
      if($url.tag=='1'){
    	   $.post('/page/index/detail',{'about':1},function(response){
    	        if (response['status'] == 0) {
    	            var html = '';
    	            $('#title').html('亿万福介绍');
                    html += response.data.platform_introduce+response.data.our_vision+response.data.management_idea;
    	            
    	            $("#body").empty();
    	            $("#body").html(html);
    	        }
    	    });
      }
      if($url.tag=='2'){
           $.post('/page/index/detail',{'about':2},function(response){
                if (response['status'] == 0) {
                    var html = '';
                    $('#title').html('公司介绍');
                    html += response.data.company_introduce;
                    
                    $("#body").empty();
                    $("#body").html(html);
                }
            });
      }
    if($url.tag=='3'){
           $.post('/page/index/detail',{'about':3},function(response){
                if (response['status'] == 0) {
                    var html = '';
                    $('#title').html('安全保障');
                    html += response.data.security_assurance;
                    
                    $("#body").empty();
                    $("#body").html(html);
                }
            });
      }
    if($url.tag=='9'){
           $.post('/page/index/detail',{'about':9},function(response){
                if (response['status'] == 0) {
                    var html = '';
                    $('#title').html('联系我们');
                    html += response.data.contact_us;
                    
                    $("#body").empty();
                    $("#body").html(html);
                }
            });
      }
    $('.about').click(function () {
        var about = $(this).attr('id');
        var title = $(this).children('span').text();
        getDetail(about,title);
    });
    
    
});

//获取 介绍 公司介绍 安全保障 联系我们 内容
function getDetail(about,title){
    $.post('/page/index/detail',{'about':about},function(response){
        if (response['status'] == 0) {
            var html = '';
            if (response.data.platform_introduce){
                $('#title').html(title);
                html += response.data.platform_introduce+response.data.our_vision+response.data.management_idea;
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



