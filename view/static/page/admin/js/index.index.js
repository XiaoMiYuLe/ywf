$(document).ready(function() {
	refreshListing();
	
	/**
	 * 刷新或搜索
	 */
	$('.action-refresh').on('click', function(){
		$('#content_listing').datagrid('reload');
	});
	
	/**
	 * 关键字搜索 - 支持回车
	 */
    $("input[name=key]").on('keypress', function (event) {
	    if (event.which == '13') {
	    	$('#content_listing').datagrid('reload');
	    }
	});
	
    /**
	 * 彻底删除 - 单条
	 */
    $("#content_listing").delegate('.operate-delete', 'click', function(){
    	var del = confirm('确定要删除该单页吗？');
    	if (! del) {return false;}
    	
    	var tr = $(this).closest('tr');
    	var page_id = $(this).attr("page_id");
		
    	$.ajax({
        	type:'post',
            url:'/pageadmin/index/delete',
            data:'id=' + page_id,
            dataType:'json',
            timeout:60000,
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
	        var url = '/pageadmin';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    group_id: $('#group_id').val(),
	                    key:$('input[name=key]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.pages;
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
	                property: 'id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'group_name',
	            	label: '所属分组',
	            	sortable: false
	            },
	            {
	                property: 'title',
	                label: '网页标题',
	                sortable: true
	            },
	            {
	                property: 'folder',
	                label: '网址',
	                sortable: true
	            },
	            {
	            	property: 'ctime',
	            	label: '创建时间',
	            	sortable: false
	            },
	            {
	            	property: 'operate',
	            	label: '操作',
	            	sortable: false
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.folder = '<a href="/page/' + item.folder + '/' + item.page_folder + '" target="_blank">' + item.page_folder + '</a>';
	            	
	            	var str_operate = '<a href="/pageadmin/index/edit/?page_id=' + item.id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>';
	            	if (item.page_folder != 'index') {
	            		str_operate += '&nbsp;&nbsp;<a href="javascript:;" class="operate-delete" page_id="' + item.id + '" title="删除"><i class="fa fa-times"></i></a>'
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