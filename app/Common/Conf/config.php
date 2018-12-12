<?php

return array(
    'URL_CASE_INSENSITIVE'  =>  true,  
	'SHOW_PAGE_TRACE'=>true,
  /*  'DEFAULT_GROUP' => 'Home',*/
    'LOG_RECORD' => true, // 开启日志记录
    'DEFAULT_CHARSET' => 'utf8',
    'URL_MODEL' 			    =>	0,				
    'URL_ROUTER_ON'   		=> true, 			
    'URL_HTML_SUFFIX'        =>  'html',
    'URL_ROUTE_RULES' 		=> array(),
    'ADMINISTRATOR' =>      array('admin'),    
    'URL_CASE_INSENSITIVE' =>true,

    'DB_ZhiCloud_Common'  => array(
        'db_type'    => 'mysqli',
        'db_user'    => 'root',
        'db_pwd'     => 'zhiCloud*%',
        'db_host'    => '127.0.0.1',
        'db_port'    => '3306',
        'db_name'    => 'zhiCloudCommon',
        'db_charset' => 'utf8',
    ),

    'DB_ZhiCloud_Auth'  => array(
        'db_type'    => 'mysqli',
        'db_user'    => 'root',
        'db_pwd'     => 'zhiCloud*%',
        'db_host'    => '127.0.0.1',
        'db_port'    => '3306',
        'db_name'    => 'ZhiCloudAuth',
        'db_charset' => 'utf8',
    ),



    'DB_ZhiCloud_Customs'=> array(
        'db_type'    => 'mysqli',
        'db_user'    => 'root',
        'db_pwd'     => 'zhiCloud*%',
        'db_host'    => '127.0.0.1',
        'db_port'    => '3306',
        'db_name'    => 'zhiCloudCustoms',
        'db_charset' => 'utf8',
    ),

    'DB_ZhiCloud_App'=> array(
        'db_type'    => 'mysqli',
        'db_user'    => 'root',
        'db_pwd'     => 'zhiCloud*%',
        'db_host'    => '127.0.0.1',
        'db_port'    => '3306',
        'db_name'    => 'zhiCloudApp',
        'db_charset' => 'utf8',
    ),
);
