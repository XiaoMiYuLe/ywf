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

class Goods_Model_Content extends Support_Detach
{
    /**
     * @var string The table name.
     */
    protected $_name = 'content';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'content_id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'goods_';
    
    // 分表字段
    protected $_detachField = 'userid';
    
    // 分表数  表名    table table01 table 02
    protected $_detachNum = 3;
    
    // 开启分表设置
    protected $_detachStatus = false;
    
    /**
     * 更新商品状态
     * 
     * @param integer $content_id 商品 ID
     * @param string $field 要更新的状态字段：is_verify、is_shelf、is_del
     * @param integer $value 要更新的状态值
     * @return boolean
     */
    public function updateStatusByContentid($content_id, $field, $value)
    {
        $data = array($field => intval($value));
        $where = $this->getAdapter()->quoteInto('content_id = ?', $content_id);
        $data['mtime'] = date(DATETIME_FORMAT);
        
        return $this->update($data, $where);
    }
    
    /**
     * 彻底删除商品（仅限于平台管理员操作）
     *
     * @param integer $content_id
     * @return integer
     */
    public function deleteByContentid($content_id)
    {
        $result = $this->deleteByPK(intval($content_id));
        if ($result) {
            /* @todo 删除 detail, attachment, property */
            Goods_Model_Content_Detail::instance()->deleteByPK($content_id);
            
        }
    
        return $result;
    }
    
    /**
     * 根据用户选择的属性信息筛选出对应商品列表
     * 
     * @param string $where
     * @param string $order
     * @param string $count
     * @param string $offset
     * @param string $cols
     * @return Ambigous <multitype:, multitype:mixed Ambigous <string, boolean, mixed> >
     */
    public function getGoodsByProperty($where = null, $order = null, $count = null, $offset = null, $cols = null)
    {
        if (is_null($cols)) {
            $cols = '*';
        }
        
        $select = $this->getAdapter()->select()->from($this->getTable(),$cols);
        
        $select->joinRight('goods_property', ' goods_property.content_id = goods_content.content_id',array('property_value_id'));
        
        if (is_string($where)) {
            $select->where($where);
        } elseif (is_array($where) && count($where)) {
            /**
             * 数组, 支持两种形式.
             */
            foreach ($where as $key => $val) {
                if (preg_match("/^[0-9]/", $key)) {
                    $select->where($val);
                } else {
                    $select->where($key . '= ?', $val);
                }
            }
        }
        
        
        if ($order !== null) {
            $select->order($order);
        }
        if ($count !== null || $offset !== null) {
            $select->limit($count, $offset);
        }
        
        $select->group('content_id');
        
        $rows = $select->query()->fetchAll();
        return (is_array($rows) && count($rows)) ? $rows : array();
    }
    
    /**
     * 根据用户选择的属性信息筛选出对应商品数量
     * 
     * @param unknown $where
     * @return Ambigous <number, mixed>
     */
    public function getCountGoodsByProperty($where) 
    {
        $select = $this->getAdapter()->select()->from($this->getTable(), array("count_num" => "count(*)",'content_id'));
        
        $select->joinRight('goods_property', ' goods_property.content_id = goods_content.content_id',array('property_value_id'));
        
        if (is_string($where)) {
            $select->where($where);
        } elseif (is_array($where) && count($where)) {
            /**
             * 数组, 支持两种形式.
             */
            foreach ($where as $key => $val) {
                if (preg_match("/^[0-9]/", $key)) {
                    $select->where($val);
                } else {
                    $select->where($key . '= ?', $val);
                }
            }
        }
        
        $select->group('content_id');
        
        $row = $select->query()->fetch();
        return $row ? $row["count_num"] : 0;
        
    }

    /**
     * 获取前n条商品
     *
     * @params Integer $count
     * @return array
     */
    public function fetchTop($count)
    {
        $where = 'is_shelf=1 AND is_del=0';
        $row = $this->fetchByWhere($where, null, $count, 0, null);
        return $row ? $row : null;
    }

    /**
     * 根据商品分类获取前n条商品
     *
     * @params Integer $category_id
     * @params Integer $count
     * @return array
     */
    public function fetchTopByCategoryId($category_id, $count)
    {
        $where = 'is_shelf=1 AND is_del=0';
        $where .= $this->getAdapter()->quoteInto(' AND category=?', $category_id);
        $row = $this->fetchByWhere($where, null, $count, 0, null);
        return $row ? $row : null;
    }

    /**
     * 根据条件筛选商品
     *
     * @params Integer $category_id
     * @params Integer $property_value_id
     * @params String $keyword
     * @params String $sort
     * @params String $order
     * @params String $page
     * @params String $perpage
     *
     * @return array
     */
    public function fetchGoodsByFilter($category_id, $property_value_id, $keyword, $sort, $order, $page = 0, $perpage = 20)
    {
        $where = 'goods_content.is_del=0 AND goods_content.is_shelf=1';
        $select = $this->getAdapter()->select()->from($this->getTable());
        $select->joinLeft('goods_property', 'goods_property.content_id = goods_content.content_id')->group(array('goods_content.content_id'));
        if ($category_id) {
            $where .= ' AND goods_content.category=' . $category_id;
        }
        if ($property_value_id) {
            $where .= ' AND goods_property.property_value_id in(' . $property_value_id . ')';
        }
        if ($keyword) {
            $where .= ' AND goods_content.name like "%' . $keyword . '%"';
        }
        if ($sort) {
            $orderBy = 'goods_content.' . $sort . ' ' . $order;
        }

        $select->order($orderBy);
        $select->limit($perpage, ($page - 1) * $perpage);
        $row = $select->where($where)->query()->fetchAll();
        return $row ? $row : null;
    }

    /**
     * 根据商品Id获取商品详情
     *
     * @params Integer $category_id
     * @params Integer $property_value_id
     * @params String $sort
     * @params String $order
     *
     * @return array
     */

    public function fetchGoodsDetailById($content_id)
    {
        $row = NULL;
        $content_list = null;
        if (empty($content_id)) {
			return $row;
		}
        $rs = Goods_Model_Content::instance()->fetchByPK($content_id);
        if (!empty($rs)) {
            $rs = $rs[0];
            $row = $rs;
            $parent_id = $rs['parent_id'];
            if (empty($parent_id)) {//主商品
                $content_list[] = $rs;
                $spece_rs = Goods_Model_Content::instance()->fetchByFV('parent_id', $content_id);
                if (!empty($spece_rs)) {
                    
                    foreach ($spece_rs as $item) {
                        $content_list[] = $item;
                    }
                }
            } else {
                $content_id = $parent_id;
                $where = "content_id = $parent_id OR parent_id = $parent_id";
                $content_list = Goods_Model_Content::instance()->fetchByWhere($where,'content_id asc',null,null,null);
            }
        } else {
            return $row;
        }
        $row['content'] = $content_list;
        $detail = Goods_Model_Content_Detail::instance()->fetchByFV('content_id', $content_id);
        $row['detail'] = $detail[0];
        $brand  = Goods_Model_Brand::instance()->fetchByFV('brand_id', $rs['brand_id'],array('brand_name', 'brand_url'));
        $row['brand'] = $brand[0];
        $attachment = Goods_Model_Attachment::instance()->fetchByFV('content_id', $content_id,'attachmentid');
        $attachment_id = null;
        if (!empty($attachment)) {
            foreach ($attachment as $item) {
                $attachment_id[] = $item['attachmentid'];
            }
            $row['attachment'] = Trend_Model_Attachment::instance()->fetchByPk($attachment_id);
        }
        return $row;
    }
    
    /**
     * 根据商品规格id获取规格对应的属性
     * @param unknown $content_id
     * @return NULL|multitype:|multitype:NULL
     */
    public function fetchGoodsPropertyByContentId($content_id)
    {
    	if (! $content_id){
    		return null;
    	}

    	$content = $this->fetchByFV('content_id', $content_id);

    	if ($content[0]['property_related']){
    		$property_related = explode(',', $content[0]['property_related']);
    		return array_map(function($v){
    	       $property = explode(':', $v);
    	       $property_id = Trend_Model_Property::instance()->fetchByFV('property_id', $property[0]);
    	       $property_value = Trend_Model_Property_Value::instance()->fetchByFV('property_value_id', $property[1]);
    	       return $property = array($property_id[0]['label_name'] => $property_value[0]['property_value']);
    		}, $property_related);
    	}
    }
    
    /**
     * 商品id加入cookie
     * @param $content_id
     */
    public function addCookieGoodsId ($content_id)
    {
        if (!empty($content_id)) {
            $viewd = $_COOKIE['viewed'];
            $arr = explode(',', $viewd);
            if (!in_array($content_id, $arr)) {
                $arr[] = $content_id;
                $_COOKIE['viewed'] = implode(',', $arr);
            }
        }
    }
    
    /**
     * 点击数
     * @param $content_id
     */
    public function addClickNum($content_id)
    {
        if (!empty($content_id)) {
            $rs = Goods_Model_Content::instance()->fetchByPK($content_id,'viewed');
            if (!empty($rs)) {
                $viewed = $rs[0]['viewed'];
                Goods_Model_Content::instance()->update(array('viewed'=>$viewed+1), 'content_id='.$content_id);
            }
        }
    }


    /**
     * @return Goods_Model_Content
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
    
    /**
     * 设置浏览cookie记录
     * @param Integer $content_id
     */
    public function _setViewed($content_id)
    {
        $viewed_arr = array();
        $viewed = $_COOKIE['viewed'];
        if (!empty($viewed)) {
            $viewed_arr = explode(",", $viewed);
            if (count($viewed_arr) >= 4) {
                if (!in_array($content_id, $viewed_arr)) {
                    array_unshift($viewed_arr, $content_id);
                    array_pop($viewed_arr);
                }
            } else {
                if (!in_array($content_id, $viewed_arr)) {
                    array_unshift($viewed_arr, $content_id);
                }
            }
        } else {
            array_unshift($viewed_arr, $content_id);
        }
        $viewed = implode(',', $viewed_arr);
        setcookie('viewed', $viewed, time() + 3600,"/");
    }
    
    /**
     * 获取浏览cookie记录
     *
     * @return array
     */
    public function _getViewed()
    {
        return explode(',', $_COOKIE['viewed']);
    }
    
    /**
     * 清除已浏览记录
     */
    public function _clearViewed() 
    {
        setcookie('viewed', '', time() - 3600,"/");
    }
}

// End ^ LF ^ encoding
