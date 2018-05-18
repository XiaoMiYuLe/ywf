 $(document).ready(function() {
     /**
   * 图片添加
   */
  var addImg = '<div class="del"><img src="" alt="" style="width:310px;height:176px;"/><i></i><input type="hidden" name="url" ></div>'
  
  $('.uploadImg').delegate('.add input', 'change', function(){
    var name = $(this).attr("name");
    var obj = $(this).parent().parent().parent();
    var newImg = $('img');
        if(newImg.length<1){
         $(addImg).insertBefore($(this).parent());
         var img = $(this).parent().prev().find('img');
         var file = $(this)[0].files[0];
         var reader = new FileReader();
         reader.onload = function( evt ){
            img.attr("src",evt.target.result);
         }
         reader.readAsDataURL(file);
        }else{
          alert("图片只能添加1张！");
        }
     });
  /**
   *删除图片
   */
  $(".uploadImg").delegate(".del i", "click", function(){
    var a = $('.uploadImg .del').length;  
    if (a > 0) {
      $(this).parent().remove();  
    };
  });
});