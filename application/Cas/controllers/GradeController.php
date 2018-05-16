<?php

class GradeController extends IndexAbstract 
{
    public $perpage = 15;

    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
               if (!Cas_Authorization::getLoggedInUserid()) {
            //未登入
            header('Location: ' . '/cas/sign/in');
            exit;
        }

        $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$_SESSION['userid']}'");
        if (!$userExists) {
            throw new Zeed_Exception('该用户不存在');
        }
         
        /* 检查用户状态 */
        if($userExists[0]['status'] == 1 ){
            throw new Zeed_Exception('该账号已禁用');
        }
        $userid = $userExists[0]['userid']; // 自定义
        $useridstr = Cas_Model_grade::instance()->fetchUserid($userid); // 获取该节点下面所有子节点
        if($useridstr){
            // 获取账户投资金额
            $strordercols = array('userid','SUM(buy_money) summoney');
            $orderwhere = "FIND_IN_SET(userid, '".$useridstr."' )" ;
            $orderwhere .= "and is_pay = 1 and goods_pattern > 0 and goods_id not in('109','117')";
            $group = 'userid';
            $userorderarray = Cas_Model_grade::instance()->fetchOrderarrayById($orderwhere,$strordercols,$group); // 获取所有用户
//          $allusermoney = $this->getallmoneybyid($userid,1);
//          $userorderarray = array_merge($allusermoney,$userorderarray);
            $orderarr = array();
            if($userorderarray){
                foreach ($userorderarray as $ork=>$orv){
                    unset($userorderarray[$ork]['userid']);
                    $orderarr[$orv['userid']] = $userorderarray[$ork];
                }
            }
            // 获取所有相关用户列表
            $strusercols = array('userid','parent_id','username','user_code','is_ecoman');
            $userwhere = "FIND_IN_SET(userid, '".$useridstr."' )" ;
            $userarray = Cas_Model_grade::instance()->fetchUserarrayById($userwhere,$strusercols);
            $userarr = array();
            if($userarray){
                foreach ($userarray as $usk=>$usv){
                    if($userarray[$usk]['userid'] == $userid){
                        $userarray[$usk]['parent_id'] = 0;
                    }
                    $userarr[$usv['userid']] = $userarray[$usk];
                }
            }
            // 合并数组
            if($userarr){
                foreach ($userarr as $k=>$v){
                    if(isset($orderarr[$k])){
                        $userarr[$k]['summoney'] = $orderarr[$k]['summoney'];
                    }else{
                        $userarr[$k]['summoney'] = 0;
                    }
                }
            }
        }
        $data = array();
        if($userarr){
            $arr = $this->get_tree($userarr,$userarr[$userid]['parent_id']); // 按级别分组
            $lastarr = $returnarr = $dataarr= $dataarrnew = array();
            $returnarr = $this->getnewlistarr($arr,$lastarr,$returnarr); // 各级别汇总数量
            $depth = array();
            if($returnarr){
               foreach ($returnarr as $rek=>$rev){
                    if($rev['depth']>2){
                            $depth[$rev['depth'].'.'.$rev['parent_id']]['userid'] = $rev['depth'].'.'.$rev['parent_id'];
                            if($rev['depth'] == 3){
                                $depth[$rev['depth'].'.'.$rev['parent_id']]['parent_id'] = $rev['parent_id'];
                            }else{
                                $depth[$rev['depth'].'.'.$rev['parent_id']]['parent_id'] = ($rev['depth']-1).'.'.$rev['parent_id'];
                            }
                            $depth[$rev['depth'].'.'.$rev['parent_id']]['username'] = ($rev['depth']-1).'级';
                            $depth[$rev['depth'].'.'.$rev['parent_id']]['user_code'] = 'ywf';
                            if(isset($depth[$rev['depth'].'.'.$rev['parent_id']])){
                                $depth[$rev['depth'].'.'.$rev['parent_id']]['summoney'] += $rev['summoney'];
                                $depth[$rev['depth'].'.'.$rev['parent_id']]['count'] +=  $rev['count'];
                            }else{
                                $depth[$rev['depth'].'.'.$rev['parent_id']]['summoney'] = $rev['summoney'];
                                $depth[$rev['depth'].'.'.$rev['parent_id']]['count'] =  $rev['count'];
                            }
                            unset($returnarr[$rek]);
                    }
                  }
            }
            $returnarr = array_merge($returnarr,$depth);
            $n = 0;
            foreach ($returnarr as $rk=>$rv){
                $n++;
                unset($returnarr[$rk]);
                $dataarr[$n]['key'] = $rv['userid'];
                $dataarr[$n]['boss'] = $rv['parent_id'];
                $dataarr[$n]['name'] = ($rv['username'])?$rv['username']:$rv['user_code'];
                $dataarr[$n]['money'] = $rv['summoney']/10000;
                $dataarr[$n]['count'] = $rv['count'];
            }
            foreach ($dataarr as $k=>$v){
                $dataarrnew [] = $v;
            }
            $data['userlist'] = json_encode($dataarrnew);
        }
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'grade.index');
        return parent::multipleResult(self::RS_SUCCESS);
    }
    
     public function get_tree($data,$pcode='0',$list = array()){
            static $deep = 0;
            static $ndata=array();
            ++$deep;
            if($data){
                foreach($data as $k=>$v){
                    if($pcode==$v['parent_id']){
                        $v['depth'] = $deep;
                        if(empty($list)){
                            $ndata[$v['userid']] = $v;
                            $ndata[$v['userid']]['children'] = array();
                            $list[$deep] = $v['userid'];
                        }else{
                            $list[$deep] = $v['userid'];
                            ksort($list);
                            $t = '$ndata';
                            foreach ($list as $key=>$val){
                                if($key==1){
                                    $t .= "[$val]";
                                }else
                                    $t .= "['children'][$val]";
                            }
                            eval($t."=\$v;");
                        }
                        unset($data[$k]);
                        $this->get_tree($data,$v['userid'],$list);
                    }
                }
            }
            $deep--;
            return $ndata;
        }
        
        public function getnewlistarr(&$arr,&$lastarr,&$returnarr){
            if(empty($lastarr)){
                foreach ($arr as $k=>$v){
                    $arrid= $this->getallmoneybyid($v['userid']);
                    $arr[$k]['summoney'] = $arrid['summoney'];
                    $arr[$k]['count'] = $arrid['count'];
                    if(isset($arr[$k]['children'])&&$arr[$k]['children']){
                        //$arr[$k]['count'] = count($v['children']);
                        $returnarr[$k] = $arr[$k];
                        unset($returnarr[$k]['children']);
                        $this->getnewlistarr($arr,$arr[$k]['children'],$returnarr);
                    }else{
                        //$arr[$k]['count'] = 0;
                        $returnarr[$k] = $arr[$k];
                    }
                    
                }
            }else{
                foreach ($lastarr as $k=>$v){
                    $arrid= $this->getallmoneybyid($v['userid']);
                    $lastarr[$k]['summoney'] = $arrid['summoney'];
                    $lastarr[$k]['count'] = $arrid['count'];
                        if(isset($lastarr[$k]['children'])&&$lastarr[$k]['children']){
//                          $lastarr[$k]['count'] = count($v['children']);
                            $returnarr[$k] = $lastarr[$k];
                            if($v['depth']>2&&isset($lastarr[$k]['children'])&&$lastarr[$k]['children']){
                                foreach ($lastarr[$k]['children'] as $nk=>$nv){
                                    $lastarr[$k]['children'][$nk]['parent_id']=$v['parent_id'];
                                }
                            }
                            unset($returnarr[$k]['children']);
                            $this->getnewlistarr($arr,$lastarr[$k]['children'],$returnarr);
                        }else{
//                          $lastarr[$k]['count'] = 0;
                            if($v['depth']>2&&isset($lastarr[$k]['children'])&&$lastarr[$k]['children']){
                                foreach ($lastarr[$k]['children'] as $nk=>$nv){
                                    $lastarr[$k]['children']['parent_id'][$nk]['parent_id']=$v['parent_id'];
                                }
                            }
                            $returnarr[$k] = $lastarr[$k];
                        }
                    }
            }
            return $returnarr;
        }
        
        public function getallmoneybyid($userid = 0,$reg = 0){
            $useridstr = Cas_Model_grade::instance()->fetchUserid($userid); // 获取该节点下面所有子节点
            $allusermoney = array();
            if($useridstr){
                // 获取账户投资金额
                $strordercols = array('userid','SUM(buy_money) summoney');
                $orderwhere = "FIND_IN_SET(userid, '".$useridstr."' )" ;
                $orderwhere .= "and is_pay = 1 and goods_pattern > 0 and goods_id not in('109','117')";
                $allusermoney = Cas_Model_grade::instance()->fetchOrderarrayById($orderwhere,$strordercols); // 获取所有用户
                
                // 获取所有相关用户列表
                $strusercols = array('count(userid) count');
                $userwhere = "FIND_IN_SET(userid, '".$useridstr."' ) and is_ecoman = 1 " ;
                $userarray = Cas_Model_grade::instance()->fetchUserarrayById($userwhere,$strusercols);
                $allusermoney[0]['count']= $userarray[0]['count'];
                
                $allusermoney = $reg?$allusermoney:$allusermoney[0];
            }
            return $allusermoney;
        }
}