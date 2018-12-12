<?php
namespace System\Model;
use Think\Model;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  财税云区域地址管理模型
 * @author  v.r
 * @package sys 
 * 
 * 注: 辅助方法为私有方法 命名以下滑线开头
 *  
 * 
 */

class YCSRegionModel extends Model{
    
    /**
     * @var string
     */
    Protected $pk = 'id';

    /**
     * @var string
     */
    protected $tableName='region';
    
    /**
     * @var array
     */
    protected $_validate = array();

    /**
     * @var string
     */
    protected $connection = 'DB_ZhiCloud_Common';

    /**
     * 获取区域名
     * @param  int  id 区域id
     * @param  int  type 类型，0:省；1:市; 2: 区 
     * @return mixed
     * 
     */
    public function getRegionByID($id = NULL,$tyep = NULL ) {
        $condition = array();

        if (!empty($type)) 
            $condition += array('type'=>$tyep);

        $arr = array();
        $condition['id'] = $id;
      
        $res = $this->where($condition)->find();
        if (empty($res)) 
            throw new \Exception("..", 915);
        $arr[$res['id']] = $res;

        $res = NULL;
        
        return $arr;
    }

    /**
     * 通过名字获取区域
     * @param  string  区域名
     * @param  int  type 类型，0:省；1:市; 2: 区 
     * @return mixed
     * 
     */
    public function getRegionByName($name = NULL , $tyep = NULL) {
        $condition = array();

        if (!empty($type)) 
            $condition += array('type'=>$tyep);

        $arr = array();
        $condition['name'] = $name;
      
        $res = $this->where($condition)->find();
        if (empty($res)) 
            throw new \Exception("..", 916);
        $arr[$res['name']] = $res;
        $res = NULL;
        return $arr;
    }


    /**
     * 通过名字获取区域
     * @param  int  区域码
     * @param  int  type 类型，0:省；1:市; 2: 区
     *  
     * @return mixed
     * 
     */
    public function getRegionByCode($code = NULL , $type = NULL) {
        $condition = array();
        if (!empty($type)) 
            $condition += array('type'=>$type);

        $arr = array();
        $condition['code'] = $code;

        $res = $this->where($condition)->find();
        if (empty($res)) 
            //throw new \Exception("..", 917);
            $ret['id'] = 0;
        return $res;
    }







}

