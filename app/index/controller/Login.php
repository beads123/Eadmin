<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use	think\Session;

class Login extends Controller
{
    public function index(Request $request)
    {
		$username=Request::instance()->post('name');
		$pwd=md5(Request::instance()->post('password'));
		if (!Session::has('name') && $username!="" && $pwd!=""){
			$result = Db::name('member')->where('member_user',$username)->find();
			if ($result["member_password"]==$pwd){
				Session::set('member',$username);
				$this->success('登陆成功', '/index/admin');
			}else 
				$this->success('密码错误', '/');
		}
		else
			$this->success('请先登陆', '/');
		
    }

	public function destroy()
    {
		Session::delete('member');
		return $this->success('注销成功', '/');
    }
	
	public function test()
	{	
		return $this->fetch();
	}
}