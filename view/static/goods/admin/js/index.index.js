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
     * 上架 - 单条
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	var goods_id = $(this).attr('goods_id');
    	var status = $('#publish_' + goods_id).is(':checked') ? 0 : 1;
    	publishContent(goods_id, status);
    	return false;
    });
	
    /**
	 * 扔进回收站 - 单条
	 */
    $("#content_listing").delegate('.operate-trash', 'click', function(){
		var content_id = $(this).attr("content_id");
		doTrashContent(content_id);
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
	/**
     * 确保标题栏不变形
     */
    $("#content_listing thead th").attr('nowrap','nowrap');
});


/**
 * 上架
 */
function publishContent(id, status) {
	$.ajax({
    	type:'post',
        url:'/goodsadmin/index/publish',
        data:'goods_id=' + id + '&status=' + status,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			if ($('#publish_' + id).is(':checked')) {
    				$('#publish_' + id).prop('checked', false);
    			} else {
    				$('#publish_' + id).prop('checked', true);
    				if (data.data.id) {
    					$('#publish_' + data.data.id).prop('checked', false);
    				}
    			}
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
	var del = confirm('确定要将所选商品扔进回收站吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/goodsadmin/index/trash',
        data:'content_id=' + id,
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
	        var url = '/goodsadmin';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    goods_pattern: $('select[name=goods_pattern]').val(),
	                    goods_type: $('select[name=goods_type]').val(),
	                    goods_status: $('select[name=goods_status]').val(),
	                    goods_id: $('input[name=goods_id]').val(),
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
//				{
//				    property: 'checkbox',
//				    label: '<input type="checkbox" />'
//				},
	            {
	                property: 'goods_id',
	                label: 'ID',
	                sortable: false
	            },
	            {
	            	property: 'ctime',
	            	label: '起售时间',
	            	sortable: false
	            },
	            {
	            	property: 'goods_name',
	            	label: '产品名称',
	            	sortable: false
	            },
	            {
	            	property: 'goods_type',
	            	label: '产品类型',
	            	sortable: false
	            },
	            {
	            	property: 'money',
	            	label: '销售进度',
	            	sortable: false
	            },
	            {
	            	property: 'end_time',
	            	label: '结息日期',
	            	sortable: false
	            },
	            {
	            	property: 'low_pay',
	            	label: '最低投资额',
	            	sortable: false
	            },
	            {
	            	property: 'buy_num',
	            	label: '已购人数',
	            	sortable: false
	            },
	            {
	            	property: 'yield',
	            	label: '年化收益率',
	            	sortable: false
	            },
	            {
	            	property: 'is_hot',
	            	label: '推荐',
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
	            	item.checkbox = '<input type="checkbox" name="post[]" class="select-single" value="' + item.content_id + '">';
	            	
	            	var is_hot = item.is_hot == 1 ? 'checked="checked"' : '';
	            		if(is_hot){
		            		item.is_hot = '<label class="switch-sm" title="不推荐" goods_id="' + item.goods_id + '">' + 
				                '<input type="checkbox" id="publish_' + item.goods_id + '" ' + is_hot + ' />' + 
				                '<span></span></label>';
		            	}else{
		            		item.is_hot = '<label class="switch-sm" title="推荐" goods_id="' + item.goods_id + '">' + 
				                '<input type="checkbox" id="publish_' + item.goods_id + '" ' + is_hot + ' />' + 
				                '<span></span></label>';	
		            	}
	            	
	            	var str_type = '';
	            	if (item.goods_type == 1) {
	            		str_type = '债权';
	            	} else if (item.goods_type == 2) {
	            		str_type = '保险';
	            	} else if (item.goods_type == 3) {
	            		str_type = '资管';
	            	} else if (item.goods_type == 4) {
	            		str_type = '基金';
	            	} else if (item.goods_type == 5) {
	            		str_type = '信托';
	            	}
	            	item.goods_type = str_type;
	            	
	            	if ((item.goods_type == '保险' && item.goods_pattern == 3) ||(item.goods_type == '资管' && item.goods_pattern == 3)||(item.goods_type == '基金' && item.goods_pattern == 3)) {
	            		item.action = '<a href="/goodsadmin/index/detail?goods_id=' + item.goods_id + '" class="load-content" title="查看"><i class="fa fa-search"></i></a>&nbsp;&nbsp; '+
	                	'<a href="/goodsadmin/index/edit?goods_id=' + item.goods_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>' 
	            	} else if (item.goods_type == '债权') {
	            		item.action = '<a href="/goodsadmin/index/detail?goods_id=' + item.goods_id + '" class="load-content" title="查看"><i class="fa fa-search"></i></a>&nbsp;&nbsp; '+
	                	'<a href="/goodsadmin/index/editGoods?goods_id=' + item.goods_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>' 
	            	} else if (item.goods_pattern == 1) {
	            		item.action = '<a href="/goodsadmin/index/detail?goods_id=' + item.goods_id + '" class="load-content" title="查看"><i class="fa fa-search"></i></a>&nbsp;&nbsp; '+
	                	'<a href="/goodsadmin/index/editNewGoods" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>' 
	            	}else if (item.goods_pattern == 4) {
	            		item.action = '<a href="/goodsadmin/index/detail?goods_id=' + item.goods_id + '" class="load-content" title="查看"><i class="fa fa-search"></i></a>&nbsp;&nbsp; '+
	                	'<a href="/goodsadmin/index/editExperienceGoods" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>' 
	            	}
	                
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}