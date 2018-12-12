<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  基站 -通讯协议解析库 -消息体基类
 * @author v.r
 * @package         web服务与基站协议解析库
 * @subpackage     lib.body.Body
 */ 
class body
{    

    /** 
     * [消息体的长度]
     * @var integer
     */
    protected  $mLength = 0;
    
    /**
     * [消息的内容，null或者""该消息没有消息体]
     * @var string
     */
    protected $mContent = "";

     /**
     * [消息体内容格式是否合法]
     * @var string
     */
    protected $mContentAvailable = true;

    /**
     * [设备类型]
     * @var array
     */
    protected $deviceType = array('1'=>'E515','2'=> 'E525');


    /**
     * [setmContent description]
     * @return [type] [description]
     */
    public function setmContent($body) {
        $this->mContent = $body;
    }

    /**
     * 消息体格式是否正确。
     * 
     * @return ture，正确；false，错误。
     */
    public function isAvailable() {
        return $this->mContentAvailable;
    }

    /**
     * 设置消息体内容格式是否正确。
     * 
     * @param available
     *            true 正确； false 错误。
     */
    protected function setIsAvailable($available) {
        $this->mContentAvailable = $available;
    }

    /**
     * [无消息体]
     * @return [type] [description]
     */
    public function toLine($body) {
        return true;
    }

    /**
     * [有消息体]
     * @return [type] [description]
     */
    public function parseLine($body) {
        return true;
    }

    /**
     * [splitFileds description]
     * @return [type] [description]
     */
    public function splitFileds(array $fieldLengthes) {
        if (empty($this->mContent) || $fieldLengthes == null) {
            return false;
        }

        $len = count($fieldLengthes);
        $start = 0;
        $fieldArray = array();

        for ($i = 0; $i < $len; $i++) { 
            $fieldArray[$i] = mb_substr($this->mContent,$start,$fieldLengthes[$i]);
            $start += $fieldLengthes[$i];
        }

        return $fieldArray;
    }


    /**
     * [打印输出]
     * @return [type] [description]
     */
    public function _print($msg) {
		 Util::_writeLog($msg); 
    }
}

