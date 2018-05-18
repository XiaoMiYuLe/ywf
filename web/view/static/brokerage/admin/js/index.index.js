$(document).ready(function() {
	refreshListing();

	/**
     * 批量结算
     */
	$('#allsettlement').on('click', function(){
		var id_arr = new Array();
		var i = 0;
		$('#content_listing').find('.select-single').each(function(){
			if ($(this).is(':checked')) {
				id_arr[i] = $(this).val();
				i++;
			}
		});
		var brokerage_id = id_arr.join(',');
		if (! brokerage_id) {
			return false;
		}
		
		allSettlement(brokerage_id);
	});
    

	/**
     * 批量拒绝
     */
	$('#allrefused').on('click', function(){
		var id_arr = new Array();
		var i = 0;
		$('#content_listing').find('.select-single').each(function(){
			if ($(this).is(':checked')) {
				id_arr[i] = $(this).val();
				i++;
			}
		});
		var brokerage_id = id_arr.join(',');
		if (! brokerage_id) {
			return false;
		}
		
		allRefused(brokerage_id);
	});
});

function allSettlement(brokerage_id) {
	var del = confirm('确定要将所选佣金记录结算吗？');
		if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/brokerageadmin/index/allSettlement',
        data:'brokerage_id=' + brokerage_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			alert('结算成功');
    			location.reload()
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
	
}

function allRefused(brokerage_id) {
	var del = confirm('确定要将所选佣金记录拒绝吗？');
		if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/brokerageadmin/index/allrefused',
        data:'brokerage_id=' + brokerage_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			alert('拒绝成功');
    			location.reload()
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
	        var url = '/brokerageadmin';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.brokerage;
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
	  	            property: 'checkbox',
	  	            label: '<input type="checkbox" />&nbsp;&nbsp;全选',
	  	        },
	  	        {
	                property: 'brokerage_id',
	                label: '佣金记录id',
	                sortable: false
	            },
	            {
	                property: 'order_no',
	                label: '归属订单',
	                sortable: false
	            },
	            {
	                property: 'order_time',
	                label: '订单时间',
	                sortable: false
	            },
	            {
	                property: 'goods_name',
	                label: '归属产品',
	                sortable: false
	            },
	            {
	            	property: 'expected_money',
	            	label: '佣金金额',
	            	sortable: false
	            },
	            {
	            	property: 'username',
	            	label: '客户姓名',
	            	sortable: false
	            },
	            {
	            	property: 'brokerage_ratio',
	            	label: '佣金比例',
	            	sortable: false
	            },
	            {
	                property: 'mtime',
	                label: '结算时间',
	                sortable: false
	            },
	            {
	                property: 'brokerage_status',
	                label: '状态',
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
	            	/* 处理提现状态  */
	            	var str_type = '';
	            	if (item.brokerage_status == 1) {
	            		str_type = '待结';
	            	}else if(item.brokerage_status == 2){
	            		str_type = '已结';
	            	}else if(item.brokerage_status == 3){
	            		str_type = '已拒绝';
	            	}
	            	item.brokerage_status = str_type;
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.brokerage_id + '">';	            	
	            	if (item.brokerage_status == '已结'){
	            		item.action = '完成';
	            	}else{
	            		item.action = '<a href="/brokerageadmin/index/settlement?brokerage_id=' + item.brokerage_id + '" class="load-content" title="结算">结算</a>&nbsp;&nbsp;' + 
						'<a href="/brokerageadmin/index/refused?brokerage_id=' + item.brokerage_id + '" class="load-content" title="拒绝">拒绝</a>';
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