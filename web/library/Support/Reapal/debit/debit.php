<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>储蓄卡签约接口</title>
  </head>
  
  <body>
    <div align="center">
    <form action="debitResult.php" method="post">
    <table align="center">
    	<tr><td colspan="2" align="center"><h2>储蓄卡签约接口</h2></td></tr>
    	<tr><td>商户号：</td><td><input type="text" name="merchant_id" value=""/></td></tr>
    	<tr><td>银行卡号：</td><td><input type="text" name="card_no" value=""/></td></tr>
		<tr><td>姓名：</td><td><input type="text" name="owner" value=""/></td></tr>
		<tr><td>会员号：</td><td><input type="text" name="member_id" value=""/></td></tr>
		<tr><td>证件号码：</td><td><input type="text" name="cert_no" value=""/></td></tr>
		<tr><td>手机号：</td><td><input type="text" name="phone" value=""/></td></tr>
		<tr><td>交易金额：</td><td><input type="text" name="total_fee" value=""/>单位：分</td></tr>
    	<tr><td></td><td><input type="submit" value="提交"/></td></tr>		
    </table>
    </form>
    </div>
  </body>
</html>