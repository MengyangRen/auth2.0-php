<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 新疆航行-对外WebService服务
 *  
 */

class XJWebService {

    /**
     * 创建一个Soap客户端
     * @return obj    
     */
    private static function CreateSoapClient() {
        $client = new SoapClient(self::WEB_SERIVCE_URI,array(
                "stream_context" => stream_context_create(array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        )
                    )
                )
            )
        );
        $client->__setSoapHeaders(new SoapHeader('http://tempuri.org/','MySoapHeader',array(
            'UserName'=>self::SOAP_HEAD_USER_NAME_MD5,
            'PassWord'=>self::SOAP_HEAD_PASS_WORD_MD5
        ),true));
        return $client;
    }

    /**
     * 获取用户信息
     * @param array $param 参数集合
     * 
     * 列子:
     * $param  = array(
     *   'CusTax'=>'650105999999070'
     * )
     * @return array    
     */
    public static function getCusInfoByNetWork(array $param = NULL)
    {
        try {

          $client = self::CreateSoapClient();
          $fuc  = self::CUS_INFO;
          $ret = $client->$fuc($param);  
          $_tpm_ = $fuc.self::RESULT;
          return $ret->$_tpm_;
        } catch (\SOAPFault $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    /**
     * 获取客户端IP
     * @param array $param 参数集合
     * 
     * 列子:
     * $param  = array(
     *   'CusTax'=>'650105999999070'
     * )
     * @return array    
     */
    public static function getCusIPByNetWork(array $param = NULL)
    {
        try {

          $client = self::CreateSoapClient();
          $fuc  = self::GET_USER_IP;
          $ret = get_object_vars($client->$fuc());  
          $_tpm_ = $fuc.self::RESULT;
          return $ret[$_tpm_];
        } catch (SOAPFault $e) {
          print $e;   
        }
    }

    /**
     * 访问地址
     * @var const
     */
    const WEB_SERIVCE_URI = 'http://interface.xjhtxx.cn:8080/zhicloud.asmx?WSDL';
    const GET_USER_IP = 'GetUserIP';
    const CUS_INFO = 'ClientInfo';
    const RESULT  = 'Result';

    const SOAP_HEAD_USER_NAME_MD5 = '78EF3F59F40FF2F144234F7A980ACC8B';
    const SOAP_HEAD_PASS_WORD_MD5 = '850DAC5CEC0FC4D0BB9AE05274685732';
}
