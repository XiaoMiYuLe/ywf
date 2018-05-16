<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/jquery.mobile-1.4.5.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="js/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
<title>首页</title>
</head>

<body>
<div data-role="page" id="page">
    <div data-role="header" data-theme="b">
      <h1>荣程商城</h1>
    </div>
    
     <div data-role="content">
        <ul data-role="listview">
                <li><a href="#page1">余额查询【http://localhost:8080/testdemo/test/pay】</a></li>
                <li><a href="#page2">代付提交 【http://localhost:8080/testdemo/test/pay】</a></li>
                <li><a href="#page3">代付提交(模板上传)【http://localhost:8080/testdemo/test/payByFile】</a></li>
                <li><a href="#page4">批次查询 【http://localhost:8080/testdemo/test/batchquery】</a></li>
                <li><a href="#page5">单笔查询【http://localhost:8080/testdemo/test/singlequery】</a></li>
        </ul>
      </div>
      
      <div data-role="footer" data-theme="b">
                <h4>Copyright &copy; 2015 融宝支付</h4>
      </div>
</div>

 
  <div data-role="page" id="page1">
	<div data-role="header" data-theme="b">
	  <a href="#page" data-role="button" data-icon="home">首页</a>
      	<h1>余额查询</h1>
      <a href="#" data-role="button" data-icon="search">搜索</a>
	</div>
	   <form action="batchquery_to.php" method="post" data-ajax="false">
		<div data-role="content">	
			    
		        <input type="submit" class="button_p2p" value="查询"/>
		</div>
	  </form>
	<div data-role="footer" data-theme="b">
		<h4>Copyright &copy; 2015 融宝支付</h4>
	</div>
   </div> 
    
    
    <div data-role="page" id="page2">
        <div data-role="header" data-theme="b">
          <a href="#page" data-role="button" data-icon="home">首页</a>
            <h1>代付提交</h1>
          <a href="#" data-role="button" data-icon="search">搜索</a>
        </div>
         <form action="paysingle_to.php" method="post" data-ajax="false">
	        <div data-role="content">	
	                <p class="t">
	                    <label for="batch_no">批次号:</label>
	                    <input type="text" class="input" name="batch_no" id="batch_no"  />
	                </p>
	                
	                <p class="t">
	                    <label for="batch_count">批次总数量:</label>
	                    <input type="number" class="input" name="batch_count" id="batch_count" />
	                </p>
	                
	                <p class="t">
	                    <label for="cardNo">批次总金额:</label>
	                    <input type="text" class="input" name="batch_amount" id="batch_amount" />
	                </p>
	                
	                 <p class="t">
	                    <label for="cardNo">紧急程度:</label>
	                    <input type="text" class="input" name="pay_type" id="pay_type" />
	                 </p>
                     
                     <p class="t">
	                    <label for="cardNo">批次明细:</label>
                         【格式：】序号,银行账户,开户名,开户行,分行,支行,公/私,金额,币种,省,市,手机号,证件类型,证件号,用户协议号,商户订单号,备注|……|……|<br />
                         【示例：】1,62220215080205389633,jack-cooper,工商银行,分行,支行,私,0.01,CNY,北京,北京,18910116131,身份证,420321199202150718,0001,12306,hehe
	                    <textarea  class="input" name="content" id="content"></textarea>
	                 </p>
	                <input type="submit" class="button_p2p" value="提交"/>  
	        </div>
	      </form>
        <div data-role="footer" data-theme="b">
            <h4>Copyright &copy; 2015 融宝支付</h4>
        </div>
    </div>
    
    <div data-role="page" id="page3">
        <div data-role="header" data-theme="b">
          <a href="#page" data-role="button" data-icon="home">首页</a>
            <h1>代付提交(模板上传)</h1>
          <a href="#" data-role="button" data-icon="search">搜索</a>
        </div>
          <form action="paybyfile_to.php" method="post" data-ajax="false" enctype="multipart/form-data">
	        <div data-role="content">	
	                <p class="t">
	                    <label for="batch_no">批次号:</label>
	                    <input type="text" class="merchant_id" name="batch_no" id="batch_no"  />
	                </p>
	                
	                <p class="t">
	                    <label for="batch_count">批次总数量:</label>
	                    <input type="number" class="input" name="batch_count" id="batch_count" />
	                </p>
                    
                    <p class="t">
	                    <label for="batch_amount">批次总金额:</label>
	                    <input type="text" class="input" name="batch_amount" id="batch_amount" />
	                </p>
                    
                    <p class="t">
	                    <label for="pay_type">紧急程度:</label>
	                    <input type="text" class="input" name="pay_type" id="pay_type" />
	                </p>
                    
                    <p class="t">
	                    <label for="uploadFile">批次明细:</label>
	                    <input type="file" class="input" name="file_stu" id="uploadFile" />
	                </p>
	                <input type="submit" class="button_p2p" value="提交"/>  
	        </div>
	      </form>  
        <div data-role="footer" data-theme="b">
            <h4>Copyright &copy; 2015 融宝支付</h4>
        </div>
    </div>
    
    <div data-role="page" id="page4">
        <div data-role="header" data-theme="b">
          <a href="#page" data-role="button" data-icon="home">首页</a>
            <h1>批次查询</h1>
          <a href="#" data-role="button" data-icon="search">搜索</a>
        </div>
          <form action="batchquery_to.php" method="post" data-ajax="false">
	        <div data-role="content">	
	                <p class="t">
	                    <label for="trans_time">提交日期:（格式yyyy-MM-dd）</label>
	                    <input type="text" class="trans_time" name="trans_time" id="trans_time"  />
	                </p>
	                
	                <p class="t">
	                    <label for="batch_no">批次号:</label>
	                    <input type="text" class="batch_no" name="batch_no" id="batch_no" />
	                </p>
	                
	                 <p class="t">
	                    <label for="detail_no">下一页标识:</label>
	                    <input type="text" class="next_tag" name="next_tag" id="next_tag" />
	                </p>
	                <input type="submit" class="button_p2p" value="查询"/>  
	        </div>
	      </form>  
        <div data-role="footer" data-theme="b">
            <h4>Copyright &copy; 2015 融宝支付</h4>
        </div>
    </div>
    
    <div data-role="page" id="page5">
        <div data-role="header" data-theme="b">
          <a href="#page" data-role="button" data-icon="home">首页</a>
            <h1>单笔查询</h1>
          <a href="#" data-role="button" data-icon="search">搜索</a>
        </div>
          <form action="singlequery_to.php" method="post" data-ajax="false">
	        <div data-role="content">	
	                <p class="t">
	                    <label for="trans_time">提交日期:（格式yyyy-MM-dd）</label>
	                    <input type="text" class="trans_time" name="trans_time" id="trans_time"  />
	                </p>
	                
	                <p class="t">
	                    <label for="batch_no">批次号:</label>
	                    <input type="text" class="batch_no" name="batch_no" id="batch_no" />
	                </p>
	                
	                 <p class="t">
	                    <label for="detail_no">序号:</label>
	                    <input type="text" class="detail_no" name="detail_no" id="detail_no" />
	                </p>
	                <input type="submit" class="button_p2p" value="查询"/>  
	        </div>
	      </form>  
        <div data-role="footer" data-theme="b">
            <h4>Copyright &copy; 2015 融宝支付</h4>
        </div>
    </div>
    
 
    
</div>
</body>
</html>

	
  

