<?php
namespace Admin\Controller;
use Think\Controller;
class RoleController extends BaseController {
	//角色列表
    public function lst(){
      $model = D('role');
      $data = $model->search();
      //数据assign到页面中
      $this->assign(array(
           'data'  => $data,
           'title' => '角色列表',
           'btn_name' => '添加角色',
           'btn_url' => U('add')
      ));
      $this->display();
    }
    //角色添加
    public function add(){
      $model = D('role');
      //判断是否接收了表单
      if(IS_POST){
      	//判断数据是否验证成功
      	if($model->create(I('post.'),1)){
      		//判断数据是否添加成功
      		if($model->add()){
      			$this->success('角色添加成功！',U('lst'));
      			exit;
      		}
      	}
      	$this->error($model->getError());
      }
      //取出权限列表
      $priModel = D('privilege');
      $priData = $priModel->getTree();
      //数据assign到页面中
      $this->assign(array(
           'priData' => $priData,
           'title' => '添加角色',
           'btn_name' => '角色列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //角色修改
    public function edit(){
      //获取要修改角色的ID
      $id = I('get.id');
      //取出该角色信息
      $model = D('role');
      $data = $model->find($id);
      //判断是否提交数据
      if(IS_POST){
      	 //判断是否通过验证
      	 if($model->create(I('post.'),2)){
      	 	//判断是否修改成功
      	 	if(FALSE !== $model->save()){
      	 		$this->success('角色修改成功！',U('lst'));
      	 	}
      	 }
      	 $this->error($model->getError());
      }
      //取出权限列表
      $priModel = D('privilege');
      $priData = $priModel->getTree();
      //取出该角色权限
      $rpModel = D('role_pri');
      $rpData = $rpModel->where(array(
         'role_id' =>array('eq',$id),
      ))->select();
      $_rpData = array();
      foreach($rpData as $k => $v){
      	 $_rpData[] = $v['pri_id'];
      }
      //数据assign到页面中
      $this->assign(array(
           'rpData' => $_rpData,
           'priData' => $priData,
           'data' => $data,
           'title' => '角色修改',
           'btn_name' => '角色列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //角色删除
    public function delete(){
      //获取要删除角色的ID
      $id = I('get.id');
      $model = D('role');
      //判断是否删除成功
     if($model->delete($id)){
     	$this->success('角色删除成功！',U('lst'));
     }
      $this->error($model->getError());
    }
}