$(document).ready(function() {

	var promotion_id =  $("#promotion_id").val();
	//URL地址
	if (promotion_id == "") {
		var url = "/promotionadmin/index/getContentsAdd";
	}else {
		var url = "/promotionadmin/index/getContentsEdit";
	}
	
	refreshListing(url);
	
	//编辑商品增加选中样式
	reset_related_goods_listing();
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
    $("input[name=related_key]").on('keypress', function (event) {
	    if (event.which == '13') {
	    	$('#content_listing').datagrid('reload');
	    	return false;
	    }
	});

	/**
	 * 重载已选关联商品列表的拖拽事件
	 */
	$('#selected_related_goods').find('ul').sortable('refresh');

	/**
	 * 选择一个商品关联活动
	 */
	$("#content_listing > tbody").delegate('.select-single', 'click', function(event){

		if ($(this).is(':checked')) {
			
			var content_id = $(this).val();	
			var promotion_id = $("select[name=category_id]").val();
			if (promotion_id == 0) {
				alert("请选择活动类型");
				return false;
			}
			var obj = $(this);
			//判断活动类型是否重叠
		    $.ajax({
		    	url:'/promotionadmin/index/checkActive',
		    	data: {content_id:content_id,promotion_id:promotion_id},
		    	timeout: 2000,
		    	async : false,
		    	dataType : "json",
		    	success:function(data){
		    		if (data.status == 0) {		    			
		    			select_related_goods(obj.parent().parent());	
		    		}else if (data.status == 1) {
		    			alert(data.error);
		    			event.preventDefault();
		    		}
		    	},
		    	error:function(){
		    		alert("网络异常");
		    	}
		    })
			
		} else {
			remove_related_goods($('li#li_related_goods_' + $(this).val()));
		}
		
	});
	
	/**
	 * 移除一个已选择的关联商品
	 */
	$("#selected_related_goods").delegate('.fa-remove-related-goods', 'click', function(){
		remove_related_goods($(this).parent().parent());
	});
});


/**
 * 将商品池中已被选中的关联商品置为已选中状态 - 用于编辑及列表翻页的情况
 */
function reset_related_goods_listing() {
	$('#selected_related_goods').find('ul>li').each(function(){
		var content_id = $(this).attr('content_id');
		$('#select_single_' + content_id).prop('checked', true);
	});
}

/**
 * 选择一个关联商品
 */
function select_related_goods(obj) {
	var template_selected_related_goods = '<li class="b-b m-t-none-reset" id="li_related_goods_{#content_id#}" content_id="{#content_id#}" draggable="true">' +
        	'<a href="javascript:;">' +
        	'<i title="移除该商品" class="fa fa-times pull-right m-t-xs fa-remove-related-goods"></i>' +
        	'<i class="fa fa-fw fa-ellipsis-v"></i><font class="related-goods-name">{#name#}</font>' +
        	'</a></li>';
	
	var content_id = obj.find('td').eq(1).text();
	var goods_name = obj.find('td').eq(3).text();
 
	var tsg = template_selected_related_goods.replace(/{#content_id#}/g, content_id)
			.replace('{#name#}', goods_name);
	
	/* 将新选择属性加入到已选列表，并重载该列表的拖拽事件 */
	$('#selected_related_goods').find('ul').append(tsg).sortable('refresh');
}

/**
 * 移除一个已选择的关联商品
 */
function remove_related_goods(obj) {
	$('#select_single_' + obj.attr('content_id')).removeAttr('checked');
	obj.remove();
}

function refreshListing(url) {
	
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
	       
	        var self = this;

	        setTimeout(function () {
	            var data = $.extend(true, [], self._data);
	            $.ajax(url, {
	                data: {
	                	rstype:"json",
	                	pageIndex: options.pageIndex,
	                    pageSize: options.pageSize,
	                    key:$('input[name=related_key]').val(),
	                    category_id: $('select[name=related_category_id]').val(), 
	                    brand_id : $('select[name="related_brand_id"]').val(), 
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

                    // 将属性池中已被选中的备选属性置为已选中状态 - 用于编辑的情况
                    reset_related_goods_listing();
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
	                property: 'content_id',
	                label: 'ID',
	                sortable: false
	            },
	            {
	            	property: 'category_name',
	            	label: '所属分类',
	            	sortable: false
	            },
	            {
	            	property: 'name',
	            	label: '商品名称',
	            	sortable: false
	            },
	            {
	            	property :"brand_name",
	            	label : "所属品牌",
	            	sortable: false
	            },
	            {
	            	property: 'sku',
	            	label: '商品编号',
	            	sortable: false
	            },
	            {
	                property: 'price',
	                label: '价格',
	                sortable: false
	            },
	            {
	                property: 'spec',
	                label: '规格',
	                sortable: false
	            },
	            
	        ],
	        formatter: function (items) {
	            $.each(items, function (index, item) {
	            	item.checkbox = '<input type="checkbox" name="post[]" id="select_single_' + item.content_id + '" class="select-single" value="' + item.content_id + '">';
	            });
	        }
	    }),
	    loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
	    itemsText: '项',
	    itemText: '项',
	    dataOptions: { pageIndex: 0, pageSize: 15 }
	});
}
