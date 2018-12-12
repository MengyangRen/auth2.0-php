<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  航天信息 -统一WebService服务
 * @author v.r
 * @package         web服务与基站协议解析库
 * @subpackage      protocol.class.php
 */


if (!defined('HTXX_LIB_PATH')) 
   define('HTXX_LIB_PATH', dirname(__FILE__));

require_once HTXX_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.class.php';
require_once HTXX_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'util'.DIRECTORY_SEPARATOR.'util.class.php';
require_once HTXX_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'util'.DIRECTORY_SEPARATOR.'http.php';

date_default_timezone_set('Asia/Shanghai');
spl_autoload_register("HtxxUtil::_loadOfClassPhp"); 

class HtxxUnifyWebService 
{	 

    public static $NO_QUERY_CUSTOMER = '未查到客户信息'; 

    /**
     * 行政区域号对照
     * @var array
     */
    public static $_as = array(
    	 '65'=>'XJ',
       /*'51'=>'SC',*/
       /*'52'=>'GZ',*/
       /*'10'=>'CN',*/
    );

    public static $_map = array(
        '65'=>'xj.hx',

    );


    /**
     * 获取用户信息
     * @param string $code 地区行政码
     * @param array $param 参数集合
     *
     * 列子:
     * $param  = array(
     *   'CusTax'=>'650105999999070'
     * )
     * @return    
     */
    
    public static function getCusInfoByNetWork($code = '51', array $param = NULL)
    {
        try {
           $class = HtxxUnifyWebService::$_as[$code].'WebService';
           return HtxxUnifyWebService::packCusInfo(
                      HtxxUnifyWebService::xmlUnSerializer($class::getCusInfoByNetWork($param)),$param);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 公共xml
     * @param string $xml  
     * @return    array
     */
    private static function xmlUnSerializer($xml = NULL) { 
        try {
            $xml_parser = xml_parser_create();
            if (!xml_parse($xml_parser,$xml,true)) {
                xml_parser_free($xml_parser);
                HtxxUnifyWebService::checkQueryStatus($xml);
            }
            return HtxxUtil::makeObjToArray(simplexml_load_string($xml));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

    /**
     * 查询状态确认
     * @param string $msg  
     * @return    array
     */
    public static function checkQueryStatus($msg = NULL) {
        if (self::$NO_QUERY_CUSTOMER == $msg) 
            throw new \Exception("Customer information not found", 1004);
        throw new \Exception("Remote service exception", 1005);
    }

    /**
     * 包装客户数据
     * @param string $xml  
     * @return    array
     */
    private static function packCusInfo(array $data = NULL,$param = NULL) {
        if (empty($data['LegalPerson']))
            $data['LegalPerson'] = '';

        if (empty($data['BusinessScope']))
            $data['BusinessScope'] = '';

        
        if (empty($data['Contact'])) 
            $data['Contact'] = self::makeNickName($data);

        if (!empty($data['ExpiryDate'])) 
           $data['ServiceChargeExpireTime'] = strtotime($data['ExpiryDate']);

        $data['CusTaxCode'] = $param['CusTaxCode'];
        $data['MasterSlave'] = $param['MasterSlave'];

        $rc = self::analyseRegionCodeByTax($param['CusTaxCode']);

        $data['origin'] = HtxxUnifyWebService::$_map[$rc['province']['_as']];

        $data += array(
            'pro'=>$rc['province']['code'],
            'cy'=>empty($rc['city']) ? '': $rc['city']['code'],
            'ze'=>empty($rc['zone']) ? '': $rc['zone']['code']
        );

        
        return $data;
    }
    
    /**
     *  生成随机名
     * @param string $xml  
     * @return    array
     */
    private function makeNickName($data = NULL) {
        return substr($data['CustName'],0,3).'auto'.HtxxUtil::makeRandomValue(3);
    }
    
    /**
     * 通过税号分析出省码
     * @param string $xml  
     * @return    array
     */
    public static function analyseProvinceCodeByTax($tax = NULL) {
        $pro = self::analyseRegionCodeByTax($tax);
        return $pro['province']['_as'];
    }


    /**
     * 通过税号分析出地区码
     * @param string $xml  
     * @return srtring (6位地区码)
     *
     * 税号规则
     * 15位=6位税务区划+企业编码
     * 17位=15位身份证+2编码
     * 18位=身份证
     * 20位=18位身份证+2编码
     *
     * 企业信用代码   91650100761133756N (18)
     * 企业税号       650100030001620 （15） 
     *
     * 直辖市 500000 
     * 
     */
    public static function analyseRegionCodeByTax($tax = NULL) {
        $ret = null;
        if (15 == strlen($tax) || 17 == strlen($tax) || 20 == strlen($tax)) 
            $ret = substr($tax,0,6);
       
        if (18 == strlen($tax)) 
           $ret = substr($tax,2,6);

        $tmp = array(
             'province'=>'', //省
             'city'=>'', //市
             'zone'=>'', //县或市或区
        );

        $rc = str_split($ret, 2);
        $tmp['province'] = array(
             'code'=>$rc[0].'0000',
             '_as'=>$rc[0],
        );
        if ('00' != $rc[1] ) {
            $tmp['city'] = array(
              'code'=>$rc[0].$rc[1].'00',
              '_as'=>$rc[1],
            );
        } 

        if ('00' != $rc[2]) {
            $tmp['zone'] = array(
              'code'=>$ret,
              '_as'=>$rc[2],
            );
        } 
        return $tmp;
    } 

}



/*
Array
(
    [CustName] => 航信企业培训
    [BankDeposit] => 农业银行
    [BankAccount] => 123456789
    [LegalPerson] =>
    [BusinessScope] =>
    [CusType] => 0
    [Contact] => 刘露
    [TelPhone] => 0991-4671583
    [Mobile] => 189999914232
    [Address] => 乌鲁木齐市北门祥和大厦四楼
    [ExpiryDate] => 2018/4/11 0:00:00
    [ServiceChargeExpireTime] => 1523376000
    [CusTaxCode] => 650105999999118
    [MasterSlave] => 0
    [origin] => xj.hx
    [pro] => 650000
    [cy] => 650100
    [ze] => 650105
)*/
/*
try {
    //主机  参数【CusTaxCode:税号 ，IsMasterSlave:false，MasterSlave:0】
    //分机  参数【CusTaxCode:税号 ，IsMasterSlave:true，MasterSlave:1】
    $_param =array('CusTaxCode'=>'650105999999118','IsMasterSlave'=>false,'MasterSlave'=>0);
    $ret = HtxxUnifyWebService::getCusInfoByNetWork(
    HtxxUnifyWebService::analyseProvinceCodeByTax($_param['CusTaxCode']),$_param);
    print_r($ret); 
} catch (Exception $e) {
    
}*/
