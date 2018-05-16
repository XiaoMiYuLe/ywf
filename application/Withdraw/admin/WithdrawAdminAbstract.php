<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class WithdrawAdminAbstract extends AdminAbstract
{
    const BIND_SOURCE_TYPE_URL = 1; // 绑定资源类型 - 链接地址
    const BIND_SOURCE_TYPE_GOODS = 2; // 绑定资源类型 - 商品
    const BIND_SOURCE_TYPE_ARTICLE = 3; // 绑定资源类型 - 文章
    const BIND_SOURCE_TYPE_DIY = 4; // 绑定资源类型 - 自定义
}

// End ^ Native EOL ^ UTF-8