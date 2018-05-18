$(document).ready(function() {
	refreshListing();
	
	/**
	 * 刷新或搜索
	 */
	$('.action-refresh').on('click', function(){
		$('#content_listing').datagrid('reload');
	});
	
	/**
	 * 关键字搜索 - 支持回车
	 */
    $("input[name=key]").on('keypress', function (event) {
	    if (event.which == '13') {
	    	$('#content_listing').datagrid('reload');
	    }
	});
	
    /**
	 * 彻底删除 - 单条
	 */
    $("#content_listing").delegate('.operate-delete', 'click', function(){
    	var del = confirm('确定要删除该单页吗？');
    	if (! del) {return false;}
    	
    	var tr = $(this).closest('tr');
    	var bulk_id = $(this).attr("bulk_id");
		
    	$.ajax({
        	type:'post',
            url:'/grouponadmin/purch/delete',
            data:'id=' + bulk_id,
            dataType:'json',
            timeout:60000,
            success:function(data){
        		if (data.status == 0) {
        			$(tr).remove();
        		} else {
        			alert(data.error);
        		}
        		return false;
        	}
        });
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
	        var url = '/grouponadmin/purch';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    type: $('#type').val(),
	                    key:$('input[name=key]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.bulks;
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
	                property: 'bulk_id',
	                label: 'ID',
	                sortable: false
	            },
	            {
	            	property: 'sku',
	            	label: 'SKU',
	            	sortable: false
	            },
	            {
	            	property: 'goods_name',
	            	label: '商品名称',
	            	sortable: false
	            },
	            {
	            	property: 'price',
	            	label: '抢购价格',
	            	sortable: false
	            },
	            {
	                property: 'start_time',
	                label: '开始时间',
	                sortable: false
	            },
	            {
	                property: 'end_time',
	                label: '结束时间',
	                sortable: false
	            },
	            {
	            	property: 'operate',
	            	label: '操作',
	            	sortable: false
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	
	            	var str_operate = '<a href="/grouponadmin/purch/edit/?bulk_id=' + item.bulk_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>';
	            		str_operate += '&nbsp;&nbsp;<a href="javascript:;" class="operate-delete" bulk_id="' + item.bulk_id + '" title="删除"><i class="fa fa-times"></i></a>'
	            	item.operate = str_operate;
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}