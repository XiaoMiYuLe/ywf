<?php
/**
 * iNewS Project
 * 
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

class Article_Model_Category extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'category';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'category_id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'article_';
    
    /**
     * 获取所有分类
     */
    public function getAllCategories()
    {
        return $this->fetchAll()->toArray();
    }
    
    /**
     * 获取所有分类 - 下拉选项型
     */
    public function getAllCategoriesForSelect()
    {
        $order = "hid ASC";
        $categories = $this->fetchByWhere(null, $order, 100);
        if (! empty($categories)) {
            $nv = array();
            foreach ($categories as $k => &$v) {
                $str_padding = '';
                if ($v['parent_id'] == 0) {
                    $v['parent_name'] = '顶级分类';
                } else {
                    if (! isset($nv[$v['parent_id']])) {
                        if ($title = $this->fetchByPK($v['parent_id'], 'title')) {
                            $nv[$v['parent_id']] = $v['parent_name'] = $title[0]['title'];
                        }
                    } else {
                        $v['parent_name'] = $nv[$v['parent_id']];
                    }
                }
                
                $level = count(explode(':', $v['hid'])) - 2;
                if ($level) {
                    for ($i = 0; $i < $level; $i++) {
                        $str_padding .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
                $v['str_padding'] = $str_padding;
            }
        }
        return $categories;
    }
    
    /**
     * 获取所有分类 - 供列表模式使用
     */
    public function getAllCategoriesForListing($where = null, $order = null, $count = null, $offset = null, $cols = null)
    {
        $categories = $this->fetchByWhere($where, $order, $count, $offset, $cols);
        if (! empty($categories)) {
            $nv = array();
            foreach ($categories as $k => &$v) {
                $str_padding = '';
                if ($v['parent_id'] == 0) {
                    $v['parent_name'] = '顶级分类';
                } else {
                    if (! isset($nv[$v['parent_id']])) {
                        if ($title = $this->fetchByPK($v['parent_id'], 'title')) {
                            $nv[$v['parent_id']] = $v['parent_name'] = $title[0]['title'];
                        }
                    } else {
                        $v['parent_name'] = $nv[$v['parent_id']];
                    }
                }
                
                $level = count(explode(':', $v['hid'])) - 2;
                if ($level) {
                    for ($i = 0; $i < $level; $i++) {
                        $str_padding .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
                $v['str_padding'] = $str_padding;
            }
        }
        return $categories;
    }
    
    /**
     * 根据 parent_id 读取分类列表
     *
     * @param integer $parent_id
     * @return array
     */
    public function fetchByParentid($parent_id = 0)
    {
        $db = $this->getAdapter();
        $where = $db->quoteInto('parent_id = ?', $parent_id);
        $sql = 'SELECT *,(SELECT count(' . $this->_primary . ') FROM ' . $this->getTable() . ' AS c1 WHERE c1.parent_id=c2.category_id) AS hasSub FROM '
                . $this->getTable() . ' AS c2 WHERE ' . $where . ' ORDER BY sort_order';
        $rows = $db->query($sql)->fetchAll();
        return is_array($rows) && count($rows) ? $rows : null;
    }
    
    /**
     * 根据 id 读取详情
     *
     * @param integer $id
     * @return array
     */
    public function getById($id)
    {
        $row = $this->fetchByPK($id);
        if (is_array($row) && count($row)) {
            $row_parent = $this->fetchByPK($row[0]['parent_id']);
            $row[0]['parent_name'] = $row_parent[0]['title'];
        }
        return empty($row) ? null : $row;
    }
    
    /**
     * 根据分类标题搜索
     *
     * @param string $regtitle
     * @return array|null
     */
    public function getByRegTitle($regtitle = null)
    {
        $rows = $this->getAdapter()->select()->from($this->getTable())->where('title like ?', "%{$regtitle}%")->query()->fetchAll();
        return $rows;
    }
    
    /**
     * 添加分类
     *
     * @param array $set
     * @return integer
     */
    public function addCategory($set)
    {
        if ($set instanceof Goods_Entity_Category) {
            $data = $set->toArray();
        } else {
            $entity = new Goods_Entity_Category();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->insert($data);
    }
    
    /**
     * 更新分类
     *
     * @param array $set
     * @param string $category_id
     * @return integer
     */
    public function updateCategory($set, $category_id)
    {
        if ($set instanceof Goods_Entity_Category) {
            $data = $set->toArray();
        } else {
            $entity = new Goods_Entity_Category();
            $data = $entity->fromArray($set)->toArray();
        }
        return $this->update($data, "category_id='{$category_id}'");
    }
    
    /**
     * 更新指定节点的序号为指定父分类下的最大值
     *
     * @param integer $id
     * @param integer $parent_id
     */
    public function updateOrderById($id, $parent_id)
    {
        $sql = "UPDATE {$this->getTable()} a,(SELECT max(sort_order) AS max_order FROM {$this->getTable()} " . 
                "WHERE parent_id={$parent_id}) b SET a.sort_order=b.max_order+1 WHERE a.category_id={$id}";
        return $this->getAdapter()->query($sql);
    }
    
    /**
     * 更新节点拖动前或拖动后，其同级节点中位于其后的节点序号
     *
     * @param integer $parent_id
     * @param integer $sort_order
     * @param string $i
     */
    public function updateOrderByPidAndOrder($parent_id, $sort_order, $i)
    {
        $set = array('sort_order' => new Zend_Db_Expr("sort_order + " . $i));
        $where = $this->getAdapter()->quoteInto('parent_id = ?', $parent_id);
        $where .= ' AND ' .$this->getAdapter()->quoteInto('sort_order > ?', $sort_order);
    
        return $this->update($set, $where);
    }
    
    /**
     * 更新拖动节点下子节点的 hid
     * 采用替换方式，只替换第一处
     *
     * @param string $movehid_start
     * @param string $movehid_end
     */
    public function updateChildOrderByHid($movehid_start, $movehid_end)
    {
        $movehid_start .= ':';
        $movehid_end .= ':';
    
        $sql = "UPDATE {$this->getTable()} SET hid = concat(" . 
               "replace(substring(hid, 1, locate('{$movehid_start}', hid) + length('{$movehid_start}')), '{$movehid_start}', '{$movehid_end}')," . 
               "substring(hid, locate('{$movehid_start}', hid) + length('{$movehid_start}') + 1))" . 
               " WHERE hid LIKE '{$movehid_start}%'";
        return $this->getAdapter()->query($sql);
    }
    
    /**
     * 执行删除操作
     *
     * @param integer $id
     * @return string
     */
    public function deleteCategory($id)
    {
        if ($this->fetchByParentid($id)) {
            return '1002';
        }
        if (! $this->delete(array('category_id = ?' => $id))) {
            return '1001';
        }
        return '1000';
    }
    
    /**
     * @return Article_Model_Category
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
