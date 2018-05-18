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
	 * 彻底删除 - 单条
	 */
    $("#content_listing").delegate('.operate-delete', 'click', function(){
		var content_id = $(this).attr("content_id");
		doDeleteContent(content_id);
	});
	
	/**
	 * 彻底删除 - 批量
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
		
		doDeleteContent(id);
	});
});


/**
 * 彻底删除
 */
function doDeleteContent(id) {
	var del = confirm('确定要将所选商品彻底删除吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/goodsadmin/trash/delete',
        data:'content_id=' + id,
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
	        var url = '/goodsadmin/trash';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    category: $('select[name=category]').val(),
	                    brand_id: $('select[name=brand_id]').val(),
	                    is_shelf: $('select[name=is_shelf]').val(),
	                    price_min: $('input[name=price_min]').val(),
	                    price_max: $('input[name=price_max]').val(),
	                    key:$('input[name=key]').val()
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
	            {
	                property: 'checkbox',
	                label: '<input type="checkbox" />'
	            },
//	            {
//	            	property: 'a',
//	            	label: ''
//	            },
	            {
	                property: 'content_id',
	                label: 'ID',
	                sortable: false
	            },
//	            {
//	            	property: 'bn',
//	            	label: '商品编号',
//	            	sortable: false
//	            },
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
	            	property: 'price',
	            	label: '销售价',
	            	sortable: false
	            },
	            {
	            	property: 'price_market',
	            	label: '市场价',
	            	sortable: false
	            },
	            {
	            	property: 'stock',
	            	label: '库存',
	            	sortable: false
	            },
	            {
	            	property: 'goods_weight',
	            	label: '重量(g)',
	            	sortable: false
	            },
	            {
	            	property: 'brand_name',
	            	label: '品牌',
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
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.content_id + '">';
	            	
	            	item.a = '<a href="/goods/preview?content_id=' + item.content_id + '" target="_blank"><i class="fa fa-search-plus"></i></a>';
	            	
	            	item.price = '￥' + parseInt(item.price).toFixed(2);
	            	item.price_market = '￥' + parseInt(item.price_market).toFixed(2);
	            	item.goods_weight = item.weight;
	            	item.brand_name = item.brand_name ? item.brand_name : '';
	            	
	                item.action = '<a href="javascript:;" class="operate-delete" id="content_' + item.content_id + '" content_id="' + item.content_id + '" title="彻底删除"><i class="fa fa-trash-o"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}