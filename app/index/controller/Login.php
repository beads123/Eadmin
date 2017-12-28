<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use	think\Session;

class Login extends Controller
{
    public function index()
    {
		if (Request::instance()->method()=="POST"){
			$username=Request::instance()->post('name');
			$pwd=Request::instance()->post('password');
			if (!Session::has('name') && $username!="" && $pwd!=""){
				$result = Db::name('member')->where('member_user',$username)->find();
				if ($result["member_password"]==$pwd){
					Session::set('member',$username);
					return "yes";
				}else 
					return "passwdno";
			}
		}
    }

	public function destroy()
    {
		Session::delete('member');
		return $this->success('注销成功', '/');
    }
}