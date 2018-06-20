<?php

/**
 * 父类，存放公共方法
 */
class P2P_Model_Public extends Zeed_Db_Model
{
    /*
     * 格式化信息
     */
    public function dataFormat($data = null, $status = 0, $error_msg = '')
    {
        $new_data['status'] = $status;
        $new_data['error_msg'] = $error_msg;
        $new_data['data'] = $data;
        return $new_data;
    }

    /*资金流水编号/订单号*/
    public function getOrderNo()
    {
        return 'P'.date('YmdH'). substr(microtime(),2,7);
    }

}

// End ^ LF ^ encoding
