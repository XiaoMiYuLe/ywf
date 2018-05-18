$(document).ready(function() {
	
	refreshListing();
	
	/**
     * 批量提现
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
		var withdraw_id = id_arr.join(',');
		if (! withdraw_id) {
			return false;
		}
		
		allSettlement(withdraw_id);
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
		var withdraw_id = id_arr.join(',');
		if (! withdraw_id) {
			return false;
		}
		
		allRefused(withdraw_id);
	});
	
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
     * 导出*/
    $(".xls-export").on('click',function(){
    	
    	location.href ="/withdrawadmin/index/exportExchangeList";
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

function allSettlement(withdraw_id) {
	var del = confirm('确定要将所选提现记录提现吗？');
		if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/withdrawadmin/index/allSettlement',
        data:'withdraw_id=' + withdraw_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			alert('提现成功');
    			location.reload()
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
	
}

function allRefused(withdraw_id) {
	var del = confirm('确定要将所选提现记录拒绝吗？');
		if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/withdrawadmin/index/allrefused',
        data:'withdraw_id=' + withdraw_id,
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
	        var url = '/withdrawadmin';
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
	                    withdraw_status: $('select[name=withdraw_status]').val(),
	                    start_time: $('input[name=start_time]').val(),
	                    end_time: $('input[name=end_time]').val(),
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.withdraw;
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
	  	                property: 'withdraw_id',
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
	  	                property: 'withdraw_money',
	  	                label: '申请金额',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'withdraw_poundage',
	  	                label: '提现手续费',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'practical_withdraw_money',
	  	                label: '实际金额',
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
	  	                property: 'ctime',
	  	                label: '提现时间',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'withdraw_status',
	  	                label: '状态',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'action',
	  	                label: '操作',
	  	                sortable: false
	  	            },
	  	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	/* 处理提现状态  */
	            	var str_type = '';
	            	if (item.withdraw_status == 1) {
	            		str_type = '未处理';
	            	}else if(item.withdraw_status == 2){
	            		str_type = '提现成功';
	            	}else if(item.withdraw_status == 3){
	            		str_type = '提现失败';
	            	}
	            	item.withdraw_status = str_type;
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.withdraw_id + '">';
					item.action = '<a href="/withdrawadmin/index/detail?withdraw_id=' + item.withdraw_id + '" class="load-content" title="查看">查看</a>&nbsp;&nbsp;' ;
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}