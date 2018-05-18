/**
 * 定义一些常量
 */
var property_specs; // 待选规格项
var property_specs_added = new Array(); // 已添加的规格信息
var is_category_changed = 0; // 添加/编辑商品时，是否改变了分类。1：是；0：否；

$(document).ready(function() {
	content_id = $('#content_id').val();
	/**
	 * 初始化品牌、扩展属性、规格等信息
	 */
	load_brand();
	load_property();
	if ($('#is_spec').is(':checked')) {
		load_spec_option();
	}
	$('input[name=category_id_last]:last').val($('#category').val());
	
	/**
	 * 动态加载分类相关联的品牌、扩展属性和规格信息
	 */
	$('#category').change(function(){
		is_category_changed = 1;
		
		/* 判断是否正在编辑规格，若是，则需弹出提示其是否切换分类，若切换分类，当前编辑的规格信息将丢失 */
		if ($('#spec_list').html()) {
			var str_confirm = "您正在编辑规格，切换分类后，当前编辑的规格信息将丢失。确认要切换分类吗？";
			if (! confirm(str_confirm)) {
				$(this).val($('input[name=category_id_last]:last').val());
				return false;
			}
		}
		
		/* 加载品牌 */
		load_brand();
		
		/* 加载扩展属性 */
		load_property();
		
		/* 判断开启规格的开关是否打开，若已打开，则需重新加载规格 */
		$('#spec_list').empty();
		property_specs_added = [];
		load_spec_option();
		
		/* 初始化编辑规格栏目的样式 */
		$('#btn_spec_reselect').trigger('click');
		$('#spec_option_list').find('input[type=checkbox]').prop('checked', false);
		
		/* 记录分类的最终状态 */
		$('input[name=category_id_last]:last').val($(this).val());
	});
	
	/**
	 * 开启/关闭规格
	 */
	$('#is_spec').change(function(){
		/* 加载规格项 */
		load_spec_option();
	});
	
	/**
	 * 选择规格项 - 选好了
	 */
	$('#btn_spec_select_ok').click(function(){
		if ($('.spec-option:checked').size() < 1) {
			alert('请至少选择一个规格项');
			return false;
		}
		
		$(this).attr('disabled', true);
		$('#edit_base_spec_option').find(':checkbox').attr('disabled', true);
		$('#btn_spec_add').removeAttr('disabled');
		$('#btn_spec_reselect').show();
	});
	
	/**
	 * 选择规格项 - 重新选择
	 */
	$('#btn_spec_reselect').click(function(){
		/* 若已添加有规格，则进行提示 */
		if ($('#spec_list').html()) {
			var str_confirm = "您正在编辑规格，重选规格项之后，当前编辑的规格信息将丢失。确认要重选规格项吗？";
			if (! confirm(str_confirm)) {
				return false;
			}
		}
		
		/* 重选规格项的重置操作 */
		$('#spec_list').empty();
		$('#edit_base_spec_option').find(':checkbox').removeAttr('disabled');
		$('#btn_spec_select_ok').removeAttr('disabled');
		$('#btn_spec_add').attr('disabled', true);
		$('#btn_spec_reselect').hide();
	});
	
	/**
	 * 选择规格项 - 添加一组规格
	 */
	$('#btn_spec_add').click(function(){
		spec_add();
	});
	
	/**
	 * 选择规格项 - 编辑一组规格
	 */
	$('#spec_list').delegate('.operate-edit-spec', 'click', function(){
		spec_edit($(this).parent().parent());
	});
	
	/**
	 * 添加/编辑一组规格 - 提交
	 */
	$('#modal').delegate('#submit_save_spec', 'click', function(){
		if ($('input[name=spec_is_edit]:last').val() == 1) {
			spec_edit_submit();
		} else {
			spec_add_submit();
		}
	});
	
	/**
	 * 选择规格项 - 移除一组规格
	 */
	var edit_html = '';
	$('#spec_list').delegate('.operate-remove-spec', 'click', function(){
		var spec_str = $(this).next().val();
		var spec_arr = spec_str.split('"content_id":""');
		
		if(spec_arr.length == 2){spec_remove($(this).parent().parent());return false;}
		
		edit_html = $(this).prev();
		$(this).prev().remove();//隐藏编辑按钮
		spec_str = spec_str.replace('"is_del":"0"','"is_del":"1"');
		$(this).next().val(spec_str);//更新隐藏值is_del
		$(this).attr('title','恢复');
		$(this).attr('class','active operate-restore-spec');
		$(this).parent().parent().attr('class','text-danger');//把删除行里的文字内容设置样式
		$(this).html('<i class="fa fa-rotate-left"></i>');
		
		//spec_remove($(this).parent().parent());
	});
	/**
	 * 选择规格项 - 恢复一组规格
	 */
	$('#spec_list').delegate('.operate-restore-spec', 'click', function(){
		$(this).parent().prepend(edit_html);
		var spec_str = $(this).next().val();
		spec_str = spec_str.replace('"is_del":"1"','"is_del":"0"');
		$(this).next().val(spec_str);//更新隐藏值is_del
		
		$(this).attr('title','删除');
		$(this).attr('class','operate-remove-spec');
		$(this).parent().parent().attr('class','');//把删除行里的文字内容设置样式
		$(this).html('<i class="fa fa-times text-danger text"></i>');
	});
});


/**
 * 动态加载分类相关联的品牌信息
 */
function load_brand() {
	var category_id = $('#category').val();
	var brand_id_now = $("select[name=brand_id]").attr('brand_id');
	var str_option = '';
	
	if (category_id == 0 || category_id == '') {
		return false;
	}

	$.ajax({
    	type:'post',
        url:'/goodsadmin/index/getBrandByCategoryid',
        data:'category_id=' + category_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			/* 清空现有品牌信息 */
    			$("select[name=brand_id]").find("option:gt(0)").remove();
    			
    			/* 加载新的品牌信息 */
    			var d = data.data.brands;
    			if (d != null && d.length > 0) {
	    			for (var i = 0; i < d.length; i++) {
	    				var selected = d[i]['brand_id'] == brand_id_now ? 'selected' : '';
	    				str_option += '<option value="' + d[i]['brand_id'] + '" ' + selected + '>' + d[i]['brand_name'] + '</option>';
	    			}
	    			$("select[name=brand_id]").append(str_option);
    			}
    		}
    		return false;
    	}
    });
}

/**
 * 动态加载分类相关联的属性信息
 */
function load_property() {
	var category_id = $('#category').val();
	var property_now = ',' + $("#edit_base_property").attr('property') + ',';
	
	if (category_id == 0 || category_id == '') {
		return false;
	}
	
	/* 移除旧的扩展属性元素 */
	$('#edit_base_property>.panel-body:first').empty();
	
	/* 定义扩展属性模板 */
	var template_property = '<div class="form-group m-b-xs">' + 
        	'<label class="col-sm-2 control-label">{#label_name#}</label>' + 
        	'<div class="col-sm-6">' + 
            '<select name="property[]" class="form-control">' + 
            '{#options#}' + 
            '</select>' + 
            '</div>' + 
            '</div>' + 
            '<div class="line line-dashed line-sm pull-in"></div>';
	
	/* 动态获取扩展属性数据 */
	$.ajax({
    	type:'post',
        url:'/goodsadmin/index/getPropertyByCategoryid',
        data:'category_id=' + category_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status != 0) {
    			$('#edit_base_property').hide();
				return false;
    		}
    		
			var d = data.data.properties;
			$('#edit_base_property').show();
			
			/* 若无扩展属性，则隐藏扩展属性编辑区 */
			if (d == null || d.length == 0) {
				$('#edit_base_property').hide();
				return false;
			}
			
			/* 加载新的扩展属性元素 */
			$(d).each(function(){
				// 格式化模板
				var template_property_format = template_property.replace(/{#label_name#}/g, this.label_name);
				
				var v = this.values;
				var str_option = '';
				$(v).each(function(){
					var str_property = this.property_id + '_' + this.property_value_id;
					var str_property_temp = ',' + str_property + ',';
					var selected = property_now.indexOf(str_property_temp) > -1 ? 'selected' : '';
					str_option += '<option value="' + str_property + '" ' + selected + '>' + this.property_value + '</option>';
				});
				
				template_property_format = template_property_format.replace(/{#options#}/g, str_option);
				
				// 加载格式化后的模板
				$('#edit_base_property>.panel-body:first').append(template_property_format);
			});
			
			/* 处理最后一条分割线 */
			$('#edit_base_property').find('.line-dashed:last').remove();
    			
    		return false;
    	}
    });
	
	return false;
}

/**
 * 加载规格项
 */
function load_spec_option() {
	var category_id = $('#category').val();
	
	if (category_id == 0 || category_id == '') {
		return false;
	}
	
	/* 显示或隐藏规格编辑区 */
	if ($('#is_spec').is(':checked')) {
		$('#edit_base_spec').show();
		$('#property_base').hide();
	} else {
		$('#edit_base_spec').hide();
		$('#property_base').show();
		return false;
	}
	
	/* 若已加载，则无需重复加载 */
	if ($('#spec_list').html()) {
		return false;
	}
	
	/* 编辑状态时的基本元素属性处理 */
	if (content_id && is_category_changed == 0) {
		$('#btn_spec_select_ok').attr('disabled', true);
		$('#btn_spec_reselect').show();
		$('#edit_base_spec_option').find(':checkbox').attr('disabled', true);
		$('#btn_spec_add').removeAttr('disabled');
	}
	
	/* 动态获取规格项数据 */
	$.ajax({
    	type:'post',
        url:'/goodsadmin/index/getPropertyByCategoryid',
        data:'category_id=' + category_id + '&is_spec=1',
        dataType:'json',
        timeout:60000,
        success:function(data){
        	/* 若无规格项，则进行提示 */
    		if (data.status != 0 || data.data == null) {
    			$('#edit_base_spec_option').hide();
    			$('#edit_base_spec>.panel-body:last').show();
				return false;
    		}
    		
    		property_specs = data.data.properties;
    		
    		$('#edit_base_spec_option').show();
    		$('#edit_base_spec>.panel-body:last').hide();
			
    		/* 编辑状态时，处理已选商品规格 */
    		var spec_property_id = $('input[name=spec_property_id]:last').val();
    		var spec_property_ids = new Array();
    		if (spec_property_id) {
    			spec_property_ids = spec_property_id.split(',');
    		}
    		
			/* 加载规格项元素 */
    		var tbody_format = '';
			$(property_specs).each(function(){
				// 判断是否选中
				var spec_checked = '';
				if ($.inArray(this.property_id, spec_property_ids) > -1 && is_category_changed == 0) {
					spec_checked = 'checked';
				}
				
				// 格式化模板
				tbody_format += '<tr>' + 
						'<td><input type="checkbox" class="spec-option select-single" value="' + this.property_id + '" ' + spec_checked + ' /></td>' + 
						'<td>' + this.label_name + '</td>' + 
						'<td>';
				
				var v = this.values;
				var option_arr = new Array();
				$(v).each(function(k){
					option_arr[k] = this.property_value;
				});
				var str_option = option_arr.join('、');
				
				tbody_format += str_option + '</td></tr>';
			});
			$('#spec_option_list>tbody').html(tbody_format);
			
			/* 编辑状态时的基本元素属性处理 */
			if (content_id && is_category_changed == 0) {
				$('#edit_base_spec_option').find(':checkbox').attr('disabled', true);
			}
			
			/* 编辑状态时，加载已选规格信息 */
			if (content_id && content_specification && is_category_changed == 0) {
				$(content_specification).each(function(){
					spec_add($(this));
					spec_add_submit();
				});
			}
			
    		return false;
    	}
    });
}

/**
 * 添加一组规格
 * 
 * @param obj spec_obj 编辑状态时，需要填充表单的单条规格信息
 */
function spec_add(spec_obj) {
	/* 定义规格模板 */
	var template_spec = '<div class="form-group m-b-xs">' + 
	    	'<label class="col-sm-2 control-label">{#label_name#}</label>' + 
	    	'<div class="col-sm-9">' + 
	        '<select name="property_id" property_id="{#property_id#}" class="form-control">' + 
	        '{#options#}' + 
	        '</select>' + 
	        '</div>' + 
	        '</div>';
	
	/* 重置表单 */
	$('#modal').find('input:text').val('');
	$('#modal').find('select').val('');
	$('input[name=spec_is_shelf]:first').prop('checked', true);
	
	/* 编辑状态时，将规格信息填充到表单 */
	var spec_property = new Array();
	if (content_id && spec_obj) {
		$('#spec_content_id').val(spec_obj.prop('content_id'));
		$('#spec_is_del').val(spec_obj.prop('is_del'));
		$('#spec_sku').val(spec_obj.prop('sku'));
		$('#spec_stock').val(spec_obj.prop('stock'));
		$('#spec_price').val(spec_obj.prop('price'));
		$('#spec_price_market').val(spec_obj.prop('price_market'));
		$('#spec_price_cost').val(spec_obj.prop('price_cost'));
		$('#spec_weight').val(spec_obj.prop('weight'));
		spec_obj.prop('is_shelf') == 1 ? $('input[name=spec_is_shelf]:first').prop('checked', true) : $('input[name=spec_is_shelf]:last').prop('checked', true);
		
		// 处理规格数据
		var spec_property_temp = spec_obj.prop('property_related').split(',');
		if (spec_property_temp.length > 0) {
			$(spec_property_temp).each(function(k){
				var spec_property_temp2 = spec_property_temp[k].split(':');
				spec_property[spec_property_temp2[0]] = spec_property_temp2[1];
			});
		}
	}
	
	/* 重置表单为添加状态 */
	$('input[name=spec_is_edit]:last').val(0);
	
	/* 统计选择的规格项 */
	var spec_selected = new Array();
	$('.spec-option:checked').each(function(k){
		spec_selected[k] = $(this).val();
	});
	
	/* 加载规格元素 */
	var spec_format = '';
	$(property_specs).each(function(){
		if (! spec_selected.in_array(this.property_id)) {
			return true;
		}
		
		// 格式化模板
		var spec_format_temp = template_spec.replace(/{#label_name#}/g, this.label_name);
		spec_format_temp = spec_format_temp.replace(/{#property_id#}/g, this.property_id);
		
		var v = this.values;
		var str_option = '';
		$(v).each(function(){
			// 处理默认选中关系 - 用于编辑状态时
			var selected_spec = '';
			if (spec_property[this.property_id] == this.property_value_id) {
				selected_spec = 'selected';
			}
			str_option += '<option value="' + this.property_value_id + '" ' + selected_spec + '>' + this.property_value + '</option>';
		});
		
		spec_format_temp = spec_format_temp.replace(/{#options#}/g, str_option);
		
		spec_format += spec_format_temp;
	});
	
	// 加载格式化后的模板
	$('#modal_form_spec').html(spec_format);
}

/**
 * 添加一组规格 - 提交
 */
function spec_add_submit() {
	/* 若是首次添加，则额外加上规格表头 */
	if (! $('#spec_list').html()) {
		/* 定义规格表头模板 */
		var template_head = '<thead>' + 
		    	'<tr>' + 
			    '<th>SKU</th>' + 
			    '{#str_spec_head#}' + 
			    '<th>库存</th>' + 
			    '<th>销售价</th>' + 
			    '<th>市场价</th>' + 
			    '<th>成本价</th>' + 
			    '<th>重量(克)</th>' + 
			    '<th>上架</th>' + 
			    '<th>操作</th>' + 
				'</tr>' + 
				'</thead>' + 
				'<tbody></tbody>';
		
		/* 统计选择的规格项 */
		var spec_selected = new Array();
		$('.spec-option:checked').each(function(k){
			spec_selected[k] = $(this).val();
		});
		
		/* 加载规格表头元素 */
		var spec_head_format = '';
		$(property_specs).each(function(){
			if (! spec_selected.in_array(this.property_id)) {
				return true;
			}
			
			spec_head_format += "<th>" + this.label_name + "</th>";
		});
		var template_head_format = template_head.replace(/{#str_spec_head#}/g, spec_head_format);
		$('#spec_list').html(template_head_format);
	}
	/* 若是首次添加，则额外加上规格表头 @end */
	
	var obj_modal_spec = $('#modal');
	
	/* 获取规格表单值 */
	var spec_content_id = obj_modal_spec.find('#spec_content_id').val();
	var spec_is_del = obj_modal_spec.find('#spec_is_del').val();
	var spec_sku = obj_modal_spec.find('#spec_sku').val();
	var spec_stock = obj_modal_spec.find('#spec_stock').val();
	var spec_price = obj_modal_spec.find('#spec_price').val();
	var spec_price_market = obj_modal_spec.find('#spec_price_market').val();
	var spec_price_cost = obj_modal_spec.find('#spec_price_cost').val();
	var spec_weight = obj_modal_spec.find('#spec_weight').val();
	var spec_is_shelf = obj_modal_spec.find('input[name=spec_is_shelf]:checked').val();
	
	// 处理是否上架
	var str_spec_shelf = '<i class="fa fa-ban text-danger"></i>';
	if (spec_is_shelf == 1) {
		str_spec_shelf = '<i class="fa fa-check text-success"></i>';
	}
	
	// 处理规格
	var property_related_arr = new Array();
	$('#modal_form_spec').find('select').each(function(k){
		property_related_arr[k] = $(this).attr('property_id') + ':' + $(this).val();
	});
	var property_related = property_related_arr.join(',');
	/* 获取规格表单值 @end */

	/* 校验 */
	if (! spec_sku) {
		alert('sku必须填写');
		return false;
	}
	
	if (property_specs_added.length > 0) {
		var check_spec_sku = true;
		var check_spec = true;
		$(property_specs_added).each(function(){
			/* SKU唯一性 */
			if (this.sku == spec_sku) {
				check_spec_sku = false;
				return false;
			}
			
			/* 规格配置唯一性 */
			if (this.property_related == property_related) {
				check_spec = false;
				return false;
			}
		});
		
		if (check_spec_sku == false) {
			alert('该SKU已被占用，请重新填写');
			return false;
		}
		
		if (check_spec == false) {
			alert('该组规格已配置，无需重复配置');
			return false;
		}
	}
	/* 校验 @end */
	
	/* 定义规格表内容模板 */
	var template_tbody = '<tr id="tr_spec_' + spec_sku + '">' + 
		    '<td>' + spec_sku + '</td>' + 
		    '{#str_spec_tbody#}' + 
		    '<td>' + spec_stock + '</td>' + 
		    '<td>' + spec_price + '</td>' + 
		    '<td>' + spec_price_market + '</td>' + 
		    '<td>' + spec_price_cost + '</td>' + 
		    '<td>' + spec_weight + '</td>' + 
		    '<td>' + str_spec_shelf + '</td>' + 
		    '<td>' + 
		    '<a href="javascript:;" class="operate-edit-spec" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
			if(main_content_id != spec_content_id || spec_content_id == ''){
		    	template_tbody += '<a href="javascript:;" class="operate-remove-spec" title="删除" spec_id="'+spec_content_id+'"><i class="fa fa-times text-danger text"></i></a>'; 
		    }
            template_tbody += '<input type="hidden" name="specs[]" class="specs-json" value="{#spec_json#}" />' + 
		    '</td>' + 
			'</tr>';
	
	
	
	/* 处理规格表内容元素 */
	var spec_tbody_format = '';
	$('#modal_form_spec').find('select').each(function(k){
		spec_tbody_format += "<td>" + $(this).find('option:selected').text() + "</td>";
	});
	var template_tbody_format = template_tbody.replace(/{#str_spec_tbody#}/g, spec_tbody_format);
	
	/* 加载到规格列表 */
	$('#spec_list').append(template_tbody_format);
	
	/* 将规格信息以 json 格式存放到隐藏字段，待发布商品提交时使用 */
	var spec_json = '{"content_id":"'+spec_content_id+'","is_del":"'+spec_is_del+'","property_related":"' + property_related + 
			'","sku":"' + spec_sku + 
			'","weight":"' + spec_weight + 
			'","price":"' + spec_price + 
			'","price_market":"' + spec_price_market + 
			'","price_cost":"' + spec_price_cost + 
			'","stock":"' + spec_stock + 
			'","is_shelf":"' + spec_is_shelf + 
			'"}';
	$('input.specs-json:last').val(spec_json);
	
	/* 将该次数据保存至全局变量，待添加或编辑规格时校验使用 */
	var k_new = parseInt(property_specs_added.length);
	property_specs_added[k_new] = new Array();
	property_specs_added[k_new]['content_id'] = spec_content_id;
	property_specs_added[k_new]['is_del'] = spec_is_del;
	property_specs_added[k_new]['sku'] = spec_sku;
	property_specs_added[k_new]['stock'] = spec_stock;
	property_specs_added[k_new]['price'] = spec_price;
	property_specs_added[k_new]['price_market'] = spec_price_market;
	property_specs_added[k_new]['price_cost'] = spec_price_cost;
	property_specs_added[k_new]['weight'] = spec_weight;
	property_specs_added[k_new]['is_shelf'] = spec_is_shelf;
	property_specs_added[k_new]['property_related'] = property_related;
	
	/* 关闭层 */
	$('#modal').modal('hide');
}

/**
 * 编辑一组规格
 */
function spec_edit(obj) {
	/* 编辑状态下取值 */
	var spec_sku = obj.attr('id').substr(8);
	
	var status_edit = false; // 标记编辑状态是否正常
	var edit_spec = '';
	$(property_specs_added).each(function(){
		if (this.sku == spec_sku) {
			status_edit = true;
			edit_spec = this;
			return false;
		}
	});
	/* 编辑状态下取值 @end */
	
	/* 编辑状态不正常 */
	if (status_edit == false) {
		alert('发生了一点意外哦，可能需要刷新后重新编辑呢');
		return false;
	}
	
	var spec_content_id = edit_spec.content_id;
	var spec_is_del = edit_spec.is_del;
	var spec_stock = edit_spec.stock;
	var spec_price = edit_spec.price;
	var spec_price_market = edit_spec.price_market;
	var spec_price_cost = edit_spec.price_cost;
	var spec_weight = edit_spec.weight;
	var spec_is_shelf = edit_spec.is_shelf;
	var spec_property_related = edit_spec.property_related;
	
	/* 将所取值赋值到规格表单 */
	var obj_modal_spec = $('#modal');
	obj_modal_spec.find('#spec_sku').val(spec_sku);
	obj_modal_spec.find('#spec_content_id').val(spec_content_id);
	obj_modal_spec.find('#spec_is_del').val(spec_is_del);
	obj_modal_spec.find('#spec_stock').val(spec_stock);
	obj_modal_spec.find('#spec_price').val(spec_price);
	obj_modal_spec.find('#spec_price_market').val(spec_price_market);
	obj_modal_spec.find('#spec_price_cost').val(spec_price_cost);
	obj_modal_spec.find('#spec_weight').val(spec_weight);
	obj_modal_spec.find('input[name=spec_is_shelf][value=' + spec_is_shelf + ']').prop('checked', true);
	
	// 赋值时处理规格
	var property_related_arr = new Array();
	$(spec_property_related.split(',')).each(function(){
		var property_related_arr_temp = this.split(':');
		property_related_arr[property_related_arr_temp[0]] = property_related_arr_temp[1];
	});
	$('#modal_form_spec').find('select').each(function(){
		var property_id = $(this).attr('property_id');
		$(this).val(property_related_arr[property_id]);
	});
	/* 将所取值赋值到规格表单 @end */
	
	/* 重置表单为添加状态 */
	$('input[name=spec_is_edit]:last').val(1);
	
	/* 记录当前正在编辑的SKU */
	$('input[name=spec_sku_edit]:last').val(spec_sku);
	
	/* 显示层 */
	$('#modal').modal('show');
}

/**
 * 编辑一组规格 - 提交
 */
function spec_edit_submit() {
	var spec_sku_edit = $('input[name=spec_sku_edit]:last').val();
	var obj_modal_spec = $('#modal');
	
	/* 获取规格表单值 */
	var spec_sku = obj_modal_spec.find('#spec_sku').val();
	var spec_stock = obj_modal_spec.find('#spec_stock').val();
	var spec_price = obj_modal_spec.find('#spec_price').val();
	var spec_price_market = obj_modal_spec.find('#spec_price_market').val();
	var spec_price_cost = obj_modal_spec.find('#spec_price_cost').val();
	var spec_weight = obj_modal_spec.find('#spec_weight').val();
	var spec_is_shelf = obj_modal_spec.find('input[name=spec_is_shelf]:checked').val();
	var content_id = obj_modal_spec.find('#spec_content_id').val();
	var spec_is_del = obj_modal_spec.find('#spec_is_del').val();
	
	// 处理是否上架
	var str_spec_shelf = '<i class="fa fa-ban text-danger"></i>';
	if (spec_is_shelf == 1) {
		str_spec_shelf = '<i class="fa fa-check text-success"></i>';
	}
	
	// 处理规格
	var property_related_arr = new Array();
	$('#modal_form_spec').find('select').each(function(k){
		property_related_arr[k] = $(this).attr('property_id') + ':' + $(this).val();
	});
	var property_related = property_related_arr.join(',');
	/* 获取规格表单值 @end */

	/* 校验 */
	if (! spec_sku) {
		alert('SKU必须填写');
		return false;
	}
	if (property_specs_added.length > 0) {
		var check_spec_sku = true;
		var check_spec = true;
		$(property_specs_added).each(function(){
			/* 跳过自身 */
			if (this.sku == spec_sku_edit) {
				return false;
			}
			
			/* SKU唯一性 */
			if (this.sku == spec_sku) {
				check_spec_sku = false;
				return false;
			}
			
			/* 规格配置唯一性 */
			if (this.property_related == property_related) {
				check_spec = false;
				return false;
			}
		});
		
		if (check_spec_sku == false) {
			alert('该SKU已被占用，请重新填写');
			return false;
		}
		
		if (check_spec == false) {
			alert('该组规格已配置，无需重复配置');
			return false;
		}
	}
	/* 校验 @end */
	
	/* 更新行 ID */
	var obj_tr = $('#tr_spec_' + spec_sku_edit);
	obj_tr.attr('id', 'tr_spec_' + spec_sku);
	
	/* 更新规格表内容规格项 */
	var spec_count = 0;
	$('#modal_form_spec').find('select').each(function(){
		spec_count++;
		obj_tr.find('td').eq(spec_count).text($(this).find('option:selected').text());
	});
	
	/* 更新规格表内容常规项 */
	obj_tr.find('td:first').text(spec_sku);
	obj_tr.find('td').eq(parseInt(spec_count) + 1).text(spec_stock);
	obj_tr.find('td').eq(parseInt(spec_count) + 2).text(spec_price);
	obj_tr.find('td').eq(parseInt(spec_count) + 3).text(spec_price_market);
	obj_tr.find('td').eq(parseInt(spec_count) + 4).text(spec_price_cost);
	obj_tr.find('td').eq(parseInt(spec_count) + 5).text(spec_weight);
	obj_tr.find('td').eq(parseInt(spec_count) + 6).html(str_spec_shelf);
	
	/* 将规格信息以 json 格式存放到隐藏字段，待发布商品提交时使用 */
	var spec_json = '{"content_id":"' + content_id + '","is_del":"'+spec_is_del+'","property_related":"' + property_related + 
			'","sku":"' + spec_sku + 
			'","weight":"' + spec_weight + 
			'","price":"' + spec_price + 
			'","price_market":"' + spec_price_market + 
			'","price_cost":"' + spec_price_cost + 
			'","stock":"' + spec_stock + 
			'","is_shelf":"' + spec_is_shelf + 
			'"}';
	$('tr#tr_spec_' + spec_sku).find('input.specs-json:last').val(spec_json);
	
	/* 将该次数据保存至全局变量，待添加或编辑规格时校验使用 */
	$(property_specs_added).each(function(){
		if (this.sku == spec_sku_edit) {
			this.sku = spec_sku;
			this.stock = spec_stock;
			this.price = spec_price;
			this.price_market = spec_price_market;
			this.price_cost = spec_price_cost;
			this.weight = spec_weight;
			this.is_shelf = spec_is_shelf;
			this.property_related = property_related;
		}
	});
	
	/* 关闭层 */
	$('#modal').modal('hide');
}

/**
 * 移除一组规格
 */
function spec_remove(obj) {
	/* 取得SKU */
	var spec_sku = obj.attr('id').substr(8);
	
	/* 从全局变量中删除该规格 */
	$(property_specs_added).each(function(k){
		if (this.sku == spec_sku) {
			property_specs_added.splice(k, 1);
		}
	});
	
	/* 移除该行 */
	obj.remove();
}
