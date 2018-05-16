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
		var property_group_id = $(this).attr("property_group_id");
		doDeleteGroup(property_group_id);
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
		
		doDeleteGroup(id);
	});
});


/**
 * 删除
 */
function doDeleteGroup(id) {
	var del = confirm('确定要删除所选分组吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/trendadmin/propertyGroup/delete',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if (parseInt(id) == id) {
    				$("#property_group_" + id).parent().parent().remove();
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
	        var url = '/trendadmin/propertyGroup';
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
	            	var data = response.data.groups;
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
	                property: 'property_group_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'property_group_name',
	            	label: '分组名称',
	            	sortable: false
	            },
	            {
	            	property: 'sort_order',
	            	label: '序号',
	            	sortable: false
	            },
	            {
	            	property: 'operate',
	                label: '操作'
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.property_group_id + '">';
	            	item.operate = '<a href="/trendadmin/propertyGroup/edit/?property_group_id=' + item.property_group_id + '" class="operate-edit load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
							'<a href="javascript:;" class="operate-delete" id="property_group_' + item.property_group_id + '" property_group_id="' + item.property_group_id + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}