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

class IndexController extends PromotionAdminAbstract
{
    public $perpage = 15;

    /**
     * 优惠促销后台首页
     */
    public function index()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        /* 接收参数 */
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));
        $category_id = (int) $this->input->get('category_id', 0);

        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {
        	$offset = $page * $perpage;
        	$page = $page + 1;

        	$where['is_del'] = 0;

            if ($category_id) {
        	    $where['category_id'] = $category_id;
        	}
        	if (! empty($key)) {
        		$where[] = "title LIKE '%{$key}%'";
        	}

        	$order = 'ctime DESC';
        	if ($ordername) {
        		$order = $ordername . " " . $orderby;
        	}

        	$contents = Promotion_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);
        	$data['count'] = Promotion_Model_Content::instance()->getCount($where);

        	if ($contents) {
        	    //获取活动分类
        	    foreach ($contents as &$content) {
        	        $promotion_category = Promotion_Model_Category::instance()->fetchByPK($content['category_id']);
        	        $content['category_name'] = $promotion_category[0]['title'];
        	    }
        	}

        	$data['contents'] = $contents ? $contents : array();
        }

        /* 获取所有活动分类 */
        $data['categories'] = Promotion_Model_Category::instance()->fetchAll()->toArray();

        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        $data['category_id'] = $category_id;

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.index');
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
        /* 获取所有活动分类 */
        $data['categories'] = Promotion_Model_Category::instance()->fetchAll()->toArray();

        /* 获取所有商品分类信息 */
        $data['goods_categories'] = Goods_Model_Category::instance()->getAllCategoriesForSelect();

        /* 获取已经启用的品牌信息 */
        $data['brands'] = Goods_Model_Brand::instance()->fetchByFV('status', '1');

        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
        return parent::multipleResult(self::RS_SUCCESS);
    }

    /**
     * 保存规则
     */
    private function addSave()
    {
        try {
            //活动类型
            $promotion_category = $this->input->query("category_id");

            //判断活动类型
            if (empty($promotion_category)) {
                throw new Zeed_Exception("活动分类不能为空");
            }

            $title = $this->input->query("title");
            $start_time = $this->input->query("start_time");
            $end_time = $this->input->query("end_time");
            $status = $this->input->query("status");
            $x = $this->input->query("x");
            $y = $this->input->query("y");

            //参与活动的商品
            $relate_goods = $this->input->query("related_goods");

            if ( ! empty($x) && ! empty($y)) {
                $rules = serialize(array(
                    "x" => $x,
                    "y" => $y
                ));
            }else {
                $rules = "";
            }

            //增加数据
            $set = array(
                "title"  => $title,
                "rules"  => $rules,
                "status" => $status,
                "start_time" => $start_time,
                "end_time" => $end_time,
                "category_id" => $promotion_category,
                'ctime' => DATENOW,
            );
            $promotion_id =  Promotion_Model_Content::instance()->addForEntity($set);
            if (! $promotion_id) {
                throw  new Zeed_Exception("添加活动失败");
            }

            if (! empty($relate_goods) && strpos($relate_goods,",")) {
                $relate_goods_array = explode(",", $relate_goods);
                foreach ($relate_goods_array as  $relate_good) {
                    
                    $goods = Goods_Model_Content::instance()->fetchByPK($relate_good,array('category','brand_id'));
                    $set = array(
                        'promotion_id' => $promotion_id,
                        'content_id'   => $relate_good,
                        'goods_category_id'   => $goods ? $goods[0]['category'] : 0,
                        'goods_brand_id'   => $goods ? $goods[0]['brand_id'] : 0
                    );
                    Promotion_Model_Goods::instance()->addForEntity($set);
                }
            }

        return self::RS_SUCCESS;

        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError("程序运行出错".$e->getMessage());
            return self::RS_SUCCESS;
        }
    }

    /**
     * 编辑规则
     */
    public function edit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isPOST()) {
            $this->editSave();
            return self::RS_SUCCESS;
        }
        try {
            $promotion_id = (int) $this->input->get('promotion_id');

            /* 查询商品主体信息 */
            if ( ! $promotion_id ) {
                throw  new Zeed_Exception("非法请求");
            }

            /*获取活动分类下详细信息*/
            $content = Promotion_Model_Content::instance()->fetchByPK($promotion_id);
            if (! $content) {
                throw  new Zeed_Exception("非法请求");
            }
            //获取活动下商品
            $relate_goods = Promotion_Model_Goods::instance()->fetchByFV("promotion_id", $promotion_id);
            foreach ($relate_goods as &$relate_good) {
                $goods = Goods_Model_Content::instance()->fetchByPK($relate_good['content_id']);
                $relate_good['content_name'] = $goods[0]["name"];
            }

            //获取活动下模板
            $category_id  = $content[0]['category_id'];
            $promotion_category = Promotion_Model_Category::instance()->fetchByPK($category_id);
            $template = Promotion_Model_Template::instance()->fetchByPK($promotion_category[0]['template_id']);
            $filepath = Support_Image_Url::getImageUrl($template[0]['filepath']);
            $template = $filepath ? file_get_contents($filepath) : '';

            $data['template'] = $template;
            $data['relate_goods'] = $relate_goods;
            $data['content']  = $content[0];
            $data['rules'] =  unserialize($content[0]["rules"]);

            /* 获取所有活动分类 */
            $data['categories'] = Promotion_Model_Category::instance()->fetchAll()->toArray();

            /* 获取所有商品分类信息 */
            $data['goods_categories'] = Goods_Model_Category::instance()->getAllCategoriesForSelect();

            /* 获取已经启用的品牌信息 */
            $data['brands'] = Goods_Model_Brand::instance()->fetchByFV('status', '1');


            $this->setData('data', $data);
            $this->addResult(self::RS_SUCCESS, 'php', 'index.edit');
            return parent::multipleResult(self::RS_SUCCESS);
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError($e->getMessage());
            return self::RS_SUCCESS;
        }

    }

    /**
     * 检查商品是否参加活动
     */
    public function checkActive()
    {
        $this->addResult(self::RS_SUCCESS,'json');

        try {
            if (! $this->input->isAJAX()) {
                throw new Zeed_Exception("非法请求");
            }

            return self::RS_SUCCESS;
        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError("程序异常".$e->getMessage());
            return self::RS_SUCCESS;
        }
    }
    /**
     * 编辑规则- 保存
     */
    private function editSave()
    {
        try {
            //活动id
            $promotion_id = $this->input->query("promotion_id");
            if (empty($promotion_id)) {
                throw new Zeed_Exception("非法请求");
            }
            $title = $this->input->query("title");
            $start_time = $this->input->query("start_time");
            $end_time = $this->input->query("end_time");
            $relate_goods = $this->input->query("related_goods");
            $status = $this->input->query("status");
            $x = $this->input->query("x");
            $y = $this->input->query("y");

            if (! empty($relate_goods) ) {
                //删除旧商品关联数据
                Promotion_Model_Goods::instance()->deleteByFV( "promotion_id" ,$promotion_id);
                $relate_goods_array = explode(",", $relate_goods);
                foreach ($relate_goods_array as $relate_good) {
                    
                    $goods = Goods_Model_Content::instance()->fetchByPK($relate_good,array('category','brand_id'));
                    $set = array(
                            'promotion_id' => $promotion_id,
                            'content_id'   => $relate_good,
                            'goods_category_id'   => $goods ? $goods[0]['category'] : 0,
                            'goods_brand_id'   => $goods ? $goods[0]['brand_id'] : 0
                    );
                    Promotion_Model_Goods::instance()->addForEntity($set);
                }
            }
           if ( ! empty($x) && ! empty($y)) {
                $rules = serialize(array(
                    "x" => $x,
                    "y" => $y
                ));
            }else {
                $rules = "";
            }

            $save_data = array(
                'title'=>$title,
                'start_time'=> $start_time,
                'end_time'=> $end_time,
                'status' => $status,
                'rules'  => $rules
            );

           Promotion_Model_Content::instance()->updateForEntity($save_data, $promotion_id);

        } catch (Zeed_Exception $e) {
            $this->setStatus(1);
            $this->setError($e->getMessage());
            return self::RS_SUCCESS  ;
        }
    }

    /**
     *
     * @提交审核
     */
    public function publish()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        if ($this->input->isAJAX()) {
            /* 获取参数，并做基础处理 */
            $promotion_id = $this->input->query("promotion_id");

            $set = array(
                "is_verify"=> "1"
            );
            $where['promotion_id'] = $promotion_id;
            Promotion_Model_content::instance()->update($set, $where);
            return self::RS_SUCCESS;
        }
    }


    /**
     * 根据分类获取绑定的活动模板
     */
    public function getTemplateById()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        $template_id = (int) $this->input->query('template_id');

        if (! $template = Promotion_Model_Template::instance()->fetchByPK($template_id)) {
            $this->setStatus(1);
            $this->setError('查无此模板');
            return self::RS_SUCCESS;
        }

        $filepath = Support_Image_Url::getImageUrl($template[0]['filepath']);
        $rules = $filepath ? file_get_contents($filepath) : '';

        $data['rules'] = $rules;
        $this->setData('data', $data);
        return self::RS_SUCCESS;
    }

    /**
     * 获取商品列表(添加)
     */
    public function getContentsAdd()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        /* 接收参数 */
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));

        $category_id = (int) $this->input->get('category_id',0);
        $brand_id = (int) $this->input->get("brand_id",0);

        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {

            $offset  = $page * $perpage;
            $page = $page + 1;
            $where['is_del'] = 0;
            // 已上架
            $where['is_shelf'] = 1;
            
            $order = "content_id ASC";

            //关键字搜索
            if (! empty($key)) {
                $where[] = " (name LIKE '%{$key}%' or sku LIKE '%{$key}%') ";
            }

            // 分类id不为空
            if (! empty($category_id)) {
                $where['category'] = $category_id;
            }
            // 品牌不为空
            if (! empty($brand_id)) {
                $where['brand_id'] = $brand_id;
            }

            /*过滤已参加活动商品*/
             $has_active  = array();
             $promotion_goods = Promotion_Model_Goods::instance()->fetchAll()->toArray();
             if (! empty($promotion_goods)) {
             foreach ($promotion_goods as $promotion_good) {
                $has_active[] = $promotion_good["content_id"];
                }
             }
             //判断是否有参加活动商品并过滤
             if (count($has_active) != 0) {
                 $string_has_active = implode(",",$has_active);
                 $where[] = " content_id not in (".$string_has_active.")";
             }

             $contents = Goods_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);
             $data['count'] = Goods_Model_Content::instance()->getCount($where);

            /*处理商品信息*/
            if (! empty($contents)) {
                foreach ($contents as &$v) {
                    // 处理分类名称
                    $category_name = array();
                    if (! empty($v['category'])) {
                        $category_id_arr = explode(",", $v['category']);
                        foreach ($category_id_arr as $category) {
                            $category_one = Goods_Model_Category::instance()->fetchByPK($category, array('category_name'));
                            if (! empty($category_one)) {
                                $category_name[] = $category_one[0]['category_name'];
                            }
                        }
                    }
                    $v['category_name'] = implode(",",$category_name);

                    // 处理品牌名称
                    if (! empty($v['brand_id'])) {
                        $brand_one = Goods_Model_Brand::instance()->fetchByPK($v['brand_id'], array('brand_name'));
                        if (! empty($brand_one)) {
                             $v['brand_name'] = $brand_one[0]['brand_name'];
                        }
                    }
                    
                    // 处理规格属性
                    $v['spec'] = '';
                    if ( $spec_info = Goods_Model_Content::instance()->fetchGoodsPropertyByContentId($v['content_id'])) {
                        $v['spec'] = array();
                        foreach ($spec_info as $spec_info) {
                            $v['spec'][] = key($spec_info) . ':' . $spec_info[key($spec_info)];
                        }
                        $v['spec'] = implode(",",$v['spec']);
                    }
                     
                }
            }
        }

        /* 获取所有分类信息 */
        $data['categories'] = Goods_Model_Category::instance()->getAllCategoriesForSelect();
        $data['contents'] = $contents ? $contents : array();

        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;

        $this->setData('data',$data);
        return self::RS_SUCCESS;
    }


    /**
     * 获取商品列表(编辑)
     */
    public function getContentsEdit()
    {
        $this->addResult(self::RS_SUCCESS, 'json');

        /* 接收参数 */
        $orderby = $this->input->get('orderby', null);
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        $key = trim($this->input->get('key'));

        $category_id = (int) $this->input->get('category_id',0);
        $brand_id = (int) $this->input->get("brand_id",0);

        /* ajax 加载数据 */
        if ($this->input->isAJAX()) {

            $offset  = $page * $perpage;
            $page = $page + 1;
            $where['is_del'] = 0;
            
            // 已上架
            $where['is_shelf'] = 1;
            
            $order = "content_id ASC";

            //关键字搜索
            if (! empty($key)) {
                $where[] = " (name LIKE '%{$key}%' or sku LIKE '%{$key}%') ";
            }

            // 分类id不为空
            if (! empty($category_id)) {
                $where['category'] = $category_id;
            }
            // 品牌不为空
            if (! empty($brand_id)) {
                $where['brand_id'] = $brand_id;
            }

            $contents = Goods_Model_Content::instance()->fetchByWhere($where, $order, $perpage, $offset);
            $data['count'] = Goods_Model_Content::instance()->getCount($where);

            /*处理商品信息*/
            if (! empty($contents)) {
                foreach ($contents as &$v) {
                    // 处理分类名称
                    $category_name = array();
                    if (! empty($v['category'])) {
                        $category_id_arr = explode(",", $v['category']);
                        foreach ($category_id_arr as $category) {
                            $category_one = Goods_Model_Category::instance()->fetchByPK($category, array('category_name'));
                            if (! empty($category_one)) {
                                $category_name[] = $category_one[0]['category_name'];
                            }
                        }
                    }
                    $v['category_name'] = implode(",",$category_name);

                    // 处理品牌名称
                    if (! empty($v['brand_id'])) {
                        $brand_one = Goods_Model_Brand::instance()->fetchByPK($v['brand_id'], array('brand_name'));
                        if (! empty($brand_one)) {
                             $v['brand_name'] = $brand_one[0]['brand_name'];
                        }
                    }
                    
                    // 处理规格属性
                    $v['spec'] = '';
                    if ( $spec_info = Goods_Model_Content::instance()->fetchGoodsPropertyByContentId($v['content_id'])) {
                        $v['spec'] = array();
                        foreach ($spec_info as $spec_info) {
                            $v['spec'][] = key($spec_info) . ':' . $spec_info[key($spec_info)];
                        }
                        $v['spec'] = implode(",",$v['spec']);
                    }
                }
            }
        }

        /* 获取所有分类信息 */
        $data['categories'] = Goods_Model_Category::instance()->getAllCategoriesForSelect();
        $data['contents'] = $contents ? $contents : array();

        $data['orderby'] = $orderby;
        $data['page'] = $page;
        $data['perpage'] = $perpage;

        $this->setData('data',$data);
        return self::RS_SUCCESS;
    }
   /**
    *活动删除
    */
    public function  trash()
    {
        $this->addResult(self::RS_SUCCESS, 'json');


        $promotion_id = (int)$this->input->query("promotion_id");
        if ($this->input->isAJAX()) {
            if (empty($promotion_id)) {
                $this->setStatus(1);
                $this->setError("非法请求");
            }
            Promotion_Model_Content::instance()->deleteByPK($promotion_id);
            //删除活动相关联的商品
            Promotion_Model_Goods::instance()->deleteByFV("promotion_id", $promotion_id);
            return self::RS_SUCCESS;
        }
    }

    /**
     * 活动详情
     */
    public function detail ()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        
        // 获取活动类型的id
        $promotion_id = (int) $this->input->query("promotion_id");
        $page = (int) $this->input->get('pageIndex', 0);
        $perpage = $this->input->get('pageSize', $this->perpage);
        // 获取活动信息
        $promotion = Promotion_Model_Content::instance()->fetchByPK($promotion_id);
        
        if ($promotion[0]['category_id']) {
            $promotion_category = Promotion_Model_Category::instance()->fetchByPK($promotion[0]['category_id'],'title');
        }
        
        $promotion[0]['category_name'] = $promotion_category[0]['title'];
        
        if ($this->input->isAJAX()) {
            $offset = $page * $perpage;
            $page = $page + 1;
            $where['promotion_id'] = $promotion_id;
            // 根据活动类型获取相关的商品
            $promotion_goods = Promotion_Model_Goods::instance()->fetchByWhere($where, null, $perpage, $offset);
            $data['count'] = Promotion_Model_Goods::instance()->getCount($where);
            
            if (! empty($promotion_goods)) {
                foreach ($promotion_goods as $goods) {
                    $promotion_goods_id[] = $goods['content_id'];
                }
                
                $contents = Goods_Model_Content::instance()->fetchByPK($promotion_goods_id);
                
                foreach ($contents as &$v) {
                    // 处理分类名称
                    if (! empty($v['category'])) {
                            $category_one = Goods_Model_Category::instance()->fetchByPK($v['category'], array(
                                    'category_name'
                            ));
                            if (! empty($category_one)) {
                               $v['category_name'] = $category_one[0]['category_name'];
                            }
                    }
                    
                    // 处理品牌名称
                    if (! empty($v['brand_id'])) {
                        $brand_one = Goods_Model_Brand::instance()->fetchByPK($v['brand_id'], array(
                                'brand_name'
                        ));
                        if (! empty($brand_one)) {
                            $v['brand_name'] = $brand_one[0]['brand_name'];
                        }
                    }
                    
                    // 处理规格属性
                    $v['spec'] = '';
                    if ( $spec_info = Goods_Model_Content::instance()->fetchGoodsPropertyByContentId($v['content_id'])) {
                        $v['spec'] = array();
                        foreach ($spec_info as $spec_info) {
                            $v['spec'][] = key($spec_info) . ':' . $spec_info[key($spec_info)];
                        }
                        $v['spec'] = implode(",",$v['spec']);
                    }
                }
            } else {
                $contents = array();
            }
        }
        
        $data['contents'] = $contents;
        $data["promotion"] = $promotion[0];
        
        $data['page'] = $page;
        $data['perpage'] = $perpage;
        
        $this->setData('data', $data);
        $this->addResult(self::RS_SUCCESS, 'php', 'index.detail');
        return parent::multipleResult(self::RS_SUCCESS);
    }
}
// End ^ Native EOL ^ UTF-8