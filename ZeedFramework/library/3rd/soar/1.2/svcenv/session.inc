<?php
class session
{
    private $svcenv;
    private $sid=null;

    public $last_error='';

    public function __construct($svcenv)
    {/*{{{*/
        $this->svcenv=$svcenv;
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
    }/*}}}*/

    public function setsid($sid)
    {/*{{{*/
        $this->sid=$sid;
    }/*}}}*/

    public function getsid()
    {/*{{{*/
        return $this->sid;
    }/*}}}*/

    public function create($uuid)
    {/*{{{*/
        $request=array($uuid);
        $response=$this->svcenv->callnode('SESSION', 'CREATE', $request);
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
        if($errcode != SOAR_ERR::OK &&
                $errcode != SOAR_ERR::SESSION_EXIST)
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
            errlog::add("%s: sid invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $sid=$paras[0];

        $this->sid=$sid;
        $this->last_error=SOAR_ERR::OK;
        return $sid;
    }/*}}}*/

    public function get($uuid)
    {/*{{{*/
        $request=array($uuid);
        $response=$this->svcenv->callnode('SESSION', 'GET', $request);
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
            errlog::add("%s: sid invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $sid=$paras[0];

        $this->sid=$sid;
        $this->last_error=SOAR_ERR::OK;
        return $sid;
    }/*}}}*/

    public function validate()
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: fail, sid invalid", __METHOD__);
            $this->last_error=SOAR_ERR::PARA;
            return false;
        }

        $request=array($this->sid);
        $response=$this->svcenv->callnode('SESSION', 'VALIDATE', $request);
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

    public function destroy()
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: sid invalid", __METHOD__);
            $this->last_error=SOAR_ERR::PARA;
            return false;
        }

        $request=array($this->sid);
        $response=$this->svcenv->callnode("SESSION", "DESTROY", $request);
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

        $this->sid=null;
        $this->last_error=SOAR_ERR::OK;
        return true;
    }/*}}}*/

    public function putkey($pairs)
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: sid invalid", __METHOD__);
            $this->last_error=SOAR_ERR::PARA;
            return false;
        }

        $request=array($this->sid, count($pairs));
        foreach($pairs as $key=>$value)
        {
            array_push($request, $key, $value);
        }
        $response=$this->svcenv->callnode("SESSION", "PUTKEY", $request);
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

    public function getkey($keys)
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: sid invalid", __METHOD__);
            $this->last_error=SOAR_ERR::PARA;
            return false;
        }

        $request=array($this->sid, count($keys));
        foreach($keys as $key)
        {
            $request[]=$key;
        }
        $response=$this->svcenv->callnode("SESSION", "GETKEY", $request);
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
            errlog::add("%s: no paras", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $paras=$response['paras'];
        if(!isset($paras[0]))
        {
            errlog::add("%s: val_num invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $num=$paras[0];
        $vals=array();
        for($i=1; $i<=$num; ++$i)
        {
            if(!isset($paras[$i]))
            {
                errlog::add("%s: no.%d value invalid", __METHOD__, $i);
                $this->last_error=SOAR_ERR::MSG;
                return false;
            }
            $vals[]=$paras[$i];
        }

        $this->last_error=SOAR_ERR::OK;
        return $vals;
    }/*}}}*/

    public function modkey($key, $val)
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: sid invalid", __METHOD__);
            $this->last_error=SOAR_ERR::PARA;
            return false;
        }

        $request=array($this->sid, $key, $val);
        $response=$this->svcenv->callnode("SESSION", "MODKEY", $request);
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

    public function destroykey($key)
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: sid invalid", __METHOD__);
            $this->last_error=SOAR_ERR::PARA;
            return false;
        }

        $request=array($this->sid, $key);
        $response=$this->svcenv->callnode("SESSION", "DESTROYKEY",
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

        $this->last_error=SOAR_ERR::OK;
        return true;
    }/*}}}*/

    public function encrypt($plain)
    {/*{{{*/
        $request=array($plain);
        $response=$this->svcenv->callnode("SESSION", "ENCRYPT", $request);
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
            errlog::add("%s: enc invalid", __METHOD__);
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
        $response=$this->svcenv->callnode("SESSION", "DECRYPT", $request);
        if($response == false)
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
            errlog::add("%s: dec invalid", __METHOD__);
            $this->last_error=SOAR_ERR::MSG;
            return false;
        }
        $plain=$paras[0];

        $this->last_error=SOAR_ERR::OK;
        return $plain;
    }/*}}}*/
}
?>
