$(document).ready(function() {
	refreshListing();

	/**
     * 发布
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	var content_id = $(this).attr('content_id');
    	publishAdvert(content_id);
    	return false;
    });

    /**
	 * 单个删除
	 */
	$('body').delegate('.operate-delete', 'click', function(){
		var del = confirm('确定要删除该广告吗？');
		if (! del) {return false;}
		
		var content_id = $(this).attr("content_id");
		/* 执行 */
		$.ajax({
	    	type:'post',
	        url:'/advertadmin/index/delete',
	        data:'content_id=' + content_id,
	        dataType:'json',
	        timeout:10000,
	        success:function(data){
	    		if (data.status == 0) {
	    			$("#content_" + content_id).parent().parent().remove();
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
	        var url = '/advertadmin';
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
	            	var data = response.data.content;
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
	                property: 'content_id',
	                label: '编号',
	                sortable: false
	            },
	            {
	                property: 'title',
	                label: '广告名称',
	                sortable: false
	            },
	            {
	            	property: 'image',
	            	label: '图片',
	            	sortable: false
	            },
	            {
	                property: 'mtime',
	                label: '更新时间',
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
//	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.board_id + '">';
	            	
	            	item.action = '<a href="/advertadmin/index/edit?content_id=' + item.content_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
							'<a href="javascript:;" class="operate-delete" id="content_' + item.content_id + '" content_id="' + item.content_id + '" title="删除"><i class="fa fa-trash-o"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}