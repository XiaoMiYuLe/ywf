$(document).ready(function() {
	refreshListing();
	
    /**
	 * 刷新或搜索
	 */
	$('.action-refresh').on('click', function(){
		$('#group_listing').datagrid('reload');
		return false;
	});
	
	/**
	 * 删除用户组
	 */
	$("#group_listing").delegate('.operate-delete', 'click', function(){
		var obj_tr = $(this).parent().parent();
		var groupid = $(this).attr('groupid');
//		var del_user = $(this).attr('del_user'); // 暂时不启用
		var del_user = 0;
		
		var str_confirm = '确定要删除该用户组吗？';
		if (del_user == 1) {
			str_confirm = '确定要删除该用户组，并同时删除该用户组下的所有用户吗？';
		}
		if (! confirm(str_confirm)) {
			return false;
		}
		
		$.ajax({
	    	type:'post',
	        url:'/admin/group/delete',
	        data:'groupid=' + groupid + '&del_user=' + del_user,
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
	        var url = '/admin/group';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json"
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.groups;
                    if (! data) {
                    	return false;
                    }

                    var count=response.data.count;//设置data.total

                    if (self._formatter) self._formatter(data);

                    callback({ data: data });
                }).fail(function (e) {

                });
	        }, self._delay);
	    }
	};
	
	$('#group_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
	            {
	                property: 'groupid',
	                label: 'ID',
	                sortable: true
	            },
	            {
	                property: 'groupname',
	                label: '用户组名',
	                sortable: false
	            },
	            {
	                property: 'description',
	                label: '描述',
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
	                item.setting_auth = '<a href="/admin/permissionAssign/assign?groupid=' + item.groupid + '" class="load-content" title="设置权限"><i class="fa fa-gear"></i></a>'; 
	                item.action = '<a href="/admin/group/edit?groupid=' + item.groupid + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
							'<a href="javascript:;" class="operate-delete" id="groupid_' + item.groupid + '" groupid="' + item.groupid + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { }	
	});
}