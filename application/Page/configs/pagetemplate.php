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

$template = array();

$template['class'] = <<<END
<?php
/**
 * {#title#}
 * 
 * @author     {#author#}
 * @since      {#since#}
 */

class {#class#}Controller extends PageAbstract
{
    /**
     * 单页频道 - {#title#} - 默认首页
     */
    public function index()
    {
        \$data['page'] = Page_Model_Listing::instance()->getPage({#group_id#}, 'index');
        \$this->setData('data', \$data);
        \$this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /* {#next_function#} */
    
}

// End ^ Native EOL ^ UTF-8
END;

$template['function'] = <<<END
/* {#page_folder#} start */
/**
     * 单页频道 - {#group_title#} - {#page_title#}
     */
    public function {#page_folder#}()
    {
        \$data['page'] = Page_Model_Listing::instance()->getPage({#group_id#}, '{#page_folder#}');
        \$this->setData('data', \$data);
        \$this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    /* {#page_folder#} end */
    
    /* {#next_function#} */
END;

return $template;

// End ^ Native EOL ^ UTF-8