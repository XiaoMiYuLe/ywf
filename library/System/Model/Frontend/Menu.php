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
class System_Model_Frontend_Menu extends Zeed_Db_Model
{

    /**
     *
     * @var string The table name.
     */
    protected $_name = 'frontend_menu';

    /**
     *
     * @var string Primary key.
     */
    protected $_primary = 'menu_id';

    /**
     *
     * @var string Table prefix.
     */
    protected $_prefix = 'system_';

    /**
     * 根据菜单组ID获取菜单列表
     */
    public function fetchByGroupId ($group_id = 1)
    {
        $rows = $this->fetchByFV('group_id', $group_id);
        return $rows ? $rows : null;
    }

    /**
     * 获取所有菜单 - 下拉选项型
     */
    public function getAllForSelect ($where = null, $order = null)
    {
        $where = $where ? $where : 'if_show = 1';
        $menus = $this->fetchByWhere($where, $order);
        
        if (! empty($menus)) {
            foreach ($menus as &$v) {
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
        
        return $menus;
    }

    /**
     * 获取所有菜单 - 页面导航专用
     */
    public function getAllForNavigation ($group_id = null)
    {
        $where = 'if_show = 1';
        if ($group_id) {
            $where .= " AND group_id = {$group_id}";
        }
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
     * 根据菜单ID删除菜单及其子菜单
     *
     * @param integer $menu_id            
     * @return integer boolean
     */
    public function removeMenu ($menu_id)
    {
        $where_son = array(
                'pid = ?' => $menu_id
        );
        $this->delete($where_son);
        
        $where = array(
                'menu_id = ?' => $menu_id
        );
        return $this->delete($where);
    }

    /**
     *
     * @return System_Model_Frontend_Menu
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ Native EOL ^ UTF-8