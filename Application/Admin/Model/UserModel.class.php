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
                     'thr_session' => md5($wxData['session_key'])
                 );
                 $result = $this->save($data);
                 if($result !== false){
                   return $data;
                 }else{
                     return 'false';
                 }
             }else{
                 $data['openid'] = $openid;
                 $data['thr_session'] = md5($wxData['session_key']);
                 $result = $this->add($data);
                 if ($result){
                     $data['id'] = $result;
                     unset($data['openid']);
                     return $data;
                 }else{
                     return 'false';
                 }
             }
         }else{
             return 'false';
         }
    }
}