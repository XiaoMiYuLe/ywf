
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
	 * 搜索对回车的支持
	 */
	$("input[name=key]").on('keypress', function (event) {
	    if (event.which == '13' && $(this).val()) {
	    	$('#content_listing').datagrid('reload');
	    	return false;
	    }
	});
	
	/**
     * 审核
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	var content_id = $(this).attr('content_id');
    	publishComment(content_id);
    	return false;
    });
	
    /**
	 * 删除 - 单条
	 */
    $("#content_listing").delegate('.operate-delete', 'click', function(){
		var content_id = $(this).attr("content_id");
		doDeleteContent(content_id);
	});
    
    /**
     * 删除 - 批量
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
    
    /**
     * 确保标题栏不变形
     */
    $("#content_listing thead th").attr('nowrap','nowrap');
});


/**
 * 审核
 */
function publishComment(id) {
	$.ajax({
    	type:'post',
        url:'/commentadmin/index/publish',
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

/**
 * 删除
 */
function doDeleteContent(id) {
	var del = confirm('确定要删除所选评论吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/commentadmin/index/delete',
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
	        var url = '/commentadmin';
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
	            	var data = response.data.contents;
                    if (!data) {
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
	                label: ''
	            },
	            {
	            	property: 'a',
	            	label: ''
	            },
	            {
	                property: 'username',
	                label: '评论人',
	                sortable: false
	            },
	            {
	                property: 'to_name',
	                label: '评论对象(商品)',
	                sortable: false
	            },
	            {
	            	property: 'rank_base',
	            	label: '商品满意度评分',
	            	sortable: false
	            },
	            {
	            	property: 'rank_logistics',
	            	label: '物流评分',
	            	sortable: false
	            },
	            {
	            	property: 'rank_speed',
	            	label: '发货速度评分',
	            	sortable: false
	            },
	            {
	                property: 'ctime',
	                label: '评论时间',
	                sortable: false
	            },
	            {
	            	property: 'status',
	            	label: '前台显示',
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
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.id + '">';
	            	
	            	item.a = '<a href="/commentadmin/index/detail/?id=' + item.id + '"  class="modal-detail"><i class="fa fa-search-plus"></i></a>';
	                
	            	var is_publish = item.status == 1 ? 'checked="checked"' : '';
	            	
	            	if(is_publish){
		                item.status = '<label class="switch-sm" title="关闭显示" content_id="' + item.id + '">' + 
		                		'<input type="checkbox" id="publish_' + item.id + '" ' + is_publish + ' />' + 
		                		'<span></span></label>';
	            	}else{
	            		item.status = '<label class="switch-sm" title="开启显示" content_id="' + item.id + '">' + 
	                			'<input type="checkbox" id="publish_' + item.id + '" ' + is_publish + ' />' + 
	                			'<span></span></label>';	
	            	}
	                
	                item.action ='<a href="javascript:;" class="operate-delete" id="content_' + item.id + '" content_id="' + item.id + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}