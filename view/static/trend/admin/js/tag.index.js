$(document).ready(function() {
	refreshListing();
	
    /**
     * 刷新
     */
    $(".action-refresh").on('click',function(){
    	$('#tag_listing').datagrid('reload');
    	return false;
    });
    
    /**
     * 单个删除标签
     */
    $('body').delegate('#tag_listing .operate-delete', 'click', function(){
    	var tr=$(this).closest('tr'),bid = tr.find(".select-single").val();
    	if(!bid || !confirm('确定要删除此标签吗？')) return false;
    	$.ajax({
	    	type:'post',
	        url:'/trendadmin/tag/delete',
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
     * 批量删除标签
     */
    $('body').delegate('#action_delete', 'click', function(){
		var id_arr = new Array();
		var i = 0;
		$('#tag_listing').find('.select-single').each(function(){
			if ($(this).is(':checked')) {
				id_arr[i] = $(this).val();
				i++;
			}
		});
		var id = id_arr.join(',');
		
		if (! id) {
			return false;
		}
		
		var del = confirm('确定要删除所选标签吗？');
		if (! del) {return false;}
		
		/* 执行删除 */
		$.ajax({
	    	type:'post',
	        url:'/trendadmin/tag/delete',
	        data:'id=' + id,
	        dataType:'json',
	        timeout:10000,
	        success:function(data){
	    		if (data.status == 0) {
	    			$('#tag_listing').find('.select-single:checked').parent().parent().remove();
	    		} else {
	    			alert(data.error);
	    		}
	    		return false;
	    	}
	    });
	 });
});


/**
 * datagrid 加载列表
 */
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
	        var url = '/trendadmin/tag';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize
//	                    key:$('input[name=key]').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.tags;
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
	
	$('#tag_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
	            {
	                property: 'checkbox',
	                label: ''
	            },
	            {
	                property: 'tag_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'tag_name',
	            	label: '标签名称',
	            	sortable: true
	            },
	            {
	                property: 'tag_type',
	                label: '标签类型',
	                sortable: false
	            },
	            {
	                property: 'rel_count',
	                label: '关联数',
	                sortable: false
	            },
	            {
	            	property: 'operate',
	                label: '操作'
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.tag_id + '">';
	            	item.operate = '<a href="/trendadmin/tag/edit/?tag_id='+item.tag_id+'" data-toggle="ajaxModal" class="operate-edit" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
					'<a href="javascript:;" class="operate-delete" title="删除"><i class="fa fa-times"></i></a>';
	            	item.tag_type = item.tag_type=='goods'?'商品标签':'文章标签';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}