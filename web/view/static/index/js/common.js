$(document).ready(function() {
	/**
	 * 选择地区
	 */
	$("select.region").change(function(){
		var obj = $(this);
		var i = obj.index();
		var region_id_now = $(this).val();
		var region_son_str = '';
		
		/* 清空后面元素的选项 */
		obj.parent().find('select:gt(' + i + ')').find("option:gt(0)").remove();
		
		$.ajax({
	    	type:'post',
	        url:'/helper/region/getregionsbypid',
	        data:'pid=' + region_id_now,
	        dataType:'json',
	        timeout:60000,
	        success:function(data){
	    		if (data.status == 0 && data.data != '' && obj.next('select.region').size() > 0) {
	    			var d = data.data;
	    			for (var i in d) {
	    				region_son_str += '<option value="' + d[i]['region_id'] + '">' + d[i]['region_name'] + '</option>';
	    			}
	    			obj.next('select.region').append(region_son_str);
	    		}
	    		return false;
	    	}
	    });
	});
});