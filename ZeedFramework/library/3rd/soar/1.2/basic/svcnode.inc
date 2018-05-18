<?php
class svcnode
{
    const PERMANENT=0;
    const ONESHOT=1;

    private static $CONN_TIMEOUT=5;
    private static $CALL_TIMEOUT=10;

    private $mode;
    private $ready;
    private $backup;

    public function __construct($addr, $mode=self::ONESHOT)
    {/*{{{*/
        if($mode != self::PERMANENT && $mode != self::ONESHOT)
            $mode=self::ONESHOT;
        $this->mode=$mode;

        $this->ready=array();
        $this->backup=array();
        $arr=explode(",", $addr);
        if($arr)
        {
            if(!empty($arr[0]))
            {
                $ready=explode("|", $arr[0]);
                foreach($ready as $url)
                {
                    $p=parse_url($url);
                    if(!empty($p['host']) && !empty($p['port']))
                    {
                        $this->ready[]=array(
                                'addr'=>gethostbyname($p['host']).':'.
                                intval($p['port']),
                                'sock'=>null);
                    }
                }
            }
            if(!empty($arr[1]))
            {
                $p=parse_url($arr[1]);
                $this->backup=array(
                        'addr'=>gethostbyname($p['host']).':'.
                        intval($p['port']),
                        'sock'=>null);
            }
        }
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
        $this->close();
    }/*}}}*/

    public function call($svc, $paras)
    {/*{{{*/
        $request='';
        if(!empty($svc))
            para::put_item($request, $svc);
        if(!empty($paras))
            $request.=$paras;
        if(empty($request)) return false; 

        $idxes=range(0, count($this->ready)-1);
        if(count($idxes) > 1)
        {
            srand((float)microtime()*1000000);
            shuffle($idxes);
        }
        for($i=0; $i<count($idxes); ++$i)
        {
            $ready=&$this->ready[$i];
            if(!is_null($ready['sock']))
            {
                if(sock::send($ready['sock'], $request,
                            self::$CALL_TIMEOUT) == true)
                {
                    if(sock::recv($ready['sock'], $response,
                                self::$CALL_TIMEOUT) == true) 
                    {
                        return $response;
                    }
                }
                socket_close($ready['sock']);
                $ready['sock']=null;
                errlog::add("%s: call ready(%d) fail, next",
                        __METHOD__, $i);
            }
            $sock=sock::connect($ready['addr'], self::$CONN_TIMEOUT);
            if($sock)
            {
                $ready['sock']=$sock;
                $currsock=&$ready['sock'];
                break;
            }
        }
        if($i == count($idxes))
        {
            if(empty($this->backup))
            {
                errlog::add("%s: no backup", __METHOD__);
                return false;
            }
            if(is_null($this->backup['sock']))
            {
                $sock=sock::connect($this->backup['addr'],
                        self::$CONN_TIMEOUT);
                if($sock === false)
                {
                    errlog::add("%s: connect backup fail", __METHOD__);
                    return false;
                }
                $this->backup['sock']=$sock;
            }
            $currsock=&$this->backup['sock'];
        }
        if(sock::send($currsock, $request, self::$CALL_TIMEOUT) == false ||
                sock::recv($currsock, $response, self::$CALL_TIMEOUT) ==
                    false) 
        {
            socket_close($currsock);
            $currsock=null;
            errlog::add("%s: call svcnode fail", __METHOD__);
            return false;
        }

        if($this->mode == self::ONESHOT)
        {
            socket_close($currsock);
            $currsock=null;
        }
        
        return $response;
    }/*}}}*/

    public function close()
    {/*{{{*/
        for($i=0; $i<count($this->ready); ++$i)
        {
            if(!is_null($this->ready[$i]['sock']))
            {
                socket_close($this->ready[$i]['sock']); 
                $this->ready[$i]['sock']=null;
            }
        }
        if(!empty($this->backup))
        {
            if(!is_null($this->backup['sock']))
            {
                socket_close($this->backup['sock']);
                $this->backup['sock']=null;
            }
        }
    }/*}}}*/
}

class svcnode_call
{
    public function getdbpasswd($addr, $dbname, $dbuser)
    {/*{{{*/
        $sn=new svcnode($addr, svcnode::ONESHOT);
        $request='';
        para::put_item($request, $dbname, $dbuser);
        $response=$sn->call('GETDBPASSWD', $request);
        if($response === false)
        {
            errlog::add("%s: call fail", __METHOD__);
            return false;
        }

        $curr=0;
        $errcode=para::get_item($response, $curr);
        if(is_null($errcode))
        {
            errlog::add("%s: errcode invalid", __METHOD__);
            return false;
        }
        if($errcode != SOAR_ERR::OK)
        {
            errlog::add("%s: errcode:%s", __METHOD__, $errcode);
            return false;
        }
        $dbpasswd=para::get_item($response, $curr);
        if(is_null($dbpasswd))
        {
            errlog::add("%s: dbpasswd invalid", __METHOD__);
            return false;
        }

        return $dbpasswd;
    }/*}}}*/
}
?>
