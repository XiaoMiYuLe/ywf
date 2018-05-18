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
 * @copyright Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Ahdong ( GTalk: ahdong.com@gmail.com )
 * @since      Nov 12, 2010
 * @version    SVN: $$Id$$
 */

class Com_Admin_Permission_Model extends Zeed_Db_Model
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        
        $dbadp = Zeed_Config::loadGroup('access.permission_db_adapter');
        
        if (is_null($dbadp)) {
            $dbadp = 'acl';
        }
        
        if ($dbadp != 'default') {
            $this->changeAdapter($dbadp);
        }
    }
}

// End ^ LF ^ encoding
