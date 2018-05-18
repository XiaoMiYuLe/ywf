<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      May 13, 2010
 * @version    SVN: $Id: mail.php 6798 2010-09-11 07:42:12Z Cyrano $
 */

$template = array();

/******************************************************************
 * 重置密码
 */
$template['resetpassword']['text'] = <<<END
亲爱的用户：
您的密码重置链接如下：
#\$activelink#
如果点击以上链接不能进入，请把以上链接复制粘贴到浏览器的地址栏，然后回车来执行此链接。
此链接#\$lifetime#小时内有效，如链接失效请到蓝色互动通行证页面
（ #\$siteurl#/resetPassword ） 重新发送重置确认信！

--------------------------------------------------------------------------------
竭诚为您服务！
通行证页面： http://www.bluemobi.cn
此邮件为系统自动发出，请勿直接回复。
END;

$template['resetpassword']['html'] = <<<END
亲爱的用户：<br />
您的密码重置链接如下：<br />
#\$activelink#<br />
如果点击以上链接不能进入，请把以上链接复制粘贴到浏览器的地址栏，然后回车来执行此链接。<br />
此链接#\$lifetime#小时内有效，如链接失效请到蓝色互动通行证页面<br />
（ #\$siteurl#/resetPassword ） 重新发送重置确认信！<br /><br />

--------------------------------------------------------------------------------<br />
竭诚为您服务！<br />
通行证页面： http://www.bluemobi.cn<br />
此邮件为系统自动发出，请勿直接回复。<br />
END;

return $template;

// End ^ LF ^ encoding
