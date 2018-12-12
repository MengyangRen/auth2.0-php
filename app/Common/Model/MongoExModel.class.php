<?php

namespace Common\Model;
use Think\Model\MongoModel;

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  定义mongo模型基类
 * @author  v.r
 * @package user 
 * 注: 辅助方法为私有方法 命名以下滑线开头
 */
class  MongoExModel extends MongoModel {

    protected $connection = array(
        'db_type' => '',
        'db_user' => '',
        'db_pwd' =>  '',
        'db_host' => '',
        'db_port' => '',
        'db_name' => '',
        'db_charset' => 'utf8',
    );

    protected $dbName = '';
    protected $trueTableName = '';

    public function __construct() {
        $this->connection['db_type'] = 'mongo';
        $this->connection['db_user'] = C('MONGO_DB_USER');
        $this->connection['db_pwd'] = C('MONGO_DB_PWD');
        $this->connection['db_host'] = C('MONGO_DB_HOST');
        $this->connection['db_port'] = C('MONGO_DB_PORT');
        $this->connection['db_name'] = C('MONGO_DB_NAME');
        $this->dbName = C('MONGO_DB_NAME');
        $this->trueTableName =C('MONGO_DB_TABLE');
        parent::__construct();
    }

}
?>