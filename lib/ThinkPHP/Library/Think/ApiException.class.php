<?php
namespace Think;
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 定义一个基础APi异常类
 * @author v.r
 * @package			TinkPHP
 * @subpackage		TinkPHP.lib.Api.ApiException
 */
class ApiException extends \Exception {
	const EX_NOT_MODIFIED = 304;
	const EX_REQUEST = 400;
	const EX_REQUEST_UNAUTHORIZED = 401;
	const EX_SERVER = 500;
	const EX_SERVER_NOTFOUND = 501;	
	const EX_REQUEST_INVAL = 601;
	const EX_RESP_EMPTY = 602;
	//user defined Exception must be bigger than 700
	const EX_USER_TOKEN_NOTEXIST = 714;
	
	public $_message = array(
		ApiException::EX_NOT_MODIFIED => "Not Modified",
		ApiException::EX_REQUEST => "Bad request",
		ApiException::EX_REQUEST_UNAUTHORIZED => 'Unauthorized',
		ApiException::EX_SERVER => 'Internal Server Error',
		ApiException::EX_SERVER_NOTFOUND => 'Service not found',
		ApiException::EX_REQUEST_INVAL => 'Invalid argument',		
		ApiException::EX_RESP_EMPTY => 'Result empty',	
		ApiException::EX_USER_TOKEN_NOTEXIST => 'The user token is invalid',		
	);

	public function &singleton() {
		static $api_exception;
		if(!isset($api_exception)) {
			$api_exception = new ApiException();
		}
		return $api_exception;
	}

	public function message($code) {
		$api_exception = ApiException::singleton();
		if(key_exists($code, $api_exception->_message)) {
			return $api_exception->_message[$code];
		} else {
			return false;
		}
	}
}