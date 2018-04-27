<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/30 0030
 * Time: 9:48
 */
namespace Admin\Model;
use Think\Model;
class AddressModel extends Model {
    public function addAddress(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');

        $addrId = I('get.addrId');
        $name = I('get.name');
        $mobile = I('get.mobile');
        $city = I('get.city');
        $address = I('get.address');
        $status = I('get.status');

        $user = checkUser($userId,$thr_session);
        if($user){
            if ($status == '1'){
                $dresData = $this->field('id')->where(array(
                    'user_id' => array('eq',$userId),
                    'status' => array('eq','1'),
                ))->select();
                if($dresData[0]['id']){
                    $this->save(array(
                        'id' => $dresData[0]['id'],
                        'status' => '0',
                    ));
                }
            }else{
                $dresData = $this->field('id')->where(array(
                    'user_id' => array('eq',$userId),
                    'status' => array('eq','1'),
                ))->select();
                if(!$dresData[0]['id']){
                    $status = '1';
                }
            }
            if($addrId){
                if($status == '0'){
                    $oldsta = $this->field('status')->find($addrId);
                    if($oldsta['status'] == '1'){
                        $newAddrId = $this->field('id')->limit('1')->where(array('user_id'=>$userId))->select();
                        if($newAddrId[0]['id'] == $addrId){
                            $status = '1';
                        }else{
                            $this->save(array(
                                'id' => $newAddrId[0]['id'],
                                'status' => '1',
                            ));
                        }
                    }
                }

                $data = array(
                    'id' => $addrId,
                    'user_id' => $userId,
                    'name' => $name,
                    'mobile' => $mobile,
                    'city' => $city,
                    'address' => $address,
                    'status' => $status,
                );
                $result = $this->save($data);
            }else{
                $data = array(
                    'user_id' => $userId,
                    'name' => $name,
                    'mobile' => $mobile,
                    'city' => $city,
                    'address' => $address,
                    'status' => $status,
                );
                $result = $this->add($data);
            }
            if ($result !== false){
                return '11';  //添加地址成功
            }else{
                return '10';  //添加地址失败
            }
        }else{
            return '0';  //登陆错误
        }
    }
    public function addressList(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if ($user){
            $addressData = $this->where(array(
                'user_id' => array('eq',$userId)
            ))->select();
            $data = array(
                'flag' => 1,
                'addressData' => $addressData,
            );
        }else{
            $data = array(
                'flag' => 0,
            );
        }
        return $data;
    }
    public function editDefault(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $id = I('get.id');

        $user = checkUser($userId,$thr_session);
        if ($user){
            $addressData = $this->where(array(
                'user_id' => array('eq',$userId),
                'status' => array('eq','1'),
            ))->select();
            $result = $this->where(array(
                'user_id' => array('eq',$userId),
                'id' => array('eq',$id),
            ))->save(array('status'=>'1'));
            if ($result){
                $this->save(array(
                    'id' => $addressData[0]['id'],
                    'status' => '0',
                ));
                $data = '11';
            }else{
                $data = '10';
            }
        }else{
            $data = '0';
        }
        return $data;
    }

    public function addrInfo(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $id = I('get.addrId');
        $user = checkUser($userId,$thr_session);
        if($user){
            $info = $this->find($id);
            if($info['user_id'] == $userId){
                $data = array(
                    'flag' => '1',
                    'info' => $info
                );
            }else{
                $data = array(
                    'flag' => '0'
                );
            }
        }else{
            $data = array(
                'flag' => '0'
            );
        }
        return $data;
    }

    public function delAddr(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $id = I('get.addrId');

        $user = checkUser($userId,$thr_session);
        if($user){
            $info = $this->find($id);
            if($info['user_id'] == $userId){
                $result = $this->delete($id);
                if($info['status'] == '1'){
                    $newAddrId = $this->field('id')->limit('1')->where(array('user_id'=>$userId))->select();
                    if(!empty($newAddrId)){
                        $this->save(array(
                            'id' => $newAddrId[0]['id'],
                            'status' => '1',
                        ));
                    }
                }
                if($result){
                    return '11';
                }else{
                    return '10';
                }
            }
        }else{
            return '0';
        }
    }

    public function addressDef(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');

        $user = checkUser($userId,$thr_session);
        if ($user) {
            $addressData = $this->where(array(
                'user_id' => array('eq', $userId),
                'status' => array('eq', '1'),
            ))->select();
            $data = array(
                'flag' => '1',
                'address' => $addressData,
            );
        }else{
            $data = array(
                'flag' => '0',
            );
        }
        return $data;
    }
}