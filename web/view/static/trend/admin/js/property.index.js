$(document).ready(function() {
	refreshListing();
	
    /**
     * 刷新搜索
     */
    $(".action-refresh,#action_search").on('click',function(){
    	$('#content_listing').datagrid('reload');
    	return false;
    });
    
    /**
	 * 关键字搜索 - 支持回车
	 */
	$('input[name=key]').on('keypress', function (event) {
	    if (event.which == '13') {
	        $('#content_listing').datagrid("reload");
	        return false;
	    }
	});
    
    /**
     * 单个删除
     */
	$("#content_listing").delegate('.operate-delete', 'click', function(){
		var property_id = $(this).attr("property_id");
		doDeleteProperty(property_id);
	});
    
    /**
     * 批量删除分类
     */
	$('#action_delete').on('click', function(){
		var id_arr = new Array();
		var i = 0;
		$('#content_listing').find('.select-single').each(function(){
			if ($(this).is(':checked')) {
				id_arr[i] = $(this).val();
				i++;
			}
		});
		var id = id_arr.join(',');
		
		if (! id) {
			return false;
		}
		
		doDeleteProperty(id);
	});
	/**
     * 确保标题栏不变形
     */
    $("#content_listing thead th").attr('nowrap','nowrap');
    
	/**
     * 显示
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	if($(this).attr('property_id')){
	    	var property_id = $(this).attr('property_id');
	    	publishProperty(property_id);
	    	return false;
    	}
    });
    
	/**
     * 规格
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	if($(this).attr('property_a_id')){
	    	var property_id = $(this).attr('property_a_id');
	    	specProperty(property_id);
	    	return false;
    	}
    });
});

/**
 * 发布
 */
function publishProperty(id) {
	$.ajax({
    	type:'post',
        url:'/trendadmin/property/publish',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if ($('#publish_' + id).prop('checked')) {
    				$('#publish_' + id).prop('checked', false);
    			} else {
    				$('#publish_' + id).prop('checked', true);
    			}
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
}

/**
 * 开启规格
 */
function specProperty(id) {
	$.ajax({
    	type:'post',
        url:'/trendadmin/property/spec',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if ($('#spec_' + id).prop('checked')) {
    				$('#spec_' + id).prop('checked', false);
    			} else {
    				$('#spec_' + id).prop('checked', true);
    			}
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
}

/**
 * 删除
 */
function doDeleteProperty(id) {
	var del = confirm('确定要删除所选属性吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/trendadmin/property/delete',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if (parseInt(id) == id) {
    				$("#property_" + id).parent().parent().remove();
    			} else {
    				$('#content_listing').find('.select-single:checked').parent().parent().remove();
    			}
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
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
	        var url = '/trendadmin/property';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$("input[name='key']").val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.properties;
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
                }).fail(function (e) {

                });
	        }, self._delay);
	    }
	};
	
	$('#content_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
	            {
	                property: 'checkbox',
	                label: '<input type="checkbox" />'
	            },
	            {
	                property: 'property_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'label_name',
	            	label: '属性名称',
	            	sortable: false
	            },
	            {
	            	property: 'property_value',
	            	label: '属性值',
	            	sortable: false
	            },
	            {
	            	property: 'note',
	            	label: '备注',
	            	sortable: false
	            },
	            {
	            	property: 'sort_order',
	            	label: '序号',
	            	sortable: false
	            },
	            {
	            	property: 'is_spec',
	            	label: '关闭规格',
	            	sortable: false
	            },
	            {
	            	property: 'status',
	            	label: '状态',
	            	sortable: false
	            },
	            {
	            	property: 'operate',
	                label: '操作'
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.property_id + '" />';
//	            	item.is_spec = item.is_spec == 1 ? '<i class="fa fa-check text-success" title="开启"></i>' : '<i class="fa fa-ban text-danger" title="不开启"></i>';
//	            	item.status = item.status == 1 ? '<i class="fa fa-check text-success" title="启用"></i>' : '<i class="fa fa-ban text-danger" title="不启用"></i>';
	            	
	            	var is_publish = item.status == 1 ? 'checked="checked"' : '';
	            	if(is_publish){
		                item.status = '<label class="switch-sm" title="关闭显示" property_id="' + item.property_id + '">' + 
		                		'<input type="checkbox" id="publish_' + item.property_id + '" ' + is_publish + ' />' + 
		                		'<span></span></label>';
	            	}else{
            		   item.status = '<label class="switch-sm" title="开启显示" property_id="' + item.property_id + '">' + 
                		'<input type="checkbox" id="publish_' + item.property_id + '" ' + is_publish + ' />' + 
                		'<span></span></label>';    		
	            	}
	            	
	            	var isSpec = item.is_spec == 1 ? 'checked="checked"' : '';
	            	if(isSpec){
		                item.is_spec = '<label class="switch-sm" title="关闭规格" property_a_id="' + item.property_id + '">' + 
		                		'<input type="checkbox" id="spec_' + item.property_id + '" ' + isSpec + ' />' + 
		                		'<span></span></label>';
	            	}else{
            		   item.is_spec = '<label class="switch-sm" title="请在操作中设置开启规格" property_a_id="' + item.property_id + '">' + 
                		'<input type="checkbox" id="spec_' + item.property_id + '" ' + isSpec + ' />' + 
                		'<span></span></label>';    		
	            	}
	            	
	            	
	            	item.operate = '<a href="/trendadmin/property/edit/?property_id=' + item.property_id + '" class="operate-edit load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
							'<a href="javascript:;" class="operate-delete" id="property_' + item.property_id + '" property_id="' + item.property_id + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}