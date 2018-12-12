<?php
namespace System\Service;

use Think\ApiException;
use Common\Service\BaseService;
use System\Filter\AuthFilter;
use System\Logic\AuthLogic;
use System\Logic\ValiditCheckLogic;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 *  认证授权服务
 * 
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */

class AuthService extends BaseService {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * @api {post} authService/authorize/json
     * @apiDescription  第三方授权服务
     * @apiGroup  授权
     * @apiName   AuthService\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\     * @apiParam  {string}    access_token    访问token 32位 (字母+数字)
     * @apiParam  {int}       client_id       openkey   10位 (数字)
     * @apiParam  {sting}     Authorization   openkey+密钥 （HTTP协议头认证 Basic dGVzdGNsaWVudDp0ZXN0cGFzcw==）
     * 
     * @apiVersion 1.0.1
     * 
     * @apiExample {curl}访问示例：
     * curl -i http://auth.zhicloud.com/apis/rest/authService/authorize/json?access_token=441659bb158169ab65015c17a373d6061ee&client_id=1102084294&_t=123434343
     * 
     * @apiSuccessExample {json} Response 200 Example
     *   HTTP/1.1 200 OK
     *   {
     *    "data"：{
     *        "authorize_code":'964338466c1275096c35321dcc2aab049b6cd9e0',
     *    },
     *    "code": 200,
     *    "message": "ok"
     * }
     * 
     */
	public function authorize($access_token = NULL,$client_id = NULL) {
        try {

            //filter layer
            $authFilter = AuthFilter::getInstance();
            $authFilter->authorize($access_token,$client_id);
            
            //validit checking 
            $ValiditCheckLogic = ValiditCheckLogic::getInstance();
            $ValiditCheckLogic->checkClientId($client_id,$this->request);
            $ValiditCheckLogic->checkToken($access_token);

            //authorization 
            $authLogic = AuthLogic::getInstance();
            return $authLogic->authorize($access_token,$client_id);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
	}
}