<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 *  推送服务一致性HASH （动态分配推送法务） 
 * 
 * @author v.r
 * @package         推送
 * @subpackage      PushHash.class.php
 */

if(!defined('PUSH_HASH_LIB_PATH'))
    define('PUSH_HASH_LIB_PATH', dirname(__FILE__));
require PUSH_HASH_LIB_PATH.DIRECTORY_SEPARATOR.'flexHash.class.php';
require PUSH_HASH_LIB_PATH.DIRECTORY_SEPARATOR.'config.class.php';

Class PushHash 
{  

    /**
     * [$hash Hash值]
     * @var null
     */
    public $hash = null;  

    /**
     * [$connectPool 连接池]
     * @var null
     */
    public $connectPool = null;
    
    /**
     * [$configPool 服务项配置池]
     * @var null
     */
    public $configPool = null;   

    /**
     * [$algorithmPool 算法池]
     * @var array
     */
    public $algorithmPool = array(  
            'Time33'=> 'Flexihash_Time33Hasher', 
            'Crc32' =>'Flexihash_Crc32Hasher',  
            'Md5' =>'Flexihash_Md5Hasher',
        );

    /**
     * [__construct 初始化服务]
     * hash值默认算法为time33
     */
    public function __construct($algorithm = 'Time33')  
    {  
        if (!in_array($algorithm,array_keys($this->algorithmPool))) 
           throw new \Exception('No algorithm exist',2457);  
        $this->hash = new Flexihash(new $this->algorithmPool[$algorithm]);  
    }  

    /**
     * [addServers 新增服务]
     * @param [type] $servers [description]
     */
    public function addServers( $servers )  
    {   
        $this->configPool = $servers;
        foreach ($servers as $server)  
        {  
            $node = $server['internetIP'] . ':' . $server['port'].':'.$server['index'];  
            $this->connectPool[$node] = false;  
            $targets[] = $node;  
        }  
        $this->hash->addTargets( $targets );      
    }  

    /**
     * [findServer 查找server]
     * @return [type] [description]
     */
    public function findServer($hashkey = NULL) {
        $node = $this->hash->lookup($hashkey, count( $this->connectPool ) );    
        $node = explode(':', $node);
        return $this->configPool[$node[2]];
    }
}  


// 列子:
/*$PushHash = new PushHash();  
$PushHash->addServers(SERVER_CONFIG::$map); 
$info = $PushHash->findServer('YCSCODE1017092908123');
var_dump($info);*/