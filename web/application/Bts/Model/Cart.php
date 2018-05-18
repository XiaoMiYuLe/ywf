<?php
/**
 * iNewS Project
 * 购物车模型
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright  Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 9, 2010
 * @version    SVN: $Id$
 */
class Bts_Model_Cart extends Zeed_Db_Model
{
    
    /**
     * @var string The table name.
     */
    protected $_name = 'cart';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'cart_id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'bts_';

    /**
     * 获取某用户的购物车列表
     *
     * @param integer $userid            
     * @return array
     */
    public function getAllGoodsByUserId ($userid, $field = '*')
    {
        $where = 'bts_cart.userid = ' . $userid;
        $select = $this->getAdapter()->select()->from($this->getTable(),$field);
        $select->JoinLeft('goods_content','goods_content.content_id = bts_cart.content_id');
        $row = $select->where($where)->query()->fetchAll();
        
        /* 计算总金额 */
        foreach ($row as $k => $v) {
            $sum_amount += $v['price'] * $v['quantity'];
            $sum_quantity += $v['quantity'];
        }
        
        $result['data'] = $row;
        $result['sum_amount'] = $sum_amount;
        $result['sum_quantity'] = $sum_quantity;

        return $result;
    }
    
    /**
     * 获取某用户的购物车其中的某几个
     *
     * @param integer $userid
     * @return array
     */
    public function getOrderGoodsByUserId ($userid, $wh = '', $field = '*')
    {
        $where = 'bts_cart.userid = ' . $userid . $wh;
        $select = $this->getAdapter()->select()->from($this->getTable(),$field);
        $select->JoinLeft('goods_content','goods_content.content_id = bts_cart.content_id');
        $row = $select->where($where)->query()->fetchAll();
    
        /* 计算总金额 */
        foreach ($row as $k => $v) {
            $sum_amount += $v['price'] * $v['quantity'];
            $sum_quantity += $v['quantity'];
        }
    
        $result['data'] = $row;
        $result['sum_amount'] = $sum_amount;
        $result['sum_quantity'] = $sum_quantity;
    
        return $result;
    }
    
    /**
     * 判断购物车是否存在该商品
     * 
     * @param integer $userid            
     * @return array
     */
    public function isExistGoodsByUserId ($userid, $content_id, $isLogin = false)
    {
        if (! is_string($userid) || empty($userid)) {
            return null;
        }
        
        $where = ($isLogin === true ? 'userid' : 'session_id') . '=\'' . $userid . '\' and content_id=' . $content_id;
        
        $select = $this->select()
            ->from($this->getTable())
            ->where($where)
            ->limit(1);
        
        $rows = $select->query()->fetchAll();
        
        return $rows;
    }
    
    /**
     * 加入购物车
     * 
     * @param array $set            
     * @return integer
     */
    public function addToCart ($set)
    {
        if ($set instanceof Bts_Entity_Cart) {
            $data = $set->toArray();
        } else {
            $entity = new Bts_Entity_Cart();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->insert($data);
    }

    /**
     * 更新购物车信息
     *
     * @param integer $id            
     * @return boolean
     */
    public function updateByWhere ($where, $set)
    {
        if ($set instanceof Bts_Entity_Cart) {
            $data = $set->toArray();
        } else {
            $entity = new Bts_Entity_Cart();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->update($data, $where);
    }

    /**
     * 删除购物车商品
     *
     * @param integer $id            
     * @return boolean
     */
    public function deleteById ($id)
    {
        return $this->getAdapter()->delete($this->getTable(), $this->getAdapter()->quoteInto('cart_id = ?', $id));
    }

    /**
     * @return Bts_Model_Cart
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
    
}

// End ^ LF ^ encoding

