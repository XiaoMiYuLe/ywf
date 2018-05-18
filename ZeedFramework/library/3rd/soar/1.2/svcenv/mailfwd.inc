<?php
class mailfwd
{
    private $svcenv;

    public $last_error='';

    public function __construct($svcenv)
    {/*{{{*/
        $this->svcenv=$svcenv;
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
    }/*}}}*/

    private function _send($svc,
            $rcpt, $from, $reply,
            $subject, $body)
    {/*{{{*/
        $request=array($rcpt, $from, $reply, $subject, $body);
        $response=$this->svcenv->callnode("MAILFWD", $svc, $request);
        if($response === false)
        {
            errlog::add("%s: call(%s) fail", __METHOD__, $svc);
            $this->last_error=SOAR_ERR::COMM_FAIL;
            return false;
        }
        if(!isset($response['errcode']))
        {
            errlog::add("%s: svc(%s) errcode invalid", __METHOD__, $svc);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $errcode=$response['errcode'];
        if($errcode != SOAR_ERR::OK)
        {
            errlog::add("%s: svc(%s) fail, errcode:%s",
                    __METHOD__, $svc, $errcode);
            $this->last_error=$errcode;
            return false;
        }

        $this->last_error=SOAR_ERR::OK;
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
