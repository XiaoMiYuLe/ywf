$(document).ready(function() {
	refreshListing();
	
    /**
	 * 刷新或搜索
	 */
	$('.action-refresh').on('click', function(){
		$('#user_listing').datagrid('reload');
		return false;
	});
	
	/**
	 * 搜索对回车的支持
	 */
	$("input[name=key]").on('keypress', function (event) {
	    if (event.which == '13' && $(this).val()) {
	    	$('#user_listing').datagrid('reload');
	    	return false;
	    }
	});
	
	/**
	 * 删除用户
	 */
    $("#user_listing").on('click', '.operate-delete', function () {
		var obj_tr = $(this).parent().parent();
		var userid = $(this).attr('userid');
		
		var str_confirm = '确定要删除该用户吗？';
		if (! confirm(str_confirm)) {
			return false;
		}
		
		$.ajax({
	    	type:'post',
	        url:'/admin/user/delete',
	        data:'userid=' + userid,
	        dataType:'json',
	        timeout:60000,
	        success:function(data){
	    		if (data.status == 0) {
	    			obj_tr.remove();
	    			alert('删除成功');
	    		} else {
	    			alert('删除失败，请稍后重试');
	    		}
	    		return false;
	    	},
	        error:ajaxError
	    });
	});
});


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
	        var url = '/admin/user';
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
	            	var data = response.data.users;
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
	
	$('#user_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
//	            {
//	            	property: 'a',
//	            	label: ''
//	            },
	            {
	                property: 'userid',
	                label: 'ID',
	                sortable: true
	            },
	            {
	                property: 'username',
	                label: '用户名',
	                sortable: false
	            },
	            {
	                property: 'fullname',
	                label: '用户全称',
	                sortable: false
	            },
	            {
	                property: 'groups',
	                label: '所属组',
	                sortable: true
	            },
	            {
	            	property: 'ctime',
	            	label: '注册时间',
	            	sortable: false
	            },
	            {
	            	property: 'status',
	            	label: '状态',
	            	sortable: false
	            },
	            {
	            	property: 'setting_auth',
	            	label: '权限',
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
	                item.status = item.status == 1 ? '<i class="fa fa-check text-success" title="已激活"></i>' : '<i class="fa fa-ban text-danger" title="未激活"></i>';
	                
	                if (item.userid == 1){
	                	item.groups = '-';
	                	item.setting_auth = '-' ;
	                } else {
	                	item.setting_auth = '<a href="/admin/permissionAssign/assign?userid=' + item.userid + '" class="load-content" title="设置权限"><i class="fa fa-gear"></i></a>'; 
	                }
	                
	                var str_action = '<a href="/admin/user/edit?userid=' + item.userid + '" userid="' + item.userid + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>';
	                if (item.username != 'admin') {
	                	str_action += '&nbsp;&nbsp;<a href="javascript:;" class="operate-delete" id="userid_' + item.userid + '" userid="' + item.userid + '" title="删除"><i class="fa fa-times"></i></a>';
	                }
	                item.action = str_action;
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}