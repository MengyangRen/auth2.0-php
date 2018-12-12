<?php
namespace System\Logic;

use Think\ApiException;
use Common\Logic\Logic;

use System\Model\UserTokenModel;
use System\Model\YCSCCDModel;
use System\Model\YCSCompanyModel;


/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 *  企业服务业务逻辑
 *    
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */

class CompanyLogic extends Logic {

    /**
     * 单例对象
     * @var obj 
     */
    public static $instance;


    /**
     * Get information about the enterprise the user is in
     * 
     * @param $access_token
     * To the cloud user to access the generated token
     * 
     * @return mixed $data default array,
     *  else Exception
     *  
     * @ingroup oauth2_section_4
     */
	public function getCompanyInfo($access_token = NULL ) {
        
        try {

            $UserTokenModel   = new UserTokenModel;
            $YCSCCDModel      = new YCSCCDModel;
            $YCSCompanyModel  = new YCSCompanyModel;
            
            $uid = $UserTokenModel->getUidByToken($access_token);
            $info  = $YCSCCDModel->getCompanyIdByUID($uid);
            $info = $YCSCompanyModel->getCompanyByID($info[$uid]['compan_id']);

            return  array(
                'company_name' => $info['company_name'],
                'company_type'=>$info['company_type'],
                'tax_number' => $info['tax_number'],
                'province'=>'',
                'city'=>'',
                'zone'=>'',
                'address' => $info['address'],
                'disable'=>'',
                'tel_phone'=>'',
                'legal_person_phone' => $info['legal_person_phone'],
                'legal_person_name' => $info['legal_person_name'],
                'created' => $info['created'],
                'nowtime' => time(),
            );
	    } catch (\Exception $e) {
 			throw new \Exception($e->getMessage(), $e->getCode());
	    }

	}
}