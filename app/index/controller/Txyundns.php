<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#参考：https://cloud.tencent.com/document/api/302/4031
#时间：2017年12月11日
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;

class Txyundns extends Controller
{
	public function index()
	{
		if (Request::instance()->method()=="POST"){
			
			switch(Request::instance()->post('Action'))
			{
				case "DomainList":
					$this->DescribeDomains();
					break;
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
		}else if (Request::instance()->method()=="GET"){
			if (Request::instance()->get('Action')=="DomainList"){
				$DomainTo=$this->DescribeDomains();
			}
		}else 
			return "请求的参数不正确！";
	}
	/**
	 * 获取域名列表
	 * 作用:管理页面修改域名解析的时候，这里查询账号下拥有域名
	 */
	private function DomainList()
	{
		$UserKey = $this->GetUserKey();
        $requestParams = array(
            "Action"	=>	"DomainList",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $UserKey[0],
            "Timestamp"	=>  time()
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$UserKey[1]);
		$val=json_decode($val,true);
		print_r($val["data"]["domains"][0]["name"]);
	}
	/**
	 * 获取域名解析记录列表
	 */
	private function RecordList()
	{
		$UserKey = $this->GetUserKey();
        $requestParams = array(
            "Action"	=>	"RecordList",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $UserKey[0],
            "Timestamp"	=>  time(),
			"domain"	=>	Request::instance()->post("domain")
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$UserKey[1]);
		$this->outPut($val);
	}
	/**
     * 添加解析记录
     */
	private function RecordCreate()
	{
		$UserKey = $this->GetUserKey();
        $requestParams = array(
            "Action"	=>	"RecordCreate",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $UserKey[0],
            "Timestamp"	=>  time(),
			"domain"	=>	Request::instance()->post("domain"),
			"recordLine"=>	"默认",
			"recordType"=>	Request::instance()->post("type"),
			"subDomain"	=>	Request::instance()->post("host"),
			"value"		=>	Request::instance()->post("ip")
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$UserKey[1]);
		$this->outPut($val);
	}
	/**
     * 修改解析记录
     */
	private function RecordModify()
	{
		$UserKey = $this->GetUserKey();
        $requestParams = array(
            "Action"	=>	"RecordModify",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $UserKey[0],
            "Timestamp"	=>  time(),
			"domain"	=>	Request::instance()->post("domain"),
			"recordLine"=>	"默认",
			"recordType"=>	Request::instance()->post("type"),
			"subDomain"	=>	Request::instance()->post("host"),
			"value"		=>	Request::instance()->post("ip")
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$UserKey[1]);
		$this->outPut($val);
	}
	/**
     * 删除解析记录
     */
	private function RecordDelete()
	{
		$UserKey = $this->GetUserKey();
        $requestParams = array(
            "Action"	=>	"RecordDelete",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $UserKey[0],
            "Timestamp"	=>  time(),
			"domain"	=>	Request::instance()->post("domain"),
			"recordLine"=>	"默认",
			"recordType"=>	Request::instance()->post("type"),
			"subDomain"	=>	Request::instance()->post("host"),
			"value"		=>	Request::instance()->post("ip")
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$UserKey[1]);
		$this->outPut($val);
	}
	/*
	 * 从数据库中取出腾讯云的access key
	 */
	private function GetUserKey(){
		$accesskey=Db::name('accesskey')->select();
		$accessKeyId  = $accesskey[0]['SecretId'];
		$accessSecrec = $accesskey[0]['SecretKey'];
		return array($accessKeyId,$accessSecrec);
	}
	
	private function requestTx($ToHost,$requestParams,$UserKeySecrec)
    {
		$GetMethod="GET";
		$ToDir="/v2/index.php?";
		$tmp = $GetMethod.$ToHost.$ToDir;
		$i=0;
        foreach($requestParams as $key=>$val){
			if ($i==0){
				$tmp .= $key.'='.$val;
				$i++;
			}else 
				$tmp .= '&'.$key.'='.$val;
        }
		$signStr = base64_encode(hash_hmac('sha1', $tmp, $UserKeySecrec, true));
		$requestParams["Signature"]=$signStr;
		return $this->sign($requestParams,$ToHost.$ToDir);
		// return $val;
    }
	
	private function sign($requestParams,$HostDir)
    {
		$arr = (["Nonce","Timestamp","Action","SecretId","domain","subDomain","recordType","recordLine","value","Signature"]);
		for ($i=0;$i<count($arr);$i++)
		{
			if (array_key_exists($arr[$i],$requestParams)){
				$arr2[$arr[$i]] = $requestParams[$arr[$i]];
			}
		}
		$tmp = "https://".$HostDir;
		$tmp .= http_build_query($arr2);
		return $this->curl($tmp);
		// return $result;
    }
	
	public function test()
	{
		$signStr = "adsnaindinsifaid=lansdokna";
		$requestParams = array(
            "Action"	=>	"RecordCreate",
			"Nonce"		=>  rand(1,99999999),
			"Region"	=>	"gz",
			"SecretId"	=>  "123",
            "Timestamp"	=>  time(),
			"domain"	=>	"nxbskl.com",
			"recordLine"=>	"默认",
			"recordType"=>	"A",
			"subDomain"	=>	"www",
			"value"		=>	"192.168.0.60"
        );
		// print_r($requestParams);
		// unset($requestParams["Region"]);
		// print_r($requestParams);
		
		// $url=$url.$tmp;
		echo $url.$tmp;
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