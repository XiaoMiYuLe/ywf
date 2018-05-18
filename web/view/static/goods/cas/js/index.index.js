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
     * 上架
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	var content_id = $(this).attr('content_id');
    	publishGoods(content_id);
    	return false;
    });
	
});


/**
 * 审核
 */
function publishGoods(id) {
	$.ajax({
    	type:'post',
        url:'/goodsadmin/index/publish',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			var d = data.data;
    			if (d.status == 1) {
    				$('#publish_' + id).prop('checked', true);
    			} else {
    				$('#publish_' + id).prop('checked', false);
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
	        var url = '/goodscas';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    category: $('#category_id').val(),
	                    key:$('input[name=key]').val(),
	                    userid:$('select[name=userid]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.contents;
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
//	            {
//	                property: 'checkbox',
//	                label: '<input type="checkbox" />'
//	            },
	            {
	            	property: 'a',
	            	label: ''
	            },
	            {
	                property: 'goods_id',
	                label: 'ID',
	                sortable: false
	            },
	            {
	            	property: 'name',
	            	label: '商品名称',
	            	sortable: false
	            },
	            {
	            	property: 'category_name',
	            	label: '所属分类',
	            	sortable: false
	            },
	            {
	            	property: 'bn',
	            	label: '商品编号',
	            	sortable: false
	            },
	            {
	            	property: 'size',
	            	label: '尺寸',
	            	sortable: false
	            },
	            {
	            	property: 'weight',
	            	label: '重量',
	            	sortable: false
	            },
	            {
	            	property: 'lengthwideheight',
	            	label: '长*宽*高',
	            	sortable: false
	            },
	            {
	                property: 'price',
	                label: '价格',
	                sortable: false
	            },
	            {
	                property: 'stock',
	                label: '库存',
	                sortable: false
	            },
	            {
	            	property: 'status',
	            	label: '上架',
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
	                item.lengthwideheight = item.length + '*' + item.wide + '*' + item.height;
	            	item.a = '<a href="/goodsadmin/index/detail?goods_id=' + item.goods_id + '"  class="modal-detail"><i class="fa fa-search-plus"></i></a>';
	            	
	                var is_publish = item.status == 1 ? 'checked="checked"' : '';
	                item.status = '<label class="switch-sm" content_id="' + item.specification_id + '">' + 
	                		'<input type="checkbox" id="publish_' + item.specification_id + '" ' + is_publish + ' />' + 
	                		'<span></span></label>';
	                
	                item.action = '<a href="/goodsadmin/index/edit?goods_id=' + item.goods_id + '" class="load-content" title="查看">查看</a>&nbsp;&nbsp;' + 
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