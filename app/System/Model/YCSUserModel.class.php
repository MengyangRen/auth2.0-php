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

class YCSUserModel extends Model{
    
    /**
     * @var string
     */
    Protected $pk = 'id';

    /**
     * @var string
     */
    protected $tableName='customs';
    
    /**
     * @var array
     */
    protected $_validate = array();

    /**
     * @var string
     */
    protected $connection = 'DB_ZhiCloud_Customs';


    /**
     * 通过手机号查找用户
     * @param  string phone 手机号
     * @return mixed
     * 
     */
    public function getUserByPhone($phone = NULL) {
        $field  = 'nick_name';
        $condition['phone'] = $phone;
        $result = $this->field($field,false)->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("The cell phone number does not exist", 705);
        return $result;
    }
    public function getUserByPhoneNULL($phone = NULL) {
        $field  = 'nick_name';
        $condition['phone'] = $phone;
        $result = $this->field($field,false)->where($condition)->find();
        return $result;
    }    
    /**
     * 通过用户id查找用户
     * @param  string phone 手机号
     * @return mixed
     *
     */
    public function getUsersById($uid = NULL) {
        $condition['id'] = $uid;
        $result = $this->where($condition)->find();
        if (empty($result))
            throw new \Exception("The cell user does not exist", 705);
        return $result;
    }

    /**
     * 通过公司id查找用户
     * @param  string phone 手机号
     * @return mixed
     * 
     */
    public function getUserByCompanId($companId = NULL) {
        $condition['compan_id'] = $companId;
        $condition['is_admin'] = 1;
        $result = $this->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("The enterprise user does not exist in the platform", 705);
        return $result;

    }

    /**
     * 通过公司id查找对应管理员
     * @param  string phone 手机号
     * @return mixed
     * 
     */
    public function getAdminUserByCompanId($companId = NULL) {
        $condition['compan_id'] = $companId;
        $condition['is_admin'] = 1;
        $order = array('created' => 'desc');
        $result = $this->where($condition)->order($order)->limit(1)->find();
        if (empty($result)) 
            throw new \Exception("The enterprise user does not exist in the platform", 705);
        return $result;

    }
    
     /**
     * 通过昵称查找用户
     * @param  string name 账号
     * @return mixed
     */
    
    public function getUserPwdByName($name = NULL) {
        $field  = 'id,pwd,salt';
        $condition['nick_name'] = $name;
        $result = $this->field($field)->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("user does not exist", 704);
        return $result;
    }
    
    public function getUserPwdByNameNULL($name = NULL) {
        $field  = 'id,pwd,salt';
        $condition['nick_name'] = $name;
        $result = $this->field($field)->where($condition)->find();
        return $result;
    }
     /**
     * 通过Uid查找用户密码
     * @param  int uid 用户id
     * @return mixed
     */
    
    public function getUserPwdByUID($uid = NULL) {
        $field  = 'id,pwd,salt';
        $condition['id'] = $uid;
        $result = $this->field($field)->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("user does not exist", 704);
        return $result;
    }


     /**
     * 通过Uid查找用户密码和注册时间
     * @param  int uid 用户id
     * @return mixed
     */
    public function getUserByUID($uid = NULL) {
        $field  = 'pwd,salt,modified';
        $condition['id'] = $uid;
        $result = $this->field($field,true)->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("user does not exist", 704);
        return $result;
    }

    /**
     * 新增用户
     * @param  int uid 用户id
     * @return mixed
     */
    public function addUser(array $data = NULL ) {
        try {
            return $this->add($data);
        } catch (\Exception $e) {    
            throw new \Exception('User add failed',1045);
        }
    }
    /**
     * 根据主键id修改true_name、hzs_id、
     * @param  int id 用户id
     * @param  int data 接口查到的用户数据
     * @param  int hzsinfo 合作商数据
     * @return mixed
     */
    public function modify($id = NULL ,$data = array(),$hzsinfo = array(),$compan_id = NULL,$is_admin=NULL){
        if(empty($id) || empty($data) || empty($hzsinfo)){
            return false;
        }
        try {
            $updata['id'] = intval($id);
            $updata['true_name'] = $data['Contact'];
            $updata['hzs_id'] = intval($hzsinfo['id']);
            if(!empty($compan_id)){
                $updata['compan_id'] = intval($compan_id);
            }
            if(isset($is_admin)){
                $updata['is_admin'] = $is_admin;
            }
            $updata['modified'] = date('Y-m-d H:i:s',time());

            return $this->save($updata);
        } catch (\Exception $e) {
            throw new \Exception('User modify failed',1045);
        }
    }
    /**
     * 根据主键id修改true_name、hzs_id、
     * @param  int id 用户id
     * @param  int data 接口查到的用户数据
     * @param  int hzsinfo 合作商数据
     * @return mixed
     */
    public function modifyZy($id = NULL ,$hzsinfo = array()){
        if(empty($id)  || empty($hzsinfo)){
            return false;
        }
        try {
            $updata['id'] = intval($id);
            $updata['hzs_id'] = intval($hzsinfo['id']);
            $updata['modified'] = date('Y-m-d H:i:s',time());

            return $this->save($updata);
        } catch (\Exception $e) {
            throw new \Exception('User modify failed',1045);
        }
    }
}

