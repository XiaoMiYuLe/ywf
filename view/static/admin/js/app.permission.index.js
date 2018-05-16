$(document).ready(function() {
	refreshListing();
	
	/**
	 * 模块搜索处理
	 */
    $("select[name=appkey]").change(function(){
		var appkey = $(this).val();
		var href = "/admin/apppermission?appkey=" + appkey;
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
	        var url = '/admin/appPermission';
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
	            	var data = response.data.app_permissions;
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
	
	$('#app_permission_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
	            {
	                property: 'appkey',
	                label: '所属模块',
	                sortable: true
	            },
	            {
	                property: 'module',
	                label: '子模块名',
	                sortable: false
	            },
	            {
	                property: 'controller',
	                label: '控制器',
	                sortable: false
	            },
	            {
	                property: 'action',
	                label: '动作',
	                sortable: true
	            },
	            {
	            	property: 'permission_id',
	            	label: '权限 ID',
	            	sortable: false
	            },
	            {
	            	property: 'permission_name',
	            	label: '权限名',
	            	sortable: false
	            },
	            {
	            	property: 'set_action',
	            	label: '操作',
	            	sortable: false
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	var p_edit = "ap_appkey=" + item.appkey + "&ap_module=" + item.module + "&ap_controller=" + item.controller + "&ap_action=" + item.action;
	            	item.set_action = '<a href="/admin/appPermission/edit?' + p_edit + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
	            			'<a href="/admin/appPermission/delete?permission_id=' + item.permission_id + '" class="load-content" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}