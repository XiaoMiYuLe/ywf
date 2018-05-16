$(document).ready(function() {
	refreshListing();
	
    /**
     * 刷新搜索
     */
    $(".action-refresh,#action_search").on('click',function(){
    	$('#content_listing').datagrid('reload');
    });
    
    /**
	 * 关键字搜索 - 支持回车
	 */
	$('input[name=key]').on('keypress', function (event) {
	    if (event.which == '13') {
	        $('#content_listing').datagrid("reload");
	    }
	});
    
    /**
     * 删除分组
     */
    $('body').delegate('#content_listing .operate-delete', 'click', function(){
    	if (! confirm('确定要删除该分组吗？')) {
    		return false;
    	}
    	
    	var tr = $(this).closest('tr');
    	var group_id = $(this).attr('group_id');
    	
    	$.ajax({
	    	type:'post',
	        url:'/pageadmin/group/delete',
	        data:'id=' + group_id,
	        dataType:'json',
	        timeout:10000,
	        success:function(data){
	    		if (data.status == 0) {
	    			$(tr).remove();
	    		} else {
	    			alert(data.error);
	    		}
	    		return false;
	    	}
	    });
	});
});


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
	        var url = '/pageadmin/group';
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
	                property: 'group_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'group_name',
	            	label: '分组名称',
	            	sortable: false
	            },
	            {
	            	property: 'folder',
	            	label: '分组网址',
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
	            	item.folder = '<a href="/page/' + item.folder + '" target="_blank">' + item.folder + '</a>';
	            	
	            	var str_operate = '<a href="/pageadmin/group/edit/?group_id=' + item.group_id + '" data-toggle="ajaxModal" class="operate-edit" title="编辑"><i class="fa fa-pencil"></i></a>';
	            	if (item.moveable == 1) {
	            		str_operate += '&nbsp;&nbsp;<a href="javascript:;" class="operate-delete" group_id="' + item.group_id + '" title="删除"><i class="fa fa-times"></i></a>'
	            	}
	            	item.operate = str_operate;
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}