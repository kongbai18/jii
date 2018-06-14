<?php
namespace Admin\Model;
use Think\Model;
class QuoteModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('id','address','user_name','telephone');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','type_name');
    //验证码规则
    protected $_validate = array(
           array('address','require','地址不能为空！',1),
           array('user_name','require','地址不能为空！',1),
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
    public function search($perpage,$admin=false){
        $where = array();
        $order = array();


        //用户名称搜索
        $uname = I('get.u_name');
        if($uname){
            $where['user_name'] = array('eq',$uname);
        }

        //用户手机搜索
        $uphone = I('get.u_phone');
        if($uphone){
            $where['telephone'] = array('eq',$uphone);
        }

        if($admin == '1'){
            $adminId = session('id');
            $where['admin_id'] = array('eq',$adminId);
        }elseif ($admin == '2'){
            //查询管理员订单
            $where['admin_id'] = array('neq','0');

            //管理员名称搜索
            $aname = I('get.a_name');
            if($aname){
                $adminModel = D('admin');
                $adminId = $adminModel->field('id')->where(array('username'=>array('eq',$aname)))->select();
                $where['admin_id'] = array('eq',$adminId[0]['id']);
            }
        }elseif ($admin == '3'){
            //查询用户订单
            $where['user_id'] = array('neq','0');
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
        $data['admin_id'] = session('id');
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
    public function getExcel(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $quoteId = $this->field('id')->where(array('user_id'=>array('eq',$userId)))->select();
            $model = D('Admin/Module');
            $data = $model->getExcel($quoteId[0]['id']);
            return $data;
        }
    }
}