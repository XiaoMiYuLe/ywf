<?php
include_once "basic/misc.inc";
include_once "basic/errcode.inc";
include_once "basic/para.inc";
include_once "basic/errlog.inc";
include_once "basic/sock.inc";

class soar
{
    private static $TIMEOUT=60;

    private static $addr=array();

    public function init($site="")
    {/*{{{*/
        errlog::init("httpd", get_cfg_var("errlog_location"));

        if(!empty($site))
        {
            $loc=get_cfg_var("soar_svc_addr_".$site);
            if(empty($loc))
            {
                $loc=get_cfg_var("soar_svc_addr");
            }
        }
        else
        {
            $loc=get_cfg_var("soar_svc_addr");
        }

        if(!empty($loc))
        {
            $addr=explode(":", $loc);
            self::$addr['ip']=gethostbyname($addr[0]);
            self::$addr['port']=intval($addr[1]);
        }
        else
        {
            errlog::add("%s|%s: soar_svc_addr invalid",
                    basename(__FILE__), __METHOD__);
        }
    }/*}}}*/

    private $sock;

    public $view;

    public function __construct($site="")
    {/*{{{*/
        $this->sock=null;
        if(empty(self::$addr))  // 兼容
        {
            if(($loc=get_cfg_var("soar_svc_addr")))
            {
                $addr=explode(":", $loc);
                self::$addr['ip']=gethostbyname($addr[0]);
                self::$addr['port']=intval($addr[1]);
            }
        }
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
        if(!is_null($this->sock))
        {
            socket_close($this->sock);
        }
    }/*}}}*/

    private function _call($request, &$respond)
    {/*{{{*/
        $respond="";
        if(is_null($this->sock))
        {
            $timeout=5;
            if(sock::connect(self::$addr['ip'], self::$addr['port'],
                        $timeout, $this->sock) == false)
            {
                errlog::add("%s|%s: connect to svcenv fail",
                    basename(__FILE__), __METHOD__);
                para::put_item($respond, SOAR_ERR_COMM_FAIL);
                return false;
            }
        }

        $timeout=self::$TIMEOUT;
        if(sock::send($this->sock, $request, $timeout) == false)
        {
            errlog::add("%s|%s: send svc(%s) request to svcenv fail",
                    basename(__FILE__), __METHOD__, $svc);
            para::put_item($respond, SOAR_ERR_COMM_FAIL);
            return false;
        }
        if(sock::recv($this->sock, $respond, $timeout) == false)
        {
            errlog::add("%s|%s: recv svc(%s) response to svcenv fail",
                    basename(__FILE__), __METHOD__, $svc);
            para::put_item($respond, SOAR_ERR_COMM_FAIL);  
            return false;
        }

        return true;
    }/*}}}*/

    public function call($svc, $request, &$respond)
    {/*{{{*/
        para::put_item($msg, "CALL", $svc);
        $msg.=$request;
        return self::_call($msg, $respond);
    }/*}}}*/

    public function callnode($node, $svc, $request, &$respond)
    {/*{{{*/
        para::put_item($msg, "CALLNODE", $node, $svc);
        $msg.=$request;
        return self::_call($msg, $respond);
    }/*}}}*/

    public function close()
    {/*{{{*/
        if(is_null($this->sock)) return false;
        para::put_item($request, "CLOSE");
        $res=self::_call($request, $respond);
        socket_close($this->sock);
        $this->sock=null; 
        return true;
    }/*}}}*/

    public function begin()
    {/*{{{*/
        if(!is_null($this->sock))
        {
            errlog::add("%s|%s: ".
                    "without ending former trans, begin new trans",
                    basename(__FILE__), __METHOD__);
            socket_close($this->sock);
            $this->sock=null;
        }
    }/*}}}*/

    public function commit()
    {/*{{{*/
        if(is_null($this->sock)) return false;
        para::put_item($request, "COMMIT");
        return self::_call($request, $respond);
        //socket_close($this->sock);
        //$this->sock=null; 
        return true;
    }/*}}}*/

    public function abort()
    {/*{{{*/
        if(is_null($this->sock)) return false;
        para::put_item($request, "ABORT");
        return self::_call($request, $respond);
        //socket_close($this->sock);
        //$this->sock=null; 
    }/*}}}*/

    public function session()
    {/*{{{*/
        include_once "web/session.inc";
        $h=new session($this);
        return $h;
    }/*}}}*/

    public function secure()
    {/*{{{*/
        include_once "web/secure.inc";
        $h=new secure($this);
        return $h;
    }/*}}}*/

    public function mailfwd()
    {/*{{{*/
        include_once "web/mailfwd.inc";
        $h=new mailfwd($this);
        return $h;
    }/*}}}*/

    public function confirm()
    {/*{{{*/
        include_once "web/confirm.inc";
        $h=new confirm($this);
        return $h;
    }/*}}}*/
}
?>
