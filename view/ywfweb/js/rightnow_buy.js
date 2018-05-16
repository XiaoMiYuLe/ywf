$(function(){
	var lock = false;
	$('#discount').click(function(event) {
		if (!lock) {
			$(this).siblings('.discount').css({
				display: 'block'
			});
			$(this).siblings('.discount').children('li').click(function(){
				var liText = $(this).text();
				$('#discount').text(liText);
				$(this).parent('.discount').css({
					display: 'none'
				});
				lock = false;
			});
		};
	});

	var choose= true;
	$('.ytext1 b').click(function(){
		if (choose) {
			$(this).removeClass('choose');
			choose=false;
		}else{
			$(this).addClass('choose');
			choose=true;
		};
	});
	
});
