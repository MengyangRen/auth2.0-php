<?php
class DevQurReqBody extends body
{
    /**
     * [系统版本号16B]
     * @var integer
     */
    private static $lengthCorrect = 121;   
    
    /**
     * [系统版本号40B]
     * @var integer
     */
    private static $lengthCorrect2= 145; 
    
    /**
     * [系统温度 4B]
     * @var string
     */
    private $sysTemp = "";

    /**
     * [CPU使用率 4B]
     * @var string
     */
    public $cpuUsg = "";

    /**
     * [内存容量 4B]
     * @var string
     */
    public $memTotal = "";

    /**
     * [剩余内存 4B]
     * @var string
     */
    public $memFree = "";

    /**
     * [内部存储容量 8B]
     * @var string
     */
    public $flashTotal = "";

    /**
     * [内部存储余量 8B]
     * @var string
     */
    public $flashFree = "";

    /**
     * [sdcard存储容量 8B]
     * @var string
     */
    public $sdTotal = "";

    /**
     * [sdcard存储余量 8B]
     * @var string
     */
    public $sdFree = "";
    
    /**
     * [车载传感器的连接状态 4B]
     * @var string
     */
    public $vmConnState = "";

    /**
     * [车载传感器的连接数量 4B]
     * @var integer
     */
    public $vmConnCount = 0;

    /**
     * [定位模块运行状态 1B]
     * @var null
     */
    public $positionRunState = null;

    /**
     * [数传电台运行状态 1B]
     * @var null
     */
    public $radioRunState = null;
    
    /**
     * [wifi模块运行状态 1B]
     * @var null
     */
    public $wifiRunState = null;

    /**
     * [ 3G模块运行状态 1B ]
     * @var null
     */
    public $_3GRunState = null;

    /**
     * [zigbee模块运行状态 1B ]
     * @var null
     */
    public $zigbeeRunState = null;

    /**
     * [系统设备类型 4B ]
     * @var null
     */
    public $sysType = null;

    /**
     * [系统编号 32B ]
     * @var string
     */
    public $sysNum = "";

    /**
     * [ 系统版本 16B]
     * @var string
     */
    public $sysVer = "";
    
    /**
     * [系统运行时间 8B]
     * @var string
     */
    public $sysRunTime = "";

    /**
     * [parseLine description]
     * @param  [type] $body [description]
     * @return [type]       [description]
     */
    public function parseLine($body) {

        $this->setmContent($body['body']);
        $len = mb_strlen($this->mContent)/2;
        
        if ($len != self::$lengthCorrect  && $len !=  self::$lengthCorrect2) {
            $this->_print("内容长度" + $len + "不正确！");
            $this->setIsAvailable(false);
			exit;
            return;
        }

        $fileds = array();
        if ($len == self::$lengthCorrect ){
            $fieldLengthes = array(8,8,8,8,16,16,16,16,8,8,2,2,2,2,2,8,64,32,16);
            $fileds = $this->splitFileds($fieldLengthes);
        }

        if ($len == self::$lengthCorrect2){
            $fieldLengthes = array(8,8,8,8,16,16,16,16,8,8,2,2,2,2,2,8,64,80,16);
            $fileds = $this->splitFileds($fieldLengthes);
        }
        //系统运行温度
        $this->sysTemp = Util::hexToDecString($fileds[0]);
        //cpu使用率
        $this->cpuUsg = Util::hexToDecString($fileds[1]);
        //内存总量
        $this->memTotal = Util::hexToDecString($fileds[2]);
        //内存余量
        $this->memFree = Util::hexToDecString($fileds[3]);
        //内部存储总量
        $this->flashTotal = Util::hexToDecString($fileds[4]);
        //内部存储余量
        $this->flashFree = Util::hexToDecString($fileds[5]);
        //sd存储总量
        $this->sdTotal = Util::hexToDecString($fileds[6]);
        //sd存储余量
        $this->sdFree = Util::hexToDecString($fileds[7]);
        //车载传感器连接状态 每一位表示一个设备
        $this->vmConnState = Util::hexToDecString($fileds[8]);
        //车载传感器的数量
        $this->vmConnCount = Util::hexToDecString($fileds[9]);
        //定位模块运行状态
        $this->positionRunState = Util::hexToDecString($fileds[10]);
        //数字电台运行状态
        $this->radioRunState = Util::hexToDecString($fileds[11]);
        //wifi模块运行的状态
        $this->wifiRunState = Util::hexToDecString($fileds[12]);
        //3G模块运行状态
        $this->_3GRunState = Util::hexToDecString($fileds[13]);
        //ZIGBEE运行状态
        $this->zigbeeRunState = Util::hexToDecString($fileds[14]);
        //系统类型
        $this->sysType = $this->formatSysType(hexdec($fileds[15]));
        //系统编号
        $this->sysNum = Util::hexToString($fileds[16]);
        //系统版本
        $this->sysVer = Util::hexToString($fileds[17]);
        //系统运行时间
        $this->sysRunTime = $this->formatSysRunTime(hexdec($fileds[18]));
        //echo $this;
    }

    /**
     * [格式化设备类型]
     * @param  [type] $n [description]
     * @return [type]    [description]
     */
    public function formatSysType($n) {
        return $this->deviceType[$n];
    }

    /**
     * [格式化系统运行时间]
     * @param  [type] $n [description]
     * @return [type]    [description]
     */
    public function formatSysRunTime($t) {
        $d = (int) ($t / (3600 * 24));
        $h = (int) (($t % (3600 * 24)) / 3600);
        $m = (int) (($t % 3600) / 60);
        $sec = (int) ($t % 60);
        return $d.'d ,'.$h.'h:'.$m.'m:'.$sec.'s';
    }

    /**
     * [获取系统的温度]
     * @return [type] [description]
     */
    public function getSysTemp() {
        return $this->sysTemp;
    }

   /**
     * [获取数据格式化为json]
     * @return [type] [description]
     */
	public function getBodyToJson() {
		$options =  array();
        $option['sysTemp'] = $this->sysTemp;
        $option['cpuUsg'] = $this->cpuUsg;
        $option['memTotal'] = $this->memTotal;
        $option['memFree'] = $this->memFree;
        $option['flashTotal'] = $this->flashTotal;
        $option['flashFree'] = $this->flashFree;
        $option['sdTotal'] = $this->sdTotal;
        $option['sdFree'] = $this->sdFree;
        $option['vmConnState'] = $this->vmConnState;
        $option['vmConnCount'] = $this->vmConnCount;
        $option['positionRunState'] = $this->positionRunState;
        $option['radioRunState'] = $this->radioRunState;
        $option['wifiRunState'] = $this->wifiRunState;
        $option['3GRunState'] = $this->_3GRunState;
        $option['zigbeeRunState'] = $this->zigbeeRunState;
        $option['sysType'] = $this->sysType;
        $option['sysNum'] = $this->sysNum;
        $option['sysVer'] = $this->sysVer;
        $option['sysRunTime'] = $this->sysRunTime;
        return json_encode($option,true);
	}

}