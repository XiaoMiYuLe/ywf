$(document).ready(function() {
	refreshListing();
	
	/**
	 * 权限模块处理
	 */
	$("select[name=appkey]").change(function(){
		var appkey = $(this).val();
		var href = "/admin/permission?appkey=" + appkey;
		window.location = href;
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
	        var url = '/admin/permission';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    appkey: $("select[name=appkey]").val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.permissions;
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
	
	$('#permission_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
	            {
	                property: 'permission_id',
	                label: '权限 ID',
	                sortable: true
	            },
	            {
	                property: 'permission_name',
	                label: '权限名',
	                sortable: false
	            },
	            {
	                property: 'description',
	                label: '描述',
	                sortable: false
	            },
	            {
	                property: 'appkey',
	                label: '模块名',
	                sortable: true
	            },
	            {
	            	property: 'permission_group',
	            	label: '权限组',
	            	sortable: false
	            },
	            {
	            	property: 'setting',
	            	label: '设置',
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
	            	item.setting = '<a href="/admin/appPermission/add?permission_id=' + item.permission_id + '&appkey=' + item.appkey + '" class="load-content">添加动作</a>' + ' | ' + 
			            	'<a href="/admin/appPermission/index?permission_id=' + item.permission_id + '&appkey=' + item.appkey + '" class="load-content">查看动作</a>';
	            	
	            	item.action = '<a href="/admin/permission/edit?permission_id=' + item.permission_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
			            	'<a href="/admin/permission/delete?permission_id=' + item.permission_id + '" class="load-content" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}