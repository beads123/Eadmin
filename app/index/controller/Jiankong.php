<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#参考：https://help.aliyun.com/document_detail/35170.html?spm=5176.7738859.6.539.KDwgZZ
#时间：2017年12月08日
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

class Jiankong extends Controller
{
	private static $obj  = null;
    public static function Obj ()
    {
        if(is_null(self::$obj))
        {
            self::$obj = new self();
        }
        return self::$obj;
    }
	public function index(){
		date_default_timezone_set("GMT");
		 $requestParams = array(
            "Action"    =>  "QueryMetricList",
            "Project"   =>  "acs_ecs_dashboard",
			"Dimensions"=>	'[{instanceId:"i-28yc0uvx5"}]',
			"Metric"	=>	"cpu_idle",
			"Period"	=>	60
        );
        $val =  $this->requestAli($requestParams);
        $this->outPut($val);
	}
	private function requestAli($requestParams)
    {
		$accesskey=Db::name('accesskey')->select();
		$accessKeyId  = $accesskey[0]['alikey'];
		$accessSecrec = $accesskey[0]['aliSecret'];
        $publicParams = array(
            "Format"        =>  "JSON",
            "Version"       =>  "2017-03-01",
            "AccessKeyId"   =>  $accessKeyId,
            "Timestamp"     =>  date("Y-m-d\TH:i:s\Z"),
            "SignatureMethod"   =>  "HMAC-SHA1",
            "SignatureVersion"  =>  "1.0",
            "SignatureNonce"    =>  substr(md5(rand(1,99999999)),rand(1,9),14),
        );
        $params = array_merge($publicParams, $requestParams);
        $params['Signature'] =  $this->sign($params, $accessSecrec);
        $uri = http_build_query($params);
        $url = 'http://metrics.cn-qingdao.aliyuncs.com/?'.$uri;
        return $this->curl($url);
    }
    private function sign($params, $accessSecrec, $method="GET")
    {
        ksort($params);
        $stringToSign = strtoupper($method).'&'.$this->percentEncode('/').'&';

        $tmp = "";
        foreach($params as $key=>$val){
            $tmp .= '&'.$this->percentEncode($key).'='.$this->percentEncode($val);
        }
        $tmp = trim($tmp, '&');
        $stringToSign = $stringToSign.$this->percentEncode($tmp);

        $key  = $accessSecrec.'&';
        $hmac = hash_hmac("sha1", $stringToSign, $key, true);

        return base64_encode($hmac);
    }


    private function percentEncode($value=null)
    {
        $en = urlencode($value);
        $en = str_replace("+", "%20", $en);
        $en = str_replace("*", "%2A", $en);
        $en = str_replace("%7E", "~", $en);
		return $en;
    }

    private function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        $result=curl_exec ($ch);
		$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		if ($httpCode == 200){
			return $result;
		}else {
			$result = json_decode($result,true);
			return $result["Message"];
		}
    }

    private function outPut($msg)
    {
		// $msg = json_decode($msg,true);
        print_r($msg);
    }
}