<?php
class sock
{
    const MSG_SIZE_SIZE=6;

    private static $RECV=0;
    private static $SEND=1;
    private static $ACCEPT=2;

    private function wrap(&$msg)
    {/*{{{*/
        $fmt=sprintf("%%0%dX", self::MSG_SIZE_SIZE);
        return sprintf($fmt, strlen($msg)).$msg;
    }/*}}}*/

    private function wait($socktype, $sock, &$timeout)
    {/*{{{*/
        if($timeout < 0)
            return true;
        switch($socktype)
        {
            case self::$RECV:
            case self::$ACCEPT:
                $read=array($sock);
                $write=null;
                break;
            case self::$SEND:
                $read=null;
                $write=array($sock);
                break;
        }
        $now=gettimeofday();
        $begin=$now['sec'];
        $to=$timeout;
        $num=socket_select($read, $write, $exp=null, $to, 0);
        if($num === false)
            return false;
        if($num == 0)
        {
            $timeout=0;
            return false;
        }
        $now=gettimeofday();
        $used=$now['sec']-$begin;
        $timeout=$to > $used ? $to-$used : 0;
        return true;
    }/*}}}*/

    public function listen($port)
    {/*{{{*/
        $sock=socket_create(AF_INET, SOCK_STREAM, 0);

        if(@socket_bind($sock, "0.0.0.0", $port) == false)
        {
            $error=socket_last_error();
            errlog::add("%s|%s: socket_bind fail, err:%d %s",
                    basename(__FILE__), __METHOD__,
                    $error, socket_strerror($error));
            return false;
        }
        if(@socket_listen($sock) == false)
        {
            $error=socket_last_error();
            errlog::add("%s|%s: socket_listen fail, err:%d %s",
                    basename(__FILE__), __METHOD__,
                    $error, socket_strerror($error));
            return false;
        }

        return $sock;
    }/*}}}*/

    public function accept($sock, &$timeout)
    {/*{{{*/
        if(self::wait(self::$ACCEPT, $sock, $timeout) == false)
        {
//            errlog::add("%s|%s: accept timeout",
//                    basename(__FILE__), __METHOD__);
            return false;
        }
        $new_sock=socket_accept($sock);
        if($new_sock === false)
        {
            errlog::add("%s|%s: socket_accept fail, err:%d",
                    basename(__FILE__), __METHOD__, socket_last_error());
            return false;
        }
        return $new_sock;
    }/*}}}*/

    public function connect($ip, $port, $timeout, &$sock)
    {/*{{{*/
        $sock=socket_create(AF_INET, SOCK_STREAM, 0);

        socket_set_nonblock($sock);
        if(@socket_connect($sock, $ip, $port) == true)
        {
            socket_set_block($sock);
            return true;
        }
        $error=socket_last_error();
        if($error != SOCKET_EINPROGRESS && $error != SOCKET_EWOULDBLOCK)
        {
            errlog::add("%s|%s: connect fail:".$error,
                    basename(__FILE__), __METHOD__);
            socket_close($sock); $sock=null;
            return false;
        }
        $read=$write=array($sock);
        $num=socket_select($read, $write, $exp=null, $timeout, 0);
        if($num === false || $num == 0)
        {
            errlog::add("%s|%s): connect select fail:".
                    socket_last_error(),
                    basename(__FILE__), __METHOD__);
            socket_close($sock); $sock=null;
            return false;
        }
        if(!in_array($sock, $read) && !in_array($sock, $write))
        {
            errlog::add("%s|%s: connect select invalid",
                    basename(__FILE__), __METHOD__);
            socket_close($sock); $sock=null;
            return false;
        }
        if(socket_getpeername($sock, $ip, $port) == false)
        {
            errlog::add("%s|%s: connect getpeername fail:".
                    socket_last_error(),
                    basename(__FILE__), __METHOD__);
            socket_close($sock); $sock=null;
            return false;
        }
        socket_set_block($sock);

        return true;
    }/*}}}*/

    public function send($sock, $msg, $timeout)
    {/*{{{*/
        if(self::wait(self::$SEND, $sock, $timeout) == false)
        {
            errlog::add("%s|%s: send timeout",
                    basename(__FILE__), __METHOD__);
            return false;
        }
        $buf=self::wrap($msg);
        $left_size=strlen($buf);
        $offset=0;
        while($left_size > 0)
        {
            $sendbuf=substr($buf, $offset, $left_size);
            $res=socket_send($sock, $sendbuf, strlen($sendbuf), 0);
            if($res === false || $res == 0)
            {
                errlog::add("%s|%s: send fail, error:%d %s",
                        basename(__FILE__), __METHOD__,
                        socket_last_error(),
                        socket_strerror(socket_last_error()));
                return false;
            }
            $left_size-=$res;
            $offset+=$res;
            if($left_size > 0)
            {
                if(self::wait(self::$SEND, $sock, $timeout) == false)
                {
                    errlog::add("%s|%s: send timeout",
                            basename(__FILE__), __METHOD__);
                    return false;
                }
            }
        }

        return true;
    }/*}}}*/

    public function recv($sock, &$msg_out, $timeout)
    {/*{{{*/
        if(self::wait(self::$RECV, $sock, $timeout) == false)
        {
            errlog::add("%s|%s: recv timeout",
                    basename(__FILE__), __METHOD__);
            return false; 
        }
        $res=socket_recv($sock, $buf, self::MSG_SIZE_SIZE, 0);
        if($res === false || $res == 0)
        {
            errlog::add("%s|%s: recv fail, error:%d %s",
                    basename(__FILE__), __METHOD__,
                    socket_last_error(),
                    socket_strerror(socket_last_error()));
            return false;
        }
        $msg_out_size=intval($buf, 16);

        $left_size=$msg_out_size;
        while($left_size > 0)
        {
            $res=socket_recv($sock, $buf, $left_size, 0);
            if($res === false || $res == 0)
            {
                errlog::add("%s|%s: recv fail, error:%d %s",
                        basename(__FILE__), __METHOD__,
                        socket_last_error(),
                        socket_strerror(socket_last_error()));
                $msg_out=null;
                return false;
            }
            $left_size-=$res;
            $msg_out.=$buf;
            if($left_size > 0)
            {
                if(self::wait(self::$RECV, $sock, $timeout) == false)
                {
                    errlog::add("%s|%s: recv timeout",
                            basename(__FILE__), __METHOD__);
                    $msg_out=null;
                    return false;
                }
            }
        }
        return true;
    }/*}}}*/
}
?>
