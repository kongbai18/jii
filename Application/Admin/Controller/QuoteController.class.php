<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8 0008
 * Time: 8:48
 */
namespace Admin\Controller;
class QuoteController extends BaseController {
    //模型列表
    public function lst(){
        $model = D('quote');
        $data = $model->search();
        //数据assign到页面中
        $this->assign(array(
            'data'  => $data,
            'title' => '报价单列表',
            'btn_name' => '添加报价单',
            'btn_url' => U('add')
        ));
        $this->display();
    }
    //类别增加
    public function add(){
        $model = D('quote');
        //判断是否接收表单
        if(IS_POST){
            $_POST['user_id'] = 0;
            //判断是否验证成功
            if($model->create(I('post.'),1)){
                //判断是否添加成功
                if($model->add()){
                    $this->success('类型添加成功！',U('detail?id='.$_POST['id']));
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        //数据assign到页面中
        $this->assign(array(
            'title' => '添加报价单',
            'btn_name' => '报价单列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //类别修改
    public function edit(){
        //获取需要修改类型的ID
        $id = I('get.id');
        $model = D('type');
        //判断是否接收表单
        if(IS_POST){
            //判断是否验证成功
            if($model->create(I('post.'),2)){
                //判断是否修改成功
                if(FALSE !== $model->save()){
                    $this->success('类型修改成功！',U('lst'));
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        //获取修改类型数据
        $data = $model->find($id);
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '修改类型',
            'btn_name' => '类型列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //模型删除
    public function delete(){
        //接收要删除模型的ID
        $id = I('get.id');
        $model = D('quote');
        //判断是否删除成功
        if($model->delete($id)){
            $this->success('报价单删除成功！',U('lst'));
        }
        $this->error($model->getError());
    }
    //报价单详情
    public function detail(){
        $id = I('get.id');
        $model = D('quote');
        $data = $model->find($id);
        $moduleModel = D('module');
        $moduleData = $moduleModel->getInfo($id);
        $this->assign(array(
            'moduleData' => $moduleData,
            'data' => $data,
            'title' => '报价单详情',
            'btn_name' => '添加家具',
            'btn_url' => U('chooseFurniture?id='.$id)
        ));
        $this->display();
    }
    public function chooseFurniture(){
        $id = I('get.id');
        $model = D('furniture');
        $furData = $model->field('id,fur_name,img_src')->order('sort_id desc')->select();
        $this->assign(array(
            'quote' => $id,
            'furData' => $furData,
            'title' => '选择模型',
            'btn_name' => '返回报价单详情',
            'btn_url' => U('detail?id='.$id)
        ));
        $this->display();
    }
    public function addModule(){
        $quoteId = I('get.quoteId');
        $furId = I('get.id');
        $furModel = D('furniture');
        $furData = $furModel->find($furId);

        //判断是否接收表单
        if(IS_POST){
            //var_dump($_POST);die;
            $furQuoId = I('post.fur-quo');
            if(!$furQuoId){
                $this->error('请选择存在的配置！');
            }
            //从家具计算属性表获取对应计算模型ID
            $furQuoModel = D('furniture_quote');
            $furQuoData = $furQuoModel->field('model_id')->find($furQuoId);

            $_POST['model_id'] = $furQuoData['model_id'];
            $_POST['fur_name'] = $furData['fur_name'];
            $_POST['cate_id'] = $furData['cate_id'];
            $_POST['quote_id'] = $quoteId;

            //根据计算模型ID获取计算模型
            $moModel = D('model');
            $moData = $moModel->find($furQuoData['model_id']);

            //获取选择得材料
            $material = json_decode($moData['material'],true);
            $_POST['material'] = array();
            foreach($material as $k => $v){
                $$k = I('post.'.$k);
                if(empty($$k)){
                    $this->error('请选择完整的材料！');
                }
                    $_POST['material'][$k] = $$k;
            }

            //获取所填参数
            $parameter = json_decode($moData['parameter'],true);
            $_POST['parameter'] = array();
            foreach ($parameter as $k => $v){
                $$v = I('post.'.$v);
                if(empty($$v)){
                    $this->error('请填写完整参数！');
                }
                $_POST['parameter'][$v] = $$v;
                unset($_POST[$v]);
            }

            //获取扩展参数
            $extend = json_decode($moData['ext'],true);
            $_POST['ext'] = array();
            foreach ($extend as $k => $v){
                foreach ($v as $k1 => $v1){
                    $$v1[1] = I('post.'.$v1[1]);
                    if($k1 == '3'){
                        if(!is_numeric ($$v1[1][0])){
                            $this->error($v1[0].'参数必须为数字类型！');
                        }
                    }else{
                        if(!$$v1[1]){
                            $this->error($v1[0].'必须选择！');
                        }
                    }

                    $total = 0;

                    if($k1 == '1'){
                        $total = $total + $$v1[1];
                    }else{
                        foreach ($$v1[1] as $k2 => $v2){
                            $total = $total + $v2;
                        }
                    }

                    $_POST['ext'][$v1[1]] = $total;
                }
            }

             $moduleModel = D('module');
            //判断是否验证成功
            if($moduleModel->create(I('post.'),1)){

                //判断是否修改成功
                if($moduleModel->add()){
                    $quoteModel = D('quote');
                    $data = array(
                      'id' => $quoteId,
                      'update_time' => time(),
                    );
                    $quoteModel->save($data);
                    $this->success('模块产品添加成功！',U('detail?id='.$quoteId));
                }
            }
            //添加失败
            $this->error($moduleModel->getError());
        }


        $this->assign(array(
            'furData' => $furData,
            'furId' => $furId,
            'title' => '添加家具',
            'btn_name' => '重新选择家具',
            'btn_url' => U('chooseFurniture?id='.$quoteId)
        ));
        $this->display();
    }
    //AJAX获取计价模型
    public function ajaxGetModel(){
        $furId = I('get.furId');
        $attr = I('get.attr');
        $attr = rtrim($attr,',');
        $model = D('furniture_quote');
        $goodsModel = D('goods');
        $data = $model->field('a.id,a.img_src,b.material,b.parameter,b.ext')
            ->alias('a')
            ->where(array('fur_id'=>array('eq',$furId),'fur_attr_id'=>array('eq',$attr)))
            ->join('LEFT JOIN __MODEL__ b ON a.model_id = b.id')
            ->select();
        if(!empty($data)){
            $material = json_decode($data[0]['material'],true);
            foreach ($material as $k => &$v){
                foreach ($v as $k1 => &$v1){
                    $cateId = explode(',',$v1);
                    $goodsData = $goodsModel->field('id,goods_name')->where(array('cat_id'=>array('in',$cateId),'is_quote'=>array('eq','1')))->select();
                    $v1 = $goodsData;
                }
            }
            $data[0]['material'] = $material;
        }
        echo json_encode($data);
    }
}