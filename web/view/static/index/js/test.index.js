$(document).ready(function() {
	$p = 1;
	$psize = 4;
	 $.ajax({
         type: "POST",
         url: "/index/test/index",
         data: {p:$p, psize:$psize},
         dataType: "json",
         success: function(data){
        	 //alert(data.status)
        	 if(data.status==0){
        		 $('#resText').empty(); //清空resText里面的所有内容 var html = ''; 
                 var html = ''
                /* html += '<div class="comment"><h6>' + data.data.goods_name + ':</h6><p class="para"' + data.data.end_time+ '</p></div>';*/
                 $.each(data.data.info, function(commentIndex, comment){
                	 alert(JSON.stringify(comment));
                      html += '<div class="comment"><h6>' + comment[commentIndex]['goods_name'] + ':</h6><p class="para"' + comment[commentIndex]['goods_id'] + '</p></div>';
                 });
                $('#resText').html(html);
        	 }else{
        		 alert(data.error)
        	 }
        	 return false;       
          }
         
     });
});