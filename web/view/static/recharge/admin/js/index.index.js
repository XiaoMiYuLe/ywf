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
	        var url = '/rechargeadmin';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    phone:$('input[name=phone]').val(),
	                    username:$('input[name=username]').val(),
	                    idcard:$('input[name=idcard]').val(),
	                    recharge_status: $('select[name=recharge_status]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.recharge;
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
	  	                property: 'record_id',
	  	                label: '业务编号',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'username',
	  	                label: '所属用户',
	  	                sortable: false
	  	            },
	  	            {
	  	            	property: 'phone',
	  	            	label: '手机号码',
	  	            	sortable: false
	  	            },
	  	            {
	  	                property: 'bank_name',
	  	                label: '所属银行',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'opening_bank',
	  	                label: '开户行',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'bank_no',
	  	                label: '银行账号',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'order_no',
	  	                label: '订单号',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'money',
	  	                label: '金额',
	  	                sortable: false
	  	            },
	  	           /* {
	  	                property: 'recharge_status',
	  	                label: '操作结果',
	  	                sortable: false
	  	            },*/
	  	            {
	  	                property: 'ctime',
	  	                label: '充值时间',
	  	                sortable: false
	  	            },
	  	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	/* 处理充值状态  */
	            	var str_type = '';
	            	if (item.recharge_status == 1) {
	            		str_type = '充值成功';
	            	}else if(item.recharge_status == 2){
	            		str_type = '充值失败';
	            	}
	            	
	            	item.recharge_status = str_type;
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}