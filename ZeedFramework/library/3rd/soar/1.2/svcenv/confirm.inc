<?php
class confirm
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

    private function _submit($svc, $token, $code)
    {/*{{{*/
        $request=array($token, $code);
        $response=$this->svcenv->callnode('CONFIRM', $svc, $request);
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

    public function generate()
    {/*{{{*/
        $request=array();
        $response=$this->svcenv->callnode('CONFIRM', 'GENERATE', $request);
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
            errlog::add("%s: token invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $token=$paras[0];
        if(!isset($paras[1]))
        {
            errlog::add("%s: code invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $code=$paras[1];
        if(!isset($paras[2]))
        {
            errlog::add("%s: img invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $img=$paras[2];

        $this->last_error=SOAR_ERR::OK;
        return array('token'=>$token, 'code'=>$code, 'img'=>$img);
    }/*}}}*/

    public function submit($token, $code)
    {/*{{{*/
        return self::_submit('SUBMIT', $token, $code);
    }/*}}}*/

    public function trysubmit($token, $code)
    {/*{{{*/
        return self::_submit('TRYSUBMIT', $token, $code);
    }/*}}}*/
}
?>
