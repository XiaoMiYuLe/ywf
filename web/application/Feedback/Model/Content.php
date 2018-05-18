<?php

/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright  Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 9, 2010
 * @version    SVN: $Id$
 */
class Feedback_Model_Content extends Zeed_Db_Model
{

    /**
     *
     * @var string The table name.
     */
    protected $_name = 'content';

    /**
     *
     * @var integer Primary key.
     */
    protected $_primary = 'content_id';

    /**
     *
     * @var string Table prefix.
     */
    protected $_prefix = 'feedback_';

    /**
     *
     * @return Feedback_Model_Content
     */
    
    /**
     * 获取未回复留言数量
     * $retrun int | null
     */
    public function countNeedReply ()
    {
        $where = "status = 0";
        $select = $this->getAdapter()->select()->from($this->getTable(), array('count_num' => "COUNT(*)"));
        $row = $select->where($where)->query()->fetch();
        
        return $row ? $row["count_num"] : 0;
    }

    /**
     * 获取反馈列表
     * 
     * @param string $where            
     * @param string $order            
     * @param number $count            
     * @param number $offset            
     * @return Ambigous <NULL, multitype:, multitype:mixed Ambigous <string,
     *         boolean, mixed> >
     */
    public function getFeedback ($where = null, $order = null, $count = 20, $offset = 0)
    {
        $select = $this->getAdapter()->select()->from(array('fbc' => $this->getTable()))->limit($count, $offset);
        
        if ($where) {
            $select->where($where);
        }
        
        $select->joinLeft('cas_user AS cu', 'fbc.userid=cu.userid', array('userid', 'realname'));
        $rows = $select->query()->fetchAll();
        
        return $rows ? $rows : null;
    }

    /**
     * 根据条件获取反馈列表条数
     * 
     * @param string $where            
     * @return number
     */
    public function getFeedbackCount ($where = null)
    {
        $select = $this->getAdapter()->select()->from(array('fbc' => $this->getTable()));
        
        if ($where) {
            $select->where($where);
        }
        
        $select->joinLeft('cas_user AS cu', 'fbc.userid=cu.userid', array('userid', 'realname'));
        $rows = $select->query()->fetchAll();
        
        return $rows ? count($rows) : 0;
    }

    /**
     * 根据content_id获取详情
     *
     * @param unknown $content_id            
     * @return NULL Ambigous mixed>
     */
    public function fetchFeedbackByContentId ($content_id)
    {
        $select = $this->getAdapter()->select()->from(array('fbc' => $this->getTable()));
        
        if (! $content_id) {
            return null;
        }
        
        $select->where('fbc.content_id=?', $content_id);
        $select->joinLeft('cas_user AS cu', 'fbc.userid=cu.userid', array('userid', 'realname'));
        $row = $select->query()->fetch();
        
        return $row ? $row : null;
    }

    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
