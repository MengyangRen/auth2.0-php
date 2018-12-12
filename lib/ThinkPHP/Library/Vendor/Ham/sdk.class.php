<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  开发快 - HAM - SDK
 * @author v.r
 * @package         SDK
 * @subpackage      kfk.ham.sdk.class.php
 */

if(!defined('HAM_API_PATH'))define('HAM_API_PATH', dirname(__FILE__));
require_once HAM_API_PATH.DIRECTORY_SEPARATOR.'config.inc.php';

//设置默认时区
date_default_timezone_set('Asia/Shanghai');
//定义软件名称，版本号等信息
define('KFK_NAME', 'kfk-ham-sdk-php');
define('KFK_VERSION', '1.1.7');
define('KFK_BUILD', '20160811');

/**
 * HAM异常类，继承自基类
 * 
 */
class HAMException extends Exception {}
class KFKHAM {

    /**
     * 默认构造函数
     * @param string $access_id (Optional)
     * @param string $access_key (Optional)
     * @param string $hostname (Optional)
     * @param string $security_token ($Optional)
     */
    public function __construct($app_key = NULL,  $secret_key = NULL,$self_uid = NULL){
        if (!$app_key && !defined('HAM_APP_KEY')){
            throw new HAMException('未设置appkey');
        }
        if (!$secret_key && !defined('HAM_SECRET_KEY')){
            throw new HAMException('未设置访问秘钥');
        }
        
        if (!$self_uid && !defined('SELF_UID')){
            throw new HAMException('未设置您的uid');
        }

        if($app_key && $secret_key && $self_uid){
            $this->app_key = $app_key;
            $this->secret_key = $secret_key;
            $this->self_uid = $self_uid;
        } elseif (defined('HAM_APP_KEY') && defined('HAM_SECRET_KEY') && defined('SELF_UID')){
            $this->app_key = HAM_APP_KEY;
            $this->secret_key = HAM_SECRET_KEY;
            $this->self_uid = SELF_UID;
        } else {
            throw new HAMException('未定义appkey与秘钥');
        }  
		
		if (defined('BBL_TOKE')) 
			$this->cli_token = BBL_TOKE;
 		else 
		    $this->cli_token = 'HAM-M250-XBYT';
    }

    /**
     * [订阅GPS主题]
     * @return [type] [description]
     */
    public function takeGPS(array $uids) {
    	$URI = self::HAM_SERVICE_DOMAIN.'/ham_topic';
    	$URI .='?access_token='.$this->genToken();
    	$options['action_type'] = 1;
    	$options['qos']  = 1;
    	$options['type'] = 1;
    	$options['send_uid'] = SELF_UID;
    	$options['subjectlist'] = HAMUtil::takeSplit($uids);
    	return self::HttpClient($URI,$options);
    }

    /**
     * [发送文本消息]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public function sendTextMSG(array $options) {
      $URI = self::HAM_SERVICE_DOMAIN.'/send_msg';
      $URI .='?access_token='.$this->genToken();
      $options['send_uid'] = $this->self_uid;
      $options['msg_type'] = 1;
      return self::HttpClient($URI,$options);
    }
    
    /**
     * [获取token值]
     * @return [type] [description]
     */
    public function genToken() {
    	  $token = HAMUtil::TokenExper();
        if (!empty($token)) 
           return $token;

        $URI = self::HAM_SERVICE_DOMAIN.'/get_access_token';
    	  $options[self::HAM_PARAM_UID] = $this->self_uid;
        $options[self::HAM_APP_KEY] = $this->app_key;
        $options[self::HAM_SECRET_KEY] = $this->secret_key;
        $token = self::HttpClient($URI,$options);
      
        if ($token['code'] == 0) {
           HAMUtil::cacheToken($token['access_token']);
           return $token['access_token'];
        }
        	
    } 
    
    /**
     * auth接口 （接入认证）
     * @param array $options
     * @return ResponseCore
     * @throws HAMException
     */
    public function auth($callback = NULL,array $options = array()) {
		$URI = self::HAM_SERVICE_DOMAIN.'/set_config';
		$options[self::HAM_PARAM_UID] = $this->self_uid;
		$options[self::HAM_PARAM_CALLBACK] = empty($callback)?CALLBACK:$callback;
		$options[self::HAM_APP_KEY] = $this->app_key;
		$options[self::HAM_SECRET_KEY] = $this->secret_key;
		$options[self::HAM_PARAM_TOKEN] = $this->cli_token;
		return self::HttpClient($URI,$options);  
    }

    /**
     * [HttpClient description]
     * @param [type] $URI     [description]
     * @param [type] $options [description]
     */
    public static function HttpClient($URI,$options) {
      	$jsonStr = json_encode($options,true);
        HttpClient::http_post_json($URI,$jsonStr,$data);
        return (array)json_decode($data);
    }


	  public $app_key = NULL;
    public $secret_key = NULL;
    public $cli_token = NULL;
    public $self_uid = NULL;

    const HAM_SERVICE_DOMAIN = HAM_DOMAIN;
    const HAM_PARAM_UID = 'uid';
    const HAM_PARAM_CALLBACK = 'url';
    const HAM_PARAM_TOKEN =  'token';
    const HAM_APP_KEY = 'appkey';
    const HAM_SECRET_KEY = 'secretkey';


}

/**
 * ham 工具类
 */
Class HAMUtil {
	/**
	 * [op]
	 * @param  array  $uids [description]
	 * @return [type]       [description]
	 */
	static function takeSplit(array $uids = array()) {
		  $tpk = '&TX$GPS$';
		  $subjectlist = array();
		  foreach ($uids as $value) {
		    $subjectlist[] = $value.$tpk;
		  }
		  return $subjectlist;
  }

  /**
   * [op]
   * @param  array  $uids [description]
   * @return [type]       [description]
   */
  static function TokenExper() {
      if (empty($_SESSION['HAM_TOKEN']))
         return false;

      $time = $_SESSION['HAM_TOKEN']['time'];
      $expr = strtotime('+2 hours',$time);

      if (time() <= $expr) {
          return $_SESSION['HAM_TOKEN']['token'];
      }

      return false;
  }

  static function cacheToken($token) {
      $_SESSION['HAM_TOKEN'] = array(
         'token'=>$token,
         'time'=>time()
      );

      return false;
  }  
  
}

/**
 * ham http控制台
 */
Class HttpClient {
    static function _post($url, $param, &$data, $timeout=10) {
        $data = '';
        $fields = http_build_query($param); 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($ch, CURLOPT_COOKIE ,$_SERVER['HTTP_COOKIE']);
        $ret = curl_exec($ch);
        $data = $ret;
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $code = (int) $code;
        if ($code >= 400) {
            return false;
        }
        return $code;
    } 

    static function http_post_json($url, $jsonStr,&$data)
    {
     // var_dump($url);
      //echo $jsonStr;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json; charset=utf-8',
          'Content-Length: ' . strlen($jsonStr)
        )
      );
      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $data = $response;
      return $httpCode;
    }  
}
