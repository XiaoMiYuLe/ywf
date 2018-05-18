<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class PushController extends CasAdminAbstract
{

    public function push()
    {
        $now = date("Y-m-d");
        $order_status4 = Bts_Model_Order::instance()->fetchOrders($now);
        if($order_status4){
            foreach ($order_status4 as $v){
                $user = Cas_Model_User::instance()->fetchByWhere("userid = {$v['userid']} and status=0");
                $content = "【亿万福】尊敬的客户：您今天有".$v['count']."笔订单到期，欢迎您续投或转投亿万福系列产品:周周福、月月福、季季福、总有福气伴着您";
                
                $data = array(
                    'type' => 'phone',
                    'action'=>'7',
                    'send_to'=>$user[0]['phone'],
                    'content'=>$content,
                    'ctime'=>date(DATETIME_FORMAT),
                );
                 
                $ID = Cas_Model_Code::instance()->addForEntity($data);
                
                $send_user = Sms_SendSms::testSingleMt1('86'.$user[0]['phone'],$content );
                
                if(!empty($user[0]['parent_id'])){
                    $parenter = Cas_Model_User::instance()->fetchByWhere("userid={$user[0]['parent_id']}");
                    $name = $parenter[0]['username'] ? $parenter[0]['username'] : '';
                    $content1 = "【亿万福】尊敬的经纪人：您客户".$name."今天有".$v['count']."笔订单到期，欢迎续投。";
                    
                    $data1 = array(
                        'type' => 'phone',
                        'action'=>'8',
                        'send_to'=>$parenter[0]['phone'],
                        'content'=>$content1,
                        'ctime'=>date(DATETIME_FORMAT),
                    );
                     
                    $ID1 = Cas_Model_Code::instance()->addForEntity($data1);
                    
                    $send_ecomaner = Sms_SendSms::testSingleMt1('86'.$parenter[0]['phone'],$content1);
                }
            }
        }
    }
    
    public function birthday(){
        $now = date("md");
        $where = "idcard like '%".$now."%' and status=0";
        $user = Cas_Model_User::instance()->fetchByWhere($where);
        if($user){
            foreach($user as $v){
                $content2 = "【亿万福】在这属于您的精彩日子里，我们送上最诚挚的祝福：祝您生日快乐！爱情甜蜜！工作顺利！愿幸福常伴您左右！";
                
                $data2 = array(
                    'type' => 'phone',
                    'action'=>'9',
                    'send_to'=>$v['phone'],
                    'content'=>$content2,
                    'ctime'=>date(DATETIME_FORMAT),
                );
                 
                $ID2 = Cas_Model_Code::instance()->addForEntity($data2);
                
                $send_ecomaner = Sms_SendSms::testSingleMt1('86'.$v['phone'],$content2);
            }
        }
    }
    
}

// End ^ LF ^ encoding










