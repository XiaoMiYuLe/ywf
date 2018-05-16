<?php 

class Support_Detach extends Zeed_Db_Model
{
    // 表名
	protected $_tableName = null;
	
	// 分表字段
	protected $_detachField = null;
    
	// 分表类型
	protected $_detachType  = 'Range';
	
	// 允许的分表类型
	protected $_detachTypes = array('Range','Mod');
	
	// 分多少表
	protected $_detachNum  = 1;
	
	protected $_detachExr = array('range'=>1000000);
	
	protected $_detachStatus = false;
	
	protected $_baseTableName = null;
	
	public function __construct($config = array())
	{
	    $this->_baseTableName = $this->_prefix.$this->_name;
	    
	    parent::__construct($config);
	    
	    if ($this->_detachStatus === true && (! is_string($this->_detachField) || '' == $this->_detachField )) {
	        
	        throw new Zeed_Exception('must specify a valid field for detcah table');
	        
	    }
	}
	
	// 数据表名-- 只返回单表表名
	protected function _detachTableName($data)
	{
	    if($this->_detachField == null || $this->_detachType == null || !in_array($this->_detachType,$this->_detachTypes)){
	       throw new Zeed_Exception('must specify a valid field for detcah table');
	    }
	    
	    $this->_detachType = ucwords($this->_detachType);
	    
	    $func = '_detach'.$this->_detachType;
	    
	    if (is_array($data) && ! empty($data[$this->_detachField])) 
	    {
	        $field = $data[$this->_detachField];
	       
        	$this->_tableName = call_user_func_array(array($this,$func),array($field));
        	
	    } elseif (is_string($data)) {
	        
	        $this->_parseWhere($data,$func);
	    }
	}
	
	// 还很不完善,没有mysql的sql解析插件可以用  @TODO
    protected function _parseWhere($where,$func)
    {
        $result = $match = array();
        
        $tmp = trim($where);
        
        $len = strlen($tmp);
        
        $start = $i = $prev = 0;
        
        $quote = false;
        
        while ($i < $len) {
            
            $char = $tmp[$i];
            
            switch ($char) {
                
            	case '\'':
            	    
            	case '"':
            	    
            	    if ($quote === false) {
            	        
            	        $quote = $char;
            	        $start = $i + 1;
            	        break;
            	    }
            	    
            	    if ($quote !== $char) {
            	        break;
            	    }
            	    
            	    if (isset($tmp[$i + 1]) && ($quote === $tmp[$i + 1])) {
            	      
            	        $i++;
            	        
            	        break;
            	    }
            	    
            	    $char = substr($tmp, $start, $i - $start);
            	    
            	    $match[] = substr($tmp, $prev, $start-$prev-1);
            	    
            	    if(preg_match('/^\d+$/',$char)){
            	        $match[count($match)-1] .= $char;
            	        
            	    } else{
            	        $result[] = $char;
            	    }
            	    
            	    $start = $i + 1;
            	    
            	    $prev = $start;
            	    
            	    $quote = false;
            	    
            	    break;
            	    
        	    default:
        	      
        	        break;
        	    }
        	    $i++;
          }
          
          if ($quote === false && ($start < $len)) {
              $char = trim(substr($tmp, $start, $i - $start));
              if ($char !== '') {
                  $match[] = $char;
              }
          }
          
          foreach($match as $val)
          {
              preg_match("/\s*{$this->_detachField}\s*=\s*\'*(\d+)/s",$val,$table);
              
              if(isset($table[1])){
                  
                  $this->_tableName = call_user_func_array(array($this,$func),array($table[1]));
                  
                  return;
              
              }
          }
    }
	
	// 分表查询  单表或者所有表查询，不支持范围表查询
	public function fetchByWhere($where = null, $order = null, $count = 0, $offset = 1, $cols = '*')
	{
	    if($this->_detachStatus == true)
	    {
	        $this->_detachTableName($where);
            
            if($this->_tableName == null)
            {
            	return $this->fetchAllTable($where,$order,$count,$offset,$col);
            	
            } else {
                
            	$this->name = $this->_tableName;
            }
            
	    }
		return parent::fetchByWhere($where,$order,$count,$offset,$col);
	}
	// 重写getCount
	public function getCount($where = null)
	{
	    if($this->_detachStatus == true)
	    {
	        $this->_detachTableName($where);
	    
	        if($this->_tableName == null)
	        {
	            return $this->fetchCount($where);
	             
	        } else {
	    
	            $this->name = $this->_tableName;
	        }
	    }
	    
	    return parent::getCount($where);
	}
	
	// 重写addForEntity
	public function addForEntity($data)
	{
	    if($this->_detachStatus == true){
	       $this->_detachTableName($data);
	       
	       if($this->_tableName){
	           $this->name = $this->_tableName;
	       }
	       
	    }   
	    
	    return parent::addForEntity($data);
	}
	
	// 重写updateForEntity
	public function updateForEntity($data,$id)
	{
	    if($this->_detachStatus == true)
	    {
	        $this->_detachTableName($data);
	
	        if($this->_tableName){ 
	            $this->name = $this->_tableName;
	        }
	
	    }
	     
	    return parent::updateForEntity($data,$id);
	}
	
	public function fetchCount($where = null)
	{
	    if (is_array($where) && count($where))
	    {
	        $_where = array();
	         
	        foreach ($where as $key => $val) {
	            if (preg_match("/^[0-9]/", $key)) {
	                $_where[] = $val;
	            } else {
	                $_where[] = $key . '=\''.$val.'\'';
	            }
	        }
	         
	        $where = implode(' and ',$_where);
	    }
	     
	    if(!empty($where)){
	        $where = ' where '.$where;
	    }
	    
	    $unionSql = array();
	     
	    for($i = 0;$i < $this->_detachNum;$i++)
	    {
	       $unionSql[] = '(SELECT count(*) as count_num FROM '.$this->_baseTableName.$this->_getDetachNum($i).$where.')';
	    }
	     
	    $unionSql = 'SELECT sum(count_num) as count_num FROM ('.implode(" UNION ",$unionSql).') as '.$this->_baseTableName;
	     
	    try {
	       $row = $this->getAdapter()->query($unionSql)->fetch();
	         
	    } catch (Exception $e) {
	     
	       throw new Zeed_Exception($e->getMessage());
	     
	    }
	    return $row ? $row["count_num"] : 0;
	}
	
	public function fetchAllTable($where = null, $order = null, $count = 0, $offset = 1, $cols = '*')
	{
	    if (is_null($cols)) {
	        $cols = '*';
	    }
	    
	    if (is_array($where) && count($where)) 
	    {
	        $_where = array();
	        
	        foreach ($where as $key => $val) {
	            if (preg_match("/^[0-9]/", $key)) {
	                $_where[] = $val;
	            } else {
	                $_where[] = $key . '=\''.$val.'\'';
	            }
	        }
	        
	        $where = implode(' and ',$_where);
	    }
	    
	    if(!empty($where)){
	        $where = ' where '.$where;
	    }

	    $where .= " order by {$this->_primary} asc limit {$offset},{$count}";
	    
	    $unionSql = array();
	    
	    for($i = 0;$i < $this->_detachNum;$i++)
	    {
	         $unionSql[] = '(SELECT '.$cols.' FROM '.$this->_baseTableName.$this->_getDetachNum($i).$where.')'; 
	    }
	    
	    $unionSql = 'SELECT '.$cols.' FROM ('.implode(" UNION ",$unionSql).') as '.$this->_baseTableName.$where;
	    
	    try {
	        
           $data = $this->getAdapter()->query($unionSql)->fetchAll();
           
	    } catch (Exception $e) {
	        
	        throw new Zeed_Exception($e->getMessage());
	        
	    }
	    
	    return $data;
	}
	
	protected function _getDetachNum($i)
	{
	    if($i>0){ 
	       return str_pad($i,2,'0',STR_PAD_LEFT);
	    } 
	}
		
	// 自增ID
	protected function _incrementId()
	{
		$this->getAdapter()->query("REPLACE INTO increment(`stub`) values('g');select LAST_INSERT_ID()");
		
	}
}