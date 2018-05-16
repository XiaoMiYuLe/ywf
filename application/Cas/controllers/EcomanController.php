<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class EcomanController extends CasAbstract {
    
    /**
     * 经纪人认证
     */
    public function index() {
        $this->addResult(self::RS_SUCCESS, 'json');
        $arr = array(
            'userid'=>$_SESSION['userid'],
        
        );
        $result = Api_Cas_GetUserAvatar::run($arr);
        $result1 = Api_Cas_ConfirmEcoman::run($arr);
        
        $data['avatar'] = $result['data']['avatar'];
        $data['remarks'] = $result['data']['remarks'];
        $data['is_invitaiton'] = $result1['data']['is_invitaiton'];
        $data['is_ecoman'] = $result1['data']['is_ecoman'];
        
        if($data['is_ecoman']==1 ){
            header('Location: ' . '/cas/user/index');
            exit;
        }
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'ecoman.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     *经纪人条款
     */
    public function ecoman() {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $arr = array(
            'userid'=>$_SESSION['userid'],
            'type'=>6,
        
        );
        $result = Api_System_GetAgreement::run($arr);
        $data['ecoman_agreement'] = $result['data'][0]['ecoman_agreement'];
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'ecoman.ecoman');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
    /**
     *上传图片
     */
    public function addpic() {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        $arr = array(
           'userid'=>$_SESSION['userid'],
            
        );
        
        $result = Api_Cas_AddAvatar::run($arr);
        
        return $result;
    }
}
