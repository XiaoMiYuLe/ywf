$(document).ready(function() {
	refreshListing();
	
    /**
	 * 刷新或搜索
	 */
	$('.action-refresh').on('click', function(){
		$('#feedback_listing').datagrid('reload');
		return false;
	});
	
	/**
	 * 搜索对回车的支持
	 */
	$("input[name=key]").on('keypress', function (event) {
	    if (event.which == '13' && $(this).val()) {
	    	$('#feedback_listing').datagrid('reload');
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
	        var url = '/feedbackadmin';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val(),
	                    hasName: $('#hasName').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.feedback;
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
	
	$('#feedback_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
				{
					property: 'a',
					label: ''
				},
	            {
	                property: 'content_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	                property: 'title',
	                label: '标题',
	                sortable: false
	            },
	            {
	                property: 'realname',
	                label: '反馈用户',
	                sortable: false
	            },
	            {
	                property: 'status',
	                label: '状态',
	                sortable: true
	            },
	            {
	                property: 'ctime',
	                label: '反馈时间',
	                sortable: true
	            },
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.a = '<a href="/feedbackadmin/index/detail?content_id=' + item.content_id + '" data-toggle="ajaxModal"><i class="fa fa-search-plus" title="查看详情"></i></a>';
	            	if (item.status == 1){
	            		item.status = '<span><i class="fa fa-check-square-o"></i></span>'
	            	} else {
		            	item.status = '<span>未处理</span>';

	            	}
	            	
	            	if (item.userid == null){
	            		item.realname = '匿名用户';
	            	}
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}