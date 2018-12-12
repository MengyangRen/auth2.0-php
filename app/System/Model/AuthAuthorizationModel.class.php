<?php
namespace System\Model;
use Think\Model;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  授权模型
 *  
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 */

class AuthAuthorizationModel extends Model{
    

    /**
     * [主键]
     * @var string
     */
    Protected $pk = 'authorization_code';

    /**
     * [定义表别名]
     * @var string
     */
    protected $tableName='auth_authorization_codes';
    
    /**
     * [自动校验]
     * @var array
     */
    protected $_validate = array();

    /**
     * [数据库配置]
     * @var string
     */
    protected $connection = 'DB_ZhiCloud_Auth';
     

    /**
     *  
     * Take the provided authorization code values and store them somewhere.
     * 
     * @param  string  code      - 授权码
     * @param  int     client_id - openkey
     * @param  int     user_id   - 用户id
     * @param  int     expires   - 过期时间
     * @param  sting   socpe     - 授权码
     * 
     * @return mixed $data default array, else Exception
     *  
     */
    public function setAuthorizationCode($code, $client_id, $user_id, $expires, $scope) {
        return $this->add(
            array(
                'authorization_code' => $code,
                'client_id'=>$client_id,
                'user_id'=>$user_id,
                'expires'=>date('Y-m-d H:i:s',$expires),
                'scope'=>$scope
            )
        );
    }

    /**
     *Gets the scope of access to this authorization code
     *
     * @param $auth_code
     * A space-separated string of scopes.
     *
     * @return mixed $data default array, 
     * else Exception
     * 
     */
    public function getScopesByAuthCode($auth_code = NULL) {
        $scopes = array();

        $condition['authorization_code'] = $auth_code;
        $result = $this->where($condition)->find();


        if (empty($result)) 
            throw new \Exception("Authorization code does not exist, please reauthorize",
                100019);

        if ( FALSE != strpos($result['scope'],',')) 
            $scopes = explode(',', $result['scope']);
        else
            $scopes[] = $result['scope'];

        return $scopes;
    }

   /**
     *Gets the expiration time of the authorization code
     *
     * @param $auth_code
     * A space-separated string of scopes.
     *
     * @return mixed $data default time, 
     * else Exception
     * 
     */
    public function getAuthCodeLifeTime($auth_code = NULL) {
        
        $condition['authorization_code'] = $auth_code;
        $result = $this->where($condition)->find();

        if (empty($result)) 
            throw new \Exception("Authorization code does not exist, please reauthorize",
                100019);

        return strtotime($result['expires']);
   
    }

   /**
     *Access to authorized user through Authorization Code
     *
     * @param $auth_code
     * A space-separated string of scopes.
     *
     * @return mixed $data default user, 
     * else Exception
     * 
     */
    public function getAuthUserByAuthCode($auth_code = NULL) {
        
        $condition['authorization_code'] = $auth_code;
        $result = $this->where($condition)->find();

        if (empty($result)) 
            throw new \Exception("Authorization code does not exist, please reauthorize",
                100019);

        return $result['user_id'];

    }

   /**
     *Obtain an authorized client by authorization code
     *
     * @param $auth_code
     * A space-separated string of scopes.
     *
     * @return mixed $data default user, 
     * else Exception
     * 
     */
    public function getAuthClientByAuthCode($auth_code = NULL) {
        
        $condition['authorization_code'] = $auth_code;
        $result = $this->where($condition)->find();

        if (empty($result)) 
            throw new \Exception("Authorization code does not exist, please reauthorize",
                100019);

        return $result['client_id'];

    }

}