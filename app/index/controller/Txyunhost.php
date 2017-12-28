<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#时间：2017年12月11日
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

class Txyunhost extends Controller
{
	public function index()
	{
		$this->Hostlis();
		// $this->requestAli();
	}
	
	private function Hostlis()
	{
		$accesskey=Db::name('accesskey')->select();
		$accessKeyId  = $accesskey[0]['SecretId'];
		$accessSecrec = $accesskey[0]['SecretKey'];
		// echo $accessKeyId."<br>";
        $requestParams = array(
            "Action"	=>	"DescribeInstances",
			"InstanceIds.0" => "ins-2mr0j5l2",
			"Nonce"		=>  rand(1,99999999),
			"Region"	=>	"gz",
			"SecretId"	=>  $accessKeyId,
			// "SignatureMethod"   =>  "HmacSHA256",
            "Timestamp"	=>  time()
        );
		$GetMethod="GET";
		$GetHost="cvm.api.qcloud.com";
		$GetDir="/v2/index.php?";
		$tmp = $GetMethod.$GetHost.$GetDir;
		$i=0;
        foreach($requestParams as $key=>$val){
			if ($i==0){
				$tmp .= $this->percentEncode($key).'='.$this->percentEncode($val);
				$i++;
			}else 
				$tmp .= '&'.$this->percentEncode($key).'='.$this->percentEncode($val);
        }
		$signStr = base64_encode(hash_hmac('sha1', $tmp, $accessSecrec, true));
		$url = 'https://cvm.api.qcloud.com/v2/index.php?'."Nonce=".$requestParams["Nonce"]."&Region=".$requestParams["Region"]."&Timestamp=".$requestParams["Timestamp"]."&Action=".$requestParams["Action"]."&SecretId=".$requestParams["SecretId"]."&InstanceIds.0=".$requestParams["InstanceIds.0"]."&Signature=".$this->percentEncode($signStr);
		$result=$this->curl($url);
		$this->outPut($result);
	}
	
	private function requestAli($GetHost)
    {
		$GetMethod="GET";
		$GetDir="/v2/index.php?";
		$tmp = $GetMethod.$GetHost.$GetDir;
		$i=0;
        foreach($requestParams as $key=>$val){
			if ($i==0){
				$tmp .= $this->percentEncode($key).'='.$this->percentEncode($val);
				$i++;
			}else 
				$tmp .= '&'.$this->percentEncode($key).'='.$this->percentEncode($val);
        }
		$signStr = base64_encode(hash_hmac('sha1', $tmp, $accessSecrec, true));
		return $signStr;
    }
	
	private function sign($params, $accessSecrec, $method="GET")
    {
        ksort($params);
        $stringToSign = strtoupper($method).'&';

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
			return $result["message"];
		}
    }

    private function outPut($msg)
    {
        print_r($msg);
    }
}