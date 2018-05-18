<?php
class mailfwd
{
    private $soar;

    public $last_error="";

    public function __construct($soar)
    {/*{{{*/
        $this->soar=$soar;
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
    }/*}}}*/

    private function _send($svc,
            $rcpt, $from, $reply,
            $subject, $body)
    {/*{{{*/
        $request="";
        para::put_item($request, $rcpt, $from, $reply, $subject, $body);
        $res=$this->soar->callnode("MAILFWD", $svc, $request, $respond);
        if($res == false)
        {
            errlog::add("%s: %s call(%s) fail, respond:%s",
                    basename(__FILE__), __METHOD__, $svc, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return false;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add("%s: %s svc(%s) errcode invalid",
                    basename(__FILE__), __METHOD__, $svc);
            $this->last_error=SOAR_ERR_MSG;
            return false;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add("%s: %s svc(%s) fail, errcode:%s",
                    basename(__FILE__), __METHOD__, $svc, $errcode);
            $this->last_error=$errcode;
            return false;
        }
        $this->last_error=SOAR_ERR_OK;

        return true;
    }/*}}}*/

    public function send($rcpt, $from, $reply,
            $subject, $body)
    {/*{{{*/
        return self::_send("SEND", $rcpt, $from, $reply,
                $subject, $body);
    }/*}}}*/

    public function sendvip($rcpt, $from, $reply,
            $subject, $body)
    {/*{{{*/
        return self::_send("SENDVIP", $rcpt, $from, $reply,
                $subject, $body);
    }/*}}}*/
}
?>
