<?php
/**
 *
 * platform programe
 * @category   Trendible
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @author     shaun.song ( GTalk/Email: songsj125@gmail.com | MSN: ssj125@hotmail.com )
 * @since      2010-6-29
 * @version    SVN: $Id$
 */
class Com_Model_Coupon extends Zeed_Db_Model
{
    
    /**
     * @var string table name
     */
    protected $_name = "coupon";
    
    /**
     * 分类表
     * @var string
     */
    const CATEGORYNAME = 'coupon_category';
    
    /**
     * 分类关联表
     * @var string
     */
    const CATEGORYLINKNAME = 'coupon_categorylink';
    
    /**
     * coupon历史表
     * @var string
     */
    const COUPONHISTORYNAME = 'coupon_history';
    
    /**
     * @var integer primary key
     */
    protected $_primary = 'id';
    
    /**
     * 
     * @var string table prifex
     */
    protected $_prifex = '';
    
    public function __construct()
    {
        parent::__construct(Zeed_Config::loadGroup('database.coupon'));
    }
    /**
     * @return Com_Model_Coupon
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
    
    /**
     * get coupon information
     * 
     * @param integer $id
     * @return null|array
     */
    public function getCouponInfo($id = 0)
    {
        if (! $id || $id <= 0) {
            return null;
        }
        
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('id') . ' = ?', $id);
        $rows = $this->getAdapter()->select()->from($this->getTable())->where($where)->query()->fetch();
        return empty($rows) ? null : $rows;
    }
    
    /**
     * udapte coupon
     * 
     * @param integer $id
     * @param array $set
     * @return number
     */
    public function updateCouponById($id, $set)
    {
        if (! $id || $id <= 0) {
            return 0;
        }
        
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('id') . ' = ?', $id);
        return $this->getAdapter()->update($this->getTable(), $set, $where);
    }
    
    /**
     * add coupon 
     * 
     * @param array $set
     * @return integer
     */
    public function addCoupon($set)
    {
        $this->getAdapter()->insert($this->getTable(), $set);
        
        return $this->getAdapter()->lastInsertId($this->getTable());
    }
    
    /**
     * get history 
     * 
     * @param array|string $where
     * @return array
     */
    public function getCouponHistoryByWhere($where = array())
    {
        $where = $this->batchWhere($where);
        $select = $this->getAdapter()->select()->from(self::COUPONHISTORYNAME);
        if ($where != '') {
            $select->where($where);
        }
        
        $rows = $select->query()->fetchAll();
        return $rows;
    
    }
    
    /**
     * 生成coupon领取历史记录
     *
     * @param array $set
     * @return integer
     */
    public function addCouponHistory($set)
    {
        $this->getAdapter()->insert(self::COUPONHISTORYNAME, $set);
        
        return $this->getAdapter()->lastInsertId(self::COUPONHISTORYNAME);
    }
    
    /**
     * 根据分类ID,$categotyautoid获取link的值
     * 
     * @param integer $categotyautoid
     * @param integer $categoryid
     * @return null|array
     */
    public function getLinkByAutoId($categotyautoid, $categoryid = 0)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('categoryautoid') . ' = ?', $categotyautoid);
        $where .= ' AND ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('categoryid') . ' = ?', $categoryid);
        $row = $this->getAdapter()->select()->from(self::CATEGORYLINKNAME)->where($where)->query()->fetch();
        
        return empty($row) ? null : $row;
    }
    
    /**
     * 根据分类ID获取$categoryautoid,对表进行行锁
     * 
     * @param integer $categoryid
     * @return boolen|integer
     */
    public function getCategoryAutoId($categoryid)
    {
        $this->beginTransaction();
        $categoryid = (int) $categoryid;
        $categoryautoid = $this->getAdapter()->query('SELECT currentid FROM ' . self::CATEGORYNAME . ' WHERE ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('categoryid') . ' = ?', $categoryid) . ' AND currentid < amount FOR UPDATE')->fetchColumn();
        
        if ($categoryautoid!==false) {
            $categoryautoid = $categoryautoid + 1;
            $this->getAdapter()->update(self::CATEGORYNAME, array(
                    'currentid' => $categoryautoid), $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('categoryid') . ' = ?', $categoryid));
        }
        $this->commit();
        return $categoryautoid;
    }
    
    /**
     * 更新分类currentid
     * 
     * @param integer $id
     * @return boolen
     */
    public function updateCategory($id)
    {
        return $this->getAdapter()->query('UPDATE ' . self::CATEGORYNAME . ' SET currentid = currentid + 1 WHERE ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('categoryid') . ' = ?', $id));
    }
    
    /**
     * 根据分类ID返回分类信息
     * 
     * @param integer $id
     * @return null|array
     */
    public function getCategoryById($id)
    {
        $where = $this->getAdapter()->quoteInto('categoryid = ?', (int) $id);
        $row = $this->getAdapter()->select()->from(self::CATEGORYNAME)->where($where)->query()->fetch();
        
        return empty($row) ? null : $row;
    }
    
    /**
     *根据$where条件返回分分类信息
     * 
     * @param array|string $where
     * @return null|array
     */
    public function getCategoriesByWhere($where = array())
    {
        $where = $this->batchWhere($where);
        $select = $this->getAdapter()->select()->from(self::CATEGORYNAME);
        if ($where != '') {
            $select->where($where);
        }
        $rows = $select->query()->fetchAll();
        
        return empty($rows) ? null : $rows;
    }
}

// End ^ LF ^ UTF-8