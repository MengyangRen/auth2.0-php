<?php
namespace System\Logic;

use Think\ApiException;
use Common\Logic\Logic;
use System\Model\AuthAuthorizationModel;
use System\Model\UserTokenModel;
use System\Model\AuthClientsModel;
use System\Model\AuthScopesModel;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 *  用户授权业务逻辑
 *    
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */

class AuthLogic extends Logic {

    /**
     * 单例对象
     * @var obj 
     */
    public static $instance;

    
    /**
     * Power of third party enterprise authorized by user
     *
     * @param $client_id
     * Client identifier related to the authorization code
     *
     * @param $access_token
     * To the cloud user to access the generated token
     * 
     * @return mixed $data default array,
     *  else Exception
     *  
     * @ingroup oauth2_section_4
     */
    
	public function authorize($access_token = NULL,$client_id = NULL) {

        try {
	   	
            $authClientsModel = new AuthClientsModel;
	   	    $userTokenModel  = new UserTokenModel;
	   	    $authClientsModel =  new AuthClientsModel;
	   	    $authScopesModel = new AuthScopesModel;
	        
            return array(
	        	'auth_code'=>$this->createAuthorizationCode(
                    $client_id,
                    $userTokenModel->getUidByToken($access_token),
                    $authScopesModel->getOpenPermissionCollection(
                        $authClientsModel->getPermissionInfo($client_id)
                    )

                )
            );

	    } catch (\Exception $e) {
 			throw new \Exception($e->getMessage(), $e->getCode());
	    }
	}
    
	/**
     * Handle the creation of the authorization code.
     *
     * @param $client_id
     * Client identifier related to the authorization code
     * @param $user_id
     * User ID associated with the authorization code
     * @param $redirect_uri
     * An absolute URI to which the authorization server will redirect the
     * user-agent to when the end-user authorization step is completed.
     * @param $scope
     * (optional) Scopes to be stored in space-separated string.
     * 
     * @ingroup oauth2_section_4
     */
    public function createAuthorizationCode($client_id, $user_id, $scope = null)
    {
        $code = $this->generateAuthorizationCode();
        $authorizationModel = new AuthAuthorizationModel;
        if (! $authorizationModel->setAuthorizationCode($code, $client_id, $user_id, time()+ AUTH_CODE_LIFETIME, $scope)) 
          throw new \Exception("Failed to create authorization code. Please contact Zhicloud administrator", 100018);
        return $code;
    }

    /**
     * Generates an unique auth code.
     *
     * Implementing classes may want to override this function to implement
     * other auth code generation schemes.
     *
     * @return
     * An unique auth code.
     *
     * @ingroup oauth2_section_4
     */
    protected function generateAuthorizationCode()
    {
        $tokenLen = 40;
        if (function_exists('random_bytes')) {
            $randomData = random_bytes(100);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $randomData = openssl_random_pseudo_bytes(100);
        } elseif (function_exists('mcrypt_create_iv')) {
            $randomData = mcrypt_create_iv(100, MCRYPT_DEV_URANDOM);
        } elseif (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 100) . uniqid(mt_rand(), true);
        } else {
            $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);
        }

        return substr(hash('sha512', $randomData), 0, $tokenLen);
    }

}
