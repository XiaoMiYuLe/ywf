$(document).ready(function () {
    refreshListing();
    /**
     * 刷新或搜索
     */
    $('.action-refresh').on('click', function () {
        $('#userListing').datagrid('reload');
        return false;
    });


    /**
     * 搜索对回车的支持
     */
    $("input[name=key]").on('keypress', function (event) {
        if (event.which == '13' && $(this).val()) {
            $('#userListing').datagrid('reload');
            return false;
        }
    });

    /**
     * 过滤激活状态
     */
    $('#statusSelect').change(function(){
        $('#userListing').datagrid('reload');
        return false;
    });
    $("#userListing").on('change', '.switch-sm input', function () {
        var userid = $(this).data('userid');
        var checked = $(this).prop('checked');
        var status = checked ? 0 : 1;
        $.post('/casadmin/invitation/changeStatus', {"userid": userid, "status":status}, function (response) {
            if(response.status==1){
                alert("操作失败："+response.error);
            }
        });
    });
    /**
     * 审核通过
     */
    $("#userListing").on('click', '.operate-tongguo', function () {
    	userid = $(this).attr('userid');
    	tr = $(this).parent().parent();
        $.ajax({
            type: 'post',
            url: '/casadmin/invitation/tongguo',
            data: 'userid=' + userid,
            dataType: 'json',
            timeout: 60000,
            success: function (data) {
                if (data.status == 0) {
                	tr.remove();
                    alert('审核成功');
                } else {
                    alert('审核失败，请稍后重试');
                }
                return false;
            },
            error: ajaxError
        });
    });
    
    /**
     * 重新上传名片
     */
    $("#userListing").on('click', '.operate-shangchuang', function () {
    	userid = $(this).attr('userid');
        $.ajax({
            type: 'post',
            url: '/casadmin/invitation/shangchuang',
            data: 'userid=' + userid,
            dataType: 'json',
            timeout: 60000,
            success: function (data) {
                if (data.status == 0) {
                	location.reload();
                } else {
                    alert('操作失败，请稍后重试');
                }
                return false;
            },
            error: ajaxError
        });
    });
    
    /**
     * 审核未通过
     * 
     */
    $("#userListing").on('click', '.operate-weitongguo', function () {
    	userid = $(this).attr('userid');
        $.ajax({
            type: 'post',
            url: '/casadmin/invitation/weitongguo',
            data: 'userid=' + userid,
            dataType: 'json',
            timeout: 60000,
            success: function (data) {
                if (data.status == 0) {
                	location.reload();
                } else {
                    alert('操作失败，请稍后重试');
                }
                return false;
            },
            error: ajaxError
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
            var url = '/casadmin/invitation/index';
            var self = this;

            setTimeout(function () {

                var data = $.extend(true, [], self._data);

                $.ajax(url, {
                    data: {
                        rstype: "json",
                        pageIndex: options.pageIndex,
                        pageSize: options.pageSize,
                        key: $('input[name=key]').val(),
                        status:$('select[name=status]').val(),
                        is_invitaiton:$('select[name=is_invitaiton]').val(),
                    },
                    dataType: 'json',
                    async: true,
                    type: 'GET'
                }).done(function (response) {
                    var data = response.data.users;

                    if (!data) {
                        return false;
                    }

                    var count = response.data.count;//设置data.total
                    // PAGING
                    var startIndex = options.pageIndex * options.pageSize;
                    var endIndex = startIndex + options.pageSize;
                    var end = (endIndex > count) ? count : endIndex;
                    var pages = Math.ceil(count / options.pageSize);
                    var page = options.pageIndex + 1;
                    var start = startIndex + 1;

                    if (self._formatter) self._formatter(data);

                    callback({data: data, start: start, end: end, count: count, pages: pages, page: page});
                }).fail(function (e) {

                });
            }, self._delay);
        }
    };

    $('#userListing').datagrid({
        dataSource: new DataGridDataSource({
            // Column definitions for Datagrid
            columns: [
//	            {
//	            	property: 'a',
//	            	label: ''
//	            },
 			/*	{
                    property: 'userid',
                    label: 'ID',
                    sortable: false
                },*/
                {
                    property: 'ctime',
                    label: '注册时间',
                    sortable: false
                },
                {
                    property: 'username',
                    label: '用户姓名',
                    sortable: false
                },
                {
                    property: 'phone',
                    label: '手机号码',
                    sortable: false
                },
                {
                    property: 'avatar',
                    label: '名片',
                    sortable: false
                },
                {
                    property: 'idcard',
                    label: '身份证号码',
                    sortable: false
                },
                {
                    property: 'asset',
                    label: '账户余额',
                    sortable: true
                },
                {
                    property: 'is_ecoman',
                    label: '是否经纪人',
                    sortable: false
                },
                {
                    property: 'is_invitaiton',
                    label: '审核状态',
                    sortable: false
                },
                {
                    property: 'audit_time',
                    label: '审核时间',
                    sortable: false
                },
                {
                    property: 'status',
                    label: '账户状态',
                    sortable: false
                },
                {
                    property: 'brokerage_money',
                    label: '总计佣金',
                    sortable: false
                },
                {
                    property: 'action',
                    label: '操作',
                    sortable: false
                },
            ],
            formatter: function (items) {
                $.each(items, function (index, item) {
                	if(item.is_invitaiton ==2){
                		item.is_invitaiton ='审核中';
                	} else if(item.is_invitaiton ==3){
                		item.is_invitaiton ='重新上传名片';
                	}else if(item.is_invitaiton ==4){
                		item.is_invitaiton ='审核未通过';
                	}

                	item.is_ecoman = item.is_ecoman == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-ban text-danger"></i>';
                	item.action = '<a href="/casadmin/invitation/edit?userid='+item.userid+'" data-toggle="ajaxModal" class="operate-edit" title="编辑"><i class="fa fa-pencil"></i></a>';
                	item.avatar = "<a href="+item.avatar+" target=_blank><img  src="+item.avatar+" width=50 height=50></a>";
                	
                    item.status = item.status == 0 ? '<label class="switch-sm status-switch"> <input type="checkbox" checked="checked" data-userid="' + item.userid + '"> <span></span></label>' : '<label class="switch-sm status-switch"> <input type="checkbox" data-userid="' + item.userid + '"> <span></span></label>';
                });
            }
        }),
        loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
        itemsText: '项',
        itemText: '项',
        dataOptions: {pageIndex: 0, pageSize: 15}
    });
}