<?php
namespace Think;
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Service控制器基类 抽象类
 * @package  Think
 * @subpackage  TinkPHP.library.Think.Service
 * @author   v.r
 */
abstract class Service {
    private $i_;  //手机IMEI设别码 | 或者其他设备 IMEI设别码
    private $d_;  //设备类型  ios | android | pc
    private $dv_; //设备版本号，如果ios 6.1请填写 6.1，安卓请填写内核版本如4.4
    private $pkg_;  //包名
    private $a_; //app 名字
    private $v_; //app版本号，五位整型，如 30202，则表示 版本v3.2.2 
    private $u_;

    public function __construct(){
        @$this->i_= addslashes(trim($_GET['i_']));
        @$this->d_ = addslashes(trim($_GET['d_']));//  ios or android
        @$this->dv_ = addslashes(trim($_GET['dv_']));//  android 1-19  ios 7.0.3
        @$this->pkg_ = addslashes(trim($_GET['pkg_']));//pkg_ 包名
        @$this->a_ = addslashes(trim($_GET['a_']));
        @$this->u_ = intval($_GET['u_']);
        @$this->v_ = intval($_GET['v_']);
    }

    /**
     * 得到请求中的数据版本号
     * @return boolean
     */
    protected function _reqVersion() {
        if (isset($_GET['version'])) {
            return $_GET['version'];
        }
        return false;
    }

    /**
     * 获取服务器端接口的数据版本号
     * @param string $serviceName
     * @param string $actionName
     * @param string $suffix
     * @return boolean
     * 1.列子:
     *  $this->_serverVersion(TestService,sayHello);
     *  通过 ver_TestService/sayHello 获取 data_TestService/sayHello
     * 2.列子:
     *  $this->_serverVersion(TestService,sayHello,4EHFGBN);
     *  通过ver_TestServicesayHello?4EHFGBN 获取 data_TestServicesayHello?4EHFGBN
     */
    
    protected function _serverVersion($serviceName, $actionName, $suffix=null) {
        vendor('cache');
        $key = $this->_genVersionKey($serviceName, $actionName, $suffix);
        $ret = Cache::get($key);
        if (!empty($ret))  return $ret;
        return false;
    }

    /**
     * 为数据打上版本号
     * @param object() $data 数据
     * @param string $serviceName
     * @param string $actionName
     * @param string $suffix
     * @return object('data'=>object, 'version'=>string)
     * 列子:
     * $this->_serverVersion(array('name'=>'vincent.ren','age'=>'25'),TestService,sayHello);
     * 
     * return array(
     *    'data'=>array('name'=>'vincent.ren','age'=>'25'),
     *    'version'=>ver_TestService/sayHello
     * )
     * 
     */
    
    protected function _attachVersion($data, $serviceName, $actionName, $suffix=null) {
        vendor('cache');
        $key = $this->_genVersionKey($serviceName, $actionName, $suffix);
        $ret = Cache::get($key); //通过版本号获得数据版本
        if (!empty($ret)) return array('data' => $data, 'version' => $ret);
        return $data;
    }

    /**
     * 保存服务器端接口的版本号
     * @param string $serviceName
     * @param string $actionName
     * @param string $suffix
     * @param string $version
     * @return boolean
     * $this->_addVersion(TestService,sayHello,null,'data_TestService/sayHello')
     */
    protected function _addVersion($serviceName, $actionName, $suffix, $version=null) {
        if (empty($version)) $version = time();
        vendor('cache');
        $key = $this->_genVersionKey($serviceName, $actionName, $suffix);
        $ret = Cache::get($key);
        if (!empty($ret)) Cache::delete($key);
        Cache::add($key, $version);
        return true;
    }

    /**
     * 清除服务器端接口的版本号
     * @param string $serviceName
     * @param string $actionName
     * @param string $suffix
     * @return boolean
     * $this->_addVersion(TestService,sayHello)
     */
    protected function _cleanVersion($serviceName, $actionName, $suffix=null) {
        vendor('cache');
        $key = $this->_genVersionKey($serviceName, $actionName, $suffix);
        $ret = Cache::get($key);
        if (!empty($ret)) Cache::delete($key);
        return true;
    }

    /**
     * 生成版本号在cache中的key
     * @param string $serviceName
     * @param string $actionName
     * @param string $suffix
     * @return string
     */
    private function _genVersionKey($serviceName, $actionName, $suffix) {
        $key = empty($suffix) ? $serviceName . '/' . $actionName : $serviceName . $actionName . '?' . $suffix;
        $key = 'ver_' . $key;
        return $key;
    }

    /**
     * [_genDataKey description]
     * @param  [type] $serviceName [description]
     * @param  [type] $actionName  [description]
     * @param  [type] $suffix      [description]
     * @return [type]              [description]
     */
    private function _genDataKey($serviceName, $actionName, $suffix) {
        $key = empty($suffix) ? $serviceName . '/' . $actionName : $serviceName . $actionName . '?' . $suffix;
        $key = 'data_' . $key;
        return $key;
    }


    protected function _cleanDataCache($serviceName, $actionName, $suffix) {
        vendor('cache');
        $key = $this->_genDataKey($serviceName, $actionName, $suffix);
        $ret = Cache::get($key);
        if (!empty($ret)) Cache::delete($key);
        return true;
    }
}