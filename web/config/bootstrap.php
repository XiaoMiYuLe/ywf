<?php

$config['defaultController'] = 'Index/controllers';

$config['controllers']['panel'] = 'Panel/controllers';
$config['controllers']['admin'] = 'Admin/controllers';
$config['controllers']['setting'] = 'Admin/setting';

$config['controllers']['trend'] = 'Trend/controllers';
$config['controllers']['page'] = 'Page/controllers';
$config['controllers']['pageadmin'] = 'Page/admin';

$config['controllers']['upload'] = 'Upload/controllers';

$config['controllers']['api'] = 'Interface/api';

$config['controllers']['advert'] = 'Advert/controllers';
$config['controllers']['advertadmin'] = 'Advert/admin';
/*在线反馈模块*/
$config['controllers']['feedbackadmin'] = 'Feedback/admin';

/* 会员中心 */
$config['controllers']['cas'] = 'Cas/controllers';
$config['controllers']['casadmin'] = 'Cas/admin';

/* 提现模块  */
$config['controllers']['withdraw'] = 'Withdraw/controllers';
$config['controllers']['withdrawadmin'] = 'Withdraw/admin';

/* 充值模块  */
$config['controllers']['recharge'] = 'Recharge/controllers';
$config['controllers']['rechargeadmin'] = 'Recharge/admin';

/* 结息模块  */
$config['controllers']['interest'] = 'Interest/controllers';
$config['controllers']['interestadmin'] = 'Interest/admin';

/* 兑付模块  */
$config['controllers']['cash'] = 'Cash/controllers';
$config['controllers']['cashadmin'] = 'Cash/admin';

/* 代金券模块  */
$config['controllers']['voucheradmin'] = 'Voucher/admin';
$config['controllers']['promotionadmin'] = 'Promotion/admin';

/* 系统模块  */
$config['controllers']['systemadmin'] = 'System/admin';

/* 佣金模块  */
$config['controllers']['brokerageadmin'] = 'Brokerage/admin';

/* 帮助及消息模块   */
$config['controllers']['helpadmin'] = 'Help/admin';
$config['controllers']['newsadmin'] = 'News/admin';
/*文章模块*/
$config['controllers']['article'] = 'Article/controllers';
$config['controllers']['articleadmin'] = 'Article/admin';

$config['controllers']['groupon'] = 'Groupon/controllers';
$config['controllers']['grouponadmin'] = 'Groupon/admin';
$config['controllers']['commentadmin'] = 'Comment/admin';/*商品模块*/
$config['controllers']['goodsadmin'] = 'Goods/admin';
$config['controllers']['goods'] = 'Goods/controllers';
$config['controllers']['trendadmin'] = 'Trend/admin';

/* 商户管理 */

$config['controllers']['store'] = 'Store/controllers';
$config['controllers']['storeadmin'] = 'Store/admin';


$config['controllers']['couponadmin'] = 'Coupon/admin';

$config['controllers']['btsadmin'] = 'Bts/admin';
$config['controllers']['bts'] = 'Bts/controllers';

/*注册*/
$config['controllers']['cas'] = 'Cas/controllers';

$config['controllers']['test'] = 'Test/controllers';

$config['controllers']['interest'] = 'Index/controllers';
$config['controllers']['index'] = 'Index/controllers';


//新版P2P的产品
$config['controllers']['borrow'] = 'P2P/borrow';
return $config;

// End ^ LF ^ UTF-8
