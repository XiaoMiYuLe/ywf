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
 * @since      2011-3-21
 * @version    SVN: $Id$
 */

class IndexController extends PageAdminAbstract
{
    protected $_skip_xss_clean = true;
    
    public $perpage = 20;
    
    /**
     * 单页列表
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $group_id = (int) $this->input->get('group_id');
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
            
            $where = null;
            if ($group_id) {
                $where['group_id'] = $group_id;
            }
            if (! empty($key)) {
                $key = mysql_real_escape_string($key);
                $where[] = "title LIKE '%{$key}%'";
            }
            
            $order = 'ctime DESC';
            if ($ordername) {
                $order = $ordername . " " . $orderby;
            }
        
            $pages = Page_Model_Listing::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Page_Model_Listing::instance()->getCount($where);
            
            $data['pages'] = $pages ? $pages : array();
        }
        
        $data['groups'] = Page_Model_Group::instance()->getAllGroups();
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        $data['group_id'] = $group_id;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加单页
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $data['groups'] = Page_Model_Group::instance()->getAllGroups();
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加单页 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if (! Page_Model_Listing::instance()->addForEntity($set['data'])) {
                    throw new Zeed_Exception('Add page failed, please try again.');
                }
                
                /* 往分组文件中写入单页方法 */
                $group = Page_Model_Group::instance()->fetchByPK($set['data']['group_id']);
                $file_target = ZEED_PATH_APPS . "Page/controllers/{$group[0]['folder']}Controller.php";
                $content = Zeed_Config::loadGroup('pagetemplate.function');
                $content = str_replace('{#group_title#}', $group[0]['group_name'], $content);
                $content = str_replace('{#page_title#}', $set['data']['title'], $content);
                $content = str_replace('{#page_folder#}', $set['data']['page_folder'], $content);
                $content = str_replace('{#group_id#}', $set['data']['group_id'], $content);
                
                $content_old = file_get_contents($file_target);
                $content_new = str_replace('/* {#next_function#} */', $content, $content_old);
                file_put_contents($file_target, $content_new);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError($e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 修改单页
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
    
        $page_id = (int) $this->input->query('page_id');
        $page = Page_Model_Listing::instance()->fetchByPK($page_id);
        if (null === $page || ! is_array($page)) {
            $this->setStatus(1);
            $this->setError('The page is not exist.');
            return self::RS_SUCCESS;
        }
        $data['page_id'] = $page_id;
        $data['page'] = $page[0];
        $data['groups'] = Page_Model_Group::instance()->getAllGroups();
    
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改单页 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                Page_Model_Listing::instance()->updateForEntity($set['data'], $set['data']['id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('Edit page failed : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存单页－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
        $res['data'] = array(
                'id' => $this->input->post('page_id', 0),
                'group_id' => $this->input->post('group_id'),
                'if_share' => $this->input->post('if_share'),
                'title' => trim($this->input->post('title')),
                'body' => $this->input->post('body'),
                'memo' => $this->input->post('memo'),
                'url' => trim($this->input->post('url')));
            
            /* 通用数据验证 */
        try {
            
            if (empty($res['data']['title'])) {
                throw new Zeed_Exception('网页名称及网页标题不能为空');
            }
            
            /* 添加状态下的数据验证 */
            if (empty($res['data']['id'])) {
                // 补齐数据
                $res['data']['page_folder'] = trim($this->input->post('page_folder'));
                $res['data']['ctime'] = date(DATETIME_FORMAT);
                
                // 网页名称不能空
                if (empty($res['data']['page_folder'])) {
                    throw new Zeed_Exception('网页名称不能为空');
                }
                
                // 校验该网页名称是否已存在
                $page = Page_Model_Listing::instance()->getPage($res['data']['group_id'], $res['data']['page_folder']);
                if (! empty($page)) {
                    throw new Zeed_Exception('该网页已存在');
                }
                
                // 校验网页名称是否合法
                if (true !== Page_Validator::folder($res['data']['page_folder'])) {
                    throw new Zeed_Exception('网页名称不合法，请重新填写');
                }
                
                // 校验网页名称第一个字母是否小写
                if (lcfirst($res['data']['page_folder']) !== $res['data']['page_folder']) {
                    throw new Zeed_Exception('网页名称第一个字母必须小写');
                }
            }
        } catch (Zeed_Exception $e) {
            $res['status'] = 1;
            $res['error'] = $e->getMessage();
            return $res;
        }
        
        /* 处理分组路径 */
        $group = Page_Model_Group::instance()->fetchByPK($res['data']['group_id']);
        $res['data']['group_name'] = $group[0]['group_name'];
        $res['data']['folder'] = $group[0]['folder'];
        
        return $res;
    }
    
    /**
     * 删除单页
     */
    public function delete()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
    
        $id = (int) $this->input->post('id');
    
        try {
            $page = Page_Model_Listing::instance()->fetchByPK($id);
            $this->_deleteLine($page[0]['page_folder'] . ' start', $page[0]['folder']);
            if ($page) {
                Page_Model_Listing::instance()->deleteByPK($id);
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('Drop page failed : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
    
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
    
    private function _deleteLine($target, $filename)
    {
        $filePath = ZEED_PATH_APPS . "Page/controllers/{$filename}Controller.php";
        $file = fopen($filePath, 'r');
        $start = 0;
        while ($s = htmlspecialchars(stream_get_line($file, 8192, "\n"))) {
            $s = htmlspecialchars_decode($s);
            if (strpos($s, $target) > 0 || $start > 0) {
                $start++;
                if ($start <= 13) {
                    continue;
                }
            }
            if ($s) {
                $s = $s . "\n";
            }
            $content .= $s;
        }
        fclose($file);
        unset($filePath);
        $filePath = ZEED_PATH_APPS . "Page/controllers/{$filename}Controller.php";
        file_put_contents($filePath, htmlspecialchars_decode($content));
    }
    
}
// End ^ Native EOL ^ UTF-8