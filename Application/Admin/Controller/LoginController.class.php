<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function login(){
        if(IS_POST){
            $model = D('admin');
            if($model->validate($model->_login_validate)->create()){
                if($model->login()){
                    $this->success('登录成功！',U('index/index'),0);
                }
            }
            $this->error($model->getError());
        }
        $this->display();
    }
    public function logout(){
        $model = D('admin');
        $model->logout();
        redirect('login');
    }
    //生成验证码
    public function chkcode(){
        $Verify = new \Think\Verify(array(
            'fontSize' =>  18,
            'length' => 4,
            'useNoise' => FALSE,
        ));
        session_start();
        $Verify->entry();
    }
}