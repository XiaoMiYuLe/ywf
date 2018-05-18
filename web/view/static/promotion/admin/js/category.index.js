$(document).ready(function() {
	refreshListing();

    /**
     * 刷新搜索
     */
    $(".action-refresh").on('click',function(){
    	$('#content_listing').datagrid('reload');
    	return false;
    });

    /**
	 * 关键字搜索 - 支持回车
	 */
	$('input[name=key]').on('keypress', function (event) {
	    if (event.which == '13') {
	        $('#content_listing').datagrid("reload");
	        return false;
	    }
	});

    /**
     * 单个删除分类
     */
	$("#content_listing").delegate('.operate-delete', 'click', function(){
		var category_id = $(this).attr("category_id");
		doDeleteCategory(category_id);
		return false;
	});

});


/**
 * 删除 - 支持批量
 */
function doDeleteCategory(category_id) {
	var del = confirm('确定要删除所选分类吗？');
	if (! del) {return false;}

	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/promotionadmin/category/delete',
        data:'category_id=' + category_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if (parseInt(category_id) == category_id) {
    				$("#category_" + category_id).parent().parent().remove();
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
	        var url = '/promotionadmin/category';
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
	            	var data = response.data.categories;
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
	                label: ''
	            },
	            {
	                property: 'category_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'title',
	            	label: '分类名称',
	            	sortable: false
	            },
	            {
	            	property: 'template_title',
	            	label: '模板名称',
	            	sortable: false
	            },
	            {
	            	property: 'action',
	                label: '操作'
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.category_id + '">';
	            	item.title = item.str_padding + item.title;
	            	item.template_title = item.template_title ? item.template_title : '';
	            	item.action = '<a href="/promotionadmin/category/edit?category_id=' + item.category_id + '" class="load-content"  title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' +
							'<a href="javascript:;" class="operate-delete" id="category_' + item.category_id + '" category_id="' + item.category_id + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }
	});
}