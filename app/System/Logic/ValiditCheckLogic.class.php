<?php
namespace System\Logic;

use Think\ApiException;
use Common\Logic\Logic;

use System\Model\UserTokenModel;
use System\Model\AuthClientsModel;
use System\Model\AuthAuthorizationModel;


/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  合法性检查业务逻辑
 *  
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */

class ValiditCheckLogic extends Logic {

    /**
     * 单例对象
     * @var obj 
     */
    public static $instance;

   /**
     * Based on Http protocol header, get credentials and carry out algorithm comparison
     *
     * @param $credentials
     * Client identifier related to the authorization code
     * 
     * @return mixed $data default Boole,
     *  else Exception
     *
     * @ingroup oauth2_section_4
     *
     *  加密算法暂未定义
     * 
     */
    public function certificateAlgorithmComparison($credentials = NULL,
        $header = NULL) {

    } 

   /**
     * Check the legality of OpenId.
     *
     * @param $client_id
     * Client identifier related to the authorization code
     * 
     * @return mixed $data default Boole,
     *  else Exception
     *
     * @ingroup oauth2_section_4
     */
    public function checkClientId($client_id = NULL,$header = NULL) {

        try {

            $header = $header->server;
            
            if (empty($header['PHP_AUTH_USER']) ||  
                empty($header['PHP_AUTH_PW']))
                throw new \Exception("http header authentication is illegal", 100009);

            if ($client_id != $header['PHP_AUTH_USER'])
                throw new \Exception("Client_id access is illegal", 100002);
                        
            $authClientsModel = new  AuthClientsModel;
            $auth = $authClientsModel->getCredentials($client_id);

            if ($header['PHP_AUTH_PW'] != $auth['client_secret'])
                throw new \Exception("client_secret  access is illegal", 100013);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    } 

   /**
     * Check the legality of Toekn.
     *
     * @param $access_token
     * user issued access credentials
     * 
     * @return mixed $data default Boole,
     *  else Exception
     *
     * @ingroup oauth2_section_4
     */
    public function checkToken($access_token = NULL) {
        try {

            $userTokenModel = new  UserTokenModel;
            $userTokenModel->getUidByToken($access_token);
            //检查toeken有效性
            
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


   /**
     * Check the expiration time of the authorization code
     *
     * @param $auth_code
     * Authorization certificates issued to tripartite enterprises
     * 
     * @return mixed $data default Boole,
     *  else Exception
     *
     * @ingroup oauth2_section_4
     */
    public function checkAuthorizationCodeLifeTime($auth_code = NULL) {

        try {

            $nowtime = time();

            $authAuthorizationModel= new AuthAuthorizationModel;
            $lifeTime = $authAuthorizationModel->getAuthCodeLifeTime($auth_code);
           
            if ($nowtime > $lifeTime) 
                throw new \Exception("authorization code expired, please reauthorize", 
                    100020);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


   /**
     * Check the interval for permission access
     *
     * @param $scope
     * Name of the permission range
     * 
     * @param $auth_code
     * Authorization certificates issued to tripartite enterprises
     * 
     * @return mixed $data default Boole,
     *  else Exception
     *
     * @ingroup oauth2_section_4
     * __FUNCTION__    
     */
    public function checkAccessPermissionScope($scope = NULL,$auth_code = NULL) {

        try {
            
            $authAuthorizationModel = new AuthAuthorizationModel;
            
            if (!in_array($scope,
               $authAuthorizationModel->getScopesByAuthCode($auth_code)))    
               throw new \Exception("The authorization code does not have permission to access",
               100018);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
  
    }


   /**
     * Whether the authorization code belongs to the client
     *
     * @param $client_id
     *  Client identifier related to the authorization code
     * 
     * @param $auth_code
     * Authorization certificates issued to tripartite enterprises
     * 
     * @return mixed $data default Boole,
     *  else Exception
     *
     * @ingroup oauth2_section_4
     */
    public function isAuthcodeToClient($client_id = NULL ,$auth_code = NULL) {
        try {

            $authAuthorizationModel = new AuthAuthorizationModel;
            $auth_client_id = $authAuthorizationModel->getAuthClientByAuthCode($auth_code);

            if ($auth_client_id != $client_id)
                throw new Exception("Authorized client illegally holds authorization code",
                100021);
                
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }



   /**
     * Whether the authorization code belongs to the authorized user
     *
     * @param $access_token
     * To the cloud user to access the generated token
     * 
     * @param $auth_code
     * Authorization certificates issued to tripartite enterprises
     * 
     * @return mixed $data default Boole,
     *  else Exception
     *
     * @ingroup oauth2_section_4
     */
    public function isAuthcodeToUser($access_token = NULL ,$auth_code = NULL) {
        try {

            $authAuthorizationModel = new AuthAuthorizationModel;
            $auth_user_id = $authAuthorizationModel->getAuthUserByAuthCode($auth_code);
            
            $userTokenModel = new  UserTokenModel;
            $user_id = $userTokenModel->getUidByToken($access_token);
            
            if ($auth_user_id != $user_id)
                throw new Exception("Authorized user illegally holds authorization code", 
                100023);
                
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }
}