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
class GroupController extends PageAdminAbstract
{

    public $perpage = 20;

    /**
     * 单页分组列表
     */
    public function index ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        /* 接收参数 */
        $ordername = $this->input->get('ordername', null);
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
            
            $where = null;
            /* 分类名 like 搜索分类 */
            if (! empty($key) && is_string($key)) {
                $key = mysql_real_escape_string($key);
                $where = 'group_name LIKE \'%' . $key . '%\'';
            }
            
            $order = "group_id ASC";
            $ordername && $order = $ordername . " " . $orderby;
            
            $data['groups'] = Page_Model_Group::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Page_Model_Group::instance()->getCount($where);
            
            $data['groups'] = $data['groups'] ? $data['groups'] : array();
        }
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'group.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加分组
     */
    public function add ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $this->addResult(self::RS_SUCCESS, 'php', 'group.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 添加分组 - 保存
     */
    public function addSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                if (! $group_id = Page_Model_Group::instance()->addForEntity($set['data'])) {
                    throw new Zeed_Exception('Add group failed, please try again.');
                }
                
                /* 生成分组，即生成控制器文件 */
                $author = Com_Admin_Authorization::getLoggedInUser();
                $file_target = ZEED_PATH_APPS . "Page/controllers/{$set['data']['folder']}Controller.php";
                $content = Zeed_Config::loadGroup('pagetemplate.class');
                $content = str_replace('{#class#}', $set['data']['folder'], $content);
                $content = str_replace('{#title#}', $set['data']['group_name'], $content);
                $content = str_replace('{#author#}', $author['fullname'], $content);
                $content = str_replace('{#since#}', date(DATETIME_FORMAT), $content);
                $content = str_replace('{#group_id#}', $group_id, $content);
                file_put_contents($file_target, $content);
                
                /* 写入该分组首页到数据库 */
                $data_index = array(
                        'group_id' => $group_id,
                        'group_name' => $set['data']['group_name'],
                        'title' => $set['data']['group_name'],
                        'body' => $set['data']['group_name'],
                        'memo' => $set['data']['group_name'],
                        'url' => '',
                        'folder' => $set['data']['folder'],
                        'page_folder' => 'index',
                        'if_share' => 1,
                        'ctime' => date(DATETIME_FORMAT)
                );
                if (! Page_Model_Listing::instance()->addForEntity($data_index)) {
                    throw new Zeed_Exception('Add page index failed, please try again.');
                }
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
     * 编辑
     */
    public function edit ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        $group_id = (int) $this->input->query('group_id');
        
        if (! $group = Page_Model_Group::instance()->fetchByPk($group_id)) {
            $this->setStatus(1);
            $this->setError('该分组不存在');
            return self::RS_SUCCESS;
        }
        $data['group'] = $group[0];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'group.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 修改 - 保存
     */
    public function editSave ()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                Page_Model_Group::instance()->updateForEntity($set['data'], $set['data']['group_id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑分组失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
        
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }

    /**
     * 保存分组－校验
     */
    private function _validate ()
    {
        $res = array(
                'status' => 0,
                'error' => null,
                'data' => null
        );
        
        $res['data'] = array(
                'group_id' => (int) $this->input->post('group_id'),
                'group_name' => trim($this->input->post('group_name')),
                'folder' => trim($this->input->post('folder')),
                'moveable' => trim($this->input->post('moveable')) ? 0 : 1
        );
        
        try {
            
            /* 数据验证 */
            if (empty($res['data']['group_name'])) {
                throw new Zeed_Exception('分组名称不能为空');
            }
            
            /* 添加状态下校验 */
            if (empty($res['data']['group_id'])) {
                if (empty($res['data']['folder'])) {
                    throw new Zeed_Exception('分组名称不能为空');
                }
                
                /* 校验分组文件名是否合法 */
                if (true !== Page_Validator::folder($res['data']['folder'])) {
                    throw new Zeed_Exception('分组文件名不合法，请重新填写');
                }
                
                /* 校验分组文件名第一个字母是否大写 */
                if (ucfirst($res['data']['folder']) !== $res['data']['folder']) {
                    throw new Zeed_Exception('分组文件名第一个字母必须大写');
                }
                
                /* 校验该分组文件名是否已存在 */
                $group = Page_Model_Group::instance()->fetchByFV('folder', $res['data']['folder']);
                if (! empty($group)) {
                    throw new Zeed_Exception('该分组文件名已存在');
                }
            }
        } catch (Zeed_Exception $e) {
            $res['status'] = 1;
            $res['error'] = $e->getMessage();
            return $res;
        }
        
        /* 编辑状态下，分组文件名 folder 不可更改 */
        if ($res['data']['group_id']) {
            unset($res['data']['folder']);
        }
        
        return $res;
    }

    /**
     * 删除分组
     * 删除分组，并删除分组下的单页（包括清除数据库和删除文件）
     * 删除前，弹框提示
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        
        $id = (int) $this->input->post('id');
        
        try {
            $page = Page_Model_Group::instance()->fetchByPK($id);
            if ($page) {
                Page_Model_Group::instance()->deleteByPK($id);
            }
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('Drop group failed : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8