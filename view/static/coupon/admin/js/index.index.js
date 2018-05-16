$(document).ready(function() {
	/**
	 *获取要操作的类型
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
	 * 删除
	 */
	$('body').delegate('.operate-delete', 'click', function(){
		var coupon_id = $(this).attr('data-coupon_id');
		var del = confirm('确定要删除该优惠券吗？');
		if (! del || ! coupon_id) return false;
		
		/* 执行 */
    	$.ajax({
	    	type:'post',
	        url:'/couponadmin/index/delete',
	        data:'coupon_id=' + coupon_id,
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
	        var url = '/couponadmin/Index/index';
	        var self = this;

	        setTimeout(function () {

	            var data = $.extend(true, [], self._data);

	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    status: $('select[name=status]').val(),
	                    coupon_type: $('input[name=coupon_type]').val(),
	                    relation_type: $('select[name=relation_type]').val(),
	                    valid_stime: $('input[name=valid_stime]').val(),
	                    valid_etime: $('input[name=valid_etime]').val(),
	                    grant_stime: $('input[name=grant_stime]').val(),
	                    grant_etime: $('input[name=grant_etime]').val(),
	                    keywords:  $('input[name=keywords]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.coupon;
                    if (! data) {
                    	return false;
                    }
                    var count = response.data.count;//设置data.total
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
				   columns: [
					{
		            	property: 'a',
		            	label: '查看'
		            },
		            {
		            	property: 'coupon_name',
		            	label: '名称',
		            	sortable: false
		            },
		            {
		            	property: 'relation_type',
		            	label: '类别',
		            	sortable: false
		            },
		            {
		            	property: 'face_value',
		            	label: '面额',
		            	sortable: false
		            },
		            {
		                property: 'total',
		                label: '发放量',
		                sortable: false
		            },
		            {
		                property: 'exchanged_total',
		                label: '兑换量',
		                sortable: false
		            },
		            {
		            	property: 'valid_time',
		            	label: '有效时间',
		            	sortable: false
		            },
		            {
		            	property: 'grant_time',
		            	label: '发放时间',
		            	sortable: false
		            },
		            {
		            	property: 'status',
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
		            	item.a = '<a href="/couponadmin/Index/detail?coupon_id=' + item.coupon_id + '" ><i class="fa fa-search-plus"></i></a>';
		            	
		            	if (item.relation_type == 1) item.relation_type = '全场满减券'; 
		            	else if (item.relation_type == 2) item.relation_type = '分类满减券';
		            	else if (item.relation_type == 3) item.relation_type = '商户满减券';
		            	else if (! item.relation_type) item.relation_type = '通用券';
		            	
		            	if (item.basic_price && item.basic_price != 0) item.face_value = '满' + item.basic_price + '减' + item.face_value;
		            	
		            	item.valid_time = item.valid_stime.substr(0,11) + ' - ' + item.valid_etime.substr(0,11);
		            	item.grant_time = item.grant_stime.substr(0,11) + ' - ' + item.grant_etime.substr(0,11);
		            	
		            	if (item.status == 1) item.status = '已启用';
		            	else if (item.status == 0) item.status = '未启用';
		            	else if (item.status == -1) item.status = '已关闭';
		            	
		            	item.action = '<a href="/couponadmin/index/edit?coupon_id='+ item.coupon_id +'" class="operate-edit" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
						'<a href="javascript:;" class="operate-delete" id=coupon_' + item.coupon_id + ' data-coupon_id="'+ item.coupon_id +'" title="删除"><i class="fa fa-times"></i></a>';
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
