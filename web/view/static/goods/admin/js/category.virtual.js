/**
 * 
 */

$(document).ready(function() {
	refreshListing();
	
	
	//重新生成虚拟分类缓存
	$('.action-virtual').click(function(){
		$.ajax({
	    	type:'post',
	        url:'/goodsadmin/category/updateCacheVirtual',
	        data:'category_id=0',
	        dataType:'html',
	        timeout:60000,
	        success:function(status){
	        	if (status == 0) {
	        		alert('生成完毕');
	        	} else {
	        		alert('请稍候重试');
	        	}
	    		return false;
	    	}
	    });
	});
});

//清空分类下的虚拟分类
function clearVirtualCategory(obj){
	if (!confirm('确定清空么？清空之后不可恢复！')) {
		return false;
	}
	var cateId = $(obj).attr('cate_id');
	$.ajax({
    	type:'post',
        url:'/goodsadmin/category/clearVirtual',
        data:'category_id=' + cateId,
        dataType:'html',
        timeout:60000,
        success:function(status){
        	if (status == 0) {
        		alert('清空完毕');
        	} else {
        		alert('请稍候重试');
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
	        var url = '/goodsadmin/category/virtualCategory';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$("input[name='key']").val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.category;
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
	                property: 'category_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'category_name',
	            	label: '分类名称',
	            	sortable: false
	            },
	            {
	            	property: 'parent_name',
	            	label: '父级分类名称',
	            	sortable: false
	            },
	            {
	            	property: 'sort_order',
	            	label: '序号',
	            	sortable: false
	            },
	            {
	            	property: 'operate',
	                label: '操作'
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	$.each(items, function (index, item) {
		            	item.operate  = '<a href="/goodsadmin/category/bindVirtual/?category_id=' + item.category_id + '" class="load-content" title="设置虚拟分类"><i class="fa fa-bitcoin"></i></a>&nbsp;&nbsp;';
		            	item.operate += '<a href="javascript:;" onclick="return clearVirtualCategory(this);" cate_id="'+item.category_id+'" class="operate-delete" title="清空虚拟分类"><i class="fa fa-eraser"></i></a>';
		            });
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}