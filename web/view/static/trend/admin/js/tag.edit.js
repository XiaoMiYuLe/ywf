$(document).ready(function() {
	/**
	 * 提交按钮处理
	 */
	$("input.input-tag-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = true; form_tag_submit(); break;
			case 'submit_save_continue' : back_listing = false; form_tag_submit(); break;
			case 'submit_goods_add': form_goods_add();break;
		}
	});
});


/**
 * 表单提交处理
 */
function form_tag_submit() {
	if (! $("input[name=tag_name]").val()) {
		alert('标签名不能为空');
		return false;
	}
	
	var tag_id = $("input[name=tag_id]").val();
	
	var saveCallBack;
	if (tag_id == '' || tag_id == 0) {
		saveCallBack = form_tag_save_added;
	} else {
		$("#edit_tag_form").attr("action", "/trendadmin/tag/edit");
		saveCallBack = form_tag_save_edited;
	}
	
	var options = {
            dataType:'json',
            timeout:60000,
            success:saveCallBack
    };
    $("#edit_tag_form").ajaxSubmit(options);
    return false;
}

/**
 * 添加成功，返回处理
 */
function form_tag_save_added(data, textStatus) {
    if (data.status === 0) {
        alert('添加成功!');
        
        // 判断是否返回列表管理
        if (back_listing == true) {
        	$('#tag_listing').datagrid('reload');
        	$('#ajaxModal').modal('hide');
        }
    } else {
    	alert(data.error);
    }
}

/**
 * 编辑成功，返回处理
 */
function form_tag_save_edited(data, textStatus) {
    if (data.status === 0) {
        alert('编辑成功!');
        $('#tag_listing').datagrid('reload');
        $('#ajaxModal').modal('hide');
    } else {
    	alert(data.error);
    }
}


/**
 * 商品页面中添加标签
 */
function form_goods_add(){
	var tag_name = $("input[name=tag_name]").val();
	if (! tag_name) {
		alert('标签名不能为空');
		return false;
	}
    $("#edit_tag_form").ajaxSubmit({
    	dataType:'json',
        timeout:60000,
        success:function(data){
           if(data.status===0){
        	   if($("input[name=list]").val()==0){
        		   html = '<label class="checkbox-inline">';
            	   html += '<input type="checkbox" name="tags[]" value="'+data.tag_id+'">'+tag_name+'</label>';
            	   $("#tags_group").append(html);
        	   }else if($("input[name=list]").val()==1){
        		   $('#goods_listing').datagrid('reload');
        	   }
        	   $('#ajaxModal').modal('hide');
           }else{
        	   alert(data.error);
           }	
        }});
    return false;
}