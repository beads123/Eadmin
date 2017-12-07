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

class Aliyundns extends Controller
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
	
	/**
	 *	起始
	 */
	public function index(Request $request)
	{
		date_default_timezone_set("GMT");
		if ($request->method()=="POST"){
			
			switch(Request::instance()->post('status'))
			{
				case "Select":
					if (Request::instance()->post('domain')!=""){
						$this->DescribeDomainRecords();
					}else
						echo "domain Error";
					break;
				case "Create":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type')!="" && Request::instance()->post('ip')!=""){
						$this->AddDomainRecord();
					}else 
						echo "参数不完整!";
					break;
				case "Update":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type')!="" && Request::instance()->post('ip')!=""){
						$this->UpdateDomainRecord();
					}else 
						echo "参数不完整!";
					break;
				case "Delete":
					if (Request::instance()->post('domain')!="" && Request::instance()->post('host')!="" && Request::instance()->post('type')){
						$this->DeleteSubDomainRecords();
					}else 
						echo "参数不完整!";
					break;
				default:
					echo "Error start";
			}
		}
	}
	
	/**
	 * 获取解析记录列表
	 */
    public function DescribeDomainRecords()
    {
        $requestParams = array(
            "Action"    =>  "DescribeDomainRecords",
            "DomainName"    =>  Request::instance()->post('domain')
        );
        $val =  $this->requestAli($requestParams);
        $this->outPut($val);
    }
	
	/**
	 * 获取解析记录信息
	 */
    public function DescribeDomainRecords_two()
    {
        $requestParams = array(
            "Action"    =>  "DescribeDomainRecords",
            "DomainName"    =>  Request::instance()->post('domain')
        );
        $val = $this->requestAli($requestParams);
		$val = json_decode($val,true);
		for ($i=0;$i<$val["PageSize"];$i++){
			if ($val["DomainRecords"]["Record"][$i]["RR"]==Request::instance()->post('host')){
				return $val["DomainRecords"]["Record"][$i]["RecordId"];
				break;
			}
		}
    }

	/**
     * 添加解析记录
     */
    public function AddDomainRecord()
    {
        $requestParams = array(
            "Action"        =>  "AddDomainRecord",
            "DomainName"    =>  Request::instance()->post('domain'),
            "RR"            =>  Request::instance()->post('host'),
            "Type"          =>  Request::instance()->post('type'),
            "Value"         =>  Request::instance()->post('ip')
        );
        $val = $this->requestAli($requestParams);
        $this->outPut($val);
    }
	
    /**
     * 修改解析记录
     */
    public function UpdateDomainRecord()
    {
        $requestParams = array(
            "Action"        =>  "UpdateDomainRecord",
            "RecordId"      =>  $this->DescribeDomainRecords_two(),
            "RR"            =>  Request::instance()->post('host'),
            "Type"          =>  Request::instance()->post('type'),
            "Value"         =>  Request::instance()->post('ip')
        );
        $val = $this->requestAli($requestParams);
        $this->outPut($val);
    }
	
	/**
     * 删除解析记录
     */
    public function DeleteDomainRecord()
    {
        $requestParams = array(
            "Action"        =>  "DeleteDomainRecord",
            "RecordId"      =>  $this->DescribeDomainRecords_two()
        );
        $val =  $this->requestAli($requestParams);
        $this->outPut($val);
    }
	
	/*
	 * 删除主机记录对应的解析记录
	 */
	public function DeleteSubDomainRecords()
	{
        $requestParams = array(
            "Action"        =>  "DeleteSubDomainRecords",
			"DomainName"	=>	Request::instance()->post('domain'),
            "RR"            =>  Request::instance()->post('host'),
			"Type"			=>	Request::instance()->post('type')
        );
        $val = $this->requestAli($requestParams);
        $this->outPut($val);
	}

    private function requestAli($requestParams)
    {
		$accesskey=Db::name('accesskey')->select();
		$accessKeyId  = $accesskey[0]['alikey'];
		$accessSecrec = $accesskey[0]['aliSecret'];
        $publicParams = array(
            "Format"        =>  "JSON",
            "Version"       =>  "2015-01-09",
            "AccessKeyId"   =>  $accessKeyId,
            "Timestamp"     =>  date("Y-m-d\TH:i:s\Z"),
            "SignatureMethod"   =>  "HMAC-SHA1",
            "SignatureVersion"  =>  "1.0",
            "SignatureNonce"    =>  substr(md5(rand(1,99999999)),rand(1,9),14),
        );

        $params = array_merge($publicParams, $requestParams);
        $params['Signature'] =  $this->sign($params, $accessSecrec);
        $uri = http_build_query($params);
        $url = 'http://alidns.aliyuncs.com/?'.$uri;
        return $this->curl($url);
    }


    private function ip()
    {
        $ip = $this->curl("http://httpbin.org/ip");
        $ip = json_decode($ip,true);
        return $ip['origin'];
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