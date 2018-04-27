<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
	public function __construct(){
		//必须先调用父类构造方法
		parent::__construct();
		//判断是否登录
        session_start();
		if(!session('id')){
			$this->error('必须先登录！',U('Login/login'));
		}
	}
}