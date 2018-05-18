<?php
class confirm
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

    private function _submit($svc, $token, $code)
    {/*{{{*/
        $request='';
        para::put_item($request, $token, $code);
        $res=$this->soar->callnode('CONFIRM', $svc, $request, $respond);
        if($res == false)
        {
            errlog::add('%s: %s call fail, respond:%s',
                    basename(__FILE__), __METHOD__, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return false;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add('%s: %s errcode invalid',
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return false;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add('%s: %s fail, errcode:%s',
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return false;
        }
        $this->last_error=SOAR_ERR_OK;

        return true;
    }/*}}}*/

    public function generate()
    {/*{{{*/
        $request='';
        $res=$this->soar->callnode('CONFIRM', 'GENERATE',
                $request, $respond);
        if($res == false)
        {
            errlog::add('%s: %s call fail, respond:%s',
                    basename(__FILE__), __METHOD__, $respond);
            $this->last_error=SOAR_ERR_COMM_FAIL;
            return null;
        }
        $curr=0;
        $errcode=para::get_item($respond, $curr);
        if(is_null($errcode))
        {
            errlog::add('%s: %s errcode invalid',
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        if($errcode != SOAR_ERR_OK)
        {
            errlog::add('%s: %s fail, errcode:%s',
                    basename(__FILE__), __METHOD__, $errcode);
            $this->last_error=$errcode;
            return null;
        }
        $token=para::get_item($respond, $curr);
        if(is_null($token))
        {
            errlog::add('%s: %s token invalid',
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $code=para::get_item($respond, $curr);
        if(is_null($code))
        {
            errlog::add('%s: %s code invalid',
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $img=para::get_item($respond, $curr);
        if(is_null($img))
        {
            errlog::add('%s: %s img invalid',
                    basename(__FILE__), __METHOD__);
            $this->last_error=SOAR_ERR_MSG;
            return null;
        }
        $this->last_error=SOAR_ERR_OK;

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
