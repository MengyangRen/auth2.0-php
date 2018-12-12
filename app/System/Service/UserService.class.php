<?php
namespace System\Service;


use Think\ApiException;
use Common\Service\BaseService;

use System\Filter\UserFilter;
use System\Logic\UserLogic;
use System\Logic\ValiditCheckLogic;




/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 *  用户资源服务
 * 
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */

class UserService extends BaseService {
    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * @api {get} UserService/getUserInfo/json
     * @apiDescription  获取用户信息
     * 
     * @apiGroup  用户资源
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
     * curl -i http://auth.zhicloud.com/apis/rest/UserService/getUserInfo/json?access_token=441659bb158169ab65015c17a373d6061ee&client_id=1102084294&_t=123434343
     * 
     *  
     * @apiSuccessExample {json} Response 200 Example
     *   HTTP/1.1 200 OK
     *   {
     *    "data": {
     *    "username": "zhiyun",
     *    "truename": "致云",
     *    "sex": "1",
     *    "phone" :"13881741247",
     *    "is_admin": "0", #是否为企业管理员
     *    "status": "0", # 用户状态
     *    "avatar": "http://api.local.zhicloud.dev.com/data/avatar/000/00/10/02_avatar_small.jpg",
     *  },
     *  "code": 200,
     *  "message": "ok"
     * }
     * 
     */
	public function getUserInfo($access_token = NULL,$client_id = NULL,$auth_code= NULL) {
        try {
            
            
            //filter layer
            $UserFilter = UserFilter::getInstance();
            $UserFilter->getUserInfo($access_token,$client_id,$auth_code);

            //validit checking 
            $ValiditCheckLogic = ValiditCheckLogic::getInstance();
            $ValiditCheckLogic->checkClientId($client_id,$this->request);
            $ValiditCheckLogic->checkToken($access_token);
            $ValiditCheckLogic->checkAuthorizationCodeLifeTime($auth_code);
            $ValiditCheckLogic->checkAccessPermissionScope(__FUNCTION__,$auth_code);
            $ValiditCheckLogic->isAuthcodeToClient($client_id,$auth_code);
            $ValiditCheckLogic->isAuthcodeToUser($access_token,$auth_code);

            //get user info 
            $UserLogic = UserLogic::getInstance();
            return $UserLogic->getUserInfo($access_token);

        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
	}

}