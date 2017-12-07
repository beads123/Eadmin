<?php
	namespace app\test\controller;
	use think\Controller;
	use think\Db;

	class T01dbtest extends Controller
	{
		public function index()
		{
			$result = Db::table('think_data')->find();
			$this->assign('result',$result);
			return $this->fetch();
		}
	}
