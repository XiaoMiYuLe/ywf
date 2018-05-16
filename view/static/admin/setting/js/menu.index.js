$(document).ready(function() {
	refreshListing();
    
    /**
     * 刷新搜索
     */
    $(".action-refresh, #action_search").on('click',function(){
    	$('#menu_listing').datagrid('reload');
    	return false;
    });
    
    /**
	 * 删除 - 单条
	 */
    $("#menu_listing").delegate('.operate-delete', 'click', function(){
		var menu_id = $(this).attr("menu_id");
		var $tr = $(this).parent().parent();
		doDelete(menu_id, $tr);
	});
    
    /**
     * 审核
     */
    $('#menu_listing').delegate('.switch-sm', 'click', function(){
    	var menu_id = $(this).attr('menu_id');
    	publishMenu(menu_id);
    	return false;
    });
});


/**
 * 更改导航状态
 */
function publishMenu(id) {
	$.ajax({
    	type:'post',
        url:'/setting/menu/publish',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			var d = data.data;
    			if (d.if_show == 1) {
    				$('#menu_' + id).prop('checked', true);
    			} else {
    				$('#menu_' + id).prop('checked', false);
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
function doDelete(id, $tr) {
	var del = confirm('确定要删除所选导航吗？');
	if (! del) {return false;}
	
	/* 执行删除 */
	$.ajax({
    	type:'post',
        url:'/setting/menu/delete',
        data:'menu_id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			$tr.remove();
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
	        var url = '/setting/menu';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	group_id:$('input[name=group_id]:last').val()
	                },
	                dataType: 'json',
	                async: true,
	                type: 'GET'
	            }).done(function (response) {
	            	var data = response.data.menus;
                    if (! data) {
                    	return false;
                    }

                    if (self._formatter) self._formatter(data);

                    callback({ data: data });
                }).fail(function (e) {

                });
	        }, self._delay);
	    }
	};
	
	$('#menu_listing').datagrid({
	    dataSource: new DataGridDataSource({
	        // Column definitions for Datagrid
	        columns: [
	            {
	                property: 'menu_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'menu',
	            	label: '导航名称',
	            	sortable: false
	            },
	            {
	            	property: 'link_type',
	            	label: '链接方式',
	            	sortable: false
	            },
	            {
	            	property: 'link',
	            	label: '链接地址',
	            	sortable: false
	            },
	            {
	            	property: 'sort_order',
	            	label: '序号',
	            	sortable: false
	            },
	            {
	            	property: 'if_show',
	            	label: '状态',
	            	sortable: false
	            },
	            {
	            	property: 'operate',
	                label: '操作'
	            }
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.menu = item.str_padding + item.menu;
	            	item.link = item.link_type == 1 ? item.folder : item.url;
	            	item.link_type = item.link_type == 1 ? '内部地址' : '外部地址';
	            	
	            	var is_publish = item.if_show == 1 ? 'checked="checked"' : '';
	                item.if_show = '<label class="switch-sm" menu_id="' + item.menu_id + '">' + 
	                		'<input type="checkbox" id="menu_' + item.menu_id + '" ' + is_publish + ' />' + 
	                		'<span></span></label>';
	                
	            	item.operate = '<a href="/setting/menu/edit/?menu_id=' + item.menu_id + '" data-toggle="ajaxModal" class="operate-edit" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
							'<a href="javascript:;" class="operate-delete" menu_id="' + item.menu_id + '" title="删除"><i class="fa fa-times"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { }	
	});
}