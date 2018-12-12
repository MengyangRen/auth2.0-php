<?php
namespace System\Service;


use Think\ApiException;
use Common\Service\BaseService;

use System\Filter\CompanyFilter;
use System\Logic\CompanyLogic;
use System\Logic\ValiditCheckLogic;


/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 *  企业资源服务
 * 
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */

class CompanyService extends BaseService {
    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * @api {get} UserService/getUserInfo/json
     * @apiDescription  获取企业信息
     * 
     * @apiGroup  企业资源
     * 
     * @apiName   UserService
     * @apiParam  {string}    access_token    访问token 32位（字母+数字）
     * @apiParam  {int}       client_id       openkey   10位 (数字)
     * @apiParam  {string}    auth_code       授权码    40位 (字母+数字)
     * @apiParam  {sting}     Authorization   openkey+密钥 （HTTP协议头认证 Basic dGVzdGNsaWVudDp0ZXN0cGFzcw==）
     * 
     * @apiVersion 1.0.1
     * 
     * @apiExample {curl}访问示例：
     * curl -i http://auth.zhicloud.com/apis/rest/CompanyService/getCompanyInfo/json?access_token=441659bb158169ab65015c17a373d6061ee&client_id=1102084294&_t=123434343
     * 
     *  
     * @apiSuccessExample {json} Response 200 Example
     *   HTTP/1.1 200 OK
     *   {
     *    "company_name": "成都致云科技有限公司",
     *    "address": "四川省成都市高新区天府三街",
     *    "legal_person_phone": "13888888888",
     *    "legal_person_name": "致云",
     *    "tax_number": "510109000401029",
     *    "created": "2017-06-06 14:26:11",
     *    "newtime": 1498454127
     *  },
     *  "code": 200,
     *  "message": "ok"
     * }
     * 
     */
	public function getCompanyInfo($access_token = NULL,$client_id = NULL,$auth_code= NULL) {
        try {
            
            
            //filter layer
            $CompanyFilter = CompanyFilter::getInstance();
            $CompanyFilter->getCompanyInfo($access_token,$client_id,$auth_code);

            //validit checking 
            $ValiditCheckLogic = ValiditCheckLogic::getInstance();
            $ValiditCheckLogic->checkClientId($client_id,$this->request);
            $ValiditCheckLogic->checkToken($access_token);
            $ValiditCheckLogic->checkAuthorizationCodeLifeTime($auth_code);
            $ValiditCheckLogic->checkAccessPermissionScope(__FUNCTION__,$auth_code);
            $ValiditCheckLogic->isAuthcodeToClient($client_id,$auth_code);
            $ValiditCheckLogic->isAuthcodeToUser($access_token,$auth_code);

            //Get information about the enterprise the user is in
            $CompanyLogic = CompanyLogic::getInstance();
            return $CompanyLogic->getCompanyInfo($access_token);

        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
	}

}