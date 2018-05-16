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
	 * 单个删除
	 */
	$('body').delegate('.operate-delete', 'click', function(){
		var del = confirm('确定要删除该帮助吗？');
		if (! del) {return false;}
		
		var help_id = $(this).attr("help_id");
		/* 执行 */
		$.ajax({
	    	type:'post',
	        url:'/helpadmin/index/delete',
	        data:'help_id=' + help_id,
	        dataType:'json',
	        timeout:10000,
	        success:function(data){
	    		if (data.status == 0) {
	    			$("#help_" + help_id).parent().parent().remove();
	    		} else {
	    			alert(data.error);
	    		}
	    		return false;
	    	}
	    });
	});
	
	/**
     * 确保标题栏不变形
     */
    $("#content_listing thead th").attr('nowrap','nowrap');
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
	        var url = '/helpadmin';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val()
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
	  	                property: 'help_id',
	  	                label: '编号',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'help_title',
	  	                label: '帮助标题',
	  	                sortable: false
	  	            },
	  	            {
	  	                property: 'ctime',
	  	                label: '生成时间',
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
	            	item.action = '<a href="/helpadmin/index/edit?help_id=' + item.help_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
					'<a href="javascript:;" class="operate-delete" id="help_' + item.help_id + '" help_id="' + item.help_id + '" title="删除"><i class="fa fa-trash-o"></i></a>';
	            
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}