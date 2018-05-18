$(document).ready(function() {
	refreshListing();
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
	        var url = '/promotionadmin/index/detail';
	        var self = this;

	        setTimeout(function () {
	            var data = $.extend(true, [], self._data);
	            $.ajax(url, {
	                data: {
	                	promotion_id: $("#promotion_id").val(),
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
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
	            	property: "brand_name",
	            	label: '品牌',
	            	sortable: false
	            },
	            {
	            	property: "sku",
	            	label: '商品sku',
	            	sortable: false
	            },
	            {
	            	property: 'price',
	            	label: '价格',
	            	sortable: false
	            },
	            {
	            	property: "status",
	            	label: "状态",
	            },
	            {
	            	property: "spec",
	            	label: "规格",
	            },
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.a = '<a href="/promotionadmin/index/detail?promotion_id=' + item.promotion_id + '" target="_blank"><i class="fa fa-search-plus"></i></a>';
	            	var status;
	            	if (item.is_shelf  == 0 ) {
	            		status = "未上架";
	            	}else if (item.is_shelf == 1) {
	            		status = "已上架"
	            	}
	            	item.status = status;

	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }
	});
}