<?php
class PosiQurReqBody extends body
{

    private static $lengthCorrect = 122;   
    /**
     * [定位状态 1B] 
     * @var string
     */
    private $locationStatus = 0;

    /**
     * [卫星总数 4B]
     * @var string
     */
    public $satelliteTotalCount = 0;

    /**
     * [卫星可用数量 4B]
     * @var string
     */
    public $satelliteAvilableCount = 0;

    /**
     * [PDOP 16B]
     * @var string
     */
    public $pdop = "";

    /**
     * [HDOP 16B]
     * @var string
     */
    public $hdop = "";

    /**
     * [vdop 16b]
     * @var string
     */
    public $vdop = "";

    /**
     * [tdop 16B]
     * @var string
     */
    public $tdop = "";

    /**
     * [纬度 16B ]
     * @var string
     */
    public $latitude= "";
    
    /**
     * [经度 16B]
     * @var string
     */
    public $longitude = "";

    /**
     * [高度 16B]
     * @var integer
     */
    public $height = 0;

    /**
     * [查分类型 1B]
     * @var null
     */
    public $diffType = -1;

    /**
     * [类型]
     * @var array
     */
    private static $DIFF_TYPE_STRING = array(
            "0: Fix not valid",
            "1: GPS fix",
            "2: Differential GPS fix, OmniSTAR VBS",
            "3：RTK float solution",
            "4: Real-Time Kinematic, fixed integers",
            "5: Real-Time Kinematic, float integers, OmniSTAR XP/HP or Location RTK");


    
    /**
     * [parseLine description]
     * @param  [type] $body [description]
     * @return [type]       [description]
     */
    public function parseLine($body) {

        $this->setmContent($body['body']);
        
        $len = mb_strlen($this->mContent)/2;
        
        if ($len != self::$lengthCorrect ) {
            $this->_print("内容长度" + $len + "不正确！");
            $this->setIsAvailable(false);
            return;
        }


        $fileds = array();

        $fieldLengthes = array(2,8,8,32,32,32,32,32,32,32,2);
        
        $fileds = $this->splitFileds($fieldLengthes);

        //定位状态
        $this->locationStatus = Util::hexToDecString($fileds[0]);

        //卫星总数
        $this->satelliteTotalCount = Util::hexToDecString($fileds[1]);

        //可用卫星数
        $this->satelliteAvilableCount = Util::hexToDecString($fileds[2]);
        
        //PDOP
        $this->pdop = Util::hexToString($fileds[3]);

        //HDOP
        $this->hdop = Util::hexToString($fileds[4]);

        //VDOP
        $this->vdop = Util::hexToString($fileds[5]);

        //TDOP
        $this->tdop = Util::hexToString($fileds[6]);

        //纬度
        $this->latitude = Util::hexToString($fileds[7]);

        //经度
        $this->longitude = Util::hexToString($fileds[8]);
        
        //高度
        $this->height = Util::hexToString($fileds[9]);

        //差分类型
        $this->diffType = Util::hexToDecString($fileds[10]);

    }

    /**
     * [获取数据格式化为json]
     * @return [type] [description]
     */
	public function getBodyToJson() {
		$options =  array();
        $option['locationStatus'] = $this->locationStatus;
        $option['satelliteTotalCount'] = $this->satelliteTotalCount;
        $option['satelliteAvilableCount'] = $this->satelliteAvilableCount;
        $option['pdop'] = $this->pdop;
        $option['hdop'] = $this->hdop;
        $option['vdop'] = $this->vdop;
        $option['vdop'] = $this->vdop;
        $option['latitude'] = $this->latitude;
        $option['longitude'] = $this->longitude;
        $option['height'] = $this->height;
        $option['diffType'] = $this->diffType;
        return json_encode($option,true);
	}

}