<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use	think\Session;
use	think\Loader;
use first\second\Smtp;

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
			$weixinuser=Db::name('weixinuser')->count(0);
			$task_jdprice_value=Db::name('jdprice')->select();
			$this->assign('task_number_head',$task_number_head);
			$this->assign('task_jdprice_number',$task_jdprice_number);
			$this->assign('task_jdprice_value',$task_jdprice_value);
			$this->assign('weixinuser',$weixinuser);
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
	public function Forms_1(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		$task_number_head=Db::name('jdprice')->count(0);
		$this->assign('task_number_head',$task_number_head);
		$accesskey=Db::name('accesskey')->select();
		// print_r($accesskey);
		$this->assign('accesskey',$accesskey);
		return $this->fetch("/admin/form-1");
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
	private function Forms_accesskey(Request $request)
	{
		if (empty(Session::get('member'))){
			$this->success('请先登陆', '/');
		}
		if ($request->method()=="POST"){
			$thismonth=date('Y-m-d H:i:s');
			$accesskey_up_status=Db::name('accesskey')->where('id',13)->update(['domain'=>Request::instance()->post('domain'),'title'=>Request::instance()->post('title'),'tomailser'=>Request::instance()->post('tomailser'),'toport'=>Request::instance()->post('toport'),'toname'=>Request::instance()->post('toname'),'topwd'=>Request::instance()->post('topwd'),'getmailser'=>Request::instance()->post('getmailser'),'getport'=>Request::instance()->post('getport'),'getname'=>Request::instance()->post('getname'),'getpwd'=>Request::instance()->post('getpwd'),'up_time'=>$thismonth]);
			if ($accesskey_up_status){
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
	//流量单位换算 1
	private function hbw($size) {
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
	private function getSymbolByQuantity($bytes) {
		$symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
		$exp = floor(log($bytes)/log(1024));

		return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
	}
	//获取CPU使用率
	private function CpuTime(){
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
	private function NetWork(){
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
	private function Memory(){
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
	private function GetDisk(){
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