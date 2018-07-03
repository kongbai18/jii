<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27 0027
 * Time: 14:51
 */
namespace Admin\Model;
use Think\Model;
class UserModel extends Model {
    public function login(){
         $code = I('get.code');
         $adminId = I('get.id');
         $appId = 'wx6a73b5816054ba24';
         $secret = '143b572cbecbae4bb6c138643ac7f6e8';
         $file_contents = file_get_contents('https://api.weixin.qq.com/sns/jscode2session?appid='.$appId.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code');
         $wxData = json_decode($file_contents,true);
         $openid = $wxData['openid'];
         if($openid){
             $id = $this->field('id')->where(array(
                 'openid' => array('eq',$openid)
             ))->find();
             if($id['id']){
                 $data = array(
                     'id' => $id['id'],
                     'thr_session' => md5($wxData['session_key']),
                     'session_key' => $wxData['session_key'],
                 );
                 $result = $this->save($data);
                 if($result !== false){
                   $data['result'] = 2;
                   unset($data['session_key']);
                   return $data;
                 }else{
                     return 'false';
                 }
             }else{
                 if(!$adminId){
                     $adminId = 0;
                 }
                 $data['openid'] = $openid;
                 $data['thr_session'] = md5($wxData['session_key']);
                 $data['session_key'] = $wxData['session_key'];
                 $data['admin_id'] = $adminId;
                 $data['add_time'] = time();
                 $result = $this->add($data);
                 if ($result){
                     $data['id'] = $result;
                     $data['result'] = 1;
                     unset($data['openid']);
                     unset($data['session_key']);
                     return $data;
                 }else{
                     return 'false';
                 }
             }
         }else{
             return 'false';
         }
    }
    public function getPhone(){
        $encryptedData = I('get.encryptedData');
        $iv = I('get.iv');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $latitude = I('get.latitude');
        $longitude = I('get.longitude');
        $address = getAddress($longitude,$latitude);
        $user = checkUser($userId,$thr_session);
        if($user){
            $key = $this->field('session_key')->find($userId);
            $data = array();
            $result = decryptData($encryptedData,$iv,$key['session_key'],$data);
            $data = json_decode($data,true);
            if($result){
                $reData = array(
                  'id' => $userId,
                  'telephone' => $data['phoneNumber']
                );
                $this->save($reData);
                $quoteModel = D('quote');
                $quoteId = $quoteModel->where(array('user_id'=>array('eq',$userId)))->select();
                if(empty($quoteId)){
                    $quoteData = array(
                        'id' => create_unique(),
                        'user_id' => $userId,
                        'telephone' => $data['phoneNumber'],
                        'address' => $address,
                        'add_time' => time(),
                    );
                    $quoteModel->add($quoteData);
                }
            }
            return true;
        }else{
            return false;
        }
    }
}
