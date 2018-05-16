<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ShareController extends IndexAbstract {

    /**
     * 分享
     */
    public function getShare() {
        $this->addResult(self::RS_SUCCESS, 'json');

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'share.getshare');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     * 
     */
    public function index() {
        
    }

}
