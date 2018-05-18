<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-5-11
 * @version SVN: $Id$
 */
class Trend_Model_Attachment extends Zeed_Db_Model
{

    /**
     *
     * @var string The table name.
     */
    protected $_name = 'attachment';

    /**
     *
     * @var string Primary key.
     */
    protected $_primary = 'attachmentid';

    /**
     *
     * @var string Table prefix.
     */
    protected $_prefix = 'trend_';

    /**
     * 新增附件, 这里仅仅处理基本信息
     *
     * @param Trend_Entity_Attachment|array $set            
     * @return integer
     */
    public function addAttachment ($set)
    {
        if ($set instanceof Trend_Entity_Attachment) {
            $data = $set->toArray();
        } else {
            $data = Trend_Entity_Attachment::newInstance()->fromArray($set)->toArray();
        }
        
        return $this->insert($data);
    }

    /**
     * 由Attachmentid(主键)获取附件信息, 支持数组
     *
     * @param integer|array $attachemtid            
     * @return array 二维数组
     */
    public function fetchByAttchmentid ($attachmentid)
    {
        return $this->fetchByFV('attachmentid', $attachmentid);
    }

    /**
     * 获取单个指定的附件信息, 返回一维数组
     *
     * @param integer $attachmentid            
     * @return 一行
     */
    public function fetchOneByAttachmentid ($attachmentid)
    {
        $rows = $this->fetchByFV('attachmentid', $attachmentid);
        
        return ! is_null($rows) ? $rows[0] : null;
    }

    /**
     * 根据HASHCODE获取附件信息, 一般用于检测某个附件是否存在
     *
     * @param string $hashcode            
     */
    public function fetchByHashcode ($hashcode)
    {
        return $this->fetchByFV('hashcode', $hashcode);
    }

    /**
     *
     * @param string|array $hashcode            
     * @return integer array
     */
    public function fetchCountByHashcode ($hashcode)
    {
        $select = $this->getAdapter()->select();
        $select->from($this->getTable(), array(
                'total' => new Zend_Db_Expr('COUNT(attachmentid)')
        ));
        if (is_string($hashcode)) {
            $select->where($this->getAdapter()
                ->quoteInto('hashcode = ?', $hashcode));
            
            return $select->query()->fetchColumn(0);
        } elseif (is_array($hashcode)) {
            $tmp = array();
            foreach ($hashcode as $val) {
                $tmp[$val] = $this->getAdapter()->quoteInto('?', $val);
            }
            $select->where('hashcode IN (' . implode(',', $tmp) . ')');
            $select->group('hashcode');
            
            return $select->query()->fetchAll();
        }
        
        return null;
    }

    /**
     *
     * @param array $filter            
     * @param string|array $order            
     * @param integer $count            
     * @param integer $offset            
     * @return array
     */
    public function fetchAttachments ($filter, $order = null, $count = 100, $offset = 0)
    {
        $select = $this->select();
        $this->_filterSelect($select, $filter);
        if (! empty($order)) {
            $select->order($order);
        } else {
            $select->order('attachmentid DESC');
        }
        $select->limit($count, $offset);
        return $select->query()->fetchAll();
    }

    /**
     *
     * @param Zend_Db_Select $select            
     * @param array $filter            
     * @return integer
     */
    private function _filterSelect ($select, $filter)
    {
        foreach ($filter as $k => $v) {
            if (is_int($k)) {
                $select->where($v);
            } else {
                $select->where($k, $v);
            }
        }
        return $select;
    }

    /**
     * 获取计数
     *
     * @param string $where            
     * @return integer
     */
    public function fetchCount ($filter = null)
    {
        $select = $this->getAdapter()->select();
        $select->from($this->getTable(), array(
                'total' => new Zend_Db_Expr('COUNT(attachmentid)')
        ));
        
        if (! empty($filter)) {
            $this->_filterSelect($select, $filter);
        }
        
        return $select->query()->fetchColumn(0);
    }

    /**
     * 批量更新附件的状态
     *
     * @param array $attachmentids            
     * @param integer $status            
     * @return integer
     */
    public function batchUpdateStatusByAttachmentids ($attachmentids, $status)
    {
        $where = 'attachmentid IN(' . implode(',', $attachmentids) . ')';
        
        $this->update(array(
                'status' => (int) $status
        ), $where);
    }

    /**
     * 删除一个附件, 同时删除模块内部引用
     *
     * @param integer $attachmentid            
     * @return integer
     */
    public function deleteByAttachmentid ($attachmentid)
    {
        $result = $this->delete('attachmentid = ' . intval($attachmentid));
        if ($result) {
            Trend_Model_Attachment_History::instance()->delete(array(
                    'attachmentid' => $attachmentid
            ));
            Trend_Model_Attachment_Label::instance()->delete(array(
                    'attachmentid' => $attachmentid
            ));
        }
        
        return $result;
    }

    /**
     * 根据 attachmentid 更新附件
     *
     * @param array $set
     *            待更新数组
     * @param integer $attachmentid            
     * @return array
     */
    public function updateByAttachmentid ($attachmentid, $set)
    {
        return $this->update($set, $this->getAdapter()
            ->quoteInto('attachmentid = ?', $attachmentid));
    }

    /**
     * 根据 hashcode 和 userid 获取附件信息，一般用于判断附件是否存在
     */
    public function fetchByHashcodeAndUserid ($hashcode, $userid)
    {
        $select = $this->getAdapter()
            ->select()
            ->from($this->getTable());
        $select->where("hashcode = ?", $hashcode);
        $select->where("userid = ?", $userid);
        $row = $select->query()->fetch();
        return is_array($row) && count($row) ? $row : null;
    }

    /**
     *
     * @return Trend_Model_Attachment
     */
    public static function instance ()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ Native EOL ^ UTF-8