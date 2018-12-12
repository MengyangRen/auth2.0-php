<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  基站 -通讯协议工具库 
 * @author v.r
 * @package         
 * @subpackage      lib.util.protocol.class.php
 */ 

class Util {
 
    /**
     * [自动加载类]
     * @return [type] [description]
     * 
     */
	public static function _loadOfClassPhp($class) {
		$file = BS_PROTOCOL_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'body'.DIRECTORY_SEPARATOR.$class.'.class.php';
		if (is_file($file)) { 
		    require_once $file;
		} else {
			Util::_writeLog("解析消息类（{$class}）文件不存在"); 
			exit;
		}
	}
    /**
     * [接受json协议的参数并分析成数组]
     * @return [type] [description]
     */
    public static function analyzeJsonProtocolParam() {
        $json = file_get_contents("php://input");
        $json = json_decode($json);
        return self::makeJsonToArray($json);
    }

    /**
     * [makeJsonToArray json换行为数组 递归]
     * @param  [type] $json [description]
     * @return [type]       [description]
     */
    public static function makeJsonToArray($json){
        $json = is_object($json) ? get_object_vars($json):$json;
        $arr = array();
        $val = '';

        foreach ($json as $key => $value) {
              if (is_array($value)|| is_string($value)) {
                  $val = $value;
              } else {
                 if(is_object($value)) {
                    $val = self::makeJsonToArray($value);
                 }
              }
              $arr[$key] = $val;
            # code...
        }
        $json = null;
        return $arr;
    }

    /**
     * [检查协议长度]
     * @param  [type] $ptl [description]
     * @param  [type] $len [description]
     * @return [type]      [description]
     */
	public static function checkPtlLen($ptl,$len) {
        $len1 = mb_strlen($ptl)/2;
        if ($len1 == $len) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * [写日志]
     * @return [type] [description]
     * 
     */
	public static function _writeLog($msg) {
   
        $filename = PLT_LOG_PATH . date('Y-m-d') . '.log';
        $dir        =  dirname($filename);

        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }

        @fopen($filename, 'a+');

        $str = @file_get_contents($filename);
        $str .=  '['. date("Y-m-d H:i:s") . '] ' . $msg ;
        $str .= PHP_EOL;
        @file_put_contents($filename, $str);
	}  

    /**
     * [获取命令类名]
     * @return [type] [description]
     * 
     */
    public static function getKeyByVal($val,$arr) {
        $name = '';
        foreach ($arr as $key=>$value) {
            # code...
            if ($value == $val) {
                $name = $key;
            }
        }
        
        if (empty($name)) return false;

        $arr = explode('_',$name);
        $arr = array_map('strtolower',$arr);
        $arr = array_map('ucfirst',$arr);
        return implode('',$arr).'Body';
    } 

    /**
     * [数组拆分成字串]
     * @return [type] [description]
     */
    public static function arrayToStr($arr) {
        return implode('',$arr);
    }

    /**
     * [16进制转10进制]
     * @return [type] [description]
     */
    public static function hexToDecString($hex) {
        return hexdec($hex);
    }

    /**
     * [10进制转16进制]
     * @return [type] [description]
     */
    public static function decToHex($dec) {
        return dechex($dec);
    }

    /**
     * [判断是否是大端序，还是小端序]
     * @return boolean [description]
     */
    public static function isBigEndian($bigendian) {
        $bin = pack("L", $bigendian);
        $hex = bin2hex($bin);
        if (ord(pack("H2", $hex)) === 0x78)
        {
            return false;
        } else {
            return true;
        }
    }

    /**
     *  十六进制转字符串
     * @return [type] [description]
     */
    public static function hexToString($hexs) {
        $str = '';
        $hexs = str_split($hexs,2);
        $len = count($hexs);
        for ($i=0; $i < $len-1 ; $i++) { 
            # code...
             $hex = '0x'.$hexs[$i];
             if ('0x00'!= $hex) {
                $str.= chr($hex);
             }
        }
        return $str;
    }

    public static function hecToString($hexs) {
        $str = '';
        $hexs = str_split($hexs,2);
        $len = count($hexs);
        for ($i=0; $i < $len-1 ; $i++) { 
            $hex = '0x'.$hexs[$i];
            $str.= chr(hexdec($hex));
        }
        
        return $str;
    }
    
    /**
     * [十六进制字串协议变成二进制协议]
     * @param  array  $hexArray [description]
     * @return [type]           [description]
     */
    public static function hexStrPtlToBinPtl(array $hexArray) {
        $temp = array();
        $len = count($hexArray);
        $bin = '';

        for ($i = 0; $i < $len; $i++) { 
            $hexs = str_split($hexArray[$i],2);
            $temp[] = $hexs[0];
            $temp[] = $hexs[1];
        }

        for ($i = 0; $i < count($temp); $i++) { 
            # code...
            $hex = '0x'.$temp[$i];
            $hex = trim($hex);
            $bin .= chr(hexdec("{$hex}"));    
        }
        return $bin;
    }

    /**
     * [二进制协议变成十六进制字符协议]
     * 根据协议要求不足2个字节的补零
     * @param  array  $hexArray [description]
     * @return [type]           [description]
     */
    public static function binPtlToHexStrPtl($bin) {
       $hexStr = '';
       $len = mb_strlen($bin);
       for ($i = 0; $i < $len ; $i++) { 
           # code...
           $hex = dechex(ord($bin[$i]));
           $byt = mb_strlen($hex);
           $lb  = str_repeat(hexdec(null),2-$byt);
           $hexStr .= trim($lb.$hex);
       }
       return $hexStr;
    }
    
    /**
     * [分析执行名]
     * @return [type] [description]
     */
    public static function parseCmdName($arr,$cmd) {
        foreach ($arr as $key=>$value) {
            if ($key == $cmd) {
                return $value;
            }
        }
    }
}
