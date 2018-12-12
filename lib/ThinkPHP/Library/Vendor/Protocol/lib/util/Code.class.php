<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  基站 -通讯协议校验码库
 * @author v.r
 * @package         
 * @subpackage      lib.util.protocol.class.php
 */ 

class code 
{
    
    /**
     * [_检查校验码]
     * @param  [type] $protocol [description]
     * @return [type]           [description]
     */
    public function _check($protocol) {
        return true;
    }

    /**
     * [_校验码生成]08ce
     * @param  [type] $protocol [description]
     * @return [type]           [description]
     */
    public static function _create($protocol) {
        return 'b0af';
    }

	 /**
     * [_校验码生成]
     * @param  [type] $protocol [description]
     * @return [type]           [description]
     */
    private function crc16($code) {
        return true;
    }

}