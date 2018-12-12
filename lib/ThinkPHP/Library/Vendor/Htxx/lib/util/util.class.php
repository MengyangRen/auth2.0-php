<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  基站 -通讯协议工具库 
 * @author v.r
 * @package         
 * @subpackage      lib.util.protocol.class.php
 */ 

class HtxxUtil {
 
    /**
     * [自动加载类]
     * @return [type] [description]
     * 
     */
	public static function _loadOfClassPhp($class) {
		$file = HTXX_LIB_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'body'.DIRECTORY_SEPARATOR.$class.'.class.php';
        if (is_file($file)) { 

		    require_once $file;
		} else {
			HtxxUtil::_writeLog("解析消息类（{$class}）文件不存在"); 
			exit;
		}
	}

    public static function makeRandomValue($bit = 8){
        if ($bit < 6) $bit = 6; 
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $salt = '';
        for ($i = 0; $i < $bit; $i++) {
            $salt .= substr($string, mt_rand(0,61), 1);
        }
        return $salt;
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
    public static function makeObjToArray($json){
        $json = is_object($json) ? get_object_vars($json):$json;
        $arr = array();
        $val = '';

        foreach ($json as $key => $value) {
              if (is_array($value)|| is_string($value)) {
                  $val = $value;
              } else {
                 if(is_object($value)) {
                    $val = self::makeObjToArray($value);
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
   
        $filename = HTXX_LIB_PATH . date('Y-m-d') . '.log';
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

    public static function makeXJTaxAffairsNoticeArr(array $ret,array $param) {
        $arr = array();
        foreach ($ret as $key => $value) {
            $url = str_replace('index.htm','',$param['url']);
            $url.= str_replace('./','',$value['url']);
            
            if (strpos($value['date'],"]")) {
                $date = str_replace(']','',$value['date']);
                $date  = str_replace('[','',$date);
                $value['date'] = $date;
            }
            $value['url'] = $url;
            $value['_as'] = md5($url);
            $arr[] = $value;
        }

        return $arr;
    }
    
    public static function makeCNTaxAffairsNoticeArr(array $ret,array $param) {
        $arr = array();
        foreach ($ret as $key => $value) {
            if (empty($value['title'])) continue;
            if($param['task_sn'] == '10006'){ //10005
                $url = str_replace('n810341/n810760/index.html','',$param['url']);
            }elseif($param['task_sn'] == '10002'){
                $url = str_replace('n810214/n810606/index.html','',$param['url']);
            }else{
                $url = str_replace('n810341/n810755/index.html','',$param['url']);
            }
            $url.= str_replace('../../','',$value['url']);
            $date = str_replace(']','',$value['date']);
            $date  = str_replace('[','',$date);
            $value['date'] = date('Y',time()).'-'.$date;
            $value['url'] = $url;
            $value['_as'] = md5($url);
            $arr[] = $value;
        }
        return $arr;
    }



    public static function makeXJTaxGuideArr(array $ret,array $param) {
        $arr = array();
        foreach ($ret as $key => $value) {
            $url = str_replace('index.htm','',$param['url']);
            $url.= str_replace('./','',$value['url']);

            if (strpos($value['date'],"]")) {
                $date = str_replace(']','',$value['date']);
                $date  = str_replace('[','',$date);
                $value['date'] = $date;
            }
            $value['url'] = $url;
            $value['_as'] = md5($url);
            $arr[] = $value;
        }

        return $arr;
    }
    /*
     * 按照总页数大小 来拼接url
     * $url  str
     * $pagesize  int
     */
    public static function makePageUrl($url,$pagesize) {
        $arr = array();
        $url = str_replace('index.htm','',$url);
        for ($i=0; $i <$pagesize ; $i++) {
            if($i == 0){
                $arr[] = $url.'index.htm';
            }else{
                $arr[] = $url.'index_'.$i.'.htm';
            }
        }
        return $arr;
    }
    /*
     * 重构获取内容的二维数组
     */
    public static function  makeNewArray(array $array){
        if(is_array($array)){
            $newarr = [];
            array_walk_recursive($array,function(&$v) use (&$newarr){
                $newarr[] =$v;
            });
            return $newarr;
        }
        return $newarr = array();
    }

    /*
     *过滤内容里的img标签 填补src完整路径
     * img的url 前缀$url
     */
    public static function getImgUrl($url,$info){
        $success = self::replaceSrc($url,$info);
        if($success){
            $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';//匹配img标签的正则表达式
            preg_match_all($preg, $info, $allImg);//这里匹配所有的img
            if(! empty($allImg)){
                $suffix =  IMG_URL.'/'; //前缀
                $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
                $info = preg_replace($pregRule, '<img src="'.$suffix.'${1}">', $info);
            }
            //print_r($_SERVER['HTTP_HOST']);
            return $info;          
        }
    }
    /*
     * 远程获取图片存入本地
     */
    public static function replaceSrc($url,$info){
         $url = pathinfo($url);
         $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';//匹配img标签的正则表达式
         preg_match_all($preg, $info, $allImg);//这里匹配所有的img
         if(! empty($allImg)){
            $suffix = $url['dirname'].'/'; //前缀
            $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
            $info = preg_replace($pregRule, '<img src="'.$suffix.'${1}">', $info);

            $pregsrc = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/i';//匹配img标签的正则表达式
            preg_match_all($pregsrc, $info, $allImgsrc);//这里匹配所有的img
            foreach ($allImgsrc[1]  as $src){
                $filename = end(explode('/',$src));
                $filenames[] = IMG_URL.'/'.self::getImg($src ,$filename);
                sleep(1);
            }
        }
        return true;
    }
    /*
     *过滤内容里的附件文字
     * 
     */
    public static function getFujianContent($info){
        $str = $info;
        $str = preg_replace('/附件：.*/i', "", $str);
        return $str;
    }
    /*
     * 通过curl来获取图片 
     */
    public static function getImg($url = "", $filename = ""){
        if ($filename == "") {
            //如果没有指定新的文件名
            $ext = strrchr($url, ".");
            //得到$url的图片格式
            if ($ext != ".gif" && $ext != ".jpg" && $ext != ".png"):return false;
            endif;
            //如果图片格式不为.gif或者.jpg.png，直接退出
            $filename = date("dMYHis") . $ext;
            //用天月面时分秒来命名新的文件名
        }         
        $hander = curl_init();
        $fp = fopen(DATA_PATH.'/'.$filename,'wb');
        curl_setopt($hander,CURLOPT_URL,$url);
        curl_setopt($hander,CURLOPT_FILE,$fp);
        curl_setopt($hander,CURLOPT_HEADER,0);
        curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
        //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
        curl_setopt($hander,CURLOPT_TIMEOUT,60);
        /*$options = array(
            CURLOPT_URL=>$url,
            CURLOPT_FILE => $fp,
            CURLOPT_HEADER => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_TIMEOUT => 60
        );
        curl_setopt_array($hander, $options);*/
        curl_exec($hander);
        curl_close($hander);
        fclose($fp);
        unset($url);
        return  $filename;           
    }

    
}
