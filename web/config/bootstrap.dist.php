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



$config['controllers']['promotionadmin'] = 'Promotion/admin';

$config['controllers']['groupon'] = 'Groupon/controllers';
$config['controllers']['grouponadmin'] = 'Groupon/admin';
$config['controllers']['commentadmin'] = 'Comment/admin';/*商品模块*/
$config['controllers']['goodsadmin'] = 'Goods/admin';
$config['controllers']['trendadmin'] = 'Trend/admin';

/* 商户管理 */

$config['controllers']['store'] = 'Store/controllers';
$config['controllers']['storeadmin'] = 'Store/admin';


$config['controllers']['couponadmin'] = 'Coupon/admin';

$config['controllers']['btsadmin'] = 'Bts/admin';

return $config;

// End ^ LF ^ UTF-8
