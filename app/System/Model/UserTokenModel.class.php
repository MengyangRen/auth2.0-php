
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

class UserTokenModel extends Model{
    


    /**
     * [主键]
     * @var string
     */
    Protected $pk = 'id';

    /**
     * [定义表别名]
     * @var string
     */
    protected $tableName='usertoken';
    
    /**
     * [自动校验]
     * @var array
     */
    protected $_validate = array();

    /**
     * [数据库配置]
     * @var string
     */
    protected $connection = 'DB_ZhiCloud_Common';
     
    
    /**
     * 通过uid获取token 
     * @param  int $uid  用户id
     * @return array
     */
    public function getTokenByUid($uid = NULL) {
        $condition['uid'] = $uid;
        $result = $this->where($condition)->find();
        if (empty($result)) 
           return false;
        return $result;
    }

    /**
     * 通过Token获取uid 
     * @param  int $uid  用户id
     * @return array
     */
    public function  getUidByToken($token = NULL ) {
        $condition['token'] = $token;
        $result = $this->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("The access token has expired and the expiration time is generally 3 months", 100014);
        return $result['uid'];
    }
    
}