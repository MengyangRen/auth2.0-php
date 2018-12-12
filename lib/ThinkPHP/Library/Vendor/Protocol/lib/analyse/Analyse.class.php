<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  基站 -通讯协议解析库 
 * @author v.r
 * @package         web服务与基站协议解析库
 * @subpackage      protocol.class.php
 */
 
 class analyse 
{
    
    const HEAD = 'head';
    const SOURCE = 'source';
    const NS = 'ns';
    const NAME = 'name';
    const LENGTH = 'length';
    const BODY = 'body';
    const CODE = 'code';

    /**
     * [获取分割未知]
     * @return [type] [description]
     */
    public static function getCutPostion($pos=0,$size=4) {
       $bit = 2;
       $space = 2; 
       $start = $pos*$space*2;
       return array($start,$size);
    }

    /**
     * [获取消息头]
     * @return [type] [description]
     */
    public static function getHead($protocol) {
        $bit = 2;
        list($start,$end)= self::getCutPostion(0);
        $head = mb_substr($protocol,$start,$end);
        if (strtoupper($head) != '7EE3') {
            $log = '协议头非法'.PHP_EOL;
            $log.='协议头:'.$head;
            Util::_writeLog($log );
            exit;
        }

        return $head;
    }
    
    /**
     * [获取资源头]
     * @return [type] [description]
     */
    public static function getSource($protocol) {
       list($start,$end)= self::getCutPostion(1);
       $source = mb_substr($protocol,$start,$end);
       return $source;
    }

    /**
     * [获取编号]
     * @return [type] [description]
     */
    public static function getNS($protocol) {
        list($start,$end)= self::getCutPostion(2);
        $ns = mb_substr($protocol,$start,$end);
        return $ns;
    }

     /**
     * [获取消息名]
     * @return [type] [description]
     */
    public static function getName($protocol) {
        list($start,$end)= self::getCutPostion(3);
        $name = mb_substr($protocol,$start,$end);
        return $name;
    }

    /**
     * [获取长度]
     * @return [type] [description]
     */
    public static function getLen($protocol) {
        list($start,$end)= self::getCutPostion(4);
        $len = mb_substr($protocol,$start,$end);
        return $len;
    }

    /**
     * [获取消息体]
     * @return [type] [description]
     */
    public static function getBody($protocol,$size) {
        list($start,$end)= self::getCutPostion(5,$size);
        $body = mb_substr($protocol,$start,$end);
        return $body;
    }

    /**
     * [获取消息体]
     * @return [type] [description]
     */
    public static function getCode($protocol,$size) {
        $code = mb_substr($protocol,($size-2)*2,4);
        return $code;

    }

    /**
     * [分析协议]
     * @param  [type] $protocol [description]
     * @return [type]           [description]
     */
    public static function analyzeProtocolToArray($protocol) {
        $content = array();
        $content[self::HEAD] = self::getHead($protocol);
        $content[self::SOURCE] = self::getSource($protocol);
        $content[self::NS] = self::getNS($protocol);
        $content[self::NAME] = self::getName($protocol);
        $content[self::LENGTH] = self::getLen($protocol);
        $size = hexdec($content[self::LENGTH])-(2+2+2+2+2+2);
        $content[self::BODY] = self::getBody($protocol,$size*2);
        $content[self::CODE] = self::getCode($protocol,hexdec($content[self::LENGTH]));
        $content['mContent'] = $protocol;
        $content['aslen'] = hexdec($content[self::LENGTH]);

        if ( !Util::checkPtlLen($protocol, $content['aslen'])) {
             $log = '协议长度非法'.PHP_EOL;
             $log.='内容:'.$protocol;
             Util::_writeLog($log );
             exit;
         }
        return $content;
    }
}