<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#时间：2017年12月24日
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use	think\Session;
use	think\Loader;
use first\second\Smtp;
use first\second\Pop3;

class PhpMail extends Controller
{
	public function index()
	{
		$this->success('请你跟我走一趟！', 'http://www.mps.gov.cn/');
	}
	// 收取邮件
	public function Getmail(Request $request)
	{
		/******************** 提取账户 ******************************/
		$tomailinfo=Db::name('webinfo')->select();
	}
	// 发送邮件
	public function ToMail(Request $request){
		if (empty(Session::get('member'))){
			$this->success('请你跟我走一趟！', 'http://www.mps.gov.cn/');
		}
		if ($request->method()=="POST"){
			/******************** 提取账户 ******************************/
			$tomailinfo=Db::name('webinfo')->select();
			/******************** 配置信息 ******************************/
			$smtpserver = "ssl://smtpdm.aliyun.com";//SMTP服务器
			$smtpserverport =$tomailinfo[0]["toport"];//SMTP服务器端口
			$smtpusermail = $tomailinfo[0]["toname"];//SMTP服务器的用户邮箱
			$smtpemailto = Request::instance()->post('toemail');//发送给谁
			$smtpuser = $tomailinfo[0]["toname"];//SMTP服务器的用户帐号，注：部分邮箱只需@前面的用户名
			$smtppass = $tomailinfo[0]["topwd"];;//SMTP服务器的用户密码
			$mailtitle = Request::instance()->post('title');//邮件主题
			$mailcontent = "<h1>".Request::instance()->post('content')."</h1>";//邮件内容
			$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
			//******************* 正式发送 ******************************/
			$smtp = new Smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
			$smtp->debug = false;//是否显示发送的调试信息
			$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
			//******************* 返回结果 ******************************/
			if($state==""){
				return "对不起，邮件发送失败！请检查邮箱填写是否有误。";
			}
			return  "恭喜！邮件发送成功！！";
		}else 
			$this->success('请你跟我走一趟！', 'http://www.mps.gov.cn/');
	}
}