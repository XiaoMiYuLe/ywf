<?php
/**
 *
 * platform programe
 * @category   Trendible
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @author     shaun.song ( GTalk/Email: songsj125@gmail.com | MSN: ssj125@hotmail.com )
 * @since      2010-4-22
 * @version    SVN: $Id$
 */
class Zeed_Db_Tools extends Zeed_Db_Model
{
    public function createEntity($table = null)
    {   
        $this->setTable($table);
        $info = $this->info();
        $cols = $info['cols'];
        
        $str = "<?php \n".' class ' . ucfirst($table) . 'Entity extends Zeed_Object {' . "\n";
        foreach ($cols as $col) {
            $str .= '    public $' . $col . ";\n";
        }
        $str .= "\n}";
        
        return $str;
    }
}

// End ^ LF ^ UTF-8