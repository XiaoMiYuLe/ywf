$(document).ready(function() {
	refreshListing();
	
	/**
	 * 刷新或搜索
	 */
	$('body').delegate('.action-refresh, #action_search', 'click', function(){
		$('#content_listing').datagrid('reload');
	});
	
	/**
	 * 关键字搜索 - 支持回车
	 */
    $("input[name=key]").on('keypress', function (event) {
	    if (event.which == '13') {
	    	$('#content_listing').datagrid('reload');
	    	return false;
	    }
	});

	/**
	 * 单个删除
	 */
	$('body').delegate('.operate-delete', 'click', function(){
		var del = confirm('确定要删除该广告页吗？');
		if (! del) {return false;}
		
		var page_id = $(this).attr("page_id");
		
		/* 执行 */
		$.ajax({
	    	type:'post',
	        url:'/advertadmin/page/delete',
	        data:'page_id=' + page_id,
	        dataType:'json',
	        timeout:10000,
	        success:function(data){
	    		if (data.status == 0) {
	    			$("#page_" + page_id).parent().parent().remove();
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
	        var url = '/advertadmin/page';
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
//	            {
//	                property: 'checkbox',
//	                label: ''
//	            },
	            {
	                property: 'page_id',
	                label: 'ID',
	                sortable: false
	            },
	            {
	                property: 'title',
	                label: '页面标题',
	                sortable: false
	            },
	            {
	                property: 'ctime',
	                label: '创建时间',
	                sortable: false
	            },
	            {
	                property: 'action',
	                label: '操作',
	                sortable: false
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
//	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.board_id + '">';
	            	
	            	item.action = '<a href="/advertadmin/page/edit?page_id=' + item.page_id + '" data-toggle="ajaxModal" class="operating-edit" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
							'<a href="javascript:;" class="operate-delete" id="page_' + item.page_id + '" page_id="' + item.page_id + '" title="删除"><i class="fa fa-trash-o"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}