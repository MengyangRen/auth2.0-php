<?php
namespace Common\Service;
use Think\Service;
use Think\Cache\Driver\Memcache;
use Common\Service\RequestService;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 服务基础类
 * @author  v.r
 * @package Common
 * 
 */

class BaseService extends Service
{
	protected $models = array();
	protected $controllers = array();
    public    $request = array();

    public function __construct() {
        $this->request = new RequestService($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
        parent::__construct();
    
    }

	public function checkToken($userid, $token) {
	    $userid = intval ( $userid );
		if (! $userid) return false;
		$flag = false;

		// 根据生成机制，先check前面几位
		if (empty ( $token ) || ! is_string ( $token ))
			return false;

		$pre = substr ( $token, 0, 1 );
		$pre = intval ( $pre );
		$check = $pre * 13 - 11;
		$mid = substr ( $token, 1, strlen ( $check ) );

		if ($check != intval ( $mid )) {
			$flag = false;
		} else {
			do {
				$cache = $this->getCache($userid);
				if (! empty ( $userid )) {     /*如果rides查到*/
					if (strcmp ( $cache, $token ) == 0) {
						$flag = true;
						break;
					}
				}
			} while ( 0 );
		}
		return $flag;
	}

	

	public function addCache($userid, $token) {
		$cacheKey = 'user_token' . $userid;
		$mem = new Memcache();
        $mem->set($cacheKey,$token,C('TOKEN_CACHE_EXPIRE'));
		return true;
	}

	public function getCache($userid) {
		$cacheKey = 'user_token' . $userid;
		$mem = new Memcache();
		$cache = $mem->get($cacheKey);
		return $cache;
	}

	public function delelteCache($userid) {
		$cacheKey = 'user_token' . $userid;
		S($cacheKey, null);
		return true;
	}
}