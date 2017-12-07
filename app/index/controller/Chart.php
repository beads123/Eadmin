<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Chart extends Controller
{
    public function index()
    {
		$arr=[];
		$strs = @file("/proc/net/dev");
		for ($i = 2; $i < count($strs); $i++ ){
			preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
			$arr[$i-2] = $info;
			// echo $info[1][0];
		}
		// print_r($arr);
		$down=count($strs);
		// $info = User::all();
		$this->assign('arr',$arr);
		$this->assign('down',$down-2);
		// return $this->fetch();
		return $this->fetch();
    }
}