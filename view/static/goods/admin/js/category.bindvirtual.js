$(document).ready(function() {
	/**
	 * 加载属性及属性分组列表
	 */
	refreshListingvirtual();
	/**
	 * 重载已选属性列表的拖拽事件
	 */
	$('#selected_virtual').find('ul').sortable('refresh');
	
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
	 * 选择/移除一个关联属性
	 */
	$("#virtual_listing>tbody").delegate('.select-single', 'change', function(){
		if ($(this).is(':checked')) {
			select_virtual($(this));
		} else {
			if ($(this).attr('data-type') == 'p') {
				remove_virtual($('li#li_virtual_' + $(this).val()));
			}
		}
	});
	
	/**
	 * 移除一个已选择的关联属性
	 */
	$("#selected_virtual").delegate('.fa-remove-virtual', 'click', function(){
		remove_virtual($(this).parent().parent());
	});
	
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = true; form_submit(); break;
		}
	});
});


/**
 * 将属性池中已被选中的备选属性置为已选中状态 - 用于编辑及列表翻页的情况
 */
function reset_virtual_listing() {
	$('#selected_virtual').find('ul>li').each(function(){
		var virtual_id = $(this).attr('virtual_id');
		if ($(this).attr('data-type') == 'p') {
			$('#select_single_p_' + virtual_id).prop('checked', true);
		} else {
			$('#select_single_g_' + virtual_id).prop('checked', true);
		}
	});
}

/**
 * 选择一个关联属性
 */
function select_virtual(obj) {
	var template_selected_virtual = '<li class="b-b m-t-none-reset" id="{#virtual_id_str#}" virtual_id="{#virtual_id#}" data-alias="{#alias#}" data-url="{#url#}" data-type="{#type#}" draggable="true">' + 
        	'<a href="javascript:;">' + 
        	'<i title="{#title#}" class="fa fa-times pull-right m-t-xs fa-remove-virtual"></i>' + 
        	'<i class="fa fa-fw fa-ellipsis-v"></i><font class="virtual-name">{#virtual_name#}</font>' + 
        	'</a></li>';
	
	var obj_tr      = obj.parent().parent();
	var data_type   = obj.attr('data-type');
	var virtual_id = obj.val();
	//var virtual_name = obj_tr.find('td').eq(2).text();
	var virtual_name = obj_tr.find('td > input').eq(1).val();
	var data_url      = obj_tr.find('td > input').eq(2).val();
	if(virtual_name == '') {
		alert('请填写分类别名和转向地址');
		obj.attr('checked',false);
		return false;
		//virtual_name = obj_tr.find('td').eq(2).text();
		//obj_tr.find('td > input').eq(1).val(virtual_name);
	}
//	if (data_url == '') {
//		alert('请填写转向址');
//		obj.attr('checked',false);
//		return false;
//	}
	
	var tsp = template_selected_virtual.replace('{#virtual_id#}', virtual_id)
			.replace('{#type#}', data_type)
			.replace('{#virtual_name#}', virtual_name)
			.replace('{#alias#}', virtual_name)
			.replace('{#url#}', data_url);
	if (data_type == 'p') {
		var tsp = tsp.replace('{#virtual_id_str#}', 'li_virtual_' + virtual_id)
				.replace('{#title#}', '移除该虚拟分类');
	}
	
	/* 将新选择属性加入到已选列表，并重载该列表的拖拽事件 */
	$('#selected_virtual').find('ul').append(tsp).sortable('refresh');
}

/**
 * 移除一个已选择的关联属性
 */
function remove_virtual(obj) {
	if (obj.attr('data-type') == 'p') {
		$('#select_single_p_' + obj.attr('virtual_id')).removeAttr('checked');
	} else {
		$('#select_single_g_' + obj.attr('virtual_id')).removeAttr('checked');
	}
	obj.remove();
}

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
	/* 处理属性 */
	var virtual_str = '';
	var sp = new Array();
	$('#selected_virtual').find('ul>li').each(function(k){
		var alias = $(this).attr('data-alias');
		var url   = $(this).attr('data-url');
		var virtual_id = $(this).attr('virtual_id');
		sp[k] = virtual_id + '|' + alias + '|' + url;
	});
	if (sp.length > 0) {
		virtual_str = sp.join(',');
	}
	$('input[name=virtual_str]').val(virtual_str);
	
	$(".input-submit").attr('disabled', true);
	
	var saveCallBack = form_save_edited;
	var options = {
            dataType:'json',
            timeout:60000,
            success:saveCallBack
    };
    $("#edit_form").ajaxSubmit(options);
    return false;
}

/**
 * 编辑成功，返回处理
 */
function form_save_edited(data, textStatus) {
    if (data.status === 0) {
    	notice('edit_notice', img_done + ' 编辑成功!', true, 5000);
        form_cancel();
    } else {
    	notice('edit_notice', img_delete + " " + data.error, true, 5000);
    }
    $(".input-submit").removeAttr('disabled');
}

/**
 * datagrid 加载属性列表
 */
function refreshListingvirtual() {
	/* fuelux datagrid */
	var DataGridDataSource = function (options) {
	    this._formatter = options.formatter;
	    this._columns = options.columns;
	    this._delay = options.delay;
	};
	
	DataGridDataSource.prototype = {
	    columns: function () {
	        return this._columns;
	    },
	    data: function (options, callback) {
	        var url = '/goodsadmin/category/getCategoryParent';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    parent_id: category_id,
	                    status: 1
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.category;
                    if (! data) {
                    	return false;
                    }

                    var count=response.data.count;//设置data.total
                    // PAGING
                    var startIndex = options.pageIndex * options.pageSize;
                    var endIndex = startIndex + options.pageSize;
                    var end = (endIndex > count) ? count : endIndex;
                    var pages = Math.ceil(count / options.pageSize);
                    var page = options.pageIndex + 1;
                    var start = startIndex + 1;

                    if (self._formatter) self._formatter(data);

                    callback({ data: data, start: start, end: end, count: count, pages: pages, page: page });
                    
                    // 将属性池中已被选中的备选属性置为已选中状态 - 用于编辑的情况
                    reset_virtual_listing();
                }).fail(function (e) {

                });
	        }, self._delay);
	    }
	};
	
	$('#virtual_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
	            {
	            	property: 'checkbox',
	                label: ''
	            },
	            {
	            	property: 'category_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'category_name',
	            	label: '分类名称',
	            	sortable: false
	            },
	            {
	            	property: 'alias',
	            	label: '分类别名',
	            	sortable: false
	            },
	            {
	            	property: 'url',
	            	label: '地址',
	            	sortable: false
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.checkbox = '<input type="checkbox" name="post[]" id="select_single_p_' + item.category_id + '" class="select-single" data-type="p" value="' + item.category_id + '">';
	            	item.alias    = '<input type="text" class="input-sm input-s form-control" value="' + item.alias + '" />';
	            	item.url      = '<input type="text" class="input-sm input-s form-control" value="' + item.url + '" />';
	            	
	            	
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}
