<?php
/**
 * 工具容器
 * @author v.r
 */
class Util {





    /**
     * [生成密码秘钥]
     * @param  integer $bit [description]
     * @return [type]       [description]
     */
    public static function makePwdSalt($bit = 6){
        if ($bit < 6) $bit = 6; 
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $salt = '';
        for ($i = 0; $i < $bit; $i++) {
            $salt .= substr($string, mt_rand(0,61), 1);
        }
        return $salt;
    }

    /**
     * [生成加密后密码]
     * @param  integer $bit [description]
     * @return [type]       [description]
     */
    public static function makeEncryptPwd($pwd = NULL,$salt = NULL,$falg = true) {
        if ($falg)  $pwd = md5($pwd);
        return md5($pwd.$salt);
    }

    /**
     * [检查是否数字]
     * @return boolean [description]
     */
    public static function isInt($param) {
      return preg_match("/^\d*$/",$param);
    } 

    /**
     * [isEmail 是否email]
     * @return boolean [description]
     */
    public static function isEmail($email) {
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if (preg_match($pattern,$email)) {  
                return true;
            }else{  
                return false;   
        }
    }

    /**
     * [isPhone 是否为手机号]
     * @return boolean [description]
     */
    public static function isPhone($phone) {
        $pattern = "/^1[34578]{1}\d{9}$/";
        if (preg_match($pattern,$phone)) {  
                return true;
            }else{  
                return false;  
        }  
    }

    /**
     * [检查数组项是否为空]
     * @return [type] [description]
     * 
     */
    public static function chcekArrItemEmpty($arr) {
        @array_walk($arr, function($v,$k) use(&$arr){ 
            $isEmpty = false;
            if (empty($v)) {
                $isEmpty = true;
            }
            if ($isEmpty) {
               $arr = null; 
            }
        });
        return $arr;
    } 

    /**
     * [检查数组中值是否相等]
     * @param  [type] $arr  [description]
     * @param  [type] $key1 [description]
     * @param  [type] $key2 [description]
     * @return [type]       [description]
     * 模式:
     * _chcekArrValEqual($data,$keys=array('k1'=>'mac','k'=>'uid','v'=>'1000')) 带条件
     * _chcekArrValEqual($data,$keys=array('k1'=>'mac','k'=>'','v'=>''));不带条件 
     */
    public static function chcekArrValEqual($data,$keys=array('k1'=>'mac','k'=>'uid','v'=>'1000')) {
        $arr = array();
        foreach ($data as $v) {
            $k1 = $keys['k1'];
            if (!empty($keys['v']) && !empty($keys['k'])) { //带条件，同uid下mac
                $key = $keys['k'];
                $kv = $keys['v'];
                if ($v[$key] == $kv) {
                    $arr[] = $v[$k1];
                }
            } else {  //无条件，比如，维护码是否唯一
                $arr[] = $v[$k1];
            }
        }
        
        $len = count($arr);

        if ($len == count(array_unique($arr))) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * [获取base64图片数据类型]
     * @return [type] [description]
     */
    public static function getBase64ImgType($base64Data) {
       // $type = array('jpeg'=>'.jpg','gif'=>'.gif','png'=>'.png');
        $baseimg =explode(',',$base64Data);
        $v  =  explode('/', $baseimg[0]);
        $info = explode(';', $v[1]);
        return $info[0];
    }

    /**
     * [getBase64图片数据的大小]
     * @param  [type] $base64Data [description]
     * @return [type]             [description]
     */
    public static function getBase64ImgSize($base64Data) {
        return mb_strlen($base64Data)/1024;  //kb的数据
    }

    /**
     * [获取base64数据]
     * @param  [type] $base64Data [description]
     * @return [type]             [description]
     */
    public static function getBase64ImgData($base64Data) {
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64Data, $result);
        return str_replace($result[1], '', $base64Data);
    }

    /**
     * [writeBase64ImgToDiske description]
     * @param  [type] $base64Data [description]
     * @param  [type] $fullname   [description]
     * @return [type]             [description]
     */
    public static function writeBase64ImgToDiske($base64Data,$fullname)
    {
        $img = base64_decode($base64Data);
        if ( !file_put_contents( $fullname , $img ) ) {
            return false;
        }
        return $fullname;
    }
    /**
     * [makeAvatarDir 算法头像目录]
     * @return [type] [description]
     */
    public static function makeAvatarDir($uid,$size,$type = 'jpeg' ) {
        $size = in_array($size, array('big', 'middle', 'small')) ? $size : 'small';
        $types = array('jpeg'=>'.jpg','gif'=>'.gif','png'=>'.png');
        $uid  = sprintf("%09d",abs(intval($uid)));
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $dir = 'avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
        $file = substr($uid, -2)."_avatar_$size".$types[$type];
        return array('dir'=>$dir,'file'=>$file);
    }

    /**
     * [IsExistAvatar 是否存在头像]
     * @return [type] [description]
     */
    public static function IsExistAvatar($uid) {
        $uid  = sprintf("%09d",abs(intval($uid)));
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $dir = 'avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
        $types = array('jpeg'=>'.jpg','gif'=>'.gif','png'=>'.png');
        $dir =  'avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
        $file = substr($uid, -2)."_avatar_small";
        $avatar = '';
        

        $dir = str_replace('avatar','', $dir);
        $avatar = $dir.$file.$type;

        //通过网络检查图片是否存在
        

       /* foreach ($types as $type) {
            if (file_exists(DATA_PATH.$dir.$file.$type)) {
                $dir = str_replace('avatar','', $dir);
                $avatar = $dir.$file.$type;
            }
        }*/

        if (empty($avatar)) 
            return '/default/default.png';
        else
            return $avatar;
    }

    /**
     * [删除头像]
     * @return [type] [description]
     */
    public static function removeAvatar($uid) {
        $uid  = sprintf("%09d",abs(intval($uid)));
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $dir = 'avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
        $types = array('jpeg'=>'.jpg','gif'=>'.gif','png'=>'.png');
        $dir =  'avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
        $file = substr($uid, -2)."_avatar_small";
        foreach ($types as $type) {
            $path = DATA_PATH.$dir.$file.$type;
            if (file_exists($path)) 
               @unlink($path);
        }
    }

    /**
     * [createAvatarDir 创建头像目录]
     * @return [type] [description]
     */
    public static function createAvatarDir($dir = NULL) {
      if (is_dir($dir)) 
            return $dir;
        else 
            mkdir($dir,0777,true);
        return $dir;
    }

    /**
     * [生成场景图片目录]
     * @param  [type] $hid [description]
     * @return [type]      [description]
     */
    public static function makeSceneDateDir($hid) {
        $hash  = sprintf("%09d",abs(intval($hid)));
        $dir = DATA_PATH.'Scene/'.$hash.'/'.date('Y').'/'.date('m').'/'.date('d').'/'; 
        if (is_dir($dir)) 
            return $dir;
        else 
            mkdir($dir,0777,true);
        return $dir;
    }

    public static function makSteArrayToMap($arr){
        $kas = array();
        $len = count($arr['title']);
        for ($i=0; $i <$len ; $i++) { 
            $key = 1001+$i;
            $kas[$key] =array(
                    $arr['title'][$i],
                    $arr['asval'][$i],
                    $arr['color'][$i],
                    1001+$i
            );
        }
        return $kas;
    }

    /**
     * [makeArrayToKeyVal description]
     * @return [type] [description]
     */
    public static function makeArrayToKeyVal($keyarr,$valarr) {
        $kas = array(); 
        $len = count($keyarr);
        for ($i=0; $i <$len ; $i++) { 
            $kas[$keyarr[$i]] = $valarr[$i];
        }
        return  $kas;
    }
    
    /**
     * [报修图片目录生成]
     * @param  [type] $picName 小区名字
     */
    public static function makeRepairsDateDir($hid) {
        $hash  = sprintf("%09d",abs(intval($sid)));
        $dir = IMG_PATH.'Repairs/'.$hash.'/'.date('Y').'/'.date('m').'/'.date('d').'/'; 
        if (is_dir($dir)) 
            return $dir;
        else 
            mkdir($dir);
            return $dir;
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
        var_dump($json); die;
        $arr = array();
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
	 * [店铺商品图片存放]
	 * @param  [type] $picName 图片名
	 * @return [type] $sid    店铺ID  
	 */
	public static function makeDateDir($picName,$sid) {
		$hash  = sprintf("%09d",abs(intval($sid)));
    	return  'Shop/Goods/'.$hash.'/'.date('Y').'/'.date('m').'/'.date('d').'/'.$picName; 
	}

    /**
     * [生成店铺评论图片生成]
     * @param  [type] $picName 图片名
     * @return [type] $sid    店铺ID  
     */
    public static function makeCmtDir($picName,$sid) {
        $hash  = sprintf("%09d",abs(intval($sid)));
        return  'shop/cmt/'.$hash.'/'.date('Y').'/'.date('m').'/'.date('d').'/'.$picName; 
    }

	/**
	 * [自动识别编码并转换UTF8编码]
	 * @param  [type] $data   [description]
	 * @param  string $output [description]
	 * @return [type]         [description]
	 */
	public static function array_iconv($data,$output='utf-8') {
        $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');   //可以出现先编码
        $encoded = mb_detect_encoding($data, $encode_arr);      
         if (!is_array($data)) {      
            return mb_convert_encoding($data, $output, $encoded);    
         }  else {  
            foreach ($data as $key=>$val) 
            {  
                if (!is_array($val)){  
                    $data[$key] = mb_convert_encoding($data, $output, $encoded);     
                 }  else  {
                    $data[$key] = self::array_iconv($val,$output);                     
                }  
            }  
             return $data;  
        }  
	}

	/**
	 * [isUtf8 是否utf8编码]
	 * @param  [type]  $str [description]
	 * @return boolean      [description]
	 */
    public static function IsUtf8($str) {
		$c=0; $b=0;
		$bits=0;
		$len=strlen($str);
		for($i=0; $i<$len; $i++){
			$c=ord($str[$i]);
			if($c > 128){
				if(($c >= 254)) return false;
				elseif($c >= 252) $bits=6;
				elseif($c >= 248) $bits=5;
				elseif($c >= 240) $bits=4;
				elseif($c >= 224) $bits=3;
				elseif($c >= 192) $bits=2;
				else return false;
				if(($i+$bits) > $len) return false;
				while($bits > 1){
					$i++;
					$b=ord($str[$i]);
					if($b < 128 || $b > 191) return false;
					$bits--;
				}
			}
		}
		return true;
	}

	/**
	 * [生成唯一的UUID]
	 * @return [type] [description]
	 */
    public static function UUID() {
		$charid = md5(uniqid(mt_rand(), true));
		$hyphen = chr(45);// "-"
		$uuid = chr(123)// "{"
		.substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12)
		.chr(125);// "}"
		return $uuid;
	}

	/**
	 * [判断值是否存在一个数组]
	 * @param [type] $value [description]
	 * @param [type] $list  [description]
	 * 
	 */
	public static function In($value, $list)
    {
        return (is_array($list) && in_array($value, $list)) ? true : false;
    }

    /**
     * [是否是IP]
     * @param [type] $ip [description]
     * ipv4
     */
    public static function IsIp($ip)
    {
        return ((false === ip2long($ip)) || (long2ip(ip2long($ip)) !== $ip)) ? false : true;
    }

    /**
     * [获取客户端IP]
     * @return [type] [description]
     * 支付负载均衡下获取客户端IP
     */
    public static function getCustomerIp() {
		$ip = $_SERVER['REMOTE_ADDR']; 
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $ip;
	}
 
 /**
	*  [根据两点间的经纬度计算距离]
	*  @param float $lat 纬度值
	*  @param float $lng 经度值
	* longitude  经度    latitude  纬度
	* 阆中	105.97  31.75
	* 成都	104.06	 30.67
	* reteurn array(2) { ["m"]=> float(214505.29341394) ["km"]=> float(214.50529341394) } 
	*/
	public static  function Distance($lat1, $lng1, $lat2, $lng2)
	 {
		 $earthRadius = 6367000;  //地球半径
		 $lat1 = ($lat1 * pi() ) / 180;
		 $lng1 = ($lng1 * pi() ) / 180;

		 $lat2 = ($lat2 * pi() ) / 180;
		 $lng2 = ($lng2 * pi() ) / 180;
		 $calcLongitude = $lng2 - $lng1;
		 $calcLatitude = $lat2 - $lat1;
		 $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
		 $calculatedDistance = $earthRadius * $stepTwo;
		 $rice = $calculatedDistance;

		  return array(
					'm'=>$rice,
					'km'=>$rice/1000
		  );
	 }

    public static function removeTagAndSplite($input, $spliter=null){
        if(empty($input)) return null;
        if(empty($spliter)) {
            $str = preg_replace('/[\n\r\t]/', ' ', $input);
            $str = str_replace(" ", "", $str);
            return trim( strip_tags($str) );
        }
        $output = array();
        $delimiter = "###";
        $str = preg_replace('#'.$spliter.'#', $spliter.$delimiter, $input);
        $str = trim( strip_tags($str) ); //remove tags
        $str = preg_replace('/[\n\r\t]/', ' ', $str); //replace \n to space

        $str_a = explode($delimiter, $str);
        
        foreach ($str_a as $value) {
            if(!empty($value)) array_push($output, trim($value));
        }
        return $output;
    }
    

	 /**
     * 字符转换，防止XSS等方面的问题
     * @param string $str
     * @return string
     */
    public static function _TagFilter($str)
    {
        $str = str_ireplace( "javascript" , "j&#097;v&#097;script", $str );
        $str = str_ireplace( "alert"      , "&#097;lert"          , $str );
        $str = str_ireplace( "about:"     , "&#097;bout:"         , $str );
        $str = str_ireplace( "onmouseover", "&#111;nmouseover"    , $str );
        $str = str_ireplace( "onclick"    , "&#111;nclick"        , $str );
        $str = str_ireplace( "onload"     , "&#111;nload"         , $str );
        $str = str_ireplace( "onsubmit"   , "&#111;nsubmit"       , $str );
        $str = str_ireplace( "<script"      , "&#60;script"          , $str );
        $str = str_ireplace( "document."  , "&#100;ocument."      , $str );
        return $str;
    }

    /**
     * [过滤文本中html+js代码]
     * @param [type] $document [description]
     */
    public static function STRIPTEXT($document)
    {
        $search = array("'<script[^>]*?>.*?</script>'si", // strip out javascript
            "'<[\/\!]*?[^<>]*?>'si", 
            "'([\r\n])[\s]+'",
            "'&(quot|#34|#034|#x22);'i", // replace html entities
            "'&(amp|#38|#038|#x26);'i", // added hexadecimal values
            "'&(lt|#60|#060|#x3c);'i",
            "'&(gt|#62|#062|#x3e);'i",
            "'&(nbsp|#160|#xa0);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "'&(reg|#174);'i",
            "'&(deg|#176);'i",
            "'&(#39|#039|#x27);'",
            "'&(euro|#8364);'i", // europe
            "'&a(uml|UML);'", // german
            "'&o(uml|UML);'",
            "'&u(uml|UML);'",
            "'&A(uml|UML);'",
            "'&O(uml|UML);'",
            "'&U(uml|UML);'",
            "'&szlig;'i",
        );
        $replace = array("",
            "",
            "\\1",
            "\"",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            chr(174),
            chr(176),
            chr(39),
            chr(128),
            "ä",
            "ö",
            "ü",
            "Ä",
            "Ö",
            "Ü",
            "ß",
        );
        $text = preg_replace($search, $replace, $document);
         return $text;
    }


    public static function Pinyin($_String, $_Code='gb2312') {
        $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" .
            "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" .
            "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" .
            "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" .
            "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" .
            "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" .
            "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" .
            "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" .
            "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" .
            "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" .
            "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" .
            "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" .
            "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" .
            "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" .
            "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" .
            "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";

        $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" .
            "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" .
            "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" .
            "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" .
            "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" .
            "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" .
            "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" .
            "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" .
            "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" .
            "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" .
            "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" .
            "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" .
            "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" .
            "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" .
            "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" .
            "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" .
            "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" .
            "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" .
            "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" .
            "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" .
            "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" .
            "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" .
            "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" .
            "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" .
            "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" .
            "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" .
            "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);

        $_Data = (PHP_VERSION >= '5.0') ? array_combine($_TDataKey, $_TDataValue) : \Util::_Array_Combine($_TDataKey, $_TDataValue);
        arsort($_Data);
        reset($_Data);

        if ($_Code != 'gb2312')
            $_String = \Util::_U2_Utf8_Gb($_String);
        $_Res = '';
        for ($i = 0; $i < strlen($_String); $i++) {
            $_P = ord(substr($_String, $i, 1));
            if ($_P > 160) {
                $_Q = ord(substr($_String, ++$i, 1));
                $_P = $_P * 256 + $_Q - 65536;
            }
            $_Res .= \Util::_Pinyin($_P, $_Data);
        }
        return preg_replace("/[^a-z0-9]*/", '', $_Res);
    }

    public static function  _Pinyin($_Num, $_Data) {
        if ($_Num > 0 && $_Num < 160)
            return chr($_Num);
        elseif ($_Num < -20319 || $_Num > -10247)
            return '';
        else {
            foreach ($_Data as $k => $v) {
                if ($v <= $_Num)
                    break;
            }
            return $k;
        }
    }

     public static function  _U2_Utf8_Gb($_C) {
        $_String = '';
        if ($_C < 0x80)
            $_String .= $_C;
        elseif ($_C < 0x800) {
            $_String .= chr(0xC0 | $_C >> 6);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x10000) {
            $_String .= chr(0xE0 | $_C >> 12);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x200000) {
            $_String .= chr(0xF0 | $_C >> 18);
            $_String .= chr(0x80 | $_C >> 12 & 0x3F);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        return iconv('UTF-8', 'GB2312', $_String);
    }

    public static function _Array_Combine($_Arr1, $_Arr2) {
        for ($i = 0; $i < count($_Arr1); $i++)
            $_Res[$_Arr1[$i]] = $_Arr2[$i];
        return $_Res;
    }
    

}