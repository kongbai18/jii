<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
	//添加时允许接收的字段
	protected $inserField = array('username','password','password1','role_id','chkcode');
	//添加时允许接收的字段 
	protected $updateField = array('id','username','password','password1','role_id');
    //验证规则
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('password1', 'password', '两次密码输入不一致！', 1, 'confirm', 3),
		array('username', '', '用户名已经存在！', 1, 'unique', 3),
		array('role_id', 'checkRole', '请至少选择一个角色！', 1,'callback'),
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

        $roleId = I('post.role_id');
		if($roleId){
            //s删除原角色
            $arModel = D('admin_role');
            $arModel->where(array(
                'admin_id' => array('eq',$option['where']['id']),
            ))->delete();
            //添加新角色

            foreach($roleId as $k => $v){
                $arModel->add(array(
                    'admin_id' => $option['where']['id'],
                    'role_id' => $v,
                ));
            }
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
    //生成个人推广二维码
    public function get_prcode() {
        $access = json_decode(get_access_token(),true);
        $access_token= $access['access_token'];
        $id = session('id');
        $path="pages/index/spread/spread?id=".$id;
        $width=430;
        $post_data='{"path":"'.$path.'","width":'.$width.'}';
        $url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
        $result = get_http_array($url,$post_data);
        return $result;
    }
    //个人推广数据
    public function spread(){
	    $adminId = session('id');
	    $userModel = D('user');
	    $countNum = $userModel->where(array('admin_id'=>array('eq',$adminId)))->count();

	    $month = strtotime(date("Y-m"),time());
        $monthCountNum = $userModel->where(array('admin_id'=>array('eq',$adminId),'add_time'=>array('gt',$month)))->count();

        $today = strtotime(date("Y-m-d"),time());
        $todayCountNum = $userModel->where(array('admin_id'=>array('eq',$adminId),'add_time'=>array('gt',$today)))->count();

        $data = array(
            'countNum' => $countNum,
            'monthCountNum' => $monthCountNum,
            'todayCountNum' => $todayCountNum,
        );
        return $data;
    }
    //所有推广
    public function allSpread(){
	    $adminData = $this->field('id,username')->select();

	    $spreadData = array();
        $userModel = D('user');
        $month = strtotime(date("Y-m"),time());
        $today = strtotime(date("Y-m-d"),time());
	    foreach ($adminData as $k => $v){
            $countNum = $userModel->where(array('admin_id'=>array('eq',$v['id'])))->count();
            $monthCountNum = $userModel->where(array('admin_id'=>array('eq',$v['id']),'add_time'=>array('gt',$month)))->count();
            $todayCountNum = $userModel->where(array('admin_id'=>array('eq',$v['id']),'add_time'=>array('gt',$today)))->count();
            $spreadData[] = array(
                'countNum' => $countNum,
                'monthCountNum' => $monthCountNum,
                'todayCountNum' => $todayCountNum,
                'userName' => $v['username'],
            );
        }
        return $spreadData;
    }
}