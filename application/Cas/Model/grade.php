<?php

class Cas_Model_grade extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'user';
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'user_id';
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';

    public function fetchUserid($id)
    {
    	$table = $this->getTable();
    	$sql = " select getChildLst(".$id.") id";
    	$row = $this->query($sql,null)->fetchColumn();
    	return $row ? $row : null;
    }
    
    public function fetchUserarrayById($where,$cols)
    {
    	$table = $this->getTable();
    	$select = $this->getAdapter()->select()->from($table,$cols);
    	$row = $select->where($where)->query()->fetchAll();
    	return $row ? $row : null;
    }
    
    public function fetchOrderarrayById($where,$cols,$group = null)
    {
    	$table = 'bts_order';
    	$select = $this->getAdapter()->select()->from($table,$cols);
    	if ($group !== null) {
    		$select->group($group);
    	}
    	$row = $select->where($where)->query()->fetchAll();
    	return $row ? $row : null;
    }
    
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
    
	}

// End ^ LF ^ encoding

