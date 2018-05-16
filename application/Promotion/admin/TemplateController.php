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

class TemplateController extends PromotionAdminAbstract
{
    public $perpage = 15;
    
    /**
     * 活动模板后台首页
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
        
        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
        	$offset = $page * $perpage;
        	$page = $page + 1;
        	
        	$where['is_del'] = 0;
        	if (! empty($key)) {
        		$where[] = "title LIKE '%{$key}%'";
        	}
        	
        	$order = 'ctime DESC';
        	if ($ordername) {
        		$order = $ordername . " " . $orderby;
        	}
        	
        	$templates = Promotion_Model_Template::instance()->fetchByWhere($where, $order, $perpage, $offset);
        	$data['count'] = Promotion_Model_Template::instance()->getCount($where);
        	
        	$data['templates'] = $templates ? $templates : array();
        }
        
        $data['ordername'] = $ordername;
        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'template.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加
     */
    public function add()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if ($this->input->isPOST()) {
            $this->addSave();
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'template.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 添加 - 保存
     */
    public function addSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
        	    /* 处理图片上传 */
                $files = $set['data']['filepath'];
                if ($files['name']) {
                    $files_upload = Trend_Attachment::add($files['tmp_name']);
                    if ($files['error'] == UPLOAD_ERR_OK) {
                        $set['data']['filepath'] = $files_upload['filepath'];
                    } else {
                        throw new Zeed_Exception('好像发生一些意外错误呢');
                    }
                } else {
                    unset($set['data']['filepath']);
                }
                
                //添加时候清除模板id
                unset($set['data']['template_id']);
                /* 写入模板表 */
                if (! $template_id = Promotion_Model_Template::instance()->addForEntity($set['data'])) {
                    throw new Zeed_Exception('添加模板失败');
                }
                $set['data']['template_id'] = $template_id;
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('添加模板失败 : ' . $e->getMessage());
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
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
    
        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        
        /* 接收参数 */
        $template_id = (int) $this->input->query('template_id');
        
        /* 获取模板信息 */
        if (! $template = Promotion_Model_Template::instance()->fetchByPK($template_id)) {
            $this->setStatus(1);
            $this->setError('查无此模板');
            return self::RS_SUCCESS;
        }
        $template = $template[0];
        
        /* 处理模板文件信息 */
        if ($template['filepath']) {
            $template['filepath'] = Support_Image_Url::getImageUrl($template['filepath']);
        }
        
        $data['template_id'] = $template_id;
        $data['template'] = $template;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'template.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 修改 - 保存
     */
    public function editSave()
    {
        $set = $this->_validate();
        if ($set['status'] == 0) {
            try {
                /* 处理图片上传 */
                $files = $set['data']['filepath'];
                if ($files['name']) {
                    $files_upload = Support_File_Upload::run($files);
                    if ($files['error'] == UPLOAD_ERR_OK) {
                        $set['data']['filepath'] = $files_upload['filepath'];
                    } else {
                        throw new Zeed_Exception('好像发生一些意外错误呢');
                    }
                } else {
                    unset($set['data']['filepath']);
                }
                
                /* 执行更新 */
                Promotion_Model_Template::instance()->updateForEntity($set['data'], $set['data']['template_id']);
            } catch (Zeed_Exception $e) {
                $this->setStatus(1);
                $this->setError('编辑模板失败 : ' . $e->getMessage());
                return false;
            }
            return true;
        }
    
        $this->setStatus($set['status']);
        $this->setError($set['error']);
        return false;
    }
    
    /**
     * 保存－校验
     */
    private function _validate()
    {
        $res = array('status' => 0, 'error' => null, 'data' => null);
    
        $res['data'] = array(
                'template_id' => $this->input->post('template_id', 0),
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'filepath' => $_FILES['filepath'],
                'mtime' => date(DATETIME_FORMAT)
        );
    
        /* 数据验证 */
        if (empty($res['data']['title'])) {
            $res['status'] = 1;
            $res['error'] = '请填写模板标题';
            return $res;
        }
    
        /* 处理添加时间 */
        if (! $res['data']['template_id']) {
            $res['data']['ctime'] = $res['data']['mtime'];
        }
    
        return $res;
    }
    
    /**
     * 删除
     */
    public function delete ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        if (! $this->input->isPOST()) {
            $this->setStatus(1);
            $this->setError('请勿非法操作');
            return self::RS_SUCCESS;
        }
        
        $template_id = (int) $this->input->query('template_id');
        
        if (! $template_id) {
            $this->setStatus(1);
            $this->setError('缺少参数，或参数错误');
            return self::RS_SUCCESS;
        }
        
        try {
            Promotion_Model_Template::instance()->deleteByPK($template_id);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError('删除模板失败 : ' . $e->getMessage());
            return self::RS_SUCCESS;
        }
        
        $this->setData('data', '删除成功');
        return self::RS_SUCCESS;
    }
}

// End ^ Native EOL ^ UTF-8