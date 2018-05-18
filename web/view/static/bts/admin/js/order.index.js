$(document).ready(function() {
	refreshListing();
    
	/**
	 * 刷新或搜索
	 */
	$('body').delegate('.action-refresh,#action_search', 'click', function(){
		$('#content_listing').datagrid('reload');
		return false;
	});
	
	/**
	 * 扔进回收站 - 单条
	 */
	$("#content_listing").delegate('.operate-trash', 'click', function(){
		var content_id = $(this).attr("content_id");
		doTrashContent(content_id);
	});
	
	/**
	 * 预处理日期选择控件
	 */
	/*$('.datepicker-input').datepicker();*/
	/**
	 *  日期控件格式化
	 */
	$(".wdatepicker").focus(function(){
		      WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	});
	
	/**
	 * 扔进回收站 - 批量
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
		
		doTrashContent(id);
	});
	
});


/**
 * 删除
 */
function doTrashContent(id) {
	var del = confirm('确定要将所选订单扔进回收站吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/btsadmin/order/trash',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if (parseInt(id) == id) {
    				$("#content_" + id).parent().parent().remove();
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
	        var url = '/btsadmin/order';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val(),
	                    status: $('select[name=status_id]').val(),
	                    goods_pattern: $('select[name=goods_pattern]').val(),
	                    start_ctime: $('input[name=start_ctime]').val(),
	                    end_ctime: $('input[name=end_ctime]').val(),
	                    goods_type: $('select[name=goods_type]').val(),
	                    goods_name: $('input[name=goods_name]').val()
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
	            /*
	            {
	                property: 'checkbox',
	                label: '<input type="checkbox" />&nbsp;&nbsp;全选',
	            },
	            */
	            {
	                property: 'order_no',
	                label: '订单号',
	                sortable: false
	            },
	            {
	                property: 'username',
	                label: '用户名',
	                sortable: false
	            },
	            {
	                property: 'phone',
	                label: '手机号码',
	                sortable: false
	            },
	            {
	                property: 'order_status_name',
	                label: '状态',
	                sortable: false
	            },	            
	            {
	            	property: 'ctime',
	            	label: '下单时间',
	            	sortable: false
	            },
	            {
	                property: 'goods_name',
	                label: '产品',
	                sortable: false
	            },
	            {
	                property: 'goods_pattern_name',
	                label: '模式',
	                sortable: false
	            },
	            {
	                property: 'buy_money',
	                label: '订单金额',
	                sortable: false
	            },
	            {
	                property: 'real_money',
	                label: '实付金额',
	                sortable: false
	            },
	            {
	                property: 'bts_yield',
	                label: '预期收益',
	                sortable: false
	            },
	            {
	                property: 'end_time',
	                label: '到期时间',
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
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.child_order_id + '">';
	            	 var str_action = '<a href="/btsadmin/order/detail?order_id=' + item.order_id + '" userid="' + item.userid + '" class="load-content" title="查看"><i class="fa fa-eye"></i></a>';
	                  item.action = str_action;
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}