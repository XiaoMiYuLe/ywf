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
 * @since      May 13, 2010
 * @version    SVN: $Id$
 */

class Com_Model_Mailqueue extends Zeed_Db_Model
{
    /*
     * @var string The table name.
     */
    protected $_name = 'mailqueue';

    /**
     * @var integer Primary key.
     */
    protected $_primary = 'id';

    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'os_';

    /**
     * @param Mailqueue|array $set
     * @return integer
     */
    public function add($set)
    {
        if ($set instanceof Com_Entity_Mailqueue) {
            $data = $set->toArray();
        } else {
            $data = Zeed_Object::instance()->Com_Entity_Mailqueue()->fromArray($set)->toArray();
        }

        /**
         * 附件必须是一个二维数组
         */
        if ( isset($data['attachment']) )
        {
            if ( ! is_array($data['attachment'])) {
                unset($data['attachment']);
            }

            $data['attachment'] = serialize($data['attachment']);
        }
        
        if (!isset($data['ctime'])) {
            $data['ctime'] = date(DATETIME_FORMAT);
        }
        
        return $this->insert($data);
    }

    /**
     * @return array
     */
    public function getOneMailqueue()
    {
        $select = $this->getAdapter()->select()->from($this->getTable());
        $select->where('status = 0');
        $select->orWhere('status = ' . Zeed_Task_Mail::QUEUE_STATUS_FAIL . ' AND failcount < ' . Zeed_Task_Mail::QUEUE_FAILCOUNTMAX);
        $select->order(array(
                'status ASC',
                'id DESC'));
        $select->limit(1);

        $row = $select->query()->fetch();

        return ($row) ? $row : null;
    }

    public function sendFailed($id)
    {
        $db = $this->getAdapter();
        $where = $db->quoteInto('id = ?', $id);
        $failcountField = $db->quoteIdentifier('failcount');

        $stmt = $db->query('UPDATE '.$this->getTable() . " SET {$failcountField}={$failcountField}+1 WHERE {$where}");
        return $stmt->rowCount();
    }

    /**
     * 更新队列状态
     *
     * @param integer $id
     * @param integer $newStatus
     */
    public function updateStatusById($id, $newStatus)
    {
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $set = array(
                'status' => $newStatus);

        return $this->update($set, $where);
    }

    /**
     *
     * @param integer $id
     * @return integer
     */
    public function deleteById($id)
    {
        $where = $this->getAdapter()->quoteInto('id = ?', $id);

        return $this->delete($where);
    }

    /**
     * 删除发送失败的队列
     *
     * @return void
     */
    public function deleteByFailStatus()
    {
        $where = $this->getAdapter()->quoteInto('status = ?', Zeed_Task_Mail::QUEUE_STATUS_FAIL);

        return $this->delete($where);
    }

    /**
     * @return Com_Model_Mailqueue
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
