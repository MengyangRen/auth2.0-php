<?php
namespace Think\Controller;
use Think\Controller;
use Think\ApiException;
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 定义一个基础APi访问类
 * 
 * @author v.r
 * @package			Thinkphp
 * @subpackage		TinkPHP.libbrary.think.controller.ApisBaseclass
 */
 
 abstract class ApisBaseController extends Controller {
 	private $uses = array();
	private $docTemplate = null;
	private $apiBase = null;

	/**
	 * [对外提供映射文档]
	 * @return [type] [description]
	 */
	public function doc()
	{
		vendor('wsdl/documentation');
		$phpdoc = new \IPPhpdoc();
		loadService($_GET['name'],true);
		if (isset($_GET['name'])) {
			$phpdoc->setClass($_GET['name']);
	    }
	    if(isset($_GET['name']))
		{   
			$phpdoc->setClasses(array($_GET['name']));
			echo $phpdoc->getDocumentation($this->docTemplate ? $this->docTemplate : VENDOR_PATH . '/wsdl/templates/default.xsl');
		}
		exit();
		# code...
	}

	/**
	 *  定义rest模式的调用方式
	 * @param  [string] $serviceName  服务名字
	 * @param  [stirng] $method       服务方法
	 * @param  string $retType        返回类型 [默认:json]
	 * @return xml 或者 Json  
	 * @列子
	 * apis/rest/TestService/SayTest/json?t_=1223423534
	 */
	final public function rest($serviceName = null, $method = null, $retType = null)
	{
		try {
			$serviceName = empty($serviceName)? $_GET["_URL_"][2] : $serviceName;
			$method =  empty($method)? $_GET["_URL_"][1] : $method;
			$retType = empty($_GET["_URL_"][0])? 'json' : $_GET["_URL_"][0];
			$ret = $this->__rest($serviceName, $method);
		} catch (ApiException $e) {
			$ret = $e;
		}

		$this->beforeOut($serviceName, $method);
		$this->__restOut($ret,$retType);
		# code...
	}

	/**
	 *  __rest 辅助方法
	 * @param  [type] $serviceName [description]
	 * @param  [type] $method      [description]
	 * @return [type]              [description]
	 */
	final private function __rest($serviceName = null, $method = null) {
	
		$className = ucwords($serviceName); 

		//访问控制
		if (!$this->access($className, $method)) { 
			throw new ApiException("Unauthorized!", ApiException::EX_REQUEST_UNAUTHORIZED);
		}
		

		$service = \loadService($className,true);
		if (!$service) {
			throw new ApiException("Service or method not found!", ApiException::EX_SERVER_NOTFOUND);
		}

		try {
			$serviceClass = new \ReflectionMethod($service, $method);
		}
		catch (Exception $e) {
			throw new ApiException("Service or method not found!", ApiException::EX_SERVER_NOTFOUND);
		}

		if (!$serviceClass->isPublic()) {
			throw new ApiException("Service or method not found!", ApiException::EX_SERVER_NOTFOUND);
		}

         //var_dump($_GET);
         //var_dump($_POST);
        $params = $_GET; unset($params["_URL_"]);
        $params += $_POST;
        
        $args = array();
		$ps = $serviceClass->getParameters();
		foreach ($ps as $p) {
			$args[] = @$params[$p->getName()];
		}

		$ret = call_user_func_array(array($service, $method),$args);
		return $ret;
	}

	/**
	 * [__restOut 输出处理]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	private function __restOut($out,$retType = 'json')
	{
		# code...
		if ($out instanceof ApiException) {
			$status = $out instanceof ApiException ? $out->getCode() : 500;
			//header("HTTP/1.1 $status Error");
			$out = array('code' => $out->getCode(), 'message' => $out->getMessage());
		} else {
			if (!key_exists('data', $out)) $out = array('data' => $out);
			if (!key_exists('code', $out)) $out['code'] = 200;
			if (!key_exists('message', $out)) $out['message'] = 'ok';
		}

		if ($retType == 'xml') {
			ob_clean();
		    header('Content-Type: application/xml');
		    echo $this->___xml_serializer($out);
		} else if ($retType == 'json') {
			header('Content-Type: application/json');
			$out = json_encode($out);
			if (isset($_GET['callback'])) { 
				echo "var _callbackvar=$out;" . $_GET['callback'] . "(_callbackvar);";
			} else {
				echo $out;
			}
		}
		exit();
	}

	/**
	 * [___xml_serializer]
	 * @param  [type] $out [description]
	 * @return [type]      [description]
	 */
    private function ___xml_serializer($out)
    {
        vendor('xmlSerializer');
    	$serializer_options = array (
		   'addDecl' => true,
		   'encoding' => 'UTF-8',
		   'indent' => '  ',
		   'rootName' => 'apiResponse',
		   'defaultTagName' => 'item',
		);

		$Serializer = new \XML_Serializer($serializer_options);
        $status = $Serializer->serialize($out);
		if ($status) {
			return $Serializer->getSerializedData();
		}
		return null;
    	# code...
    }

	/**
	 * 输出之间处理
	 * @param  [type] $serviceName [description]
	 * @param  [type] $method      [description]
	 * @return [type]              [description]
	 */
	protected function beforeOut($serviceName, $method){
        return true;
    }

	/**
	 * 访问控制
	 * @param  [type] $serviceName [description]
	 * @param  [type] $method      [description]
	 * @return [type]              [description]
	 */
	protected function access($serviceName, $method) {
		return true;
	}

	/**
	 * 排除控制
	 * @return [type] [description]
	 */
	protected function excludes() {
		return null;
	}

	/**
	 * [定义soap模式调用方式
	 * @return [type] [description]
	 */
	final public function soap()
	{
		# code...
	}

	/**
	 * [定义rpc模式调用方式]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function rpc($serviceName = null, $method = null, $retType = null)
	{
		# code...
		try {
			$serviceName = empty($serviceName)? $_GET["_URL_"][2] : $serviceName;
			$method =  empty($method)? $_GET["_URL_"][1] : $method;
			$retType = empty($_GET["_URL_"][0])? 'json' : $_GET["_URL_"][0];
			$ret = $this->__rpc($serviceName, $method);
		} catch (ApiException $e) {
			$ret = $e;
		}
		$this->beforeOut($serviceName, $method);
		$this->__restOut($ret, $retType);
	}

	/**
	 * [__rpc 辅助方法]
	 * @param  [type] $serviceName [description]
	 * @param  [type] $method      [description]
	 * @return [type]              [description]
	 */
	public function __rpc($serviceName, $method)
	{
		# code...
		$className = ucwords($serviceName); 

		if (!$this->access($className, $method)) { 
			throw new ApiException("Unauthorized!", ApiException::EX_REQUEST_UNAUTHORIZED);
		}
		
		$service = \loadService($className, true);

		if (!$service) {
			throw new ApiException("Service or method not found!", ApiException::EX_SERVER_NOTFOUND);
		}
		
		Vendor('phpRPC.phprpc_server');
		$rpcServer  =   new \PHPRPC_Server();
        $methods    =   array_diff(array($method),array('login'));   
		$rpcServer->add($methods,$service);
		if(APP_DEBUG) {
            $rpcServer->setDebugMode(true);
        }
        $rpcServer->setEnableGZIP(true);
        $rpcServer->start();
        $rpcServer->comment(); 
	}
 }
