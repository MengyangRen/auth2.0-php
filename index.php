<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

//系统中常量
define('APP_DEBUG',true);
define('APP_PATH','./app/');
define('SITE_PATH', dirname(__FILE__)."/");

define('DATA_PATH','/data/auth/');
define("RUNTIME_PATH","/data/auth/runtime/");
define('BIND_MODULE', 'System');


//定义系统运行中常量


define('AVATAR_URI','http://avatar.zhicloud.dev.com');
define('AUTH_CODE_LIFETIME',30);  //AUTH_CODE_LIFETIME 过期不

require './lib/ThinkPHP/ThinkPHP.php';