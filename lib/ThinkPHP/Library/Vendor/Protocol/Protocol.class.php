<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  基站 -通讯协议解析库 
 * @author v.r
 * @package         web服务与基站协议解析库
 * @subpackage      protocol.class.php
 */


if(!defined('BS_PROTOCOL_LIB_PATH'))
    define('BS_PROTOCOL_LIB_PATH', dirname(__FILE__));


define('PLT_HADE','7EE3');
define('PLT_NS','0000');
define('PLT_LOG_PATH','F:/kfk/web_server/station_network_mange/data/runtime/Logs/Protocol/');

if (!defined('PLT_LOG_PATH'))
   define('PLT_LOG_PATH',BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'Logs'.DIRECTORY_SEPARATOR);

require_once BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'MessageSource.class.php';
require_once BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'MessageName.class.php';
require_once BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'util'.DIRECTORY_SEPARATOR.'Util.class.php';
require_once 
BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'util'.DIRECTORY_SEPARATOR.'Code.class.php';

require_once 
BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'analyse'.DIRECTORY_SEPARATOR.'Analyse.class.php';

require_once 
BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'Build.class.php';


require_once 
BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'body'.DIRECTORY_SEPARATOR.'Body.class.php';

spl_autoload_register("Util::_loadOfClassPhp"); //注册自动加载方法


/**
 * 与RS121和MS121通信协议定义
 * 通讯协议结构:
 *  消息头：2个字节（0x7E 0xE3）
 *  消息源：2个字节
 *  消息编号：2个字节 。此字段忽略，发送给设备固定是0x0000，设备返回的消息该字段无意义。
 *  消息名称：2个字节
 *  消息长度:2个字节
 *  消息体：不定长，ASCII编码
 *  校验码：2个字节
 */
class protocol 
{    
    /**
     * [消息头]
     * @var [type]
     */
    public $head;

    /**
     * [消息源]
     * @var [type]
     */
    public $source;

    /**
     * [消息编号]
     * @var [type]
     */
    public $ns;

    /**
     * [消息编号]
     * @var [type]
     */
    public $name;

    /**
     * [消息长度]
     * @var [type]
     */
    public $length;
    
    /**
     * [消息体内容]
     * @var [type]
     */
    public $body;
    
    /**
     * [校验码]
     * @var [type]
     */
    public $checkCode;

    /**
     *  [load类文件名]        
     */
    
    public $loadClass;

  

    /**
     * [设置类属性]
     * @param  RespondMessageName $name     [description]
     * @param  [type]             $protocol [description]
     * @return [type]                       [description]
     */
    private function setClassAttr($ptl) {
        $this->head = $ptl[analyse::HEAD];
        $this->source = $ptl[analyse::SOURCE];
        $this->ns = $ptl[analyse::NS];
        $this->name = $ptl[analyse::NAME];
        $this->length = $ptl['aslen'];
        $this->checkCode =$ptl[analyse::CODE];
    }

    /**
     * [二进制协议转十六进制协议]
     * @return [type] [description]
     */
    public function binPtlToHexPtl($ptl) {
        $hexStr = Util::binPtlToHexStrPtl($ptl);
        $log = '解析十六进制协议:'.$hexStr.PHP_EOL;
        self::_writeLog($log);
        return $hexStr;
    }

    /**
     * [十六进制协议转二进制协议]
     * @return [type] [description]
     */
    public function hexPtlToBinPtl(array $ptl = null) {
        return Util::hexStrPtlToBinPtl($ptl);
    }

    /**
     * [创建协议]
     * @return [type] [description]
     */
    public function build(MessageName $name,$cmd) {
        return Build::createPltToHexStr($name,$cmd);
    }

    /**
     * [协议分析]
     * @param  RespondMessageName $name     [description]
     * @param  [type]             $protocol [description]
     * @return [type]                       [description]
     */
    public function analyse(RespondMessageName $name,$protocol) {
        return $this->analyseBody($name,analyse::analyzeProtocolToArray($protocol));
    }

    /**
     * [body分析]
     * @param  RespondMessageName $name [description]
     * @param  [type]             $arr  [description]
     * @return [type]                   [description]
     */
    private function analyseBody(RespondMessageName $name,$arr) {
        $this->setClassAttr($arr);

        $class = new ReflectionClass($name); 
        $attr = $class->getConstants();

        if (!in_array($arr['name'], $attr)) {
            $log = '解析消息名称不存在（'.$arr['name'].'）'.PHP_EOL;
            $log.= '错误协议:'.PHP_EOL.json_encode($arr);
            self::_writeLog($log);
            exit;
        }

        $class = Util::getKeyByVal($arr['name'],$attr);
        $obj = new $class;
        $obj->parseLine($arr);
        $this->loadClass = $class;

        if (!$obj->isAvailable()) {
            $_log = $class.'('.$arr['name'].')'.PHP_EOL;
            $_log .='设备返回数据:'.PHP_EOL.$arr['mContent'].PHP_EOL;
            $_log .='解析失败';
            self::_writeLog($_log);
            exit;
        }

        if ($obj->isAvailable()) {
            $this->body = $obj->getBodyToJson();
            $_log = $class.'('.$arr['name'].')'.PHP_EOL;
            $_log .='设备返回数据:'.PHP_EOL.$arr['mContent'].PHP_EOL;
            $_log .='数据解析成功'.PHP_EOL;
            $_log .= $this->body;
            self::_writeLog($_log);
            return $this->body;
        }
        return false;
    } 

    /**
     * [_写日志]
     * @param  [type] $msg [description]
     * @return [type]      [description]
     */
    private static function _writeLog($msg) {
		Util::_writeLog($msg); 
    }
}


//7ee3006500000002000c08ce 使用案例

//$respondProtocol='7ee3006504061002009D00000000000000020001E1C40001775C000000000B1EC0000000000009F130000000000000000000000000000000000000000001000000100101010102000000013030303030303030303030303030300000000000000000000000000000000000363836322C333731302C36313533000000000000000000000000000000000000000000000000000000000000000038D5F752';
//$ptl = new protocol();
//$ptl->analyse(new RespondMessageName,$respondProtocol);
//echo $ptl->body;

//$p = $ptl->build(new MessageName,'DEV_QUR_REQ');
