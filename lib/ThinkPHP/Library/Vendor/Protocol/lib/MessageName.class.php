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
 * 定义响应消息名
 * 
 */
final class RespondMessageName 
{ 

    /**
     * 响应设备状态
     */
    const DEV_QUR_REQ = '1002';
   
    /** 
     * 响应定位信息
     */
    const POSI_QUR_REQ = "1087";

    /** 
     * 响应网络信息
     */
    const NET_QUR_REQ = "1003";
    
    /**
     * 响应串口信息
     */
    const COM_QUR_REQ = "1004";
    
    /**
     * 响应日志信息
     */
    const LOG_QUR_REQ = "1005";

    /** 
     * 响应升级服务器信息
     */
    const UPDATE_SERVER_QUR_REQ = "1061";

    /** 
     * 响应基本配置信息
     */
    const GET_BASE_CFG_REQ = "1063";
}


/**
 * 定义响应消息名
 * 
 */
final class MessageName 
{ 

   /**
     * 响应设备状态
     */
    const DEV_QUR_REQ = '0002';
   
    /** 
     * 响应定位信息
     */
    const POSI_QUR_REQ = "0087";

    /** 
     * 响应网络信息
     */
    const NET_QUR_REQ = "0003";
    
    /**
     * 响应串口信息
     */
    const COM_QUR_REQ = "0004";
    
    /**
     * 响应日志信息
     */
    const LOG_QUR_REQ = "0005";

    /** 
     * 响应升级服务器信息
     */
    const UPDATE_SERVER_QUR_REQ = "0061";

    /** 
     * 响应基本配置信息
     */
    const GET_BASE_CFG_REQ = "0063";
}