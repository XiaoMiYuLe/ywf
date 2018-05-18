<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>查询绑卡列表接口</title>
  </head>
  
  <body>
    <div align="center">
    <form action="bindListQueryResult.php" method="post">
    <table align="center">
    	<tr><td colspan="2" align="center"><h2>查询绑卡列表接口</h2></td></tr>
    	<tr><td>商户号：</td><td><input type="text" name="merchant_id" value=""/></td></tr>
    	<tr><td>商户会员号：</td><td><input type="text" name="member_id" value=""/></td></tr>
		<tr><td>卡类型：</td><td><input type="text" name="bank_card_type" value=""/>储蓄卡：0，信用卡：1</td></tr>
    	<tr><td></td><td><input type="submit" value="提交"/></td></tr>		
    </table>
    </form>
    </div>
  </body>
</html>