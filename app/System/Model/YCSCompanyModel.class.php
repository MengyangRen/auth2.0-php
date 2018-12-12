<?php
namespace System\Model;
use Think\Model;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  财税云公司管理模型
 * @author  v.r
 * @package sys 
 * 
 * 注: 辅助方法为私有方法 命名以下滑线开头
 *  
 * 
 */

class YCSCompanyModel extends Model{
    
    /**
     * @var string
     */
    Protected $pk = 'id';

    /**
     * @var string
     */
    protected $tableName='companys';
    
    /**
     * @var array
     */
    protected $_validate = array();

    /**
     * @var string
     */
    protected $connection = 'DB_ZhiCloud_Customs';

    /**
     * 新增企业
     * @param  array 企业数据
     * @return mixed
     */
    public function addCompany(array $data = NULL ) {
        try {
            return $this->add($data);
        } catch (\Exception $e) {    
            throw new \Exception('companys add failed',1045);
        }
    }

    /**
     * 通过税号查询到企业id
     * @param  array 企业数据
     * @return string 
     */
    public function getCompanIdByTaxCode($tax = NULL ) {
        $field  = 'id';
        $condition['tax_number'] = $tax;
        $result = $this->field($field)->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("No information query the user belongs to the company", 905);
        return $result['id'];
    }


    /**
     * 
     * 通过企业id获取详情
     * 
     * @param  string  id  企业id    
     * @return mixed
     *  
     */  
    public function getCompanyByID($id = NULL) {
        $condition['id'] = $id;
        $result = $this->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("No information query the user belongs to the company", 905);
        return $result;
    }


    /**
     * 
     * 通过企业税号获取详情
     * @param  string  id  企业id    
     * @return mixed
     *  
     */ 
    public function findCompayByTax($tax = NULL) {
        $condition['tax_number'] = $tax;
        $result = $this->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("No information query the user belongs to the company", 905);
        return $result;
    }
    /**
     * 
     * 通过企业税号获取详情
     * @param  string  id  企业id    
     * @return mixed
     * 不抛出异常
     */ 
    public function findCompayByTaxNotNull($tax = NULL) {
        $condition['tax_number'] = $tax;
        $result = $this->where($condition)->find();
        return $result;
    }  

    /**
     *  税号是否存在 
     * 
     * @param  string  id  企业id    
     * @return mixed
     *  
     */ 
    public function findCompayByTaxNum($tax = NULL) {
        $condition['tax_number'] = $tax;
        $result = $this->where($condition)->find();
        if (!empty($result)) 
            throw new \Exception("The company already exists", 905);
        return $result;
    }
    
    /**
     * 
     * 通过企业税号获取详情
     * @param  string  id  企业id    
     * @return mixed
     *  
     */ 
    public function findCompayByCompanyName($company_name= NULL) {
        $condition['company_name'] = $company_name;
        $result = $this->where($condition)->find();
        /*if (!empty($result)) 
            throw new \Exception("The company already exists", 906);*/
        return $result;
    }
    
    /**
     * 
     * 获取描述企业范围（地区和行业）
     * 
     * @param  string  id  企业id    
     * @return mixed
     *  
     */  
    public function getCompanyRangeByID( $id = NULL) {
        $condition['id'] = $id;
        $field  = 'province_id,city_id,zone_id,trade_id';
        $result = $this->field($field)->where($condition)->find();
        if (empty($result)) 
            throw new \Exception("No information query the user belongs to the company", 905);
        return $result;
    }

    /**
     * 
     * 更新企业税号
     * 
     * @param  string  id  企业id    
     * @return mixed
     *  
     */  
    public function updateCompayUserTaxByOldTax($new_tax = NULL,$old_tax = NULL) {
        $condition['tax_number'] = $old_tax;
        return $this->where($condition)->setField(array('tax_number'=>$new_tax));
    }

    /**
     * 根据主键id 填充对应空数据
     * @param  int id 用户id
     * @param  int data 接口查到的用户数据
     * @param  int hzsinfo 合作商数据
     * @return mixed
     */
    public function modify($id = NULL ,$data = array(),$hzsinfo = array()){
        if(empty($id) || empty($data) || empty($hzsinfo)){
            return false;
        }
        //获取对应省市区码
        $new_data['pro'] = $data['pro'];
        $new_data['city_id'] = $data['cy'];
        $arr = $this->_getRegionIds($new_data);
        
        try {

            $updata['id'] = intval($id);
            $updata['trade_id'] = 444;
            $updata['hzs_id'] = $hzsinfo['id'];
            $updata['company_name'] =  $data['CustName'];
            $updata['tax_number'] = $data['CusTaxCode'];
            
            $updata['company_type'] = 1;
            $updata['province_id'] = $arr['province_id'];
            $updata['city_id'] = $arr['city_id'];
            $updata['address'] = $data['Address'];
            $updata['disable'] = 1;
            $updata['status'] = 1;
            $updata['legal_person_phone'] = $data['Mobile'];
            $updata['legal_person_name'] = $data['Contact'];
            $updata['sex']   = 3;
            $updata['level'] = 5;
            $updata['created'] = date('Y-m-d H:i:s',time());
            $updata['modified'] = date('Y-m-d H:i:s',time());
            $updata['tel_phone'] = $data['TelPhone'];
            $updata['master_slave'] = $data['MasterSlave'];
            $updata['bank_deposit'] = $data['BankDeposit'];
            $updata['bank_account'] = $data['BankAccount'];
            $reust =  $this->save($updata);
        } catch (\Exception $e) {
            throw new \Exception('User modify failed',1045);
        }
    }

    /**
     * 根据主键id 填充对应空数据
     * @param  int id 用户id
     * @param  int data 接口查到的用户数据
     * @param  int hzsinfo 合作商数据
     * @return mixed
     */
    public function modifyZy($id = NULL ,$data = array(),$hzsinfo = array()){
        if(empty($id) || empty($data) || empty($hzsinfo)){
            return false;
        }
       
        
        try {
            $updata['id'] = intval($id);
            $updata['hzs_id'] = intval($hzsinfo['id']);
            $updata['address'] = '';
            $updata['master_slave'] = 0;
            $updata['modified'] = date('Y-m-d H:i:s',time());
            $updata['status'] = 1;
            $updata['sex']   = 3;
            $updata['legal_person_phone'] = '';
            $updata['trade_id'] = 444;
            $updata['company_type'] = 3;
            $updata['legal_person_name'] = '';
            $updata['province_id'] = 0;
            $updata['city_id'] = 0;
            $updata['zone_id'] = 0;
            $updata['status'] = 1;
            $updata['disable'] = 1;
            $updata['level'] = 5;
            return $this->save($updata);
        } catch (\Exception $e) {
            throw new \Exception('User modify failed',1045);
        }
    }
    /**
	 * 获取省，市，id
	 * @param  array data 用户数据 
	 * @return mixed
	 * 
	 */
	public function _getRegionIds(array $data = NULL) {
		$YCSRegionModel = new YCSRegionModel;
	    
        $arr = array(
	   	  'province_id'=>'',
	   	  'city_id'=>'',
	    );
        
        if (!empty($data['pro'])) {
         	$ret = $YCSRegionModel->getRegionByCode($data['pro']);
            $arr['province_id'] = $ret['id'];
        }
        
        if (!empty($data['cy'])) {
         	$ret = $YCSRegionModel->getRegionByCode($data['cy']);
            $arr['city_id'] = $ret['id'];
        }
        
        if (empty($data['cy'])) 
        	 $arr['city_id'] = $arr['province_id'];
        
        return $arr;
        
    }    
}