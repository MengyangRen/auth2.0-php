<?php
namespace Common\Filter;

use Think\ApiException;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  过滤层检查层基类
 * 
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */


class Filter  
{
    
    /**
     * [$input 数据过滤对象]
     * @var array
     */
    protected $input = array(); 
    
    /**
     * [$rule 基于规则自动检查]
     * @var array
     */
    protected $rule = array(
    );  


    /**
	 * [初始化方法]
	 * @param [type] $token [description]
	 */
	protected function __construct($config = NULL)
	{   
		vendor('Kfk/Input',NULL,'.class.php');
		$this->input = new \Input;
		# code...
	}

	/**
	 * [获取单例]
	 * @return [type] [description]
	 */
	
	public static function getInstance($config = NULL) {
        if (static::$instance === null) {
			static::$instance = new static($config); 
		}
		return static::$instance;
    }
}
