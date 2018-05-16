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
     *审核提交申请
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	var promotion_id = $(this).attr('promotion_id');
    	publishContent(promotion_id);
    });


    /**
	 * 扔进回收站 - 单条
	 */
    $("#content_listing").delegate('.operate-trash', 'click', function(){
		var promotion_id = $(this).attr("promotion_id");
		doTrashContent(promotion_id);
	});


	/**
	 * 扔进回收站 - 批量
	 */
	$('#action_trash').on('click', function(){
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
		doTrashContent(id);
	});
});


/**
 * 提交审核
 */
function publishContent(id) {
	$.ajax({
    	type:'post',
        url:'/promotionadmin/index/publish',
        data:'promotion_id=' + id ,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			alert("申请成功");
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
}

/**
 * 删除 - 扔进回收站
 */
function doTrashContent(id) {
	var del = confirm('确定要将所活动删除吗？');
	if (! del) {return false;}

	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/promotionadmin/index/trash',
        data:'promotion_id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if (parseInt(id) == id) {
    				$("#promotion_" + id).parent().parent().remove();
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
	        var url = '/promotionadmin';
	        var self = this;

	        setTimeout(function () {
	            var data = $.extend(true, [], self._data);
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val(),
	                    category_id: $('#category_id').val()
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
	            	property:"a",
	            	label: "",
	            	sortable:false
	            },
	            {
	                property: 'promotion_id',
	                label: 'ID',
	                sortable: false
	            },
//	            {
//	            	property: 'image',
//	            	label: '主题图',
//	            	sortable: false
//	            },
	            {
	            	property: 'title',
	            	label: '活动名称',
	            	sortable: false
	            },
	            {
	            	property: 'category_name',
	            	label: '所属类型',
	            	sortable: false
	            },
	            {
	            	property: 'start_time',
	            	label: '开始时间',
	            	sortable: false
	            },
	            {
	            	property: "end_time",
	            	label: '结束时间',
	            	sortable: false
	            },
	            {
	            	property: 'ctime',
	            	label: '发布时间',
	            	sortable: false
	            },
//	            {
//	            	property: "is_verify",
//	            	label: "审核状态",
//	            },
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
	            	item.a = '<a href="/promotionadmin/index/detail?promotion_id=' + item.promotion_id + '" ><i class="fa fa-search-plus"></i></a>';
	            	 item.action = '<a href="/promotionadmin/index/edit?promotion_id=' + item.promotion_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;' +
	     				'<a href="javascript:;" class="operate-trash" id="promotion_' + item.promotion_id + '" promotion_id="' + item.promotion_id + '" title="删除"><i class="fa fa-trash-o"></i></a>&nbsp;';

	            	/*
	            	if (item.is_verify == 0 ) {
	            		item.is_verify = "未审核";
	            		 item.action = '<a href="/promotionadmin/index/edit?promotion_id=' + item.promotion_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;' +
					     				'<a href="javascript:;" class="operate-trash" id="promotion_' + item.promotion_id + '" promotion_id="' + item.promotion_id + '" title="删除"><i class="fa fa-trash-o"></i></a>&nbsp;'+
					     				'<a title="提交审核" promotion_id= '+item.promotion_id+ ' class="switch-sm" href="javascript:;"><i class="fa fa-arrow-up"></i></a>';

	            	}else if (item.is_verify == 1) {
	            		 item.is_verify = "审核中";
	            		 item.action = '<a href="/promotionadmin/index/edit?promotion_id=' + item.promotion_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;' +
					      			   '<a href="javascript:;" class="operate-trash" id="promotion_' + item.promotion_id + '" promotion_id="' + item.promotion_id + '" title="删除"><i class="fa fa-trash-o"></i></a>&nbsp;'
	            	}else if (item.is_verify == 2) {
	            		 item.is_verify = "审核通过";
	            		 item.is_verify = "审核中";
	            		 item.action = '<a href="/promotionadmin/index/edit?promotion_id=' + item.promotion_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;' +
					      			   '<a href="javascript:;" class="operate-trash" id="promotion_' + item.promotion_id + '" promotion_id="' + item.promotion_id + '" title="删除"><i class="fa fa-trash-o"></i></a>&nbsp;'
	            	}
	            	*/
	            	var status = item.status == 1 ? "启用" : "未启用";
	                item.status = status;
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }
	});
}