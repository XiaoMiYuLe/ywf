/**
 * 定义一些常量
 */
var account_access = true; // 管理员帐号是否通过验证。不填也视为通过

$(document).ready(function() {
	/**
	 * 切换模板编辑位置
	 */
	$('.nav-map').click(function(){
		/* 模板导航的样式处理 */
		$('.nav-map').removeClass('active');
		$(this).addClass('active');
		
		/* 切换编辑区域 */
		$('.edit-map').hide();
		var nav_id = $(this).attr('id');
		var edit_id = nav_id.replace('nav', 'edit');
		$('#' + edit_id).show();
	});
	
	/**
	 * 预处理日期选择控件
	 */
	$('.datepicker-input').datepicker();
	
	/**
	 * 初始化地区选单
	 */
	$("select.region").each(function(k){
		var pid = $(this).attr('data-init');
		if (k == 0) {
			pid = pid ? pid : 3743;
		}
		if (pid > 0) {
			getRegionByPid(pid, $(this));
		}
	});
	
	/**
	 * 是否审核
	 */
	$('#is_verify').val() == -1 ? $('#rejection_reason').show() : $('#rejection_reason').hide();
	$('#is_verify').change(function(){
		$(this).val() == -1 ? $('#rejection_reason').show() : $('#rejection_reason').hide();
	});
	
	/**
	 * 实时校验账户信息
	 */
	$('#username').on('blur', function(){
		var username = $(this).val();
		if (! username || $.trim(username) == '') {
			return false;
		}
		
		if (! CASValidator.username(username)) {
			account_access = false;
			alert('账号不符合规则');
			return false;
		}
		
		$.ajax({
	    	type:'get',
	        url:'/cas/sign/isUsernameAvailable',
	        data:'username=' + username,
	        timeout:60000,
	        success:function(data){
	        	if (! data) {
	        		account_access = false;
	        		alert('账号已被注册，请重新填写');
	        	}
	        	account_access = true;
	    		return false;
	    	}
	    });
	});
	
	/**
	 * 实时校验公司名称
	 */
	$('#company_name').on('blur', function(){
		var company_name = $(this).val();
		if (! company_name || $.trim(company_name) == '') {
			return false;
		}
		
		/* 编辑状态下，没有改动时，不校验 */
		if ($('#store_id').val() && $('#company_name').val() == $('#company_name_edit').val()) {
			return false;
		}
		
		$.ajax({
	    	type:'post',
	        url:'/storeadmin/index/isCompanynameAvailable',
	        data:'company_name=' + company_name,
	        dataType:'json',
	        timeout:60000,
	        success:function(data){
	        	if (data.status != 0) {
	        		alert('该公司名称已被注册，请重新填写');
	        	}
	    		return false;
	    	}
	    });
	});
	
	/**
	 * 实时校验店铺名称
	 */
	$('#store_name').on('blur', function(){
		var store_name = $(this).val();
		if (! store_name || $.trim(store_name) == '') {
			return false;
		}
		
		/* 编辑状态下，没有改动时，不校验 */
		if ($('#store_id').val() && $('#store_name').val() == $('#store_name_edit').val()) {
			return false;
		}
		
		$.ajax({
	    	type:'post',
	        url:'/storeadmin/index/isStorenameAvailable',
	        data:'store_name=' + store_name,
	        dataType:'json',
	        timeout:60000,
	        success:function(data){
	        	if (data.status != 0) {
	        		alert('该店铺名称已被注册，请重新填写');
	        	}
	    		return false;
	    	}
	    });
	});
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_type = $(this).attr("data_submit_type");
		switch (submit_type) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = true; form_submit(); break;
			case 'submit_save_continue' : back_listing = false; form_submit(); break;
		}
	});
});


/**
 * 取消处理
 */
function form_cancel() {
	history.go(-1);
}

/**
 * 表单提交处理
 */
function form_submit() {
	notice('edit_notice', img_loading_small, false);
	
	var store_id = $("#store_id").val();
	
	/* 校验必填项 */
	if (! $("#signing_time_start").val() || $.trim($("#signing_time_start").val()) == '') {
		notice('edit_notice', img_delete + ' 签约开始时间不能为空', true, 5000);
		return false;
	}
	if (! $("#signing_time_end").val() || $.trim($("#signing_time_end").val()) == '') {
		notice('edit_notice', img_delete + ' 签约结束时间不能为空', true, 5000);
		return false;
	}
	if ($("#signing_time_end").val() < $("#signing_time_start").val()) {
		notice('edit_notice', img_delete + ' 签约结束时间不能在开始时间之前', true, 5000);
		return false;
	}
	if (! $("#company_name").val() || $.trim($("#company_name").val()) == '') {
		notice('edit_notice', img_delete + ' 请输入公司名称', true, 5000);
		return false;
	}
	
	/* 校验账户信息 */
	if (! store_id) {
		if (! $('#username').val() || $.trim($('#username').val()) == '') {
			notice('edit_notice', img_delete + ' 请输入管理员帐号', true, 5000);
			return false;
		}
		if (! $('#password').val() || ! $('#repassword').val()) {
			notice('edit_notice', img_delete + ' 请设置管理员密码', true, 5000);
			return false;
		}
		if ($('#password').val() == $('#username').val()) {
			notice('edit_notice', img_delete + ' 密码不能和账号相同', true, 5000);
			return false;
		}
		if ($('#password').val().length < 6 || $('#password').val().length > 14) {
			notice('edit_notice', img_delete + ' 密码长度至少6位，最多不超过14位', true, 5000);
			return false;
		}
		if (! CASValidator.password($('#password').val())) {
			notice('edit_notice', img_delete + ' 密码中仅能使用以下字符：a-z、A-Z、0-9 以及下划线“_”', true, 5000);
			return false;
		}
		if ($('#repassword').val() != $('#password').val()) {
			notice('edit_notice', img_delete + ' 两次输入密码不一致', true, 5000);
			return false;
		}
	}
	
	/* 编辑时，校验重置密码 */
	if (store_id && $('#password').val()) {
		if ($('#password').val() == $('#username_edit').val()) {
			notice('edit_notice', img_delete + ' 密码不能和账号相同', true, 5000);
			return false;
		}
		if ($('#password').val().length < 6 || $('#password').val().length > 14) {
			notice('edit_notice', img_delete + ' 密码长度至少6位，最多不超过14位', true, 5000);
			return false;
		}
		if (! CASValidator.password($('#password').val())) {
			notice('edit_notice', img_delete + ' 密码中仅能使用以下字符：a-z、A-Z、0-9 以及下划线“_”', true, 5000);
			return false;
		}
	}
	
	/* 编辑时，若填写了重置密码，则需提示是否确认要重置密码 */
	if (store_id && $('#password').val() && ! confirm('本次更新将连同密码一并更新，确定要继续吗？')) {
		notice('edit_notice', '', false);
		return false;
	}
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack ;
	if (store_id == '' || store_id == 0) {
		saveCallBack = form_save_added;
	} else {
		$("#edit_form").attr("action", "/storeadmin/index/edit");
		saveCallBack = form_save_edited;
	}
	
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
 * 添加成功，返回处理
 */
function form_save_added(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 添加成功!', true, 5000);
        
        // 判断是否返回列表管理
        if (back_listing == true) {
        	history.back(-1);
        }
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}


/**
 * 编辑成功，返回处理
 */
function form_save_edited(data, textStatus) {
    if (data.status === 0) {
        notice('edit_notice', img_done + ' 编辑成功!', true, 5000);
        history.back(-1);
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}