<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

class Promotion_Model_Goods extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'goods';

    /**
     * @var string Primary key.
     */
    protected $_primary = 'promotion_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'promotion_';
    
    
    public function GetGoodsToPromotion($goodsIds)
    {
        if (!is_array($goodsIds) || empty($goodsIds)) {
            return array();
        }
        $goodsIds = implode($goodsIds, ',');
        $select = $this->getAdapter()->select()->from($this->getTable(),array('promotion_id','content_id'));
        $select->where("promotion_content.status=1 and promotion_content.start_time<='".date("Y-m-d",time())."'  and promotion_content.end_time>='".date("Y-m-d",time())."' and promotion_goods.content_id in ($goodsIds)");
        $select->joinLeft('promotion_content','promotion_content.promotion_id = promotion_goods.promotion_id',array('rules','category_id'));
        $rows = $select->query()->fetchAll();
        //按活动格式化数组
        if (!empty($rows)) {
           foreach ($rows as $value) {
                $temp1 = $value['rules'];
                $temp2 = $value['category_id'];
                unset($value['rules']);
                unset($value['category_id']);
                $return[$value['promotion_id']]['promotion_goods'][] = $value;
                $return[$value['promotion_id']]['promotion_rules'] = unserialize($temp1);
                $return[$value['promotion_id']]['promotion_type'] = $temp2;
            }
        }
        return $return ? $return : array();
    }

    public function GetPromotionByGoods($goodsId){
        $select = $this->getAdapter()->select()->from($this->getTable(),array('promotion_id','content_id'));
        $select->where("promotion_content.status=1 and promotion_content.start_time<='".date("Y-m-d",time())."'  and promotion_content.end_time>='".date("Y-m-d",time())."' and promotion_goods.content_id = $goodsId");
        $select->joinLeft('promotion_content','promotion_content.promotion_id = promotion_goods.promotion_id',array('rules','category_id','title as promotion_title'));
        $rows = $select->query()->fetch();

        return $rows ? $rows : array();
    }

    /**
     * @return Promotion_Model_Goods
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
// End ^ Native EOL ^ UTF-8