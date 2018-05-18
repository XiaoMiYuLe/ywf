<?php
class svcenv
{
    const DEFAULT_SERVER_PORT=11000;
    const TIMEOUT=60;

    private $attrs=array(
            'addr'=>'localhost:11000',
            'json'=>true
            );
    private $sock=null;

    public function __construct($site='')
    {/*{{{*/
        if(!empty($site))
        {
            if(isset($site['addr']))
                $this->attrs['addr']=$site['addr'];
            if(isset($site['json']))
                $this->attrs['json']=$site['json'];
        }
//        errlog::add("%s: addr(%s) json(%s)",
//                __METHOD__, $this->attrs['addr'], $this->attrs['json']);
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
        if(!is_null($this->sock))
        {
            socket_close($this->sock);
        }
    }/*}}}*/

    private function _call($request)
    {/*{{{*/
        if(is_null($this->sock))
        {
            $timeout=5;
            $sock=sock::connect($this->attrs['addr'], $timeout);
            if($sock === false)
            {
                errlog::add("%s|%s: connect to svcenv fail",
                    basename(__FILE__), __METHOD__);
                return false;
            }
            $this->sock=$sock;
        }

        $timeout=self::TIMEOUT;
        if(sock::send($this->sock, $request, $timeout) == false)
        {
            errlog::add("%s|%s: send request to svcenv fail",
                    basename(__FILE__), __METHOD__);
            return false;
        }
        if(sock::recv($this->sock, $response, $timeout) == false)
        {
            errlog::add("%s|%s: recv response to svcenv fail",
                    basename(__FILE__), __METHOD__);
            return false;
        }

        return $response;
    }/*}}}*/

    public function call($svc, $paras)
    {/*{{{*/
        if($this->attrs['json'])
        {
            $request=json_encode(
                    array('cmd'=>'CALL', 'svc'=>$svc, 'paras'=>$paras));
            $res=self::_call($request);
            if($res === false)
                $response=array('errcode'=>SOAR_ERR::COMM_FAIL);
            else
                $response=json_decode($res, true);
        }
        else
        {
            $request='';
            para::put_item($request, 'CALL', $svc);
            $request.=$paras;
            $response='';
            $res=self::_call($request);
            if($res === false)
                para::put_item($response, SOAR_ERR::COMM_FAIL);
            else
                $response=$res;
        }

        return $response;
    }/*}}}*/

    public function callnode($node, $svc, $paras)
    {/*{{{*/
        if($this->attrs['json'])
        {
            $request=json_encode(
                    array('cmd'=>'CALLNODE', 'node'=>$node, 'svc'=>$svc,
                        'paras'=>$paras));
            //errlog::add("%s: request:%s", __METHOD__, $request);
            $res=self::_call($request);
            if($res === false)
                $response=array('errcode'=>SOAR_ERR::COMM_FAIL);
            else
                $response=json_decode($res, true);
        }
        else
        {
            $request='';
            para::put_item($request, "CALLNODE", $node, $svc);
            $request.=$paras;
            $response='';
            $res=self::_call($request);
            if($res === false)
                para::put_item($response, SOAR_ERR::COMM_FAIL);
            else
                $response=$res;
        }

        return $response;
    }/*}}}*/

    public function close()
    {/*{{{*/
        if(is_null($this->sock)) return false;

        if($this->attrs['json'])
        {
            $request=json_encode(array('cmd'=>'CLOSE'));
        }
        else
        {
            $request='';
            para::put_item($request, 'CLOSE');
        }
        self::_call($request);

        socket_close($this->sock);
        $this->sock=null; 
        return true;
    }/*}}}*/

    public function begin()
    {/*{{{*/
        if(!is_null($this->sock))
        {
            errlog::add("%s: ".
                    "without ending former trans, begin new trans",
                    __METHOD__);
            socket_close($this->sock);
            $this->sock=null;
        }
    }/*}}}*/

    public function commit()
    {/*{{{*/
        if(is_null($this->sock)) return false;

        if($this->attrs['json'])
        {
            $request=json_encode(array('cmd'=>'COMMIT'));
            $res=self::_call($request);
            if($res === false)
                return false;
            $response=json_decode($res, true);
            if(!isset($response['errcode']) ||
                        $response['errcode'] != SOAR_ERR::OK)
                return false;
        }
        else
        {
            $request='';
            para::put_item($request, 'COMMIT');
            $res=self::_call($request);
            if($res === false)
                return false;
            $curr=0;
            $errcode=para::get_item($res, $curr);
            if(is_null($errcode) || $errcode != SOAR_ERR::OK)
                return false;
        }
        return true;
    }/*}}}*/

    public function abort()
    {/*{{{*/
        if(is_null($this->sock)) return false;

        if($this->attrs['json'])
        {
            $request=json_encode(array('cmd'=>'ABORT'));
            $res=self::_call($request);
            if($res === false)
                return false;
            $response=json_decode($res, true);
            if(!isset($response['errcode']) ||
                        $response['errcode'] != SOAR_ERR::OK)
                return false;
        }
        else
        {
            $request='';
            para::put_item($request, 'ABORT');
            $res=self::_call($request);
            if($res === false)
                return false;
            $curr=0;
            $errcode=para::get_item($res, $curr);
            if(is_null($errcode) || $errcode != SOAR_ERR::OK)
                return false;
        }
        return true;
    }/*}}}*/

    public function session()
    {/*{{{*/
        include_once "session.inc";
        $h=new session($this);
        return $h;
    }/*}}}*/

    public function secure()
    {/*{{{*/
        include_once "secure.inc";
        $h=new secure($this);
        return $h;
    }/*}}}*/

    public function mailfwd()
    {/*{{{*/
        include_once "mailfwd.inc";
        $h=new mailfwd($this);
        return $h;
    }/*}}}*/

    public function confirm()
    {/*{{{*/
        include_once "confirm.inc";
        $h=new confirm($this);
        return $h;
    }/*}}}*/
}
?>
