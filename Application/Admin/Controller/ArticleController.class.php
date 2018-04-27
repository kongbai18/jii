<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends BaseController {
    //类别列表
    public function lst(){
      $model = D('article');
      $data = $model->search(10);
      //数据assign到页面中
      $this->assign(array(
           'data'  => $data,
           'title' => '文章列表',
           'btn_name' => '发布文章',
           'btn_url' => U('add')
      ));
      $this->display();
    }
    //类别增加
    public function add(){
      $model = D('article');
      //判断是否接收表单
      if(IS_POST){
          if(empty($_FILES)){
              $this->error('照片大小合计超过2M，请分次上传！');
          }
          if($_FILES['pic']['error'] != '0'){
              $this->error('请上传封面图！');
          }
      	//判断是否验证成功
      	if($model->create(I('post.'),1)){
      		//判断是否添加成功
      		if($model->add()){
      			$this->success('文章添加成功！',U('lst'));
      		}
      	}
      	//添加失败
      	$this->error($model->getError());
      }
      //数据assign到页面中
      $this->assign(array(
           'title' => '发布文章',
           'btn_name' => '文章列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //类别修改
    public function edit(){
      //获取需要修改类型的ID
      $id = I('get.id');
      $model = D('article');
      //判断是否接收表单
      if(IS_POST){
          if(empty($_FILES)){
              $this->error('照片大小合计超过2M，请分次上传！');
          }
      	//判断是否验证成功
      	if($model->create(I('post.'),2)){
      		//判断是否修改成功
      		if(FALSE !== $model->save()){
      			$this->success('文章修改成功！',U('lst'));
      		}
      	}
      	//添加失败
      	$this->error($model->getError());
      }
      //获取文章内容图
      $arDescModel = D('article_desc');
      $descData = $arDescModel->where(array('article_id'=>$id))->select();
      //获取修改类型数据
      $data = $model->find($id);
      $goodsModel = D('goods');
      $goodsData = array();
      foreach (explode(',',$data['goods']) as $v){
          $goodsData[] = $goodsModel->field('id,goods_name')->find($v);
      }
      //数据assign到页面中
      $this->assign(array(
           'data' => $data,
           'descData' => $descData,
           'goodsData' => $goodsData,
           'title' => '修改文章',
           'btn_name' => '文章列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //类别删除
    public function delete(){
      //接收要删除类型的ID
      $id = I('get.id');
      $model = D('article');
      //判断是否删除成功
      if($model->delete($id)){
      	$this->success('文章删除成功！',U('lst'));
      }
      $this->error($model->getError());
    }
    //ajax获取搜索商品
    public function ajaxGetgoods(){
        $model = D('goods');
        $data = $model->search(100);
        echo json_encode($data['data']);
    }
    //AJAX删除描述图片
    public function ajaxDelDesc(){
        //获得描述图片ID
        $id = I('get.descid');
        //获得旧图片路径
        $descModel = D('article_desc');
        $oldImg = $descModel->field('img_src')->where(array(
            'id' => array('eq',$id),
        ))->select();
        //从七牛云删除
        foreach($oldImg as  $v){
            $key = rtrim($v['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
        }
        //从数据库上删除
        $descModel->where(array(
            'id' => array('eq',$id),
        ))->delete();
    }
    //AJAX改变商品排序
    public function ajaxChangeSort(){
        $id = I('get.did');
        $sort = I('get.sort');
        $model = D('article_desc');
        $result = $model->save(array(
            'id' => $id,
            'sort_id' => $sort,
        ));
        if($result !== false){
            $data = true;
        }else{
            $data = false;
        }
        echo json_encode($data);
    }
}