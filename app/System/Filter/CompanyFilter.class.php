<?php
namespace System\Filter;

use Think\ApiException;
use Common\Filter\Filter;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  
 *  定义企业服务的过滤层
 * 
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */

class CompanyFilter extends Filter {

    protected static $instance;
    
    /**
     * Get the information parameter filtering layer of the enterprise where the user belongs
     * 
     * @param  string  access_token   - token   rule(32 bit )
     * @param  int     client_id      - openkey rule(10 bit)
     * @param  string  auth_code      - 授权码  rule(40 bit)
     * 
     * @return mixed $data default null, 
     * else Exception
     *  
     */
    public function getCompanyInfo($access_token = NULL,$client_id = NULL,$auth_code= NULL) {
        if (empty($access_token) || empty($client_id) ||empty($auth_code)) 
           throw new \Exception(ApiException::message(ApiException::EX_REQUEST_INVAL),ApiException::EX_REQUEST_INVAL);
        if (!preg_match("/^[a-z0-9]+$/", $access_token))
           throw new \Exception(ApiException::message(ApiException::EX_REQUEST_INVAL),ApiException::EX_REQUEST_INVAL);
        if (!preg_match("/^[0-9]{10}$/", $client_id))
             throw new \Exception(ApiException::message(ApiException::EX_REQUEST_INVAL),ApiException::EX_REQUEST_INVAL);
        if (!preg_match("/^[a-z0-9]{40}$/", $auth_code))
           throw new \Exception(ApiException::message(ApiException::EX_REQUEST_INVAL),ApiException::EX_REQUEST_INVAL);
    }
}
