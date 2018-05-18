<?php
class para
{
    const ITEM_DLM="\xff";
    const ITEM_SIZE_CHAR=5;

    public function put_item(&$msg_in)
    {/*{{{*/
        $fmt=sprintf("%%0%dX", self::ITEM_SIZE_CHAR);
        $item_max_size=(1<<self::ITEM_SIZE_CHAR*misc::BIT_OF_CHAR/2)-1;
        $total_size=0;
        $argnum=func_num_args();
        $arglist=func_get_args();
        for($i=1; $i<$argnum; ++$i)
        {
            $arg=$arglist[$i];
            if(is_int($arg))
                $size=strlen(strval($arg));
            else
                $size=strlen($arg);
            if($size > $item_max_size)
                return -1;
            $total_size+=self::ITEM_SIZE_CHAR+1+$size+1;
        }
        for($i=1; $i<$argnum; ++$i)
        {
            $arg=$arglist[$i];
            if(is_int($arg))
                $msg_in.=sprintf($fmt, strlen(strval($arg)));
            else
                $msg_in.=sprintf($fmt, strlen($arg));
            $msg_in.=self::ITEM_DLM.$arg.self::ITEM_DLM;
        }

        return $total_size;
    }/*}}}*/

    public function get_item($msg_in, &$curr)
    {/*{{{*/
        $leftsize=strlen($msg_in)-$curr;
        if($leftsize < self::ITEM_SIZE_CHAR+1+1)
            return null;
        $itempos=$curr+self::ITEM_SIZE_CHAR+1;
        if(strcmp(substr($msg_in, $itempos-1, 1), self::ITEM_DLM))
            return null;
        $itemsize=intval(substr($msg_in, $curr,
                    self::ITEM_SIZE_CHAR), 16);
        if($itemsize < 0 ||
                $itemsize > $leftsize-self::ITEM_SIZE_CHAR-1-1 ||
                strcmp(substr($msg_in, $itempos+$itemsize, 1),
                    self::ITEM_DLM))
            return null;
        $item=substr($msg_in, $itempos, $itemsize);
        $curr+=self::ITEM_SIZE_CHAR+1+$itemsize+1;
        return $item;
    }/*}}}*/
}
?>
