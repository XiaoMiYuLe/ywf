<?php
class secure
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

    public function encrypt($pubkey, $plain)
    {/*{{{*/
        $request=array($pubkey, $plain);
        $response=$this->svcenv->callnode('SECURE', 'ENCRYPT',
                $request);
        if($response === false)
        {
            errlog::add("%s: call fail", __METHOD__);
            $this->last_error=SOAR_ERR::COMM_FAIL;
            return false;
        }
        if(!isset($response['errcode']))
        {
            errlog::add("%s: errcode invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $errcode=$response['errcode'];
        if($errcode != SOAR_ERR::OK)
        {
            errlog::add("%s: errcode:%s", __METHOD__, $errcode);
            $this->last_error=$errcode;
            return false;
        }
        if(!isset($response['paras']))
        {
            errlog::add("%s: paras invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $paras=$response['paras'];
        if(!isset($paras[0]))
        {
            errlog::add("%s: encrypt text invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $enc=$paras[0];

        $this->last_error=SOAR_ERR::OK;
        return $enc;
    }/*}}}*/

    public function decrypt($enc)
    {/*{{{*/
        $request=array($enc);
        $response=$this->svcenv->callnode('SECURE', 'DECRYPT', $request);
        if($response === false)
        {
            errlog::add("%s: call fail", __METHOD__);
            $this->last_error=SOAR_ERR::COMM_FAIL;
            return false;
        }
        if(!isset($response['errcode']))
        {
            errlog::add("%s: errcode invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $errcode=$response['errcode'];
        if($errcode != SOAR_ERR::OK)
        {
            errlog::add("%s: errcode:%s", __METHOD__, $errcode);
            $this->last_error=$errcode;
            return false;
        }
        if(!isset($response['paras']))
        {
            errlog::add("%s: paras invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $paras=$response['paras'];
        if(!isset($paras[0]))
        {
            errlog::add("%s: plain text invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $plain=$paras[0];

        $this->last_error=SOAR_ERR::OK;
        return $plain;
    }/*}}}*/

    public function sign($orig)
    {/*{{{*/
        $request=array($orig);
        $response=$this->svcenv->callnode('SECURE', 'SIGN', $request);
        if($response === false)
        {
            errlog::add("%s: call fail", __METHOD__);
            $this->last_error=SOAR_ERR::COMM_FAIL;
            return false;
        }
        if(!isset($response['errcode']))
        {
            errlog::add("%s: errcode invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $errcode=$response['errcode'];
        if($errcode != SOAR_ERR::OK)
        {
            errlog::add("%s: errcode:%s", __METHOD__, $errcode);
            $this->last_error=$errcode;
            return false;
        }
        if(!isset($response['paras']))
        {
            errlog::add("%s: paras invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $paras=$response['paras'];
        if(!isset($paras[0]))
        {
            errlog::add("%s: sign text invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $sign=$paras[0];

        $this->last_error=SOAR_ERR::OK;
        return $sign;
    }/*}}}*/

    public function verify($pubkey, $orig, $sign)
    {/*{{{*/
        $request=array($pubkey, $orig, $sign);
        $response=$this->svcenv->callnode('SECURE', 'VERIFY', $request);
        if($response === false)
        {
            errlog::add("%s: call fail", __METHOD__);
            $this->last_error=SOAR_ERR::COMM_FAIL;
            return false;
        }
        if(!isset($response['errcode']))
        {
            errlog::add("%s: errcode invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $errcode=$response['errcode'];
        if($errcode != SOAR_ERR::OK)
        {
            errlog::add("%s: errcode:%s", __METHOD__, $errcode);
            $this->last_error=$errcode;
            return false;
        }

        $this->last_error=SOAR_ERR::OK;
        return true;
    }/*}}}*/

    public function encsign($pubkey, $plain)
    {/*{{{*/
        $request=array($pubkey, $plain);
        $response=$this->svcenv->callnode('SECURE', 'ENCSIGN', $request);
        if($response === false)
        {
            errlog::add("%s: call fail", __METHOD__);
            $this->last_error=SOAR_ERR::COMM_FAIL;
            return false;
        }
        if(!isset($response['errcode']))
        {
            errlog::add("%s: errcode invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $errcode=$response['errcode'];
        if($errcode != SOAR_ERR::OK)
        {
            errlog::add("%s: errcode:%s", __METHOD__, $errcode);
            $this->last_error=$errcode;
            return false;
        }
        if(!isset($response['paras']))
        {
            errlog::add("%s: paras invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $paras=$response['paras'];
        if(!isset($paras[0]))
        {
            errlog::add("%s: encrypt text invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $enc=$paras[0];
        if(!isset($paras[1]))
        {
            errlog::add("%s: sign text invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $sign=$paras[1];

        $this->last_error=SOAR_ERR::OK;
        return array('enc'=>$enc, 'sign'=>$sign);
    }/*}}}*/

    public function vrfydec($pubkey, $enc, $sign)
    {/*{{{*/
        $request=array($pubkey, $enc, $sign);
        $response=$this->svcenv->callnode("SECURE", "VRFYDEC", $request);
        if($response === false)
        {
            errlog::add("%s: call fail", __METHOD__);
            $this->last_error=SOAR_ERR::COMM_FAIL;
            return false;
        }
        if(!isset($response['errcode']))
        {
            errlog::add("%s: errcode invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $errcode=$response['errcode'];
        if($errcode != SOAR_ERR::OK)
        {
            errlog::add("%s: errcode:%s", __METHOD__, $errcode);
            $this->last_error=$errcode;
            return false;
        }
        if(!isset($response['paras']))
        {
            errlog::add("%s: paras invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $paras=$response['paras'];
        if(!isset($paras[0]))
        {
            errlog::add("%s: plain text invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $plain=$paras[0];

        $this->last_error=SOAR_ERR::OK;
        return $plain;
    }/*}}}*/
}
?>
