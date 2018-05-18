<?php
class ep
{
    const CONN=0;
    const RECV=1;
    const ABORT=2;
    const TIMEOUT=3;

    // 'listen' 'sock' 'timeout' 'wrap'
    // 'self_ip' 'self_port'
    // 'peer_ip' 'peer_port'
    private $_slots=array();
 
    public function add_listen($sock)
    {/*{{{*/
        if(is_null($sock)) return false;

        $slot_size=count($this->_slots);
        for($slot_id=0; $slot_id<$slot_size; ++$slot_id)
            if(empty($this->_slots[$slot_id])) break;
        $s=&$this->_slots[$slot_id];
        $s=array('listen'=>true, 'sock'=>$sock,
                'timeout'=>0, 'wrap'=>false);
        socket_getsockname($sock, $addr, $port); 
        $s['self_ip']=$addr;
        $s['self_port']=$port;
        $s['peer_ip']="0.0.0.0";
        $s['peer_port']="*";

        return $slot_id;
    }/*}}}*/

    public function add($sock)
    {/*{{{*/
        if(is_null($sock)) return false;

        $slots_size=count($this->_slots);
        for($slot_id=0; $slot_id<$slots_size; ++$slot_id)
            if(empty($this->_slots[$slot_id])) break;
        if(socket_set_nonblock($sock) == false)
            return false;
        $s=&$this->_slots[$slot_id];
        $s=array('listen'=>false, 'sock'=>$sock,
                'wrap'=>false, 'timeout'=>0, 'last_ts'=>time());
        socket_getsockname($sock, $addr, $port); 
        $s['self_ip']=$addr;
        $s['self_port']=$port;
        socket_getpeername($sock, $addr, $port); 
        $s['peer_ip']=$addr;
        $s['peer_port']=$port;

        return $slot_id;
    }/*}}}*/

    public function set_wrap($slot_id)
    {/*{{{*/
        if(empty($this->_slots[$slot_id])) return false;
        $s=&$this->_slots[$slot_id];
        if($s['listen']) return false;
        $s['wrap']=true; 
        return true;
    }/*}}}*/

    public function set_timeout($slot_id, $timeout)
    {/*{{{*/
        if(empty($this->_slots[$slot_id])) return false;
        $s=&$this->_slots[$slot_id];
        if($s['listen']) return false;
        $s['timeout']=$timeout; 
        return true;
    }/*}}}*/

    private function _fill($slot_id)
    {/*{{{*/
        $RECV_SIZE=128*1024;
        $active=array();

        $slot=&$this->_slots[$slot_id];

        if($slot['listen'])
        {
            $new_sock=socket_accept($slot['sock']);
            $active[]=array('type'=>self::CONN,
                    'slot_id'=>$slot_id,
                    'new_sock'=>$new_sock);
            return $active;
        }

        $msg="";
        do
        {
            $num=socket_recv($slot['sock'], $buf, $RECV_SIZE, 0);
            if($num === false || $num == 0)
            {
                errlog::add("%s|%s: recv fail, error:%d %s",
                        basename(__FILE__), __METHOD__,
                        socket_last_error(),
                        socket_strerror(socket_last_error()));
                $active[]=array('type'=>self::ABORT,
                        'slot_id'=>$slot_id,
                        'addr'=>$slot['peer_ip'].":".$slot['peer_port'],
                        'error'=>socket_last_error());
                socket_close($slot['sock']);
                unset($this->_slots[$slot_id]);
                return $active;
            }
            $msg.=$buf;
        } while($num == $RECV_SIZE);
        if($slot['wrap'])
        {
            $cache=&$slot['recv_cache'];
            $cache.=$msg; 
            while(!empty($cache))
            {
                $cache_size=strlen($cache);
                if($cache_size <= sock::MSG_SIZE_SIZE)
                    break;
                $s=substr($cache, 0, sock::MSG_SIZE_SIZE);
                if(!misc::str_is_hex($s) || ($size=intval($s, 16)) <= 0)
                {
                    socket_close($slot['sock']);
                    unset($this->_slots[$slot_id]);
                    errlog::add("%s: msg_size_size(%s) invalid",
                            __METHOD__, $s);
                    return $active;
                }
                if(sock::MSG_SIZE_SIZE+$size > $cache_size)
                    break; 
                $msg=substr($cache, sock::MSG_SIZE_SIZE, $size);
                $active[]=array('type'=>self::RECV,
                        'slot_id'=>$slot_id, 'msg'=>$msg);
                $cache=substr($cache, sock::MSG_SIZE_SIZE+$size);
            }
        }
        else
        {
            $active[]=array('type'=>self::RECV,
                    'slot_id'=>$slot_id, 'msg'=>$msg);
        }

        return $active;
    }/*}}}*/

    /*
     * out: array(array('type'=>ep::CONN,'slot_id'=>,'new_sock'=>))
     *      array(array('type'=>ep::RECV,'slot_id'=>,'msg'=>))
     *      array(array('type'=>ep::ABORT,'slot_id'=>,'addr'=>,'error'=>))
     *      array(array('type'=>ep::TIMEOUT,'slot_id'=>,'addr'=>))
     */
    public function poll()
    {/*{{{*/
        static $TIMEOUT=2;
        $active=array();

        $read=$write=array();
        $read_slot=$write_slot=array();
        $now=time();
        foreach($this->_slots as $slot_id=>$slot)
        {
            if(!$slot['listen'] &&
                    $slot['timeout'] > 0 &&
                    $now-$slot['last_ts'] > $slot['timeout'])
            {
                $active[]=array('type'=>self::TIMEOUT,
                        'slot_id'=>$slot_id,
                        'addr'=>$slot['peer_ip'].":".$slot['peer_port']);
                socket_close($this->_slots[$slot_id]);
                unset($this->_slots[$slot_id]);
            }
            else
            {
                $read[]=$slot['sock'];
                $read_slot[$slot_id]=$slot['sock'];
                if(!empty($slot['send_queue']))
                {
                    $write[]=$slot['sock'];
                    $write_slot[$slot_id]=$slot['sock'];
                }
            }
        }
        //print_r($read);
        $num=socket_select($read, $write, $exp=null, $TIMEOUT, 0);
        if($num === false) return false;
        if($num == 0) return $active;
        $now=time();
        foreach($read as $sock)
        {
            $slot_id=array_search($sock, $read_slot); 
            $active=array_merge($active, $this->_fill($slot_id));
        }
        foreach($write as $sock)
        {
            $slot_id=array_search($sock, $write_slot); 
            $queue=&$this->_slots[$slot_id]['send_queue'];
            while(($msg=array_shift($queue)))
            {
                $orgsize=strlen($msg);
                $num=socket_send($sock, $msg, $orgsize, 0);
                if($num === false || $num == 0)
                {
                    errlog::add("%s|%s: send fail, error:%d %s",
                            basename(__FILE__), __METHOD__,
                            socket_last_error(),
                            socket_strerror(socket_last_error()));
                    array_unshift($queue, $msg);
                    break;
                }
                if($num < $org_size)
                {
                    $msg=substr($msg, $num);
                    array_unshift($queue, $msg);
                    break;
                }
            }
        }

        return $active;
    }/*}}}*/

    public function send($slot_id, $msg)
    {/*{{{*/
        if(empty($this->_slots[$slot_id]) || empty($msg))
            return false;

        $s=&$this->_slots[$slot_id];
        if($s['listen']) return false;
        if($s['wrap']) $msg=sock::wrap($msg);
        if($s['timeout'] > 0) $s['last_ts']=time(); 

        if(empty($s['send_queue']))
        {
            $orgsize=strlen($msg);
            $num=@socket_send($s['sock'], $msg, $orgsize, 0);
            if($num === false || $num == 0)
            {
                errlog::add("%s|%s: send fail, error:%d %s",
                        basename(__FILE__), __METHOD__,
                        socket_last_error(),
                        socket_strerror(socket_last_error()));
                return false;
            }
            if($num == $orgsize) return true;
            $msg=substr($msg, $num);
        }
        $s['send_queue'][]=$msg;

        return true;
    }/*}}}*/

    public function get_info($slot_id)
    {/*{{{*/
        if(empty($this->_slots[$slot_id])) return false;
        $s=&$this->_slots[$slot_id];
        return array('self_ip'=>$s['self_ip'],
                'self_port'=>$s['self_port'],
                'peer_ip'=>$s['peer_ip'],
                'peer_port'=>$s['peer_port']);
    }/*}}}*/
}
?>
