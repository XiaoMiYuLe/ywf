<?php
/**
 * Playcool Project
 *
 * LICENSE
 *
 * http://www.playcool.com/license/ice
 *
 * @category ICE
 * @package ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author xSharp ( GTalk: xSharp@gmail.com )
 * @since Jun 4, 2009
 * @version SVN: $Id: Json.php 13223 2012-07-13 09:42:25Z xsharp $
 */
class Zeed_View_Json extends Zeed_View_Php
{
    public function process($result, Zeed_Controller_Action $action)
    {
        echo $this->_process($result, $action);
        exit();
    }
    
    /**
     *
     * @todo 支持过滤getData()中返回的字段
     * @param string $result
     * @param Zeed_Controller_Action $action
     */
    protected function _process($result, Zeed_Controller_Action $action)
    {
        if (is_array($headers = headers_list())) {
            $headerSend = false;
            foreach ($headers as $header) {
                if (strpos(strtolower($header), 'content-type') !== false) {
                    $headerSend = true;
                }
            }
            
            if (! $headerSend) {
                header('Content-type: application/json');
            }
        }
        
        $action_config = $action->getConfig();
        $this->_action = & $action;
        
        if ('' != $resource = @$action_config[$result]['resource']) {
            // 这里尚不检测自定义构造的JSON格式数据
            return parent::render($resource);
        } else {
            if (defined('ZEED_IN_PRODUCTION') && ZEED_IN_PRODUCTION) {
                return json_encode($action->getData());
            } else {
                // 格式化过的数据
                return Zeed_View_Json::prettyJsonEncode($action->getData());
            }
        }
    }
    
    /**
     * JSON格式编码数据
     *
     * @param Mixed $value
     * @return String
     */
    public static function prettyJsonEncode($value)
    {
        $tab = "  ";
        $new_json = "";
        $indent_level = 0;
        $in_string = false;
        
        $json = json_encode($value);
        $len = strlen($json);
        
        for ($c = 0; $c < $len; $c ++) {
            $char = $json[$c];
            switch ($char) {
                case '{' :
                case '[' :
                    if (! $in_string) {
                        $new_json .= $char . "\n" . str_repeat($tab, $indent_level + 1);
                        $indent_level ++;
                    } else {
                        $new_json .= $char;
                    }
                    break;
                case '}' :
                case ']' :
                    if (! $in_string) {
                        $indent_level --;
                        $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
                    } else {
                        $new_json .= $char;
                    }
                    break;
                case ',' :
                    if (! $in_string) {
                        $new_json .= ",\n" . str_repeat($tab, $indent_level);
                    } else {
                        $new_json .= $char;
                    }
                    break;
                case ':' :
                    if (! $in_string) {
                        $new_json .= ": ";
                    } else {
                        $new_json .= $char;
                    }
                    break;
                case '"' :
                    if ($c > 0 && $json[$c - 1] != '\\') {
                        $in_string = ! $in_string;
                    }
                default :
                    $new_json .= $char;
                    break;
            }
        }
        
        return $new_json;
    }
}
// End ^ LF ^ UTF-8
