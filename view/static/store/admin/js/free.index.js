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
    $('#content_listing').delegate('.setting-verify', 'click', function(){
    	var store_id = $(this).attr('store_id');
    	
    	var str_confirm = "确定要通过该商户的审核请求吗？";
    	if ($("#verify_" + store_id).is(':checked')) {
    		str_confirm = "确定要解除该商户的审核状态吗？";
    	}
    	if (! confirm(str_confirm)) {return false;}
    	
    	verifyStore(store_id);
		return false;
    });
    
    
    /**
	 * 删除 - 单条
	 */
    $("#content_listing").delegate('.operate-delete', 'click', function(){
		var store_id = $(this).attr("store_id");
		doDeleteStore(store_id);
		return false;
	});
});


/**
 * 审核
 */
function verifyStore(store_id) {
	$.ajax({
    	type:'post',
        url:'/storeadmin/index/verify',
        data:'store_id=' + store_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			var d = data.data;
    			if (d.is_verify == 1) {
    				$('#verify_' + store_id).prop('checked', true);
    			} else {
    				$('#verify_' + store_id).prop('checked', false);
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
function doDeleteStore(store_id) {
	var del = confirm('确定要删除该店铺吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/storeadmin/index/delete',
        data:'store_id=' + store_id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
				$("#store_" + store_id).parent().parent().remove();
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
	        var url = '/storeadmin/free/index';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	            
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=key]').val(),
	                    status:$('#status').val(),
	                    is_verify:$('#is_verify').val()
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
					property: 'a',
					label: ''
				},
	            {
	                property: 'store_id',
	                label: 'ID',
	                sortable: false
	            },
	            {
	                property: 'store_name',
	                label: '商户名称',
	                sortable: false
	            },
	            {
	            	property: 'address',
	            	label: '地址',
	            	sortable: false
	            },
	            {
	            	property: 'legalp_name',
	            	label: '法人姓名',
	            	sortable: false
	            },	            
	            {
	            	property: 'is_signing',
	            	label: '签约状态',
	            	sortable: false
	            },
	            {
	            	property: 'status',
	            	label: '状态',
	            	sortable: false
	            },
	            {
	            	property: 'is_verify',
	            	label: '审核',
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
	            	/* 查看详情 */
	            	item.a = '<a href="/storeadmin/index/detail/?store_id=' + item.store_id + '"><i class="fa fa-search-plus" title="查看详情"></i></a>';
	            	
	                /* 审核状态 */
	                if(item.is_verify == 1)
	                {
	                	item.is_verify = '已审核';
	                }
	                else if(item.is_verify == -1)
                	{
	                	item.is_verify = '审核不通过';
                	}
	                else if(item.is_verify == 0)
                	{
	                	item.is_verify = '待审核';
                	}
	                else
	                {
	                	item.is_verify = '未知';
	                }
	                
	                
	                if (item.is_signing == 1) {
	                	item.is_signing = '已签约';
	                } else {
	                	item.is_signing = '未签约';
	                }
	                
	                /* 开启状态 */
	                if(item.status == 1)
	                {
	                	item.status = '正常';
	                }
	                else if(item.status == -1)
                	{
	                	item.status = '已删除';
                	}
	                else if(item.status == 0)
                	{
	                	item.status = '未启用';
                	}
	                else
	                {
	                	item.status = '未知';
	                }
	                
	                /* 操作 */
	            	item.action = '<a href="/storeadmin/index/edit?store_id=' + item.store_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
	            			'<a href="javascript:;" class="operate-delete" id="store_' + item.store_id + '" store_id="' + item.store_id + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}
