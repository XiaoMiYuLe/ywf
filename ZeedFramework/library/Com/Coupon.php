<?php
/**
 *
 * platform programe
 * @category   Trendible
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @author     shaun.song ( GTalk/Email: songsj125@gmail.com | MSN: ssj125@hotmail.com )
 * @since      2010-6-29
 * @version    SVN: $Id$
 */
class Com_Coupon
{
    /**
     * 错误信息
     * @var array
     * 1001	已经抽取过一个，不可以重复抽取
     * 1002	无库存
     * 1003 code已经被领取过
     * 1004 coupon取不到或coupon已被领取
     * 1005 系统错误
     */
    private static $_ERR = array(
            1001 => 'you have get,can\'t get agin',
            1002 => 'Not in stock',
            1003 => 'operation fail',
            1004 => 'coupon Invalid',
            1005 => 'system error',
            1006 => 'category error',
            1007 => 'category not exists');
    
    private static $res = array(
            'status' => 0,
            'error' => null,
            'data' => null);
    
    private static $_allowUniquefields = array(
            'useid',
            'ip');
    
    private static $endnodecategory;
    
    /**
     * 根据category末接点获取coupon
     * 返回数据示例
     * Array
     * (
     * [status] => 0
     * [error] => 
     * [data] => Array
     * (
     * [code] => 228X8V75
     * [categoryid] => 2
     * [userid] => 2
     * [cycle] => 0
     * [ctime] => 2010-07-06 10:06:47
     * [ip] => 127.0.0.1
     * )
     * 
     * )
     * 
     * @param integer $categoryid 分类ID  这里的分类是指 末梢分类 ，即分配过coupon的分类
     * @param integer $userid	用户ID
     * @param integer $duplicate	是否可以重复抽取
     * @param string $uniquefield	uniq字段
     * @param string $ip	访问者的IP
     * @return array
     */
    public static function getCoupon($categoryid = 0, $userid = 0, $duplicate = 1, $uniquefield = '', $ip = '')
    {
        if ($ip == '') {
            $ip = Zeed_Util::clientIP();
        }
        
        if (! $duplicate && ! self::checkDuplicate($categoryid, $uniquefield, array(
                'userid' => $userid,
                'ip' => $ip))) {
            return self::$res;
        }
        
        /**
         * 对coupon_category进行行锁，更新categoryautoid=categoryautoid+1并取出categoryautoid
         * 根据categoryautoid、categoryid取得coupon_categorylink的值
         */
        if (! ($linkInfo = self::getCategoryLink($categoryid))) {
            return self::$res;
        }
        
        Com_Model_Coupon::instance()->beginTransaction();
        try {
            
            if (! ($couponinfo = self::getCouponInfo($linkInfo['codeid']))) {
                Com_Model_Coupon::instance()->rollBack();
                return self::$res;
            }
            
            /**
             * 检查code已经被领取过
             */
            if (! self::checkHistory($couponinfo['code'])) {
                Com_Model_Coupon::instance()->rollBack();
                return self::$res;
            }
          
            $set = array(
                    'status' => 2,
                    'mtime' => date('Y-m-d H:i:s'));
            
            Com_Model_Coupon::instance()->updateCouponById($couponinfo['id'], $set);
            
            $history = array(
                    'code' => $couponinfo['code'],
                    'categoryid' => $categoryid,
                    'userid' => $userid,
                    'cycle' => $linkInfo['cycle'],
                    'ctime' => date('Y-m-d H:i:s'),
                    'ip' => $ip);
            Com_Model_Coupon::instance()->addCouponHistory($history);
            Com_Model_Coupon::instance()->commit();
            self::$res['data'] = $history;
            self::$res['data']['serialnumber'] = $couponinfo['serialnumber'];
            return self::$res;
        } catch (Exception $e) {
            Com_Model_Coupon::instance()->rollBack();
            self::$res['status'] = 1005;
            self::$res['error'] = self::$_ERR[1005];
            return self::$res;
        }
    }
    
    /**
     * 根据分类中的活动根节点，来获取coupon
     * 
     * 
     * @param integer $categoryid
     * @param integer $userid
     * @return array
     */
    public static function get($categoryid, $userid)
    {
        $category = Com_Model_Coupon::instance()->getCategoryById($categoryid);
        
        /**
         *验证分类是否存在 
         */
        if (! (! empty($category))) {
            self::$res['status'] = 1006;
            self::$res['error'] = self::$_ERR[1006];
            return self::$res;
        }
        
        /**
         * 验证活动是否有效
         */
        $date = date('Y-m-d H:i:s');
        if (! ($date >= $category['stime'] && $date <= $category['etime'])) {
            self::$res['status'] = 1007;
            self::$res['error'] = self::$_ERR[1007];
            return self::$res;
        }

        /**
         * 获取末梢分类
         */
        self::getEndnodeCategory($categoryid);
        if(empty(self::$endnodecategory))
        {
        	self::$res['status'] = 1002;
            self::$res['error'] = self::$_ERR[1002];
            return self::$res;
        }
        
        /**
         * 返回Coupon
         */
       return self::getCoupon(self::$endnodecategory['categoryid'],$userid,self::$endnodecategory['duplicate'],self::$endnodecategory['uniquefield']);
    }
    
    /**
     * 当不可重复领取，检查是否已经领取过
     * 
     * @param integer $categoryid
     * @param string $uniquefield(暂时仅支持ip,userid)
     * @param array $uniquevalue
     * @return boolen
     */
    private static function checkDuplicate($categoryid, $uniquefield, $uniquevalue)
    {
        $uniquefield = $uniquefield != '' && in_array($uniquefield, self::$_allowUniquefields) ? $uniquefield : 'userid';
        $where = array(
                'categoryid' => $categoryid);
        $where[$uniquefield] = $uniquevalue[$uniquefield];
        $rows = Com_Model_Coupon::instance()->getCouponHistoryByWhere($where);
        
        if (empty($rows)) {
            return true;
        } else {
            self::$res['status'] = 1001;
            self::$res['error'] = self::$_ERR[1001];
            return false;
        }
    
    }
    
    /**
     * 检查code是否已被领取过
     * 
     * @param string $code
     * @return boolen
     */
    private static function checkHistory($code)
    {
        $rows = Com_Model_Coupon::instance()->getCouponHistoryByWhere(array(
                'code' => $code));
        if (empty($rows)) {
            return true;
        } else {
            self::$res['status'] = 1003;
            self::$res['error'] = self::$_ERR[1003];
            
            return false;
        }
    }
    
    /**
     * 获取coupon
     * 
     * @param integer $id
     * @return array|boolen
     */
    private static function getCouponInfo($id)
    {
        $row = Com_Model_Coupon::instance()->getCouponInfo($id);
        if (! empty($row) && $row['status'] == 0) {
            return $row;
        }
        
        self::$res['status'] = 1004;
        self::$res['error'] = self::$_ERR[1004];
        return false;
    }
    
    /**
     * 获取categoryautoid
     * 
     * @param ineteger $categoryid
     * @return boolen|integer
     */
    private static function getCategoryAutoId($categoryid = 0)
    {
        $autoid = Com_Model_Coupon::instance()->getCategoryAutoId($categoryid);
        
        if (! $autoid) {
            return false;
        }
        
        return $autoid;
    }
    
    /**
     * 获取categorylink
     * 
     * @param integer $categoryautoid
     * @return boolen|array
     */
    private static function getCategoryLink($categoryid = 0)
    {
        $autoid = self::getCategoryAutoId($categoryid);
        if ($autoid) {
            $linkInfo = Com_Model_Coupon::instance()->getLinkByAutoId($autoid, $categoryid);
            if (! empty($linkInfo)) {
                return $linkInfo;
            }
        }
        self::$res['status'] = 1002;
        self::$res['error'] = self::$_ERR[1002];
        return false;
    }
    
    /**
     * 获取当前活动的末接点分类
     * 
     * @param integer $categoryid
     * @return void
     */
    private static function getEndnodeCategory($categoryid)
    {
      
        $subcategories = Com_Model_Coupon::instance()->getCategoriesByWhere(" parentid = '$categoryid' AND amount > currentid");
        if (! empty($subcategories)) {
            $list = array();
            $weight = array();
            
            foreach ($subcategories as $item) {
                $list[$item['categoryid']] = $item;
                $weight[$item['categoryid']] = $item['weight'];
            }
           
            $roll = self::roll($weight);
            $subcategory = $list[$roll];
            
            if (! empty($subcategory)) {
                if ($subcategory['endnode'] == 1) {
                    self::$endnodecategory = $subcategory;
                } else {
                    self::getEndnodeCategory($subcategory['categoryid']);
                }
            }
        
        }
    
    }
    
    /**
     * 根据权重 roll
     *  
     * @param array $weight
     * @return integer
     */
    private static function roll($weight = array())
    {
        $roll = rand(1, array_sum($weight));
        $temp = 0;
        $rollnum = 0;
        foreach ($weight as $k => $v) {
            $min = $temp;
            $temp += $v;
            $max = $temp;
            if ($roll > $min && $roll <= $max) {
                $rollnum = $k;
                break;
            }
        }
        
        return $rollnum;
    
    }

}



// End ^ LF ^ UTF-8