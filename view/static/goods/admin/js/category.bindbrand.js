$(document).ready(function() {
	/**
	 * 加载品牌列表
	 */
	refreshListing();
	
	/**
     * 刷新搜索
     */
    $(".action-refresh,#action_search").on('click',function(){
		$('#brand_listing').datagrid('reload');
    });
    
	/**
	 * 关键字搜索 - 支持回车
	 */
	$('input[name=key]').on('keypress', function (event) {
	    if (event.which == '13') {
    		$('#brand_listing').datagrid("reload");
	        return false;
	    }
	});
	
	/**
	 * 重载已选属性列表的拖拽事件
	 */
	$('#selected_brand').find('ul').sortable('refresh');
	
	/**
	 * 选择/移除一个关联属性
	 */
	$("#brand_listing>tbody").delegate('.select-single', 'change', function(){
		if ($(this).is(':checked')) {
			select_brand($(this));
		} else {
			remove_brand($('li#li_brand_' + $(this).val()));
		}
	});
	
	/**
	 * 移除一个已选择的关联属性
	 */
	$("#selected_brand").delegate('.fa-remove-brand', 'click', function(){
		remove_brand($(this).parent().parent());
	});
	
	/**
	 * 提交按钮处理
	 */
	$(".input-submit").click(function(){
		var submit_id = $(this).attr("id");
		switch (submit_id) {
			case 'submit_cancel' : form_cancel(); break;
			case 'submit_save_back' : back_listing = true; form_submit(); break;
			case 'submit_save_continue' : back_listing = false; form_submit(); break;
		}
	});
});


/**
 * 将属性池中已被选中的备选属性置为已选中状态 - 用于编辑及列表翻页的情况
 */
function reset_brand_listing() {
	$('#selected_brand').find('ul>li').each(function(){
		var brand_id = $(this).attr('brand_id');
		$('#select_single_' + brand_id).prop('checked', true);
	});
}

/**
 * 选择一个关联属性
 */
function select_brand(obj) {
	var template_selected_brand = '<li class="b-b m-t-none-reset" id="li_brand_{#brand_id#}" brand_id="{#brand_id#}" draggable="true">' + 
        	'<a href="javascript:;">' + 
        	'<i title="移除该品牌" class="fa fa-times pull-right m-t-xs fa-remove-brand"></i>' + 
        	'<i class="fa fa-fw fa-ellipsis-v"></i><font class="brand-name">{#brand_name#}</font>' + 
        	'</a></li>';
	
	var obj_tr = obj.parent().parent();
	var brand_id = obj.val();
	var brand_name = obj_tr.find('td').eq(3).text();
	
	var tsb = template_selected_brand.replace(/{#brand_id#}/g, brand_id)
			.replace('{#brand_name#}', brand_name);
	
	/* 将新选择属性加入到已选列表，并重载该列表的拖拽事件 */
	$('#selected_brand').find('ul').append(tsb).sortable('refresh');
}

/**
 * 移除一个已选择的关联属性
 */
function remove_brand(obj) {
	$('#select_single_' + obj.attr('brand_id')).removeAttr('checked');
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
	/* 处理所选品牌 */
	var brand_ids = '';
	var sb = new Array();
	$('#selected_brand').find('ul>li').each(function(k){
		var brand_id = $(this).attr('brand_id');
		sb[k] = brand_id
	});
	if (sb.length > 0) {
		brand_ids = sb.join(',');
	}
	$('input[name=brand_ids]').val(brand_ids);
	
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
 * datagrid 加载列表
 */
function refreshListing() {
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
	        var url = '/goodsadmin/brand';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.brands;
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
                    
                    // 将品牌池中已被选中的备选品牌置为已选中状态 - 用于编辑的情况
                    reset_brand_listing();
                }).fail(function (e) {

                });
	        }, self._delay);
	    }
	};
	
	$('#brand_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
	            {
	                property: 'checkbox',
	                label: ''
	            },
	            {
	                property: 'brand_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'brand_logo',
	            	label: '标志',
	            	sortable: true
	            },
	            {
	                property: 'brand_name',
	                label: '品牌名称',
	                sortable: false
	            },
	            {
	                property: 'brand_url',
	                label: '链接网址',
	                sortable: false
	            },
	            {
	                property: 'ctime',
	                label: '创建时间',
	                sortable: true
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.checkbox = '<input type="checkbox" name="post[]" id="select_single_' + item.brand_id + '" class="select-single" value="' + item.brand_id + '">';
	            	
	            	if (item.brand_logo) {
	            		item.brand_logo = '<a class="thumb m-l" href="javascript:;">' + 
		            			'<img src="/upload' + item.brand_logo + '">' + 
		            			'</a>';
	            	} else {
	            		item.brand_logo = '';
	            	}
	            	
	            	item.operate = '<a href="/goodsadmin/brand/edit/?brand_id='+item.brand_id+'" data-toggle="ajaxModal" class="operate-edit" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
	            			'<a href="javascript:;" class="operate-delete" id="brand_' + item.brand_id + '" brand_id="' + item.brand_id + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}