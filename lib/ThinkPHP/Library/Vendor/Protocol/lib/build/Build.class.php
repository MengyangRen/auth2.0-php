<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  基站 -通讯协议解析库 
 * @author v.r
 * @package          web服务与基站协议解析库
 * @subpackage      analyse.class.php
 */ 

class Build 
{
    
    const HEAD = 'head';
    const SOURCE = 'source';
    const NS = 'ns';
    const NAME = 'name';
    const LENGTH = 'length';
    const BODY = 'body';
    const CODE = 'code';

    /**
     * [创建协议字串]
     * @return [type] [description]
     */
    public static function createPltToArray(MessageName $name,$cmd) {
    	$content = array();
        $content[self::HEAD] = self::createHead();
        $content[self::SOURCE] = self::createSource();
        $content[self::NS] = self::createNS();
        $content[self::NAME] = self::createName($name,$cmd);
        $body = self::createBody($cmd);
        $content[self::LENGTH] =self::createLen($body);
        $content[self::BODY] = $body;
        $str = Util::ArrayToStr($content);
        $content[self::CODE] =self::createCode($str);
        return Util::ArrayToStr($content);
    }

     /**
     * [创建协议数组]
     * @return [type] [description]
     */
    public static function createPltToHexStr(MessageName $name,$cmd) {
        $content = array();
        $content[self::HEAD] = self::createHead();
        $content[self::SOURCE] = self::createSource();
        $content[self::NS] = self::createNS();
        $content[self::NAME] = self::createName($name,$cmd);
        $body = self::createBody($cmd);
        $content[self::LENGTH] =self::createLen($body);
        if (!empty($body)) $content[self::BODY] = $body;
        $str = Util::ArrayToStr($content);
        $content[self::CODE] =self::createCode($str);
        return array_values($content);
    }

    /**
     * [创建名] 7EE3
     * @return [type] [description]
     */
    public static function createHead() {
        return PLT_HADE;    
    }

     /**
     * [创建编号] 000,  2字节
     * @return [type] [description]
     */
    public static function createNS() {
        return PLT_NS; 
    }

    /**
     * [定义数据源]
     * @return [type] [description]
     */
    public static function createSource() {
        return MessageSource::HTTP;
    }

    /**
     * [创建指令名]
     * @return [type] [description]
     */
    public static function createName(MessageName $name,$cmd) {
        $class = new ReflectionClass($name); 
        $attr = $class->getConstants();
        return Util::parseCmdName($attr,$cmd);
    }

    /**
     * [创建code码]
     * @param  [type] $ptl [description]
     * @return [type]      [description]
     */
    public static function createCode($ptl) {
    	return code::_create($ptl);
    } 

    /**
     * [创建body]
     * @return [type] [description]
     */
    public static function createBody($cmd) {
    	return null;
    }
    /**
     * [创建长度]
     * @return [type] [description]
     */
    public static function createLen($ptl) {
        $len = 2+2+2+2+2+2+mb_strlen($ptl);
        if ($len > 65535) return false;
        $hex = Util::decToHex($len);
        $byt = mb_strlen($hex);
        $lb = str_repeat(hexdec(null),4-$byt);
        return $lb.$hex;
    }
}