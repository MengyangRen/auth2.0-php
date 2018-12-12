<?php
namespace Think\DataUtil;

/**
 * 定义远程调用容器
 * @author v.r
 */
class DataUtil {
    
    /**
     * [通讯方式]
     * @var [type]
     */
    private $way = 'rpc';

    /**
     * [$host description]
     * @var [type]
     * 格式模式: www.serverName.com
     * 格式模式: 192.168.4.6:80
     * 
     */
    public $host = 'http://dev.jhqc.cn/apis/rpc'; 

    /**
     * [$respondType description]
     * @var string
     */
    private $respondType = 'json';
    
    /**
     * [$struct description]
     * @var array
     */
    private $struct = array();
    
    /**
     * [$params description]
     * @var array
     */
    private $params = array();
    
    /**
     * [__construct description]
     */
    public function __construct()
    {
    	# code...
    }

	/**
	 * [getRpcClient description]
	 * @return [type] [description]
	 */
	public function rpcClient()
	{
        do {
        	Vendor('phpRPC.phprpc_client');
			list($_uri,$_fuc)= $this->getURIANDFUC();
			echo $_uri;
			$client = new \PHPRPC_Client();
			$client->useService($_uri);
            var_dump($this->params);
            $args = array(1, 2);
            echo $client->invoke('add', &$args);


			//$retulst = $client->invoke('getTODO',array('12'),true);
			//$client->setTimeout(3600);
        } while (false);
      //  return $retulst;
        echo $rerulst; die;
	}

	/**
	 * [setWay description]
	 * @param [type] $way [description]
	 */
	public  function setWay($way)
	{
	   $this->way = $way;
	}

	/**
	 * [getURIANDFUC description]
	 * @return [type] [description]
	 */
    private function getURIANDFUC()
    {
    	$urlBase = $this->host;
        $module = empty($this->struct[2])?'':'/'.$this->struct[2];
    	$moduleSerice = '/'.$this->struct[1];
    	$_uri = $urlBase.$module.$moduleSerice.'/'.$this->struct[0].'/'.$this->respondType.'?'.$this->genSuffix();
    	return array($_uri,$this->struct[0]);
    	# code...
    }

	/**
	 * [__call description]
	 * @param  [type] $fuc    [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function __call($fuc,$params)
	{    
		$statcFuc = $this->way.'Client';
        return $this->$statcFuc();
	}

	/**
	 * [genSuffix description]
	 * @return [type] [description]
	 */
    private function genSuffix()
    {
    	return 'Iamsuperman=1&t_=234234&p_=34';
    	# code...
    }

    /**
     * [__callstatic 静态调用]
     * @return [type] [description]
     */
    public function __callstatic($fun,$params)
    {    
    	$dataUtil = new DataUtil;
        $dataUtil->paseParam($params[0]);
        $func=$dataUtil->way.'Client';
        $dataUtil->$fun();
    }

    /**
     * [paseParam 分析参数]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function paseParam($params)
    {
    	$sericeStr = NULL;
    	do {
			if (strpos($params,'(') !== FALSE) {
				$whole = explode('(',$params);
				$this->params = explode(',',trim($whole[1],')'));
				$sericeStr = $whole[0]; 
			} else {
				$sericeStr = $params;
			}
			$this->struct = array_reverse(explode('.', $sericeStr));
    	} while (false);
    }

	/*	public function _init() {
		// print_r($
		RPC_URL);
		// echo $RPC_URL[$_SESSION['CRU_REGIONCODE']];
		// $RPC_URL[$_SESSION['CRU_REGIONCODE']] && $url = $RPC_URL[$_SESSION['CRU_REGIONCODE']];
		defined('KEEP_PHPRPC_COOKIE_IN_SESSION') or define('KEEP_PHPRPC_COOKIE_IN_SESSION', true);
        //Vendor('phpRPC.phprpc_server');
		//$client = new \PHPRPC_Client('http://192.168.0.100:8080/DocuServer_LeShan/');
		//$client->setTimeout(3600);
		//return $client;
	}*/
}