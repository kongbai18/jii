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
         $userId = I('get.id');
         $appId = 'wx6bf5eec027a0fe45';
         $secret = 'd8d44854cbefe9989123167cdabcab42';
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
                 if(!$userId){
                     $userId = 0;
                 }
                 $data['openid'] = $openid;
                 $data['thr_session'] = md5($wxData['session_key']);
                 $data['session_key'] = $wxData['session_key'];
                 $data['parent_id'] = $userId;
                 $data['add_time'] = time();
                 $result = $this->add($data);
                 if ($result){
                     $integrationModel = D('integration');
                     $inteData = array(
                         'id' => $result,
                         'integration' => 0,
                         'sum' => 0,
                         'cash' => 0,
                         'surplus' => 0,
                     );
                     $integrationModel->add($inteData);
                     if($userId != 0){
                         $fp = fopen('./lockOrd.text','r');
                         flock($fp,LOCK_EX);         //锁机制

                         $integrationModel = D('integration');
                         $integrationData = $integrationModel->find($userId);

                         //获取单次增加积分数
                         $rewardModel = D('reward');
                         $rewardData = $rewardModel->find('1');

                             $integrationModel->save(array(
                                 'id' => $userId,
                                 'integration' => $integrationData['integration'] + $rewardData['integration'],
                                 'surplus' => $integrationData['surplus'] + 0.1*$rewardData['integration'],
                                 'custom' => $integrationData['custom'] + 1,
                                 'total_custom' => $integrationData['total_custom'] + 1,
                             ));

                         $userOne = $this->find($userId);
                         if($userOne['parent_id'] != 0){
                             $oneData = $integrationModel->find($userOne['parent_id']);

                             $integrationModel->save(array(
                                 'id' => $userOne['parent_id'],
                                 'total_custom' => $oneData['total_custom'] + 1,
                             ));

                             $userTwo = $this->find($userOne['parent_id']);
                             if($userTwo['parent_id'] != 0){
                                 $twoData = $integrationModel->find($userTwo['parent_id']);

                                 $integrationModel->save(array(
                                     'id' => $userTwo['parent_id'],
                                     'total_custom' => $twoData['total_custom'] + 1,
                                 ));
                             }
                         }

                         flock($fp,LOCK_UN);
                         fclose($fp);

                         $integrationRecordModel = D('integration_record');
                         $integrationRecordModel->add(array(
                             'user_id' => $userId,
                             'integration' => $rewardData['integration'],
                             'add_time' => time(),
                             'message' => '推广注册',
                         ));

                     }

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
                $phoneResult = $this->save($reData);

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

                    if($phoneResult){
                        $userData = $this->find($userId);

                        if($userData['parent_id'] != 0){
                            $fp = fopen('./lockOrd.text','r');
                            flock($fp,LOCK_EX);         //锁机制

                            $integrationModel = D('integration');
                            $integrationData = $integrationModel->find($userData['parent_id']);

                            //获取授权手机号增加积分数
                            $rewardModel = D('reward');
                            $rewardData = $rewardModel->find('2');

                            $integrationModel->save(array(
                                'id' => $userData['parent_id'],
                                'integration' => $integrationData['integration'] + $rewardData['integration'],
                                'surplus' => $integrationData['surplus'] + 0.1*$rewardData['integration'],
                            ));


                            flock($fp,LOCK_UN);
                            fclose($fp);
                            $integrationRecordModel = D('integration_record');
                            $integrationRecordModel->add(array(
                               'user_id' => $userId,
                               'integration' => $rewardData['integration'],
                                'add_time' => time(),
                                'message' => '授权手机号',
                            ));
                        }
                    }
                }
                return true;
            }
            return false;
        }else{
            return false;
        }
    }
    //生成个人推广二维码
    public function get_prcode() {
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $access = json_decode(get_access_token(),true);
            $access_token= $access['access_token'];
            $path="pages/index/spread/spread?id=".$userId;
            $width=430;
            $post_data='{"path":"'.$path.'","width":'.$width.'}';
            $url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
            $result = get_http_array($url,$post_data);
            return $result;
        }
    }
    //获取三级推广数据
    public function thrSpread(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $data = $this->field('id,parent_id')->select();
            $result = $this->getTree($data,$userId);
            $level = array();
            foreach ($result as $k => $v){
                if($v['level'] == 1){
                    $level[1] = $level[1]+1;
                }else if($v['level'] == 2){
                    $level[2] = $level[2]+1;
                }else if($v['level'] == 3){
                    $level[3] = $level[3]+1;
                }
            }
            return $level;
        }
    }
    private function getTree($data,$parentId,$level=1){
        static $ret =array();
        foreach($data as $k => $v){
            if($v['parent_id']==$parentId){
                $v['level'] = $level;
                $ret[] = $v;
                if($level < 3){
                    //找子分类
                    $this->getTree($data,$v['id'],$level+1);
                }
            }
        }
        return $ret;
    }

    //积分
    public function integration(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $integrationModel = D('integration');
            $data = $integrationModel->find($userId);
            return $data;
        }
    }
    //个人积分信息
    public function userInfo(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $integrationModel = D('integration');
            $integrationData = $integrationModel->find($userId);

            $today = strtotime(date("Y-m-d"),time());
            $integrationRecordModel = D('integration_record');
            $integrationRecordData = $integrationRecordModel->where(array('user_id'=>array('eq',$userId),'add_time'=>array('gt',$today)))->select();

            $todaySum = $integrationRecordModel->field('sum(integration) as todaySum')->where(array('user_id'=>array('eq',$userId),'add_time'=>array('gt',$today)))->select();

            if(empty($todaySum)){
                $todaySum = $todaySum[0]['todaySum'];
            }else{
                $todaySum = 0;
            }

            $data = array(
                'integration' => $integrationData,
                'integrationRecord' => $integrationRecordData,
                'todaySum' => $todaySum,
                'child' => $child,
            );
            return $data;
        }
    }

    //提现
    public function withdraw(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $withdraw = I('get.withdraw');
        $user = checkUser($userId,$thr_session);
        if($user){
            $userData = $this->find($userId);

            $integrationModel = D('integration');
            $integrationData = $integrationModel->find($userId);
            if($withdraw < $integrationData['surplus'] || $withdraw < $integrationData['sum']){
                var_dump($userData['openid']);
                $result = transfers($userData['openid'],$withdraw);
                if($result){
                    $data = array(
                        'flag' => '1',
                    );
                }else{
                    $data = array(
                        'flag' => '0',
                        'msg' => '提现失败！',
                    );
                }
            }else{
                $data = array(
                    'flag' => '0',
                    'msg' => '提现超额！',
                );
            }
            return $data;
        }
    }


}
