$(document).ready(function () {

//请求格式： .../GetPageData?query=test&pageIndex=0&pageSize=10
//返回数据： {"data":[1,2,3,4,5,6,7,8,9,10],"total":800}
    $("#paging").page({
        pageSize:7,
        firstBtnText: '首页',
        lastBtnText: '尾页',
        prevBtnText: '上一页',
        nextBtnText: '下一页',
        showInfo: true,
        showJump: true,
        jumpBtnText: '跳转',
//        showPageSizes: true,
        infoFormat: '共{total}条', //{start} ~ {end}条，

        remote: {
            url: '/cas/user/getincome', //请求地址
            params: {}, //自定义请求参数
            success: function (result, pageIndex) {
                var html = '';

                $.each(result.content, function (index, content) {
                    if (content.goods_name == null || content.goods_name == undefined || content.goods_name =='') {
                        content.goods_name = '';
                    }
                    
                    html += '<tr>' +
                            '<td>'+content.goods_name+'</td>' +
                            '<td>'+content.ctime+'</td>' +
                            '<td>'+content.status+content.money+'</td>' +
                            '<td>'+content.type+'</td>' +
                            '<td>'+content.flow_asset+'</td>' +
                            '</tr>';
                });

                $("#tbody").empty();
                $("#tbody").html(html);
                //回调函数
                //result 为 请求返回的数据，呈现数据
            },
            pageIndexName: 'pageIndex', //请求参数，当前页数，索引从0开始
            pageSizeName: 'pageSize', //请求参数，每页数量
            totalName: 'totalnum', //指定返回数据的总数据量的字段名
        }
    });
});