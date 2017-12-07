<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#时间：2017年11月27日
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

class Jdapi extends Controller
{
	public function jd_create(Request $request){
		if ($request->method()=="POST"){
			$jd_id=Request::instance()->post('id');
			$jd_p=$this->jd_value($jd_id);
			$thismonth=date('Y-m-d H:i:s');
			$jd_data= ['product_name'=>Request::instance()->post('name'),'product_category'=>Request::instance()->post('category'),'product_Price'=>$jd_p,'product_number'=>$jd_id,'product_time'=>$thismonth];
			$jd_task_status=Db::name('jdprice')->insert($jd_data);
			if ($jd_task_status){
				return "OK";
			}else
				return "NO";
		}
	}
	public function jd_update(Request $request){
		if ($request->method()=="POST"){
			$jd_id=Request::instance()->post('id');
			$jd_p=$this->jd_value($jd_id);
			$thismonth=date('Y-m-d H:i:s');
			$jd_task_status=Db::name('jdprice')->where('product_number', $jd_id)->update(['product_Price' => $jd_p,'product_time'=>$thismonth]);
			if ($jd_task_status){
				$task=Db::name('jdprice')->where('product_number',$jd_id)->find();
				return $task["product_Price"];
			}else
				return "NO";
		}
	}
	public function jd_modify(Request $request){
		if ($request->method()=="POST"){
			$jd_id=Request::instance()->post('id');
			$jd_p=$this->jd_value($jd_id);
			$thismonth=date('Y-m-d H:i:s');
			$jd_task_status=Db::name('jdprice')->where('product_number', $jd_id)->update(['product_name'=>Request::instance()->post('name'),'product_category'=>Request::instance()->post('category'),'product_Price' => $jd_p,'product_time'=>$thismonth]);
			if ($jd_task_status){
				return "OK";
			}else
				return "NO";
		}
	}
	public function jd_delete(Request $request){
		if ($request->method()=="POST"){
			$jd_id=Request::instance()->post('id');
			$jd_task_status=Db::name('jdprice')->where('product_number',$jd_id)->delete();
			if ($jd_task_status){
				return "OK";
			}else
				return "NO";
		}
	}
	function jd_value($jd_id){
		// define("URL","http://p.3.cn/prices/mgets?type=1&skuIds="); 
		$jd_url = "http://p.3.cn/prices/mgets?type=1&skuIds=";
		$jd_url .= $jd_id;
		$ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $jd_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		//释放curl句柄
		curl_close($ch);
		//打印获得的数据
		// print_r($output);
		$output=json_decode($output,true);
		return $output[0]["p"];
	}
}