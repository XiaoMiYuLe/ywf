var CODEMIRROR_LOADED_HEADER = false;
var CODEMIRROR_LOADED_FOOTER = false;
var editor_header, editor_footer;

$(document).ready(function() {
	/**
	 * 载入在线编辑资源
	 */
	setTimeout(function(){initCM();}, 500);
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_save' : form_submit(); break;
		}
	});
});


/**
 * 延迟重载 codemirror
 */
function initCM() {
    if (CODEMIRROR_LOADED_HEADER == false || CODEMIRROR_LOADED_FOOTER == false) {
    	editor_header = CodeMirror_Helper('content_code_header', 'editor_header');
    	editor_footer = CodeMirror_Helper('content_code_footer', 'editor_footer');
    } else {
        setTimeout('initCM()', 500);
    }
}

/**
 * 表单提交处理
 */
function form_submit() {
	notice('edit_notice', img_loading_small, false);
	
	$(".input-submit").attr('disabled', true);
	
	/* 更新编辑器状态 */
	$("#textarea_content_header").text(editor_header.getValue());
	$("#textarea_content_footer").text(editor_footer.getValue());
	
	var saveCallBack = form_save_edited;
	var options = {
            dataType:'json',
            timeout:60000,
            success:saveCallBack,
            error:ajaxError
    };
    $("#edit_form").ajaxSubmit(options);
    return false;
}

/**
 * 编辑成功，返回处理
 */
function form_save_edited(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 保存成功!', true, 5000);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}