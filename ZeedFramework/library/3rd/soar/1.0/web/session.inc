<?php
class session
{
    private $soar;
    private $sid=null;

    public $last_error="";

    public function __construct($soar)
    {/*{{{*/
        $this->soar=$soar;
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
        $request="";
        para::put_item($request, $uuid);
        $res=$this->soar->callnode("SESSION", "CREATE",
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
        if($errcode != SOAR_ERR_OK && $errcode != SOAR_ERR_SESSION_EXIST)
        {
            errlog::add("%s: %s fail, errcode:%s",
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return null;
        }
        $sid=para::get_item($respond, $curr);
        if(is_null($sid))
        {
            errlog::add("%s: %s sid invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->sid=$sid;
        $this->last_error=$errcode;
        return $sid;
    }/*}}}*/

    public function get($uuid)
    {/*{{{*/
        $request="";
        para::put_item($request, $uuid);
        $res=$this->soar->callnode("SESSION", "GET", $request, $respond);
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
        $sid=para::get_item($respond, $curr);
        if(is_null($sid))
        {
            errlog::add("%s: %s sid invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->sid=$sid;
        $this->last_error=SOAR_ERR_OK;
        return $sid;
    }/*}}}*/

    public function validate()
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: %s fail, sid invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_PARA;
            return false;
        }

        $request="";
        para::put_item($request, $this->sid);
        $res=$this->soar->callnode("SESSION", "VALIDATE",
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

    public function destroy()
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: %s fail, sid invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_PARA;
            return false;
        }

        $request="";
        para::put_item($request, $this->sid);
        $res=$this->soar->callnode("SESSION", "DESTROY",
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
        $this->sid=null;
        $this->last_error=SOAR_ERR_OK;

        return true;
    }/*}}}*/

    public function putkey($pairs)
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: %s fail, sid invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_PARA;
            return false;
        }

        $request="";
        para::put_item($request, $this->sid, count($pairs));
        foreach($pairs as $key=>$value)
        {
            para::put_item($request, $key, $value);
        }
        $res=$this->soar->callnode("SESSION", "PUTKEY",
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

    public function getkey($keys)
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: %s fail, sid invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_PARA;
            return null;
        }

        $request="";
        para::put_item($request, $this->sid, count($keys));
        foreach($keys as $key)
        {
            para::put_item($request, $key);
        }
        $res=$this->soar->callnode("SESSION", "GETKEY",
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
        $num=para::get_item($respond, $curr);
        if(is_null($num))
        {
            errlog::add("%s: %s val_num invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $vals=array();
        for($i=0; $i<$num; ++$i)
        {
            $item=para::get_item($respond, $curr);
            if(is_null($item))
            {
                errlog::add("%s: %s no.%d value invalid",
                        basename(__FILE__), __METHOD__, $i+1);
                $this->last_error=SOAR_ERR_MSG;
                return null;
            }
            $vals[]=$item;
        }
        $this->last_error=SOAR_ERR_OK;

        return $vals;
    }/*}}}*/

    public function modkey($key, $val)
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: %s fail, sid invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_PARA;
            return false;
        }

        $request="";
        para::put_item($request, $this->sid, $key, $val);
        $res=$this->soar->callnode("SESSION", "MODKEY",
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

    public function destroykey($key)
    {/*{{{*/
        if(is_null($this->sid))
        {
            errlog::add("%s: %s fail, sid invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_PARA;
            return false;
        }

        $request="";
        para::put_item($request, $this->sid, $key);
        $res=$this->soar->callnode("SESSION", "DESTROYKEY",
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

    public function encrypt($plain)
    {/*{{{*/
        $request="";
        para::put_item($request, $plain);
        $res=$this->soar->callnode("SESSION", "ENCRYPT",
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
            errlog::add("%s: %s enc invalid",
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
        $res=$this->soar->callnode("SESSION", "DECRYPT",
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
            errlog::add("%s: %s enc invalid",
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->last_error=SOAR_ERR_OK;

        return $plain;
    }/*}}}*/
}
?>
