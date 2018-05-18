$(document).ready(function () {

    $("#word").click(function () {
        var order_no = $("#order_no").val();

        $.post('/cas/order/getWord', {"order_no": order_no}, function (response) {
            if (response.status == 0) {
                var url = response.data.url;
                window.location.href = url;
            } else {
                alert(response.error);
            }

        });

//},'json');

    });
});