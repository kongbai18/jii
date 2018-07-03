<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends BaseController {
	//用户列表
    public function lst(){
      $model = D('admin');
      $data = $model->alias('a')
      ->field('a.*,GROUP_CONCAT(c.role_name) role_name')
      ->join('LEFT JOIN __ADMIN_ROLE__ b ON a.id=b.admin_id
              LEFT JOIN __ROLE__ c ON b.role_id=c.id')
      ->group('a.id')
      ->select();
      //数据assign到页面中
      $this->assign(array(
           'data'  => $data,
           'title' => '用户列表',
           'btn_name' => '添加用户',
           'btn_url' => U('add')
      ));
      $this->display();
    }
    //用户添加
    public function add(){
      $model = D('admin');
      //判断是否接收了表单
      if(IS_POST){
      	//判断数据是否验证成功
      	if($model->create(I('post.'),1)){
      		//判断数据是否添加成功
      		if($model->add()){
      			$this->success('用户添加成功！',U('lst'));
      			exit;
      		}
      	}
      	$this->error($model->getError());
      }
      //取出所有的角色
      $rModel = D('role');
      $rData = $rModel->select();
      //数据assign到页面中
      $this->assign(array(
           'rData' => $rData,
           'title' => '添加用户',
           'btn_name' => '用户列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //用户修改
    public function edit(){
      //获取要修改用户的ID
      $id = I('get.id');
      //取出该用户信息
      $model = D('admin');
      $data = $model->find($id);
      //判断是否提交数据
      if(IS_POST){
      	 //判断是否通过验证
      	 if($model->create(I('post.'),2)){
      	 	//判断是否修改成功
      	 	if(FALSE !== $model->save()){
      	 		$this->success('用户修改成功！',U('lst'));
      	 	}
      	 }
      	 $this->error($model->getError());
      }
       //取出所有的角色
      $rModel = D('role');
      $rData = $rModel->select();
      //取出用户所属角色
      $arModel = D('admin_role');
      $arData = $arModel->where(array(
           'admin_id' =>array('eq',$id),
      ))->select();
      $_arData = array();
      foreach($arData as $k => $v){
      	$_arData[] = $v['role_id'];
      }
      //数据assign到页面中
      $this->assign(array(
           'arData' => $_arData,
           'rData' => $rData,
           'data' => $data,
           'title' => '用户修改',
           'btn_name' => '用户列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //用户修改密码
    public function editpass(){
        //获取要修改用户的ID
        $id = session('id');
        //取出该用户信息
        $model = D('admin');
        $data = $model->find($id);
        //判断是否提交数据
        if(IS_POST){

            $pass = I('post.password');
            $pass1 = I('post.password1');

            if($pass != $pass1){
                $this->error('两次密码输入不一致！');
            }
            $saveData = array(
                'id' => I('post.id'),
                'password' => $pass,
            );
            //判断是否修改成功
            if(FALSE !== $model->save($saveData)) {
                $this->success('密码修改成功！',U('Index/index'));
            }
            $this->error($model->getError());
        }

        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '密码修改',
        ));
        $this->display();
    }
    //用户删除
    public function delete(){
      //获取要删除用户的ID
      $id = I('get.id');
      $model = D('admin');
      //判断是否删除成功
     if($model->delete($id)){
     	$this->success('用户删除成功！',U('lst'));
     }
      $this->error($model->getError());
    }
    //个人推广二维码
    public function myPrcode(){
        $model = D('admin');
        $model->get_prcode();
    }
    //个人推广
    public function spread(){
        $model = D('admin');
        $data = $model->spread();
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '我的推广记录',
        ));
        $this->display();
    }
    //所有推广列表
    public function allSpread(){
        $model = D('admin');
        $data = $model->allSpread();
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '推广列表',
        ));
        $this->display();
    }
}