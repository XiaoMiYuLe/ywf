<?php
class secure
{
    private $soar;

    public $last_error='';

    public function __construct($soar)
    {/*{{{*/
        $this->soar=$soar;
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
    }/*}}}*/

    public function encrypt($pubkey, $plain)
    {/*{{{*/
        $request='';
        para::put_item($request, $pubkey, $plain);
        $res=$this->soar->callnode('SECURE', 'ENCRYPT',
                $request, $respond);
        if($res == false)
        {
            errlog::add("%s: %s call fail, respond:%s",
                    basename(__FILE__), __METHOD__, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return null;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add("%s: %s errcode invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add("%s: %s fail, errcode:%s",
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return null;
        }
        $enc=para::get_item($respond, $curr);
        if(is_null($enc))
        {
            errlog::add("%s: %s encrypt text invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->last_error=SOAR_ERR_OK;

        return $enc;
    }/*}}}*/

    public function decrypt($enc)
    {/*{{{*/
        $request="";
        para::put_item($request, $enc);
        $res=$this->soar->callnode("SECURE", "DECRYPT",
                $request, $respond);
        if($res == false)
        {
            errlog::add("%s: %s call fail, respond:%s",
                    basename(__FILE__), __METHOD__, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return null;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add("%s: %s errcode invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add("%s: %s fail, errcode:%s",
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return null;
        }
        $plain=para::get_item($respond, $curr);
        if(is_null($plain))
        {
            errlog::add("%s: %s plain text invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->last_error=SOAR_ERR_OK;

        return $plain;
    }/*}}}*/

    public function sign($orig)
    {/*{{{*/
        $request="";
        para::put_item($request, $orig);
        $res=$this->soar->callnode("SECURE", "SIGN",
                $request, $respond);
        if($res == false)
        {
            errlog::add("%s: %s call fail, respond:%s",
                    basename(__FILE__), __METHOD__, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return null;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add("%s: %s errcode invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add("%s: %s fail, errcode:%s",
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return null;
        }
        $sign=para::get_item($respond, $curr);
        if(is_null($sign))
        {
            errlog::add("%s: %s sign text invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->last_error=SOAR_ERR_OK;

        return $sign;
    }/*}}}*/

    public function verify($pubkey, $orig, $sign)
    {/*{{{*/
        $request="";
        para::put_item($request, $pubkey, $orig, $sign);
        $res=$this->soar->callnode("SECURE", "VERIFY",
                $request, $respond);
        if($res == false)
        {
            errlog::add("%s: %s call fail, respond:%s",
                    basename(__FILE__), __METHOD__, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return false;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add("%s: %s errcode invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return false;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add("%s: %s fail, errcode:%s",
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return false;
        }
        $this->last_error=SOAR_ERR_OK;

        return true;
    }/*}}}*/

    public function encsign($pubkey, $plain)
    {/*{{{*/
        $request="";
        para::put_item($request, $pubkey, $plain);
        $res=$this->soar->callnode("SECURE", "ENCSIGN",
                $request, $respond);
        if($res == false)
        {
            errlog::add("%s: %s call fail, respond:%s",
                    basename(__FILE__), __METHOD__, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return null;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add("%s: %s errcode invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add("%s: %s fail, errcode:%s",
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return null;
        }
        $enc=para::get_item($respond, $curr);
        if(is_null($enc))
        {
            errlog::add("%s: %s encrypt text invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $sign=para::get_item($respond, $curr);
        if(is_null($sign))
        {
            errlog::add("%s: %s sign text invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->last_error=SOAR_ERR_OK;

        return array('enc'=>$enc, 'sign'=>$sign);
    }/*}}}*/

    public function vrfydec($pubkey, $enc, $sign)
    {/*{{{*/
        $request="";
        para::put_item($request, $pubkey, $enc, $sign);
        $res=$this->soar->callnode("SECURE", "VRFYDEC",
                $request, $respond);
        if($res == false)
        {
            errlog::add("%s: %s call fail, respond:%s",
                    basename(__FILE__), __METHOD__, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return null;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add("%s: %s errcode invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add("%s: %s fail, errcode:%s",
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return null;
        }
        $plain=para::get_item($respond, $curr);
        if(is_null($plain))
        {
            errlog::add("%s: %s plain text invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->last_error=SOAR_ERR_OK;

        return $plain;
    }/*}}}*/
}
?>
