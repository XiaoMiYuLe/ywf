<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      May 7, 2010
 * @version    SVN: $Id$
 */

class Zeed_Text_Snatch
{
    
    /**
     * 分析HTML中的链接信息:
     * href|src|url|location|codebase|background|data|profile|action|open
     *
     * @param String $source
     * @param Array $tags_to_extract
     * @param Boolean $aggressive_mode
     * @return Array
     */
    public static function findLinks($source, $tags_to_extract = array('src'), $aggressive_mode = false)
    {
        $match_part = "";
        
        $all_links = array(); // will get filled with the found links
        $target_array = array();
        if (! is_array($tags_to_extract)) {
            $tags_to_extract = array(
                    (string) $tags_to_extract);
        }
        
        // Now check if user added linktags to extract and if so, use them
        // (build expression-part like "href|src|location ...")
        @reset($tags_to_extract);
        if (count($tags_to_extract) > 0) {
            while (list($key) = @each($tags_to_extract)) {
                $match_part .= "|" . $tags_to_extract[$key];
            }
            $match_part = substr($match_part, 1);
        } else {
            // else we use the default extraction
            $match_part = "href|src|url|location|codebase|background|data|profile|action|open";
        }
        
        // 1. <a href="...">LINKTEXT</a> (well formed with </a> at the end)
        // Get the link AND the linktext from these tags
        // This has to be done FIRST !!
        

        preg_match_all("/<[ ]{0,}a[ \n\r][^<>]{0,}(?<= |\n|\r)(?:" . $match_part . ")[ \n\r]{0,}=[ \n\r]{0,}[\"|']{0,1}([^\"'>< ]{0,})[^<>]{0,}>((?:(?!<[ \n\r]*\/a[ \n\r]*>).)*)<[ \n\r]*\/a[ \n\r]*>/ is", $source, $regs);
        
        // regs[0] -> complete <a href> tags
        // regs[1] -> the links (raw)
        // regs[2] -> linktext
        

        for ($x = 0; $x < count($regs[1]); $x ++) {
            $tmp_array["link_raw"] = trim($regs[1][$x]);
            $tmp_array["linktext"] = $regs[2][$x];
            $tmp_array["linkcode"] = trim($regs[0][$x]);
            
            $map_key = $tmp_array["link_raw"];
            
            if (! isset($map_array[$map_key])) {
                $target_array[] = $tmp_array;
                $map_array[$map_key] = true;
            }
        }
        
        // Now we "preg" all other matches
        // 2. all like <..href="..."> <..src=".."> and so on
        

        $pregs[] = "/<[^<>]{0,}[ \n\r](?:" . $match_part . ")[ \n\r]{0,}=[ \n\r]{0,}[\"|']{0,1}([^\"'>< ]{0,})[^<>]{0,}>/ is";
        
        // Now, if agressive_mode is set to true, we look for some
        // other things
        if ($aggressive_mode == true) {
            // Everyhtnig inside OR outside a tag
            // "=" or "(" after tag
            $pregs[] = "/[ \.:;](?:" . $match_part . ")[ \n\r]{0,}[=|\(][ \n\r]{0,}[\"|']{0,1}([^\"'>< ;]{0,})['\"<> ;]/ is";
            
        // Stuff like ..open="("...")
        // currently in the expression above
        // slowed down the whole thing..but returned better "linkcode"
        // $pregs[]="/(?:".$match_part.")[ \n\r]{0,}\([ \n\r]{0,}(?:\"|'){1}([^\"'><\n ]{0,})(\"|')[^)]*\)/ is";
        }
        
        // Now execute the pregs
        

        for ($x = 0; $x < count($pregs); $x ++) {
            unset($regs);
            preg_match_all($pregs[$x], $source, $regs);
            
            for ($y = 0; $y < count($regs[1]); $y ++) {
                unset($tmp_array);
                $tmp_array["link_raw"] = trim($regs[1][$y]);
                $tmp_array["linkcode"] = trim($regs[0][$y]);
                $tmp_array["linktext"] = "";
                
                $map_key = $tmp_array["link_raw"];
                
                if (! isset($map_array[$map_key])) {
                    $target_array[] = $tmp_array;
                    $map_array[$map_key] = true;
                }
            }
        
        }
        
        return $target_array;
    }
}

// End ^ LF ^ encoding
