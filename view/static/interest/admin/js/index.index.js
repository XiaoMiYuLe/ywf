$(document).ready(function() {
	
	refreshListing();
	
	/**
	 * 刷新或搜索
	 */
	$('.action-refresh').on('click', function(){
		$('#content_listing').datagrid('reload');
		return false;
	});
	
	/**
	 *  日期控件格式化
	 */
	$(".wdatepicker").focus(function(){
		      WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
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
     * 确保标题栏不变形
     */
    $("#content_listing thead th").attr('nowrap','nowrap');
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
	        var url = '/interestadmin';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val(),
	                    username:$('input[name=username]').val(),
	                    idcard:$('input[name=idcard]').val(),
	                    start_time: $('input[name=start_time]').val(),
	                    end_time: $('input[name=end_time]').val(),
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.interest;
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
	  	                property: 'goods_name',
	  	                label: '产品名称',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'username',
	  	                label: '用户名称',
	  	                sortable: false
	  	            },
	  	            {
	  	            	property: 'order_no',
	  	            	label: '订单号',
	  	            	sortable: false
	  	            },
	  	            {
	  	            	property: 'buy_moeny',
	  	            	label: '订单金额',
	  	            	sortable: false
	  	            },
	  	            {
	  	                property: 'settlement_money',
	  	                label: '计息金额',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'stime',
	  	                label: '计息时间',
	  	                sortable: false
	  	            },
	  	           {
	  	                property: 'ctime',
	  	                label: '记录时间',
	  	                sortable: false
	  	            },
	  	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}