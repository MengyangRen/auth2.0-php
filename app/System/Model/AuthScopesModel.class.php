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

class AuthScopesModel extends Model{
    

    /**
     * [主键]
     * @var string
     */
    Protected $pk = '';

    /**
     * [定义表别名]
     * @var string
     */
    protected $tableName='auth_scopes';
    
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
     * Get open permission collection
     * 
     * @param  string  _as       - 权限别称
     * @return mixed $data default array, else Exception
     *  
     */
    
    public function getOpenPermissionCollection($_as = NULL ) {
        $tmp =  array();
        $scopes = array();
        if (!is_array($_as)) 
            $tmp[] = $_as;

        foreach ($tmp as $val ) {
            $condition['as'] = $val;
            $res = $this->where($condition)->select();
            foreach ($res as $value) 
                $scopes[] = $value['scope'];
        }

    
        if ( 1 == count($scopes)) {
            return $scopes[0];
        }  else {
            return join(',',$scopes);
        }

    }
    
}