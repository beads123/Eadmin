<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#参考：https://www.cnblogs.com/hanyouchun/p/5382709.html
#时间：2017年11月11日
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use first\second\AliDomain;
use first\second\TxDomain;

class Aliyundns extends Controller
{	
	/**
	 *	起始
	 */
	public function index()
	{
		$accesskey=Db::name('accesskey')->select();
		if (Request::instance()->param("company")=="aliyun"){
			$Parameter = array(
				"accessKeyId"  => $accesskey[0]['alikey'],
				"accessSecrec" => $accesskey[0]['aliSecret']
			);
			$this->Aliyun($Parameter);
		}else if (Request::instance()->param("company")=="qcloud"){
			$Parameter = array(
				"SecretId"  => $accesskey[0]['SecretId'],
				"SecretKey" => $accesskey[0]['SecretKey']
			);
			$this->Txyun($Parameter);
		}else if (Request::instance()->param("company")=="jcloud"){
			$Parameter = array(
				"accessKeyId"  => $accesskey[0]['jdkey'],
				"accessSecrec" => $accesskey[0]['jdSecret']
			);
		}else 
			return "Error";
	}
	private function Aliyun($Parameter){
		if (Request::instance()->method()=="POST"){
			switch(Request::instance()->post('Action'))
			{
				case "DomainList":
					new AliDomain(Request::instance()->method(),Request::instance()->post('Action'),"",$Parameter);
					break;
				case "Select":
					if (Request::instance()->post('domain')!=""){
						new AliDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
					}else
						echo "domain Error";
					break;
				case "Create":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type')!="" && Request::instance()->post('ip')!=""){
						$Parameter['host']=Request::instance()->post('host');
						$Parameter['type']=Request::instance()->post('type');
						$Parameter['ip']=Request::instance()->post('ip');
						new AliDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
					}else 
						echo "参数不完整!";
					break;
				case "Update":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type')!="" && Request::instance()->post('ip')!=""){
						$Parameter['host']=Request::instance()->post('host');
						$Parameter['type']=Request::instance()->post('type');
						$Parameter['ip']=Request::instance()->post('ip');
						new AliDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
					}else 
						echo "参数不完整!";
					break;
				case "Delete":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type')){
						$Parameter['host']=Request::instance()->post('host');
						$Parameter['type']=Request::instance()->post('type');
						new AliDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
					}else 
						echo "参数不完整!";
					break;
				default:
					echo "Error start";
			}
		}else if (Request::instance()->method()=="GET"){
			if (Request::instance()->get('Action')=="DomainList"){
				new AliDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
			}
		}else 
			return "请求的参数不正确！";
	}
	private function Txyun($Parameter)
	{
		if (Request::instance()->method()=="POST"){
			switch(Request::instance()->post('Action'))
			{
				case "DomainList":
					new TxDomain(Request::instance()->method(),Request::instance()->post('Action'),"",$Parameter);
					break;
				case "Select":
					if (Request::instance()->post('domain')!=""){
						new TxDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
					}else
						echo "domain Error";
					break;
				case "Create":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type')!="" && Request::instance()->post('ip')!=""){
						$Parameter['host']=Request::instance()->post('host');
						$Parameter['type']=Request::instance()->post('type');
						$Parameter['ip']=Request::instance()->post('ip');
						new TxDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
					}else 
						echo "参数不完整!";
					break;
				case "Update":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type')!="" && Request::instance()->post('ip')!=""&& Request::instance()->post('newip')!=""){
						$Parameter['host']=Request::instance()->post('host');
						$Parameter['type']=Request::instance()->post('type');
						$Parameter['oldip']=Request::instance()->post('ip');
						$Parameter['newip']=Request::instance()->post('newip');
						new TxDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
					}else 
						echo "参数不完整!";
					break;
				case "Delete":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type') != "" && Request::instance()->post('ip')!=""){
						$Parameter['host']=Request::instance()->post('host');
						$Parameter['type']=Request::instance()->post('type');
						$Parameter['ip']=Request::instance()->post('ip');
						new TxDomain(Request::instance()->method(),Request::instance()->post('Action'),Request::instance()->post('domain'),$Parameter);
					}else 
						echo "参数不完整!";
					break;
				default:
					echo "Error start";
			}
		}else if (Request::instance()->method()=="GET"){
			if (Request::instance()->get('Action')=="DomainList"){
				new TxDomain(Request::instance()->method(),Request::instance()->post('Action'),"",$Parameter);
			}
		}else 
			return "请求的参数不正确！";
	}
	public function test (){
		$accesskey=Db::name('accesskey')->select();
		$Parameter = array(
				"accessKeyId"  => $accesskey[0]['alikey'],
				"accessSecrec" => $accesskey[0]['aliSecret'],
				"host"		=> "test",
				"type"		=>	"A",
				"ip"		=>	"116.85.27.169",
				"ip_two"	=>	"192.168.0.61"
			);
			// $this->Txyun($Parameter);
		new AliDomain("POST","Update","niexin.me",$Parameter);
	}
}