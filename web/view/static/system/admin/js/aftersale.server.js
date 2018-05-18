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
	        var url = '/articleadmin/index/serviceSale';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key: $('input[name=key]').val(),
	                    page_id: $("select[name=page_id]:first").val(),
	                    board_id: $("select[name=board_id]:first").val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.content;
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
//	            	property: 'a',
//	            	label: ''
//	            },
	            {
	                property: 'ser_sale_id',
	                label: 'ID',
	                sortable: false
	            },
	            {
	            	property: 'cat_id',
	            	label: '服务类型',
	            	sortable: false
	            },
	            {
	            	property: 'ser_sale_type',
	            	label: '服务性质',
	            	sortable: false
	            },
	          
	            {
	                property: 'ser_sale_mtime',
	                label: '更新时间',
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
	            	item.a = '<a href="/advertadmin/index/detail/?content_id=' + item.content_id + '"  class="modal-detail"><i class="fa fa-search-plus" title="查看详情"></i></a>';	            	
	            	/* 处理广告类型 */
	            	var str_type = '';
	            	if (item.type == 1) {
	            		str_type = '图片';
	            	} else if (item.type == 2) {
	            		str_type = '文字';
	            	} else if (item.type == 3) {
	            		str_type = 'Flash';
	            	} else if (item.type == 4) {
	            		str_type = '视频';
	            	} else if (item.type == 5) {
	            		str_type = '轮播';
	            	}
	            	item.type = str_type;
	            	
	            	item.status = item.status == 1 ? '<i class="fa fa-check text-success" title="显示"></i>' : '<i class="fa fa-ban text-danger" title="关闭"></i>';
	            	
	            	item.action = '<a href="/advertadmin/index/edit?content_id=' + item.content_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}