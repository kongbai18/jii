<?php
namespace Admin\Model;
use Think\Model;
class QuoteModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('id','user_id','address','telephone');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','type_name');
    //验证码规则
    protected $_validate = array(
           array('address','require','地址不能为空！',1),
           array('telephone','checkMobil','请输入正确的手机号！',1,'callback'),
    );
    public function checkMobil($mobile){
        if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
            return true;
        }else{
            return false;
        }
    }
    //搜索商品信息
    public function search($perpage){
        $where = array();
        $order = array();
        //商品名称搜索
        $keyword = I('get.keyword');
        if($keyword){
            $where['goods_name'] = array('like',"%$keyword%");
        }
        //品牌搜索
        $brandId = I('get.brand_id');
        if($brandId){
            $where['brand_id'] = array('eq',$brandId);
        }
        /*****************翻页*************************/
        //获取总记录数
        $count = $this->where($where)->count();
        //生成翻页对象类
        $pageObj = new \Think\Page($count,$perpage);
        //设置样式
        $pageObj->setConfig('next','下一页');
        $pageObj->setConfig('prev','上一页');
        //获取翻页字符串
        $pageString = $pageObj->show();
        /****************取一页的数据******************/
        $data = $this->field('a.*')
            ->alias('a')
            ->where($where)
            ->order($order)
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
        return  array(
            'data' => $data,
            'page' => $pageString,
        );
    }
    public function create_unique(){
        return create_unique();
    }
    //添加之前
    public function _before_insert(&$data,$option){
        $data['add_time'] = time();
        $data['id'] = $this->create_unique();
    }
    //修改之前
    public function _before_update(&$data,$option){
    	
    }
    //删除之前
    public function _before_delete($option){

    }

    public function checkQuote(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $data = $this->where(array('user_id'=>array('eq',$userId)))->select();
            if(empty($data)){
                return true;
            }else{
                return false;
            }
        }
    }
}