<?php
class misc
{
    const NAME_SIZE=30;
    const LONG_NAME_SIZE=200;
    const BIT_OF_CHAR=8;
    const CRLF="\r\n";

    const DATE_SIZE=8;
    const TIME_SIZE=6;
    const TIMESTAMP_SIZE=14;

    public function str_is_dec($str)
    {/*{{{*/
        static $CHAR=array('0','1','2','3','4','5','6','7','8','9');
        $len=strlen($str);
        for($i=0; $i<$len; ++$i)
        {
            $c=substr($str, $i, 1);
            if(!in_array($c, $CHAR)) return false;
        }
        return true;
    }/*}}}*/

    public function str_is_hex($str)
    {/*{{{*/
        static $CHAR=array('0','1','2','3','4','5','6','7','8','9',
                'A','B','C','D','E','F','a','b','c','d','e','f');
        $len=strlen($str);
        for($i=0; $i<$len; ++$i)
        {
            $c=substr($str, $i, 1);
            if(!in_array($c, $CHAR)) return false;
        }
        return true;
    }/*}}}*/

    public function diff_millisecond($begin, $end)
    {/*{{{*/
        return ($end['sec']-$begin['sec'])*1000+
            ($end['usec']-$begin['usec'])/1000;
    }/*}}}*/

    public function check_date($date)
    {/*{{{*/
        if(strlen($date) != self::DATE_SIZE)
            return false;
        $year=intval(substr($date, 0, 4));
        $month=intval(substr($date, 4, 2));
        $day=intval(substr($date, 6, 2));
        if($year <= 0 || $month <= 0 || $month > 12 ||
                $day <=0 ||
                $day > cal_days_in_month(CAL_GREGORIAN, $month, $year))
            return false;
        return true;
    }/*}}}*/

    public function check_time($time)
    {/*{{{*/
        if(strlen($time) != self::TIME_SIZE)
            return false;
        $hour=substr($time, 0, 2);
        $min=substr($time, 2, 2);
        $sec=substr($time, 4, 2);
        if($hour < 0 || $hour > 24 ||
                $min < 0 || $min > 59 ||
                $sec < 0 || $sec > 59)
            return false;
        return true;
    }/*}}}*/

    public function check_timestamp($ts)
    {/*{{{*/
        if(strlen($ts) != self::TIMESTAMP_SIZE)
            return false;
        $date=substr($ts, 0, self::DATE_SIZE);
        $time=substr($ts, self::DATE_SIZE, self::TIME_SIZE);
        return self::check_date($date) && self::check_time($time);
    }/*}}}*/
}
?>
