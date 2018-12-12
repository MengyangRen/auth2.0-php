<?php
namespace System\Model;
use Think\Model;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  财税云用户 公司 设备中间表 模型
 * @author  v.r
 * @package sys 
 * 
 * 注: 辅助方法为私有方法 命名以下滑线开头
 *  
 * 
 */

class YCSCCDModel extends Model{
    
    /**
     * [主键]
     * @var string
     */
    Protected $pk = 'id';

    /**
     * [定义表别名]
     * @var string
     */
    protected $tableName='custom_company_hzs';
    
    /**
     * [自动校验]
     * @var array
     */
    protected $_validate = array();

    /**
     * [数据库配置]
     * @var string
     */
    protected $connection = 'DB_ZhiCloud_Customs';

    /**
     * [通过UID获取公司ID]
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public function getCompanyIdByUID($uid = NULL ) {
        $arr = array();
        $condition['custom_id'] = $uid;
        $res = $this->where($condition)->find();
        if (empty($res)) 
            throw new \Exception("No information query the user belongs to the company", 905);
        $arr[$res['custom_id']] = $res;
        $res = NULL;
        return $arr;
    }

    /**
     * [通过公司获取所有的用户列表]
     * @param  [type] $company_id [description]
     * @return [type]             [description]
     */
    public function getUidMapsByCompanyID($company_id = NULL) {
        $arr = array();
        $condition['compan_id'] = $company_id;
        $res = $this->where($condition)->select();
        if (empty($res)) 
            throw new \Exception("There are no users below this user", 904);

        foreach ($res as $key => $value) {
            $arr[$value['compan_id']]  = $value;
        }

        $res = NULL;
        return $arr;
    } 

    /**
     * [getScodeByCompanyID 通过公司ID获取邀请码]
     * @param  [type] $company_id [description]
     * @return [type]             [description]
     */
    public function getScodeByCompanyID($company_id = NULL) {
        $arr = array();
        $condition['compan_id'] = $company_id;
        $res = $this->where($condition)->find();
        if (empty($res)) 
            throw new \Exception("There are no users below this user", 904);
        return $res['sn'];
    }


   /**
     * 
     * 通过uid获取合作商ID
     * 
     * @param  string  sign  标记    
     * @return mixed
     *  
     */
    public function getHZSIdByUid($uid = NULL) {
        $condition['custom_id'] = $uid;
        $res = $this->where($condition)->find();
          if (empty($res)) 
            throw new \Exception("No Hzs Id was found", 904);
        return $res['hzs_id'];

    }
    /**
     *
     * 通过uid获取合作商ID
     *
     * @param  string  sign  标记
     * @return mixed
     *
     */
    public function getHZSIdByUuid($uid = NULL) {
        $condition['custom_id'] = $uid;
        $res = $this->where($condition)->find();
        if (empty($res))
            throw new \Exception("No Hzs Id was found", 904);
        return $res;

    }
    /**
     * 添加一条记录
     * @param  int uid 用户id
     * @return mixed
     */
    public function addCCD(array $data = NULL ) {
        try {
            return $this->add($data);
        } catch (\Exception $e) {    
            throw new \Exception('User add failed',1045);
        }
    }
    /**
     * 根据主键id修改hzs_id、identify_code
     * @param  int id 用户id
     * @param  int data 接口查到的用户数据
     * @param  int hzsinfo 合作商数据
     * @return mixed
     */
    public function modify($id = NULL ,$hzsinfo = array(),$compan_id = NULL){
        if(empty($id)  || empty($hzsinfo)){
            return false;
        }
        try {
            $newtime = time();
            $updata['id'] = intval($id);
            $updata['hzs_id'] = intval($hzsinfo['id']);
            if(!empty($compan_id)){
             $updata['compan_id'] = intval($compan_id);
            }
            $updata['identify_code'] = md5($newtime);

            return $this->save($updata);
        } catch (\Exception $e) {
            throw new \Exception('User modify failed',1045);
        }
    }
}

