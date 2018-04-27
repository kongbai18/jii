<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
    //添加时允许接收的字段
    protected $inserField = array('username','password','password1','role_id','chkcode');
    //添加时允许接收的字段
    protected $updateField = array('id','username','password','password1');
    //验证规则
    protected $_validate = array(
        array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
        array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
        array('password', 'require', '密码不能为空！', 1, 'regex', 1),
        array('password1', 'password', '两次密码输入不一致！', 1, 'confirm', 3),
        array('username', '', '用户名已经存在！', 1, 'unique', 3),
    );
    //验证是否选择角色
    protected function checkRole(){
        $roleId =I('post.role_id');
        if($roleId){
            return true;
        }else{
            return false;
        }
    }
    // 为登录的表单定义一个验证规则
    public $_login_validate = array(
        array('username', 'require', '用户名不能为空！', 1),
        array('password', 'require', '密码不能为空！', 1),
        array('chkcode', 'require', '验证码不能为空！', 1),
        array('chkcode', 'check_verify', '验证码不正确！', 1, 'callback'),
    );
    // 验证验证码是否正确
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        session_start();
        return $verify->check($code, $id);
    }
    //登录
    public function login()
    {
        // 从模型中获取用户名和密码
        $username = I('post.username');
        $password = I('post.password');
        // 先查询这个用户名是否存在
        $user = $this->where(array(
            'username' => array('eq', $username),
        ))->find();
        if($user)
        {
            if($user['password'] == md5($password))
            {
                // 登录成功存session
                session_start();
                session('id', $user['id']);
                session('username', $user['username']);
                return TRUE;
            }
            else
            {
                $this->error = '密码不正确！';
                return FALSE;
            }
        }
        else
        {
            $this->error = '用户名不存在！';
            return FALSE;
        }
    }
    //退出
    public function logout(){
        session(null);
    }
    //添加前
    public function _before_insert(&$data,$option){
        $data['password'] = md5($data['password']);
    }
    //修改前
    public function _before_update(&$data,$option){
        if($data['password']) {
            $data['password'] = md5($data['password']);
        }else{
            unset($data['password']);
        }

    }
    //删除前
    public function _before_delete($option){
        if($option['where']['id'] == 1){
            $this->error = '超级管理员无法删除！';
            return FALSE;
        }else{
            //删除用户角色
            $arModel = D('admin_role');
            $arModel->where(array(
                'admin_id' => array('eq',$option['where']['id']),
            ))->delete();
        }
    }
    //添加后
    public function _after_insert($data,$option){
        //获取角色ID
        $roleId = I('post.role_id');
        $arModel = D('admin_role');
        foreach($roleId as $k => $v){
            $arModel->add(array(
                'admin_id' => $data['id'],
                'role_id' => $v,
            ));
        }
    }
}