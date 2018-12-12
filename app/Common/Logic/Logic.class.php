<?php
namespace Common\Logic;

use Think\ApiException;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  业务逻辑基类
 * 
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */
class Logic
{

    /**
     * 单例对象
     * @var obj 
     */
    public static $instance;
    
    /**
	 * [初始化方法]
	 * @param [type] $token [description]
	 */
	protected function __construct($config = NULL)
	{   
		
	}

	/**
	 * [获取单例]
	 * @return [type] [description]
	 */
	public static function getInstance($config  = NULL) {
        if (static::$instance === null) {
			static::$instance = new static($config); 
		}

		return static::$instance;
    }
}
