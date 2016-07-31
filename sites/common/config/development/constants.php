<?php  
//上线要改
define('TEMP_UPLOAD_DIR', './upload/qr_code/');
//站点url
define('SITE_URL', 'http://www.yctfgw.com/');
define('IMG_URL', 'http://www.yctfgw.com/');

//pay表的类型 1注册 2下级支付上级费用（升级）
define('PAY_TYPE_REG', 1);
define('PAY_TYPE_DOWN_UP', 2);

//邀请注册费用
define('REGISTER_MONEY', 0.01);

define('PLATE_ONE_GRADE_ONE', 0.01);
define('PLATE_ONE_GRADE_TWO', 0.02);
define('PLATE_ONE_GRADE_THREE', 0.03);


define('PLATE_TWO_GRADE_ONE', 0.04);
define('PLATE_TWO_GRADE_TWO', 0.05);
define('PLATE_TWO_GRADE_THREE', 0.06);

define('PLATE_THREE_GRADE_ONE', 20000);
define('PLATE_THREE_GRADE_TWO', 30000);
define('PLATE_THREE_GRADE_THREE', 50000);

// define('PLATE_ONE_GRADE_ONE', 300);
// define('PLATE_ONE_GRADE_TWO', 500);
// define('PLATE_ONE_GRADE_THREE', 2000);


// define('PLATE_TWO_GRADE_ONE', 4000);
// define('PLATE_TWO_GRADE_TWO', 6000);
// define('PLATE_TWO_GRADE_THREE', 20000);

// define('PLATE_THREE_GRADE_ONE', 20000);
// define('PLATE_THREE_GRADE_TWO', 30000);
// define('PLATE_THREE_GRADE_THREE', 50000);

define('WEIXIN_OPENID_KEY', 'wx_openid_key');


//支付订号标示单线下或者测试 测试环境用0正是环境用1
define('ORDER_SN_PREFIX',0);



?>
