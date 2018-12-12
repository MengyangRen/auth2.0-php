<?php
namespace System\Model;
use Think\Model;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  系统版本管理模型
 * @author  v.r
 * @package sys 
 * 
 * 注: 辅助方法为私有方法 命名以下滑线开头
 *  
 * 
 */

class AuthClientsModel extends Model{
    

    /**
     * [主键]
     * @var string
     */
    Protected $pk = 'auth_clients';

    /**
     * [定义表别名]
     * @var string
     */
    protected $tableName='auth_clients';
    
    /**
     * [自动校验]
     * @var array
     */
    protected $_validate = array();

    /**
     * [数据库配置]
     * @var string
     */
    protected $connection = 'DB_ZhiCloud_Auth';
     

    /**
     *  
     * Get visitor permission information
     * 
     * @param  string  client_id   Openkey
     * @return mixed $data default array, 
     * else Exception
     *  
     */
    public function getPermissionInfo($client_id = null) {
        $condition['client_id'] = $client_id;
        $result = $this->where($condition)->find();
        
        if (!$result)  
            throw new \Exception("The client_id  does not exist.", 100008);
        
        if (FALSE != strpos($result['scope'],',')) 
           $result['scope']= explode(',', $result['scope']);
        
        return $result['scope'];
    }

    /**
     *  
     * Get visitor credentials
     * 
     * @param  string  client_id   Openkey
     * @return mixed $data default array, 
     * else Exception
     *  
     */
    public function getCredentials($client_id = null) {
        $condition['client_id'] = $client_id;
        $result = $this->where($condition)->find();
        
        if (!$result)  
            throw new \Exception("The client_id  does not exist.", 100008);

        return array(
            'client_id'=> $result['client_id'],
            'client_secret'=>$result['client_secret']
        );
    }
}