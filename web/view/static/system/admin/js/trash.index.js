$(document).ready(function() {
	refreshListing();
	
	/**
	 * 载入树形分类
	 */
	var zTreeObj;
	var setting = {
	    async: {
	    	enable: true,
		    url: "/articleadmin/category/getZTree",  
		    autoParam: ["name", "id", "status"]
		},
		view: {
			showIcon: false
		},
		data: {
			id: "id",
		},
		callback:{
			onClick: function(treeId, treeNode) {  
				var Node = $.fn.zTree.getZTreeObj(treeNode).getSelectedNodes()[0];  
				$("#category_id").val(Node.id);
				$('#content_listing').datagrid('reload');
            }
		}
	};
	zTreeObj = $.fn.zTree.init($("#treeDemo"), setting);
	
	/**
	 * 返回所有分类的文章列表
	 */
	$('#category_all').click(function(){
		zTreeObj.cancelSelectedNode();
		$("#category_id").val(0);
		$('#content_listing').datagrid('reload');
	});
    
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
     * 还原
     */
    $('#content_listing').delegate('.operate-restore', 'click', function(){
    	var content_id = $(this).attr('content_id');
    	restoreArticle(content_id);
    	return false;
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
 * 还原
 */
function restoreArticle(id) {
	$.ajax({
    	type:'post',
        url:'/articleadmin/trash/restore',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			$("#restore_" + id).parent().parent().remove();
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
}

/**
 * 删除
 */
function doDeleteContent(id) {
	var del = confirm('确定要将所选文章彻底删除吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/articleadmin/trash/delete',
        data:'id=' + id,
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
	        var url = '/articleadmin/trash';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    category: $('#category_id').val(),
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
	            {
	                property: 'content_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'category_name',
	            	label: '分类',
	            	sortable: false
	            },
	            {
	            	property: 'title',
	            	label: '标题',
	            	sortable: false
	            },
	            {
	                property: 'pinned',
	                label: '权重',
	                sortable: true
	            },
	            {
	                property: 'mtime',
	                label: '修改时间',
	                sortable: true
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
	                item.action = '<a href="javascript:;" class="operate-restore" id="restore_' + item.content_id + '" content_id="' + item.content_id + '" title="还原"><i class="fa fa-reply"></i></a>&nbsp;&nbsp;' + 
        					'<a href="javascript:;" class="operate-delete" id="content_' + item.content_id + '" content_id="' + item.content_id + '" title="彻底删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}