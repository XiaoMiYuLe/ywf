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
class System_Model_Navigation extends Zeed_Db_Model
{

    /**
     *
     * @var string The table name.
     */
    protected $_name = 'navigation';

    /**
     *
     * @var integer Primary key.
     */
    protected $_primary = 'navigation_id';

    /**
     *
     * @var string Table prefix.
     */
    protected $_prefix = 'system_';

    /**
     * 获取所有导航 - 下拉选项型
     */
    public function getAllForSelect ($where = null, $order = null)
    {
        $where = $where ? $where : 'status = 1';
        $navigations = $this->fetchByWhere($where, $order);
        
        if (! empty($navigations)) {
            foreach ($navigations as &$v) {
                $str_padding = '';
                $level = count(explode(':', $v['hid'])) - 2;
                if ($level) {
                    for ($i = 0; $i < $level; $i ++) {
                        $str_padding .= "　　";
                    }
                }
                $v['str_padding'] = $str_padding;
            }
        }
        
        return $navigations;
    }

    /**
     * 获取所有导航 - 页面导航专用
     */
    public function getAllForNavigation ()
    {
        $where = 'status = 1';
        $order = 'sort_order ASC';
        $navs = $this->fetchByWhere($where, $order);
        
        if (! empty($navs)) {
            $nav_top = $nav_two = $nav_three = array();
            foreach ($navs as $v) {
                switch (strlen($v['hid'])) {
                    case 6:
                        $nav_top[] = $v;
                        break;
                    case 11:
                        $nav_two[] = $v;
                        break;
                    case 16:
                        $nav_three[] = $v;
                        break;
                    default:
                        break;
                }
            }
        }
        $navigations['nav_top'] = $nav_top;
        $navigations['nav_two'] = $nav_two;
        $navigations['nav_three'] = $nav_three;
        
        return $navigations;
    }

    /**
     *
     * @return System_Model_Navigation
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
