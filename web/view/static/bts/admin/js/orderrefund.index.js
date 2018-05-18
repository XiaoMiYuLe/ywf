$(document).ready(function() {
	refreshListing();
    
	/**
	 * 刷新或搜索
	 */
	$('body').delegate('.action-refresh, #action_search', 'click', function(){
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
	 * 预处理日期选择控件
	 */
	$('.datepicker-input').datepicker();
	
	/**
	 * 删除 - 单条
	 */
    $("#content_listing").delegate('.operate-delete', 'click', function(){
		var userid = $(this).attr("refund_id");
		doDeleteUser(userid);
	});
    
});

/**
 * 删除
 */
function doDeleteUser(userid) {
	var del = confirm('确定要删除所选订单吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/btsadmin/orderRefund/delete',
        data:'return_id=' + userid,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
				$("#refund_" + userid).parent().parent().remove();
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
}

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
	        var url = '/btsadmin/orderRefund';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val(),
	                    start_ctime: $('input[name=start_ctime]').val(),
	                    end_ctime: $('input[name=end_ctime]').val(),
	                    status: $('select[name=status_id]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.listing;
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
	            /*{
	                property: 'checkbox',
	                label: ''
	            },*/
	            {
	            	property: 'a',
	            	label: ''
	            },
	            {
	                property: 'refund_sn',
	                label: '退单号',
	                sortable: false
	            },
	            {
	                property: 'goods_name',
	                label: '商品名称',
	                sortable: false
	            },
	            {
	            	property: 'buy_num',
	            	label: '商品数量',
	            	sortable: false
	            },
	            {
	                property: 'order_number',
	                label: '订单号',
	                sortable: false
	            },
	            {
	                property: 'reason',
	                label: '退货原因',
	                sortable: false
	            },
	            {
	                property: 'ctime',
	                label: '退货时间',
	                sortable: false
	            },	            
	            {
	            	property: 'status',
	            	label: '退单状态',
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
	            	item.a = '<a href="/btsadmin/orderRefund/detail/?refund_id=' + item.refund_id + '"  class="modal-detail"><i class="fa fa-search-plus"></i></a>';
	            	//item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.partner_id + '">';
	            	item.action = '<a href="/btsadmin/orderRefund/edit/?refund_id=' + item.refund_id + '" data-toggle="ajaxModal" class="operate-edit" title="编辑">审核</a>&nbsp;&nbsp;'+
	            	'<a href="javascript:;" class="operate-delete" id="refund_' + item.refund_id +  '" refund_id="' + item.refund_id +  '" title="删除">删除</i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}