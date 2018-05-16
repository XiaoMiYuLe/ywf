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
    	var link_id = $(this).attr('link_id');
    	publishAdvert(link_id);
    	return false;
    });
    
    /**
     * 批量删除
     */
    $('body').delegate('#action_delete', 'click', function(){
    	var id_arr = [];
    	var i = 0;
    	$('#content_listing').find('.select-single').each(function(){
    		if ($(this).is(':checked')) {
    			id_arr[i] = $(this).val();
    			i++;
    		}
    	});
    	var id = id_arr.join(',');
    	if (!id) {
    		return false;
    	}
    	
    	var del = confirm('确定要删除所选友情链接吗？');
    	if (! del) {return false;}
    	
    	/* 执行删除 */
    	$.ajax({
        	type:'post',
            url:'/advertadmin/frendlink/delete',
            data:'id=' + id,
            dataType:'json',
            timeout:10000,
            success:function(data){
        		if (data.status == 0) {
        			$('#content_listing').find('.select-single:checked').parent().parent().remove();
        		} else {
        			alert(data.error);
        		}
        		return false;
        	}
        });
     });
    
    /**
     * 单个删除
     */
    $('body').delegate('.operate-delete', 'click', function(){
    	var tr = $(this).closest('tr'),bid = tr.find(".select-single").val();
    	if(!bid || !confirm('确定要删除此友情链接吗？')) {
            return false;
        }
    	$.ajax({
        	type:'post',
            url:'/advertadmin/frendlink/delete',
            data:'id=' + bid,
            dataType:'json',
            timeout:10000,
            beforeSend:function(){$(tr).addClass('remove');},
            success:function(data){
        		if (data.status == 0) {
        			$(tr).fadeIn().remove();
        		} else {
        			alert(data.error);
        		}
        		return false;
        	},complete:function(){$(tr).removeClass('remove');}
        	
        });
    });

	
    /**
     * 确保标题栏不变形
     */
    $("#content_listing thead th").attr('nowrap','nowrap');
    
});    


/**
 * 审核
 */
function publishAdvert(id) {
	$.ajax({
    	type:'post',
        url:'/advertadmin/frendlink/publish',
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
	        var url = '/advertadmin/frendlink';
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
	            	var data = response.data.links;
                    if (! data) {
                    	$("#content_listing tbody").html('<p>暂无数据！</p>');
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
	                property: 'link_name',
	                label: '友情链接名称',
	                sortable: false
	            },
	            {
	                property: 'href',
	                label: '友情链接地址',
	                sortable: false
	            },
	            {
	            	property: 'image_url',
	            	label: '标志',
	            	sortable: false
	            },
	            {
	                property: 'sort_order',
	                label: '序号',
	                sortable: true
	            },	            
	            {
	                property: 'status',
	                label: '前台显示',
	                sortable: false
	            },
	            {
	            	property: 'action',
	                label: '操作'
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.link_id + '">';
                    
                    item.href = item.href ? item.href.substring(0, 30) : '';
	            	if (item.image_url) {
		            	item.image_url = '<a href="/uploads' + item.image_url + '" class="thumb-sm" target="_blank">' + 
	                            '<img src="/uploads' + item.image_url + '" /></a>';
	            	} else {
	            		item.image_url = '';
	            	}
	            	
	            	var is_publish = item.status == 1 ? 'checked="checked"' : '';
	            	
	            	if(is_publish){
		                item.status = '<label class="switch-sm" title="关闭显示" link_id="' + item.link_id + '">' + 
		                		'<input type="checkbox" id="publish_' + item.link_id + '" ' + is_publish + ' />' + 
		                		'<span></span></label>';
	            	}else{
            		   item.status = '<label class="switch-sm" title="开启显示" link_id="' + item.link_id + '">' + 
                		'<input type="checkbox" id="publish_' + item.link_id + '" ' + is_publish + ' />' + 
                		'<span></span></label>';    		
	            	}
	            	
                    item.action = '<a href="/advertadmin/frendlink/edit?link_id=' + item.link_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
							'<a href="javascript:;" class="operate-delete" id="link_' + item.link_id + '" link_id="' + item.link_id + '" title="删除"><i class="fa fa-trash-o"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}
