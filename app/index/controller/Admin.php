<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use	think\Session;
use	think\Loader;
use first\second\Smtp;
use first\second\receiveMail;

class Admin extends Controller
{
    public function index(Request $request)
    {
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		if ($request->method()!="POST"){
			$arr=[];
			$strs = @file("/proc/net/dev");
			for ($i = 2; $i < count($strs); $i++ ){
				preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
				$arr[$i-2] = $info;
			}
			$down=count($strs)-2;
			$this->assign('arr',$arr);
			$this->assign('down',$down);
			$task_number_head=Db::name('jdprice')->count(0);
			$task_jdprice_number=Db::name('jdprice')->count(0);
			$task_jdprice_value=Db::name('jdprice')->select();
			$this->assign('task_number_head',$task_number_head);
			$this->assign('task_jdprice_number',$task_jdprice_number);
			$this->assign('task_jdprice_value',$task_jdprice_value);
			// print_r($task_jdprice_value);
			return $this->fetch();
		}
    }
	
	public function tasks(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		if ($request->method()!="POST"){
			$task=Db::name('jdprice')->select();
			$task_number=Db::name('jdprice')->count(0);
			$this->assign('tasks',$task);
			$this->assign('task_number',$task_number);
			$task_number_head=Db::name('jdprice')->count(0);
			$this->assign('task_number_head',$task_number_head);
			return $this->fetch("/admin/tasks");
		}
	}
	
	public function Inbox(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		$task_number_head=Db::name('jdprice')->count(0);
		$this->assign('task_number_head',$task_number_head);
		return $this->fetch("/admin/message");
	}
	
	public function NewsFeed(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		$task_number_head=Db::name('jdprice')->count(0);
		$this->assign('task_number_head',$task_number_head);
		return $this->fetch("/admin/activity");
	}
	
	public function Buttons(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		$task_number_head=Db::name('jdprice')->count(0);
		$this->assign('task_number_head',$task_number_head);
		return $this->fetch("/admin/ui-button-icon");
	}
	
	public function Typography(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		$task_number_head=Db::name('jdprice')->count(0);
		$this->assign('task_number_head',$task_number_head);
		return $this->fetch("/admin/ui-typography");
	}
	public function Forms(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		$task_number_head=Db::name('jdprice')->count(0);
		$this->assign('task_number_head',$task_number_head);
		$web_info=Db::name('webinfo')->select();
		// print_r($web_info);
		$this->assign('web_info',$web_info);
		return $this->fetch("/admin/form");
	}
	public function Forms_webinfo_up(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		if ($request->method()=="POST"){
			$thismonth=date('Y-m-d H:i:s');
			$webinfo_up_status=Db::name('webinfo')->where('id',13)->update(['domain'=>Request::instance()->post('domain'),'title'=>Request::instance()->post('title'),'tomailser'=>Request::instance()->post('tomailser'),'toport'=>Request::instance()->post('toport'),'toname'=>Request::instance()->post('toname'),'topwd'=>Request::instance()->post('topwd'),'getmailser'=>Request::instance()->post('getmailser'),'getport'=>Request::instance()->post('getport'),'getname'=>Request::instance()->post('getname'),'getpwd'=>Request::instance()->post('getpwd'),'up_time'=>$thismonth]);
			if ($webinfo_up_status){
				return "OK";
			}else
				return "NO";
		}
	}
	public function Tables(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		$task_number_head=Db::name('jdprice')->count(0);
		$this->assign('task_number_head',$task_number_head);
		return $this->fetch("/admin/table");
	}
	public function Charts(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		$task_number_head=Db::name('jdprice')->count(0);
		$this->assign('task_number_head',$task_number_head);
		return $this->fetch("/admin/charts");
	}
	public function Getmail(Request $request)
	{
		/******************** 提取账户 ******************************/
		$tomailinfo=Db::name('webinfo')->select();
		/******************** 提取账户 ******************************/
		$obj = new receiveMail($tomailinfo[0]["getmailser"],$tomailinfo[0]["getport"],3,$tomailinfo[0]["getname"],$tomailinfo[0]["getpwd"],0);  
		print_r($obj);
		// 连接到邮件服务器  
		print_r($obj->connect($tomailinfo[0]["getmailser"],$tomailinfo[0]["getport"]));         //If connection fails give error message and exit  
		print_r($obj->login($tomailinfo[0]["getname"],$tomailinfo[0]["getpwd"]));         //If connection fails give error message and exit  
		// 读取全部信件  
		// $tot = $obj->getTotalMails(); //Total Mails in Inbox Return integer value  
		  
		// echo "收到$tot封邮件::<br>";  
		// for($i = $tot; $i > 0; $i--)  
		// {  
			// $head = $obj->getHeaders($i);  // 读取获取邮件头信息，返回数组 **数组键值为 (subject,to,toOth,toNameOth,from,fromName)  
			// echo "主题 :: ".$head['subject']."<br>";  
			// echo "收件人 :: ".$head['to']."<br>";  
			// echo "抄送 :: ".$head['toOth']."<br>";  
			// echo "发件人 :: ".$head['from']."<br>";  
			// echo "发件人名称 :: ".$head['fromName']."<br>";  
			// echo "<br><br>";  
			// echo "<br>*******************************************************************************************<br>";  
			// echo $obj->getBody($i);  // 邮件正文  
			// $str = $obj->GetAttach($i,"./"); // 获取邮件附件，返回的文件名以逗号隔开。 例如. (mailid, Path to store file)  
			// $ar = explode(",",$str);  
			// foreach($ar as $key=>$value)  
				// echo ($value == "") ? "" : "Atteched File :: " . $value . "<br>";  
			// echo "<br>------------------------------------------------------------------------------------------<br>";  
			//$obj->deleteMails($i); // Delete Mail from Mail box  
		// }  
		// $obj->close_mailbox();   //Close Mail Box  
	}
	public function tomail(Request $request){
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
			return "必须使用POST方法提交,当前请求为".$request->method()."方法";
	}
	//流量单位换算 1
	public function hbw($size) {
		// $size *= 8;
		if($size > 1024 * 1024 * 1024) {
				$size = round($size / 1073741824 * 100) / 100 ."GB";
		} elseif($size > 1024 * 1024) {
				$size = round($size / 1048576 * 100) / 100 ."MB";
		} elseif($size > 1024){
				$size = round($size / 1024 * 100) / 100 ."KB";
		} else
			$size = $size ."B";
		return $size;
	}
	//流量单位换算 2
	function getSymbolByQuantity($bytes) {
		$symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
		$exp = floor(log($bytes)/log(1024));

		return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
	}
	//获取CPU使用率
	public function CpuTime(){
		$file_path = "/proc/stat";
		$Total = 0;
		if(file_exists($file_path)){
			$cpu_arr = file($file_path);
		}
		preg_match_all("/\d+/",$cpu_arr[0],$arr);
		for ($i=0;$i<7;$i++){
			$Total += $arr[0][$i];
		}
		$cpu_idle = $arr[0][3];
		
		$arr= [
			"cpu_idle" => $cpu_idle,
			"Total" => $Total
		];
		return array($arr);
	}
	//获取流量信息
	public function NetWork(){
		$strs = @file("/proc/net/dev"); 
		for ($i = 0; $i < (count($strs)-2); $i++ )
		{
			preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i+2], $info );
			$NetOutSpeed[$i] = $info[10][0];
			$NetInputSpeed[$i] = $info[2][0];
			$NetInput[$i] = $this->hbw($info[2][0]);
			$NetOut[$i]  = $this->hbw($info[10][0]);
		}
		$arr[0]=$NetOutSpeed;
		$arr[1]=$NetInputSpeed;
		$arr[2]=$NetInput;
		$arr[3]=$NetOut;
		return array($arr);
	}
	//获取内存用量
	public function Memory(){
		$file_path = "/proc/meminfo";
		if(file_exists($file_path)){
			$file_arr = file($file_path);
			$file_arr[0] = str_replace("MemTotal: ","",$file_arr[0]);
			$file_arr[1] = str_replace("MemFree: ","",$file_arr[1]);
			$file_arr[2] = str_replace("MemAvailable: ","",$file_arr[2]);
			for ($i=0;$i<3;$i++){
				$file_arr[$i] = str_replace("kB","",$file_arr[$i]);
				$file_arr[$i] = str_replace("\n","",$file_arr[$i]);
				$file_arr[$i] = intval($file_arr[$i]);
			}
			$file_arr[3]=($file_arr[0]-$file_arr[1])/$file_arr[0]*100;
			$file_arr[0]=$file_arr[0]/1024;
			$file_arr[1]=$file_arr[1]/1024;
			$file_arr[3]=round($file_arr[3], 0);
			$file_arr[0]=round($file_arr[0], 0);
			$file_arr[1]=round($file_arr[1], 0);
		}
		$arr=[
			"MemTotal" => $file_arr[0],
			"MemFree" => $file_arr[1],
			"Mempa"	=> $file_arr[3]
		];
		return array($arr);
	}
	//获取磁盘使用量
	public function GetDisk(){
		$total = disk_total_space(".");
		$free = disk_free_space(".");
		return round((1-$free/$total)*100, 0);
	}
	
	//合集
	public function server(Request $request){
		if ($request->method()!="POST"){
			$net=$this->NetWork();
			$cpu=$this->CpuTime();
			$mem=$this->Memory();
			$disk=$this->GetDisk();
			$arr = [
				"net" => $net,
				"cpu" => $cpu,
				"mem" => $mem,
				"disk" => $disk
			];
			return json($arr);
		}
	}
}