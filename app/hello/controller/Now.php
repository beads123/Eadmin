<?php
namespace app\hello\controller;

use think\Controller;

class Now extends Controller
{
	public function index()
	{
		$this->assign('now', date('Y-m-d H:i:s'));
		return $this->fetch();
	}
}
