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
        $.post('/casadmin/ecoman/changeStatus', {"userid": userid, "status":status}, function (response) {
            if(response.status==1){
                alert("操作失败："+response.error);
            }
        });
    });
    /**
     * 删除用户
     */
    $("#userListing").on('click', '.operate-delete', function () {
        var obj_tr = $(this).parent().parent();
        var userid = $(this).attr('userid');
        var str_confirm = '确定要删除该用户吗？';
        if (!confirm(str_confirm)) {
            return false;
        }

        $.ajax({
            type: 'post',
            url: '/casadmin/index/delete',
            data: 'userid=' + userid,
            dataType: 'json',
            timeout: 60000,
            success: function (data) {
                if (data.status == 0) {
                    obj_tr.remove();
                    alert('删除成功');
                } else {
                    alert('删除失败，请稍后重试');
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
            var url = '/casadmin/ecoman/index';
            var self = this;

            setTimeout(function () {

                var data = $.extend(true, [], self._data);

                $.ajax(url, {
                    data: {
                        rstype: "json",
                        pageIndex: options.pageIndex,
                        pageSize: options.pageSize,
                        key: $('input[name=key]').val(),
                        status:$('select[name=status]').val()
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
                }
            ],
            formatter: function (items) {
                $.each(items, function (index, item) {
                	item.is_ecoman = item.is_ecoman == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-ban text-danger"></i>';
                    item.status = item.status == 0 ? '<label class="switch-sm status-switch"> <input type="checkbox" checked="checked" data-userid="' + item.userid + '"> <span></span></label>' : '<label class="switch-sm status-switch"> <input type="checkbox" data-userid="' + item.userid + '"> <span></span></label>';
                    var str_action = '<a href="/casadmin/ecoman/detail?userid=' + item.userid + '" userid="' + item.userid + '" class="load-content" title="查看"><i class="fa fa-eye"></i></a>';
                    item.action = str_action;
                });
            }
        }),
        loadingHTML: '<span><img src="/static/panel/img/loading.gif"><i class="fa fa-info-sign text-muted" "></i>正在加载……</span>',
        itemsText: '项',
        itemText: '项',
        dataOptions: {pageIndex: 0, pageSize: 15}
    });
}