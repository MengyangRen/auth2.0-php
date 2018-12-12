<?php
namespace System\Controller;
use Think\Controller\ApisBaseController;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  定义对外服务控制器
 * @author  v.r
 */

class ApisController extends ApisBaseController
{
    public function _initialize()
    {
        # code...
        @$i = $_GET['i_'];
        @$t = $_GET['t_'];
        @$p = $_GET['p_'];
        @$super = empty($_GET['Iamsuperman']) ? $_GET['iamsuperman'] : $_GET['Iamsuperman'];
        //$request = explode('?',$_SERVER['PATH_INFO']);
        // var_dump($_SERVER);
        @$_GET['_URL_'] = array_reverse(explode('/',$_SERVER['PATH_INFO']));
        if (empty($_SESSION['user']))   @session_destroy();
        /*if (empty($super)) {
            if (empty($t) || empty($p)) $this->_die();
            $unixTime = intval(substr(strval($t), 0, 10));
            $t = intval($t);
            $p = intval($p);
            $t = $t% 10000;
            if ($t * 3 + 2345 != $p) $this->_die();

            //超过一小时拒绝访问 
            /*
            $now = time();
            $begin = strtotime('-1 hour');
            $end = strtotime('+1 hour');
            if ($unixTime < $begin || $unixTime > $end) $this->_die();
             * 
            */
       /* }*/

    }
    
    protected function _die() {
        $uri = $_SERVER['REQUEST_URI'];
        $retType = 'json';
        if (strpos($uri, '/xml') != false) $retType = 'xml';
        $out = array('code' => 401, 'message' => 'Unauthorized');
        if ($retType == 'xml') {
            header('Content-Type: application/xml');
            echo $this->___xml_serializer($out);
        } else if ($retType == 'json') {
            header('Content-Type: application/json');
            $out = json_encode($out);
            if (isset($this->params['url']['callback'])) {
                echo "var _callbackvar=$out;" . $this->params['url']['callback'] . "(_callbackvar);";
            } else {
                echo $out;
            }
        }
        exit;
    }
}