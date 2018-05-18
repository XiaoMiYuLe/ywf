<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xshArp ( GTalk: xSharp@gmail.com )
 * @since      2006-11-27
 * @version    SVN: $Id: Redirector.php 131 2009-04-29 03:17:55Z xsharp $
 */

/**
 * Redirector 接口
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Redirector
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
interface Redirector_Interface
{
    public function toString();
}

/**
 * Redirector 工厂
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Redirector
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
class Zeed_Util_Redirector
{
    private $_redirector;

    /**
     *
     * @param 	String	$factory
     * @param 	String	$goUrl
     * @param 	Integer	$delayTime
     * @param 	String	$note		额外信息,比如Redirector_Post中附加提交信息
     */
    public function __construct($factory = 'header', $goUrl = null, $delayTime = 0, $note = null)
    {
        $class = 'Redirector_' . ucfirst(strtolower($factory));
        $this->_redirector = new $class($goUrl, $delayTime, $note);
    }

    /**
     * 转换成String
     *
     * @return String
     */
    public function toString()
    {
        return $this->_redirector->toString();
    }

    /**
     * 输出到屏幕
     */
    public function output()
    {
        $this->_redirector->output();
    }

    /**
     * @return Redirector_*
     */
    public static function factory($factory = 'header', $goUrl = null, $delayTime = 0, $note = null)
    {
        $class = 'Redirector_' . ucfirst(strtolower($factory));
        $redirector = new $class($goUrl, $delayTime, $note);

        return $redirector;
    }
}

/**
 * Redirector Core
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Redirector
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
abstract class Redirector_Core
{
    public $_goUrl;
    public $_delayTime;
    public $_note = null;



    public function __construct($goUrl, $delayTime, $note = null)
    {
        $this->_goUrl = $goUrl;
        $this->_delayTime = $delayTime;
        $this->_note = $note;
    }

    public function toString()
    {
        die('must overwrite this function.');
    }

    public function output()
    {
        echo $this->toString();
    }
}

/**
 * Redirector PHP Header 方式
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Redirector
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
class Redirector_Goto extends Redirector_Core implements Redirector_Interface
{
    public function toString()
    {
        /**
         * 判断如果 HEADER 不支持则使用 JS
         */

        $get = new Redirector_Get($this->_goUrl, $this->_delayTime, $this->_note);
        return $get->toString();
    }
}


/**
 * Redirector PHP Header 方式
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Redirector
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
class Redirector_Header extends Redirector_Core implements Redirector_Interface
{
    public function toString()
    {
        header('Location: ' . $this->_goUrl);
        return null;
    }
}

/**
 * Redirector GET 方式
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Redirector
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
class Redirector_Get extends Redirector_Core implements Redirector_Interface
{
    public function toString()
    {
        $goUrl = $this->_goUrl;
        $note = $this->_note ? $this->_note : 'Loading ...';
        $delayTime = $this->_delayTime;
        $_str = <<<EOF
<html>
<head></head>
<script language="javascript">
var t = $delayTime;
function Timer(){
	document.title = 'Loading ...' + window.t + ' secs. remaining';
	window.t = window.t - 1;
	if(window.t <= 0){
		window.location.href = '{$goUrl}';
		return false;
	}else{
		setTimeout("Timer()",1000);
	}
}
</script>

<body onload="Timer()">
	<div id="topNote" style="font-size: 10px; font-family: Tahoma; float:right; margin: 3px 10px; padding:2px 5px; background-color: #f4360a; color: white;">
    <a href="{$goUrl}">{$note}</a></div>
</body>
<script language="javascript">
    document.getElementById("topNote").style.display="none";
</script>
</html>
EOF;

        return $_str;
    }
}

/**
 * Alias Redirector_Javascript
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Redirector
 * @see        Redirector_Javascript
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
class Redirector_Js extends Redirector_Get implements Redirector_Interface
{
}

/**
 * Redirector POST 方式
 *
 * @category   Zeed
 * @package    Zeed_Misc
 * @subpackage Redirector
 * @author     xSharp ( GTalk/Email: xSharp@gmail.com | MSN: xSharp@msn.com )
 */
class Redirector_Post extends Redirector_Core implements Redirector_Interface
{
    /**
     * 转向参数
     */
    protected $_params = array();

    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;
    }

    public function setParams($params)
    {
        foreach ($params as $name => $value)
        {
            $this->_params[$name] = $value;
        }
    }

    public function toString()
    {
        $input = '';
        if (null !== $this->_params) {
            foreach ($this->_params as $name=>$value) {
                $name = urlencode($name);
                $value = urlencode($value);
                $input .= "<input type=\"hidden\" name=\"{$name}\" value=\"{$value}\">";
            }
        }

        $goUrl = $this->_goUrl;
        $note = $this->_note ? $this->_note : 'Loading ...';
        $delayTime = $this->_delayTime;
        $_str = <<<EOF
<html>
<head></head>
<body onload="Timer()">
<form name="repostForm" id="repostForm" action="$goUrl" method="post">{$input}<div style="font-size: 10px; font-family: Tahoma; float:right; margin: 3px 10px; padding:2px 5px; background-color: #f4360a; color: white;"> $note </div></form>

<script language="javascript">
var t = $delayTime;
function Timer(){
	document.title = 'Loading ...' + window.t + ' secs. remaining';
	window.t = window.t - 1;
	if(window.t <= 0){
		document.getElementById('repostForm').submit();
	}else{
		setTimeout("Timer()",1000);
	}
}
</script>

</body>
<html>
EOF;
        return $_str;
    }
}

// End ^ LF ^ UTF-8
