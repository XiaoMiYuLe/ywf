<?php/** * Zeed Platform Project * Based on Zeed Framework & Zend Framework. *  * LICENSE * http://www.zeed.com.cn/license/ *  * @category   Zeed * @package    Zeed_ChangeMe * @subpackage ChangeMe * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn) * @author     Zeed Team (http://blog.zeed.com.cn) * @since      2011-3-21 * @version    SVN: $Id$ */class AdvertController extends IndexAbstract{    /**      * 关于我们主页面     */    public function index()    {        $this->addResult(self::RS_SUCCESS, 'json');        $id = $this->input->get('id');        $data['id'] = $id;        $this->setData('data', $data);        $this->addResult(self::RS_SUCCESS, 'php', 'advert.index');        return parent::multipleResult(self::RS_SUCCESS);    }    }// End ^ Native EOL ^ UTF-8