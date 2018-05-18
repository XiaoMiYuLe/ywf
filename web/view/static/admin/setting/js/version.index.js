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
     * 发布
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	var version_id = $(this).attr('version_id');
    	publishVersion(version_id);
    	return false;
    });
	
    /**
	 * 删除 - 单条
	 */
    $("#content_listing").delegate('.operate-delete', 'click', function(){
		var id = $(this).attr("version_id");
		doDeleteVersion(id);
	});
});


/**
 * 发布
 */
function publishVersion(id) {
	$.ajax({
    	type:'post',
        url:'/setting/version/publish',
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

/**
 * 删除
 */
function doDeleteVersion(id) {
	var del = confirm('确定要删除所选版本吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/setting/version/delete',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if (parseInt(id) == id) {
    				$("#version_" + id).parent().parent().remove();
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
	        var url = '/setting/version';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    platform: $('select[name=platform]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.versions;
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
	                property: 'id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'platform',
	            	label: '平台类型',
	            	sortable: false
	            },
	            {
	            	property: 'v_code',
	            	label: '版本号',
	            	sortable: false
	            },
	            {
	                property: 'v_name',
	                label: '版本名称',
	                sortable: true
	            },
	            {
	            	property: 'content',
	            	label: '版本详情',
	            	sortable: false
	            },
	            {
	            	property: 'filepath',
	            	label: '文件路径',
	            	sortable: false
	            },
	            {
	            	property: 'size',
	            	label: '大小（KB）',
	            	sortable: false
	            },
	            {
	            	property: 'ctime',
	            	label: '发布时间',
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
	                var is_publish = item.status == 1 ? 'checked="checked"' : '';
	                item.status = '<label class="switch-sm" version_id="' + item.id + '">' + 
	                		'<input type="checkbox" id="publish_' + item.id + '" ' + is_publish + ' />' + 
	                		'<span></span></label>';
	                
	                item.action = '<a href="/setting/version/edit?id=' + item.id + '" class="operate-edit load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
        					'<a href="javascript:;" class="operate-delete" id="version_' + item.id + '" version_id="' + item.id + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}