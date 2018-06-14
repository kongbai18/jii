<?php
namespace Admin\Controller;
use Think\Controller;
class PrivilegeController extends BaseController {
	//权限列表
    public function lst(){
      $model = D('privilege');
      $data = $model->getTree();
      //数据assign到页面中
      $this->assign(array(
           'data'  => $data,
           'title' => '权限列表',
           'btn_name' => '添加权限',
           'btn_url' => U('add')
      ));
      $this->display();
    }
    //权限添加
    public function add(){
      $model = D('privilege');
      $data = $model->getTree();
      //判断是否接收了表单
      if(IS_POST){
      	//判断数据是否验证成功
      	if($model->create(I('post.'),1)){
      		//判断数据是否添加成功
      		if($model->add()){
      			$this->success('权限添加成功！',U('lst'));
      			exit;
      		}
      	}
      	$this->error($model->getError());
      }
      //数据assign到页面中
      $this->assign(array(
           'data'  => $data,
           'title' => '添加权限',
           'btn_name' => '权限列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //权限修改
    public function edit(){
      //获取要修改权限的ID
      $id = I('get.id');
      //取出该权限信息
      $model = D('privilege');
      $data = $model->find($id);
      //判断是否提交数据
      if(IS_POST){
      	 //判断是否通过验证
      	 if($model->create(I('post.'),2)){
      	 	//判断是否修改成功
      	 	if(FALSE !== $model->save()){
      	 		$this->success('权限修改成功！',U('lst'));
      	 	}
      	 }
      	 $this->error($model->getError());
      }
      //获取无限极列表
      $tree = $model->getTree();
      //获得子集
      $children = $model->getChildren($id);
      //数据assign到页面中
      $this->assign(array(
           'children' => $children,
           'tree' => $tree,
           'data' => $data,
           'title' => '权限修改',
           'btn_name' => '权限列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //权限删除
    public function delete(){
      //获取要删除权限的ID
      $id = I('get.id');
      $model = D('privilege');
      //判断是否删除成功
     if($model->delete($id)){
     	$this->success('权限删除成功！',U('lst'));
     }
      $this->error($model->getError());
    }
}