<?php
class Mail_Code
{
    
    public static function getCode()
    {
    	return md5("p".rand(100000, 999999)."ice");
    }
}