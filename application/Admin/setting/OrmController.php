<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-12-6
 * @version    SVN: $Id$
 */

class OrmController extends AdminAbstract
{
    // orm 模板配置文件
    protected $_orm_template = null;
    
    /**
     * ORM 管理
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 获取所有模块 */
        $data['apps'] = AppModel::instance()->getAllApps();
        
        /* 获取所有数据表 */
        $data['tables'] = Zeed_Db_Tools::instance()->listTables();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'orm.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 生成
     */
    public function build()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        
        /* 获取参数 */
        $build_type = $this->input->post('build_type');
        
        try {
            /* 判断配置文件 */
            if (! $this->_orm_template = Zeed_Config::loadGroup('orm.template')) {
                throw new Zeed_Exception('orm 配置文件缺失');
            }
            
            /* 生成 */
            if ($build_type == 'app') {
                $this->buildFromApp();
            } elseif ($build_type == 'table') {
                $this->buildFromTable();
            } else {
                $this->buildAll();
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('生成失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        return self::RS_SUCCESS;
    }
    
    /**
     * 生成 - 通过模块的方式
     */
    private function buildFromApp()
    {
        /* 获取参数 */
        $apps = $this->input->post('apps');
        
        /* 若参数为空，则直接返回 */
        if (empty($apps)) {
            return false;
        }
        
        /* 获取所有数据表 */
        $tables_all = Zeed_Db_Tools::instance()->listTables();
        
        /* 执行生成 - 先过滤，后生成 */
        foreach ($tables_all as $v) {
            $v_arr = explode('_', $v);
            if (in_array($v_arr[0], $apps)) {
                // 创建文件目录
                $file_path = $this->createFile($v);
                
                // 生成 ORM 对象
                if (substr($v, 0, 6) === 'admin_') {
                    $str_entity = Zeed_Db_Tools::instance()->createEntity($v, true);
                } else {
                    $str_entity = Zeed_Db_Tools::instance()->createEntity($v);
                }
                
                // 组织最终写入文件的内容
                $file_content = str_replace('{#date#}', date('Y-m-d', time()), $this->_orm_template);
                $file_content = str_replace('{#entity_class#}', $str_entity, $file_content);
                
                // 写入文件
                file_put_contents($file_path, $file_content);
            }
        }
    }
    
    /**
     * 生成 - 通过数据表的方式
     */
    private function buildFromTable()
    {
        /* 获取参数 */
        $tables = $this->input->post('tables');
        
        /* 若参数为空，则直接返回 */
        if (empty($tables)) {
            return false;
        }
        
        /* 获取所有数据表 */
        $tables_all = Zeed_Db_Tools::instance()->listTables();
        
        /* 执行生成 - 先过滤，后生成 */
        foreach ($tables as $v) {
            if (in_array($v, $tables_all)) {
                // 创建文件目录
                $file_path = $this->createFile($v);
                
                // 生成 ORM 对象
                if (substr($v, 0, 6) === 'admin_') {
                    $str_entity = Zeed_Db_Tools::instance()->createEntity($v, true);
                } else {
                    $str_entity = Zeed_Db_Tools::instance()->createEntity($v);
                }
                
                // 组织最终写入文件的内容
                $file_content = str_replace('{#date#}', date('Y-m-d', time()), $this->_orm_template);
                $file_content = str_replace('{#entity_class#}', $str_entity, $file_content);
                
                // 写入文件
                file_put_contents($file_path, $file_content);
            }
        }
        
        return true;
    }
    
    /**
     * 生成 - 生成所有
     */
    private function buildAll()
    {
        /* 获取所有数据表 */
        $tables_all = Zeed_Db_Tools::instance()->listTables();
        
        /* 执行生成 */
        foreach ($tables_all as $v) {
            // 创建文件目录
            $file_path = $this->createFile($v);
            
            // 生成 ORM 对象
            if (substr($v, 0, 6) === 'admin_') {
                $str_entity = Zeed_Db_Tools::instance()->createEntity($v, true);
            } else {
                $str_entity = Zeed_Db_Tools::instance()->createEntity($v);
            }

            // 组织最终写入文件的内容
            $file_content = str_replace('{#date#}', date('Y-m-d', time()), $this->_orm_template);
            $file_content = str_replace('{#entity_class#}', $str_entity, $file_content);

            // 写入文件
            file_put_contents($file_path, $file_content);
        }
    
        return true;
    }
    
    /**
     * 生成 - 创建文件
     */
    private function createFile($table)
    {
        $table_array = explode('_', $table);
        
        /* 获取要生成的文件名 */
        $filename = ucfirst(end($table_array)) . '.php';
        
        /* 拼装文件绝对路径的前部公共部分，并获取要生成的文件名 */
        switch ($table_array[0]) {
            case 'admin': 
                $table_array_temp = array();
                foreach ($table_array as $k => $v) {
                    if ($k > 0) {
                        $table_array_temp[] = ucfirst($v);
                    }
                }
                $filename = implode('', $table_array_temp) . 'Entity.php';
                $path_pre = ZEED_PATH_APPS . 'Admin' . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR;
            break;
            case 'epv': 
                $path_pre = ZEED_PATH_LIB . 'Epv' . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR;
            break;
            case 'system': 
                $path_pre = ZEED_PATH_LIB . 'System' . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR;
            break;
            default: 
                $filename = ucfirst(end($table_array)) . '.php';
                $path_pre = ZEED_PATH_APPS . ucfirst($table_array[0]) . DIRECTORY_SEPARATOR . 'Entity' . DIRECTORY_SEPARATOR;
            break;
        }
        
        /* 拼装文件所在目录的完整目录路径 */
        $dir_path = $path_pre;
        if ($table_array[0] != 'admin' && count($table_array) > 2) {
            // 去掉数组的第一个和最后一个元素
            array_shift($table_array);
            array_pop($table_array);
            
            foreach ($table_array as $v) {
                $dir_array[] = ucfirst($v);
            }
            $dir_path .= implode(DIRECTORY_SEPARATOR, $dir_array) . DIRECTORY_SEPARATOR;
        }
        
        /* 创建文件 */
        if (! is_dir($dir_path)) {
            mkdir($dir_path, 0777, true);
        }
        
        /* 返回文件绝对路径 */
        return $dir_path . $filename;
    }
}

// End ^ Native EOL ^ UTF-8