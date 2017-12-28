<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#时间：2017年12月15日
namespace first\second;

class JDvalue 
{
	function go($jd_id){
	// define("URL",            "http://p.3.cn/prices/mgets?type=1&skuIds="); 
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
		$output=json_decode($output,true);
		return $output;
	}
}
?>