$(document).ready(function() {
	refreshListing();
	
	/**
	 * 刷新或搜索
	 */
	$('body').delegate('.action-refresh, #action_search', 'click', function(){
		$('#content_listing').datagrid('reload');
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
     * 审核
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	var board_id = $(this).attr('board_id');
    	publishBoard(board_id);
    	return false;
    });
    
    /**
	 * 单个删除
	 */
	$('body').delegate('.operate-delete', 'click', function(){
		var del = confirm('确定要删除该广告位吗？');
		if (! del) {return false;}
		
		var board_id = $(this).attr("board_id");
		
		/* 执行 */
		$.ajax({
	    	type:'post',
	        url:'/advertadmin/board/delete',
	        data:'board_id=' + board_id,
	        dataType:'json',
	        timeout:10000,
	        success:function(data){
	    		if (data.status == 0) {
	    			$("#board_" + board_id).parent().parent().remove();
	    		} else {
	    			alert(data.error);
	    		}
	    		return false;
	    	}
	    });
	});
});

/**
 * 审核
 */
function publishBoard(id) {
	$.ajax({
    	type:'post',
        url:'/advertadmin/board/publish',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if ($('#publish_' + id).prop('checked')) {
    				$('#publish_' + id).prop('checked', false);
    			} else {
    				$('#publish_' + id).prop('checked', true);
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
	        var url = '/advertadmin/board';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val(),
	                    page_id:$('select[name=page_id]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.boards;
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
//	                label: ''
//	            },
	            {
	                property: 'board_id',
	                label: 'ID',
	                sortable: false
	            },
	            {
	                property: 'name',
	                label: '广告位名称',
	                sortable: false
	            },
	            {
	                property: 'width',
	                label: '广告位宽度',
	                sortable: false
	            },
	            {
	                property: 'height',
	                label: '广告位高度',
	                sortable: false
	            },
	            {
	            	property: 'sort_order',
	            	label: '序号',
	            	sortable: false
	            },
	            {
	                property: 'ctime',
	                label: '创建时间',
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
//	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.board_id + '">';
	            	
	            	var is_publish = item.status == 1 ? 'checked="checked"' : '';
	            	if(is_publish){
		                item.status = '<label class="switch-sm" title="关闭" board_id="' + item.board_id + '">' + 
		                		'<input type="checkbox" id="publish_' + item.board_id + '" ' + is_publish + ' />' + 
		                		'<span></span></label>';
	            	}else{
	            		item.status = '<label class="switch-sm" title="启用" board_id="' + item.board_id + '">' + 
                		'<input type="checkbox" id="publish_' + item.board_id + '" ' + is_publish + ' />' + 
                		'<span></span></label>';
	            	}
	            	item.action = '<a href="/advertadmin/board/edit?board_id=' + item.board_id + '" data-toggle="ajaxModal" class="operating-edit" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
							'<a href="javascript:;" class="operate-delete" id="board_' + item.board_id + '" board_id="' + item.board_id + '" title="删除"><i class="fa fa-trash-o"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}