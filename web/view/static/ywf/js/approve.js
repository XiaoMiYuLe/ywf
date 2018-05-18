$(function(){
	$('.browse').delegate('input', 'change', function(){
      	var img = $(this).parent().prev().children('img');
    		var file = $(this)[0].files[0];
    		var reader = new FileReader();
    		reader.onload = function( evt ){
    			img.attr("src",evt.target.result);
    		}
    		reader.readAsDataURL(file);
    });

	var choose= true;
	$('.tiaokuan i').click(function(){
			if (choose) {
				$(this).removeClass('choose');
				choose=false;
			}else{
				$(this).addClass('choose');
				choose=true;
			};
		});
});


