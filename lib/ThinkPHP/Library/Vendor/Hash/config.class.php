<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  服务站配置项
 * @author v.r
 * @package         推送服务群配置
 * @subpackage      config.class.php
 */
class SERVER_CONFIG 
{

    /**
	 * TODO：推送服务配置项
	 * index:编号,按照顺序编写
	 * internetIP: 外网地址
	 * intranetIP：内网地址
	 * post:端口
	 * channel:服务频道列
	 * @var array
	 */
	public static $map = array(
		array(  
			'index'=> 0, 
			'internetIP' =>'172.18.10.168',  
			'intranetIP' =>'124.55.56.23',
			'port' =>'3030',    
			'channel'=>array('zhicloud-message')
		),  
		array(  
			'index'=> 1,
			'internetIP' =>'172.18.10.168',  
			'intranetIP' =>'127.0.0.1',
			'port' =>'3030',    
			'channel'=>array('zhicloud-message')
		), 
		array(  
			'index'=> 2,
			'internetIP' =>'172.18.10.168',    
			'intranetIP' =>'127.0.0.1',
			'port' =>'3030',    
			'channel'=>array('zhicloud-message')
		),
		array(  
			'index'=> 3,
			'internetIP' =>'172.18.11.128',   
			'intranetIP' =>'127.0.0.1',
			'port' =>'3030',    
			'channel'=>array('zhicloud-message')
		)     
	); 
}