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
	        var url = '/voucheradmin/index/interest';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val(),
	                    phone:$('input[name=phone]').val(),
	                    idcard:$('input[name=idcard]').val(),
	                    username:$('input[name=username]').val(),
	                    voucher_status: $('select[name=voucher_status]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.voucher;
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
	  	                label: '编号',
	  	                sortable: false
	  	            },
	  	            {
	  	            	property: 'order_id',
	  	            	label: '所属订单',
	  	            	sortable: false
	  	            },
	  	            {
	  	                property: 'voucher_status',
	  	                label: '状态',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'use_money',
	  	                label: '满多少使用',
	  	                sortable: false
	  	            },
		            {
	  	                property: 'increase_interest',
	  	                label: '加息',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'start_data',
	  	                label: '有效开始时间',
	  	                sortable: false
	  	            },
	  	          {
	  	                property: 'valid_data',
	  	                label: '有效截止时间',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'use_time',
	  	                label: '使用时间',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'creat_time',
	  	                label: '生成时间',
	  	                sortable: false
	  	            },
	  	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
							'<a href="javascript:;" class="operate-trash" id="content_' + item.content_id + '" content_id="' + item.content_id + '" title="删除(扔进回收站)"><i class="fa fa-trash-o"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}