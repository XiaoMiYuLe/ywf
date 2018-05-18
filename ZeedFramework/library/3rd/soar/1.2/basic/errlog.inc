<?php
class errlog
{
    private static $filename='';
    private static $addr;
    private static $dir;

    public function init($filename, $location=".")
    {/*{{{*/
        if(empty($filename))
             return true;
        self::$filename=basename($filename);

        if(empty($location))
             $location=".";
        if(substr($location, 0, 2) == "//")
        {
            $addr=explode(":", str_replace("//", "", $location));
            self::$addr['ip']=gethostbyname($addr[0]);
            self::$addr['port']=intval($addr[1]);
        }
        else
        {
            if(substr($location, 0, 1) == "/")
                 $dir=$location;
            else
                 $dir=getcwd()."/".$location;
            if(file_exists($dir))
            {
                if(!is_dir($dir) ||
                     !is_readable($dir) ||
                     !is_writeable($dir) ||
                     !is_executable($dir))
                    return false;
            }
            else
            {
                if(mkdir($dir) == false)
                     return false;
            }
            self::$dir=$dir;
        }

        return true;
    }/*}}}*/

    public function add()
    {/*{{{*/
        if(empty(self::$filename))
        {
            if(func_num_args() > 0)
            {
                $args=func_get_args();
                $fmt=array_shift($args);
                $msg=strftime("%Y-%m-%d %H:%M:%S")." -- ".
                    vsprintf($fmt, $args)."\n";
                print $msg;
            }
        }
        else if(isset(self::$addr['ip']) && isset(self::$addr['port']))
        { 
            if(func_num_args() > 0)
            {
                $sock=socket_create(AF_INET, SOCK_DGRAM, 0); 
                if(socket_connect($sock,
                            self::$addr['ip'],
                            self::$addr['port']) == false)
                {
                    socket_close($sock);
                    return false;
                }
                $args=func_get_args();
                $fmt=array_shift($args);
                $msg=vsprintf($fmt, $args);
                $len=para::put_item($sendbuf, "LOG", self::$filename,
                        $msg);
                $num=socket_select($read=null, $write=array($sock),
                        $exp=null, 5, 0);
                if($num > 0)
                    socket_send($sock, $sendbuf, $len, 0); 
                socket_close($sock);
            }
        }
        else if(!empty(self::$dir))
        {
            if(func_num_args() > 0)
            {
                $file=self::$dir."/".self::$filename.".log.".
                    strftime("%Y%m%d");
                $f=fopen($file, "a");
                if($f === false) return false;
                $args=func_get_args();
                $fmt=array_shift($args);
                $msg=strftime("%Y-%m-%d %H:%M:%S")." -- ".
                    vsprintf($fmt, $args)."\n";
                fputs($f, $msg);
                fclose($f);
            }
        }
        else
        {
            return false;
        }

        return true;
    }/*}}}*/

    public function write()
    {/*{{{*/
        $location=get_cfg_var("errlog_location");
        if($location === false || empty($location))
        {
            return false;
        }

        $addr=explode(":", str_replace("//", "", $location));
        $ip=gethostbyname($addr[0]);
        $port=intval($addr[1]);
        $sock=socket_create(AF_INET, SOCK_DGRAM, 0); 
        if(socket_connect($sock, $ip, $port) == false)
        {
            socket_close($sock);
            return false;
        }

        $args=func_get_args();
        $argsnum=func_num_args();
        if($argsnum > 0)
        {
            $fmt=array_shift($args);
            $msg=vsprintf($fmt, $args);
            $len=para::put_item($sendbuf, "LOG", "httpd", $msg);
            $num=socket_select($read=null, $write=array($sock),
                    $exp=null, 5, 0);
            if($num > 0)
                socket_send($sock, $sendbuf, $len, 0); 
        }
        socket_close($sock);
        return true;
    }/*}}}*/
}
?>
