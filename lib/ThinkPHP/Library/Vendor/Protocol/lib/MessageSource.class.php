<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  基站 -通讯协议解析库 
 * @author v.r
 * @package         web服务与基站协议解析库
 * @subpackage      protocol.class.php
 */ 

/**
 * 定义消息源
 * 
 */
final class MessageSource 
{

   /**
    * Tcp服务
    */
   const TCP = '0024';

   /**
    * MQTT服务
    */
   const MQTT = '0025';
  
   /**
    * 测试服务
    */
   const TEST = '0065';
   
   /**
    * HTTP服务
    */
    const HTTP = "0065"; 

}