<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>信用卡签约接口</title>
  </head>
  
  <body>
    <div align="center">
    <form action="creditResult.php" method="post">
    <table align="center">
    	<tr><td colspan="2" align="center"><h2>信用卡签约接口</h2></td></tr>
    	<tr><td>商户号：</td><td><input type="text" name="merchant_id" value=""/></td></tr>
    	<tr><td>银行卡号：</td><td><input type="text" name="card_no" value=""/></td></tr>
		<tr><td>姓名：</td><td><input type="text" name="owner" value=""/></td></tr>
		<tr><td>会员号：</td><td><input type="text" name="member_id" value=""/></td></tr>
		<tr><td>证件号码：</td><td><input type="text" name="cert_no" value=""/></td></tr>
		<tr><td>安全码：</td><td><input type="text" name="cvv2" value=""/>提示：卡背面三位数字</td></tr>
		<tr><td>有效期：</td><td><input type="text" name="validthru" value=""/>提示：格式为月年（MMyy）</td></tr>
		<tr><td>手机号：</td><td><input type="text" name="phone" value=""/></td></tr>
		<tr><td>交易金额：</td><td><input type="text" name="total_fee" value=""/>单位：分</td></tr>
    	<tr><td></td><td><input type="submit" value="提交"/></td></tr>		
    </table>
    </form>
    </div>
  </body>
</html>