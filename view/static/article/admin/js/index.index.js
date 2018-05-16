$(document).ready(function() {
	
	refreshListing();
	
	/**
	 * 载入树形分类
	 */
	var zTreeObj;
	var setting = {
	    async: {
	    	enable: true,
		    url: "/articleadmin/category/getZTree",  
		    autoParam: ["name", "id"]
		},
		view: {
			showIcon: false
		},
		data: {
			id: "id",
		},
		callback:{
			onClick: function(treeId, treeNode) {  
				var Node = $.fn.zTree.getZTreeObj(treeNode).getSelectedNodes()[0];  
				$("#category_id").val(Node.id);
				$('#content_listing').datagrid('reload');
            }
		}
	};
	zTreeObj = $.fn.zTree.init($("#treeDemo"), setting);
	
	/**
	 * 返回所有分类的文章列表
	 */
	$('#category_all').click(function(){
		zTreeObj.cancelSelectedNode();
		$("#category_id").val(0);
		$('#content_listing').datagrid('reload');
	});
    
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
     * 发布
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	if($(this).attr('content_id')){
	    	var content_id = $(this).attr('content_id');
	    	publishArticle(content_id);
	    	return false;
    	}
    });
	
	/**
     * 推荐
     */
    $('#content_listing').delegate('.switch-sm', 'click', function(){
    	if($(this).attr('content_a_id')){
	    	var content_a_id = $(this).attr('content_a_id');
	    	recommendedArticle(content_a_id);
	    	return false;
    	}
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
});


/**
 * 发布
 */
function publishArticle(id) {
	$.ajax({
    	type:'post',
        url:'/articleadmin/index/publish',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			var d = data.data;
    			if (d.status == 1) {
    				$('#publish_' + id).prop('checked', true);
    			} else {
    				$('#publish_' + id).prop('checked', false);
    			}
    		} else {
    			alert(data.error);
    		}
    		return false;
    	}
    });
}

/**
 * 推荐
 */
function recommendedArticle(id) {
	$.ajax({
    	type:'post',
        url:'/articleadmin/index/recommended',
        data:'id=' + id,
        dataType:'json',
        timeout:60000,
        success:function(data){
    		if (data.status == 0) {
    			var d = data.data;
    			if (d.recommended == 1) {
    				$('#recommended_' + id).prop('checked', true);
    			} else {
    				$('#recommended_' + id).prop('checked', false);
    			}
    			 location.reload();
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
function doTrashContent(id) {
	var del = confirm('确定要将所选文章扔进回收站吗？');
	if (! del) {return false;}
	
	/* 执行 */
	$.ajax({
    	type:'post',
        url:'/articleadmin/index/trash',
        data:'id=' + id,
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
	        var url = '/articleadmin';
	        var self = this;
	        
	        setTimeout(function () {
	
	            var data = $.extend(true, [], self._data);
	
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    category: $('#category_id').val(),
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
	            {
	                property: 'checkbox',
	                label: '<input type="checkbox" />'
	            },
	            {
	            	property: 'a',
	            	label: ''
	            },
	            {
	                property: 'content_id',
	                label: 'ID',
	                sortable: true
	            },
	            {
	            	property: 'category_name',
	            	label: '分类',
	            	sortable: false
	            },
	            {
	            	property: 'title',
	            	label: '标题',
	            	sortable: false
	            },
	            {
	                property: 'pinned',
	                label: '权重',
	                sortable: true
	            },
	            {
	                property: 'mtime',
	                label: '修改时间',
	                sortable: true
	            },
	            {
	                property: 'recommended',
	                label: '推荐',
	                sortable: false
	            },
	            {
	            	property: 'publish',
	            	label: '发布',
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
	                item.a = '<a href="/articleadmin/index/preview/?id=' + item.content_id + '" data-toggle="ajaxModal" class="modal-detail"><i class="fa fa-search-plus"></i></a>';
//	                item.recommended = item.recommended == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-ban text-danger"></i>';

	                var is_recommended = item.recommended == 1 ? 'checked="checked"' : '';
	                if(is_recommended){
		                item.recommended = '<label class="switch-sm" title="关闭推荐" content_a_id="' + item.content_id + '">' + 
		                		'<input type="checkbox" id="recommended_' + item.content_id + '" ' + is_recommended + ' />' + 
		                		'<span></span></label>';
	                }else{
	                	item.recommended = '<label class="switch-sm" title="启用推荐" content_a_id="' + item.content_id + '">' + 
                		'<input type="checkbox" id="recommended_' + item.content_id + '" ' + is_recommended + ' />' + 
                		'<span></span></label>';
	                }
	                
	                
	                var is_publish = item.status == 1 ? 'checked="checked"' : '';
	                if(is_publish){
		                item.publish = '<label class="switch-sm" title="关闭发布" content_id="' + item.content_id + '">' + 
		                		'<input type="checkbox" id="publish_' + item.content_id + '" ' + is_publish + ' />' + 
		                		'<span></span></label>';
	                }else{
	                	item.publish = '<label class="switch-sm" title="开启发布" content_id="' + item.content_id + '">' + 
                		'<input type="checkbox" id="publish_' + item.content_id + '" ' + is_publish + ' />' + 
                		'<span></span></label>';
	                }
	                
	                item.action = '<a href="/articleadmin/index/edit?content_id=' + item.content_id + '" class="load-content" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;' + 
        					'<a href="javascript:;" class="operate-trash" id="content_' + item.content_id + '" content_id="' + item.content_id + '" title="删除(扔进回收站)"><i class="fa fa-trash-o"></i></a>';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }	
	});
}