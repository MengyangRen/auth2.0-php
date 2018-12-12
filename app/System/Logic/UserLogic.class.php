<?php
namespace System\Logic;

use Think\ApiException;
use Common\Logic\Logic;

use System\Model\UserTokenModel;
use System\Model\YCSUserModel;
use System\Model\YCSCCDModel;
use System\Model\YCSCompanyModel;


/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 *  用户服务业务逻辑
 *    
 * @author  v.r
 * @copyright copyright http://my.oschina.net/u/1246814
 * 
 */

class UserLogic extends Logic {
    
    /**
     * 单例对象
     * @var obj 
     */
    public static $instance;

    /**
     * Get user information entity business logic
     * 
     * @param $access_token
     * To the cloud user to access the generated token
     * 
     * @return mixed $data default array,
     *  else Exception
     *  
     * @ingroup oauth2_section_4
     */
    public function getUserInfo($access_token = NULL ) {
        try {

            $UserTokenModel  = new UserTokenModel;
            $YCSUserModel    = new YCSUserModel;
            vendor('Kfk/Util', NULL, '.class.php');
            
            $userInfo = $YCSUserModel->getUserByUID( 
                $UserTokenModel->getUidByToken($access_token)
            );

            return array(
                'username' => $userInfo ['nick_name'],
                'truename' => $userInfo['true_name'],
                'sex' => $userInfo['sex'],
                'phone'=> $userInfo['phone'],
                'is_admin' => $userInfo['is_admin'], //是否管理员
                'status' => $userInfo['staus'], //用户状态
                'avatar' => AVATAR_URI . \Util::IsExistAvatar($userInfo['id']),
                'created'=> $userInfo['created'],
                'nowtime' => time(),
            );
	    } catch (\Exception $e) {
 			throw new \Exception($e->getMessage(), $e->getCode());
	    }
	}
}
