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
require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$parent_id = $data['parent_id'];
$regions = $data['regions'];

$regions_parent = $regions_son = array();
foreach ($regions as $k => $v) {
    if ($v['parent_id'] == 2) {
        $regions_parent[] = $v;
    }
    if ($v['parent_id'] == $parent_id) {
        $regions_son[] = $v;
    }
}

$smarty->assign("parent_id", $parent_id);
$smarty->assign("regions_parent", $regions_parent);
$smarty->assign("regions_son", $regions_son);

$smarty->display('admin/template/region.index.html');
