$(document).ready(function() {
	//返回
	$("#button_cancel").on("click",function(){
		history.back(-1);
	});
	
	/**
	 * 加载列表
	 */
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
    $("input[name=keywords]").on('keypress', function (event) {
	    if (event.which == '13') {
	    	$('#content_listing').datagrid('reload');
	    	return false;
	    }
	});
    
	/**
	 * 失效设置
	 */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
        var cpns_id = $(this).attr('cpns_id');
        if (! cpns_id) return false;
        $.ajax({
            url : '/couponadmin/index/disabled',
            type : 'post',
            data : {cpns_id : cpns_id},
            dataType : 'json',
            timeout : 60000,
            success : function(data){
                if (data.status == 0){
                    if (data.data.disabled == 1){
                        $('#cpns_' + cpns_id).prop('checked', true);
                    } else {
                        $('#cpns_' + cpns_id).prop('checked', false);
                    }
                } else {
                    alert(data.error);
                }
            }
        });
        return false;
    });
    
    /**
	 * 删除
	 */
	$('body').delegate('.operate-delete', 'click', function(){
		var cpns_id = $(this).attr('data-cpns_id');
		var del = confirm('确定要删除该条信息吗？');
		if (! del || ! cpns_id) return false;
		
		/* 执行 */
    	$.ajax({
	    	type:'post',
	        url:'/couponadmin/index/deleteUser',
	        data:'cpns_id=' + cpns_id,
	        dataType:'json',
	        timeout:10000,
	        success:function(data){
	    		if (data.status == 0) {
	    			alert(data.data);
	    		} else {
	    			alert(data.error);
	    		}
	    		return false;
	    	},error:ajaxError
	    });
    	$('#content_listing').datagrid('reload');
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
	        var url = '/couponadmin/Index/detail';
	        var self = this;
	        setTimeout(function () {
	            var data = $.extend(true, [], self._data);
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    coupon_id: $('input[name=coupon_id]').val(),
	                    keywords: $('input[name=keywords]').val(),
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
	
	var options = {
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
		   columns: [
	            {
	                property: 'a',
	                label: ' ',
	                sortable: false
	            },
	            {
	                property: 'username',
	                label: '用户名',
	                sortable: false
	            },
	            {
	                property: 'phone',
	                label: '手机号',
	                sortable: false
	            },
	            {
	            	property: 'cpns_status',
	            	label: '优惠券状态',
	            	sortable: false
	            },
	            {
	            	property: 'ctime',
	            	label: '获取时间',
	            	sortable: false
	            },
	            {
	            	property: 'disabled',
	            	label: '是否标记为失效',
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
	            	item.a = ' ';
	            	
	            	var disabled = item.disabled == 1 ? 'checked="checked"' : '';
	            	item.disabled = '<label class="switch-sm" cpns_id="' + item.cpns_id + '">' +
                     '<input type="checkbox" id="cpns_' + item.cpns_id + '" ' + disabled + ' />' +
                     '<span></span></label>';
	            	
	            	if (item.cpns_status == 0) item.cpns_status = '未使用';
	            	if (item.cpns_status == 1) item.cpns_status = '已使用';
	            	if (item.cpns_status == -1) item.cpns_status = '异常';
	            	
	            	item.action =  '<a href="javascript:;" class="operate-delete" data-cpns_id="' + item.cpns_id + '" title="删除"><i class="fa fa-trash-o"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }
	}

	$('#content_listing').datagrid(options);
}
