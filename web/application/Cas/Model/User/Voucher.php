<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Cas
 * @package    Cas_Model
 * @subpackage Cas_Model_User
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @version    SVN: $Id$
 */

class Cas_Model_User_Voucher extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'user_voucher';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';
    
    
    /*
     * 根据userid  获取代金券
     */
    public function GetContentByUserid($where,$order,$perpage,$offset)
    {
        //$where .= " AND voucher_content.disabled = 1";
        $where .= " AND 1 = 1";
        $select = $this->getAdapter()->select()->from($this->getTable());
    
        //$select->joinLeft('voucher_content' , 'voucher_content.voucher_id = cas_user_voucher.voucher_id',array('voucher_money', 'valid_data', 'disabled' ,'use_money'));
    
        //$select->group('cas_user_voucher.id');
    
        $row = $select->where($where)->query()->fetchAll();
        return $row ? $row : null;
    }
    
    public function sendCoupon($type,$userid){
        $user_voucher = Cas_Model_User::instance()->fetchByWhere("userid=$userid");
        /* 生成用户优惠券记录  */
        $voucher = Voucher_Model_Content::instance()->fetchByWhere("disabled=1");
        
        if(!empty($voucher)){
            foreach ($voucher as $k=>&$v){
                //注册时发
                if(!empty($v['voucher_type'])){
                    $voucher_type_arr = explode(',', $v['voucher_type']);
                    if(in_array($type, $voucher_type_arr)){
                        //给自己发
                        $arr['voucher_money'] = $v['voucher_money'];
                        $arr['voucher_id'] = $v['voucher_id'];
                        $arr['userid'] = $userid;
                        $arr['voucher_status'] = 1;
                        $arr['start_data'] = date(DATETIME_FORMAT);
                        $arr['valid_data'] = date('Y-m-d',strtotime("+{$v['valid_data']} day"));
                        $arr['use_money'] = $v['use_money'];
                        $arr['creat_time'] = date(DATETIME_FORMAT);
                        $arr['type'] = $v['type'];
        
                        $arr['order_money'] = $v['order_money'];
                        $arr['increase_interest'] = $v['increase_interest'];
                        $result = Cas_Model_User_Voucher::instance()->addForEntity($arr);
                        //给推荐人发
                        if($v['to_recommender']==1){
                            if(!empty($user_voucher[0]['parent_id'])){
                                $arr['voucher_money'] = $v['voucher_money'];
                                $arr['voucher_id'] = $v['voucher_id'];
                                $arr['userid'] = $user_voucher[0]['parent_id'];
                                $arr['voucher_status'] = 1;
                                $arr['start_data'] = date(DATETIME_FORMAT);
                                $arr['valid_data'] = date('Y-m-d',strtotime("+{$v['valid_data']} day"));
                                $arr['use_money'] = $v['use_money'];
                                $arr['creat_time'] = date(DATETIME_FORMAT);
                                $arr['type'] = $v['type'];
        
                                $arr['order_money'] = $v['order_money'];
                                $arr['increase_interest'] = $v['increase_interest'];
                                $result = Cas_Model_User_Voucher::instance()->addForEntity($arr);
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function sendCouponByOrder($userid,$buy_money){
        $user_voucher = Cas_Model_User::instance()->fetchByWhere("userid=$userid");
        /* 生成用户优惠券记录  */
        $voucher = Voucher_Model_Content::instance()->fetchByWhere("disabled=1 and (type=1 or type=3)");
    
        if(!empty($voucher)){
            foreach ($voucher as $k=>&$v){
                //下单时发
                if(!empty($v['voucher_type'])){
                    $voucher_type_arr = explode(',', $v['voucher_type']);
                    //首次下单返
                    if(in_array(2, $voucher_type_arr)){
                        //用户是否为首次下单
                        if($user_voucher[0]['is_buy']==0){
                                //给自己发
                                $arr['voucher_money'] = $v['voucher_money'];
                                $arr['voucher_id'] = $v['voucher_id'];
                                $arr['userid'] = $userid;
                                $arr['voucher_status'] = 1;
                                $arr['start_data'] = date(DATETIME_FORMAT);
                                $arr['valid_data'] = date('Y-m-d',strtotime("+{$v['valid_data']} day"));
                                $arr['use_money'] = $v['use_money'];
                                $arr['creat_time'] = date(DATETIME_FORMAT);
                                $arr['type'] = $v['type'];
                                
                                $arr['order_money'] = $v['order_money'];
                                $arr['increase_interest'] = $v['increase_interest'];
                                $result = Cas_Model_User_Voucher::instance()->addForEntity($arr);
                            //给推荐人发
                            if($v['to_recommender']==1){
                                if(!empty($user_voucher[0]['parent_id'])){
                                    $arr['voucher_money'] = $v['voucher_money'];
                                    $arr['voucher_id'] = $v['voucher_id'];
                                    $arr['userid'] = $user_voucher[0]['parent_id'];
                                    $arr['voucher_status'] = 1;
                                    $arr['start_data'] = date(DATETIME_FORMAT);
                                    $arr['valid_data'] = date('Y-m-d',strtotime("+{$v['valid_data']} day"));
                                    $arr['use_money'] = $v['use_money'];
                                    $arr['creat_time'] = date(DATETIME_FORMAT);
                                    $arr['type'] = $v['type'];
                            
                                    $arr['order_money'] = $v['order_money'];
                                    $arr['increase_interest'] = $v['increase_interest'];
                                    $result = Cas_Model_User_Voucher::instance()->addForEntity($arr);
                                }
                            }
                        }
                    }
                    
                    if(in_array(3, $voucher_type_arr)){
                            //每次下单必须满多少金额
                            if((!empty($v['order_money'])) && ($buy_money>=$v['order_money'])){
                                //给自己发
                                $arr['voucher_money'] = $v['voucher_money'];
                                $arr['voucher_id'] = $v['voucher_id'];
                                $arr['userid'] = $userid;
                                $arr['voucher_status'] = 1;
                                $arr['start_data'] = date(DATETIME_FORMAT);
                                $arr['valid_data'] = date('Y-m-d',strtotime("+{$v['valid_data']} day"));
                                $arr['use_money'] = $v['use_money'];
                                $arr['creat_time'] = date(DATETIME_FORMAT);
                                $arr['type'] = $v['type'];
                            
                                $arr['order_money'] = $v['order_money'];
                                $arr['increase_interest'] = $v['increase_interest'];
                                $result = Cas_Model_User_Voucher::instance()->addForEntity($arr);
                            
                                //给推荐人发
                                if($v['to_recommender']==1){
                                    if(!empty($user_voucher[0]['parent_id'])){
                                        $arr['voucher_money'] = $v['voucher_money'];
                                        $arr['voucher_id'] = $v['voucher_id'];
                                        $arr['userid'] = $user_voucher[0]['parent_id'];
                                        $arr['voucher_status'] = 1;
                                        $arr['start_data'] = date(DATETIME_FORMAT);
                                        $arr['valid_data'] = date('Y-m-d',strtotime("+{$v['valid_data']} day"));
                                        $arr['use_money'] = $v['use_money'];
                                        $arr['creat_time'] = date(DATETIME_FORMAT);
                                        $arr['type'] = $v['type'];
                            
                                        $arr['order_money'] = $v['order_money'];
                                        $arr['increase_interest'] = $v['increase_interest'];
                                        $result = Cas_Model_User_Voucher::instance()->addForEntity($arr);
                                    }
                                }
                            }
                        }
                }
            }
        }
    }
    
    
    /**
     * @return Cas_Model_User_Voucher
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
