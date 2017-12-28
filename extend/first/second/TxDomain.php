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
namespace first\second;

class TxDomain 
{
	/**
	 *	起始
	 */
	public function __construct($Status,$Action,$Domain="",$Parameter)
	{
		if ($Status=="POST"){
			switch($Action)
			{
				case "DomainList":
					$this->DomainList($Parameter);
					break;
				case "Select":
					$this->RecordList($Domain,$Parameter);
					break;
				case "Create":
					$this->RecordCreate($Domain,$Parameter);
					break;
				case "Update":
					$this->RecordModify($Domain,$Parameter);
					break;
				case "Delete":
					$this->RecordDelete($Domain,$Parameter);
					break;
				case "test":
					$this->RecordDelete($Domain,$Parameter);
					break;
				default:
					echo "Error start";
			}
		}else if ($Status=="GET"){
			$this->DomainList($Parameter);
		}else 
			return "请求的参数不正确！";
	}
	/**
	 * 获取域名列表
	 * 作用:管理页面修改域名解析的时候，这里查询账号下拥有域名
	 */
	private function DomainList($Parameter)
	{
        $requestParams = array(
            "Action"	=>	"DomainList",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $Parameter["SecretId"],
            "Timestamp"	=>  time()
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$Parameter["SecretKey"]);
		$val=json_decode($val,true);
		$DomainList=array(
			$val["data"]["info"]["domain_total"]
		);
		for ($i=0;$i<$DomainList[0];$i++)
		{
			$DomainList[$i+1]=$val["data"]["domains"][$i]["name"];
		}
		$DomainList = json_encode($DomainList);
		print_r($DomainList);
	}
	/**
	 * 获取域名解析记录列表
	 */
	private function RecordList($Domain,$Parameter)
	{
        $requestParams = array(
            "Action"	=>	"RecordList",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $Parameter["SecretId"],
            "Timestamp"	=>  time(),
			"domain"	=>	$Domain
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$Parameter["SecretKey"]);
		$this->outPut($val);
	}
	/**
	 * 获取域名解析记录的ID
	 */
	private function DomainRecordId($Domain,$Parameter,$Action)
	{
        $requestParams = array(
            "Action"	=>	"RecordList",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $Parameter["SecretId"],
            "Timestamp"	=>  time(),
			"domain"	=>	$Domain
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$Parameter["SecretKey"]);
		$val=json_decode($val,true);
		if ($Action=="Update"){
			for ($i=0;$i<$val["data"]["info"]["record_total"];$i++)
			{
				if ($val["data"]["records"][$i]["name"]==$Parameter["host"]&&$val["data"]["records"][$i]["value"]==$Parameter["ip"]){
					return $val["data"]["records"][$i]["id"];
					break;
				}
			}
		}else 
			for ($i=0;$i<$val["data"]["info"]["record_total"];$i++)
			{
				if ($val["data"]["records"][$i]["name"]==$Parameter["host"]){
					return $val["data"]["records"][$i]["id"];
					break;
				}
			}
		return 0;
	}
	/**
     * 添加解析记录
     */
	private function RecordCreate($Domain,$Parameter)
	{
        $requestParams = array(
            "Action"	=>	"RecordCreate",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $Parameter["SecretId"],
            "Timestamp"	=>  time(),
			"domain"	=>	$Domain,
			"recordLine"=>	"默认",
			"recordType"=>	$Parameter['type'],
			"subDomain"	=>	$Parameter['host'],
			"value"		=>	$Parameter['ip']
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$Parameter["SecretKey"]);
		$this->outPut($val);
	}
	/**
     * 修改解析记录
     */
	private function RecordModify($Domain,$Parameter)
	{
		$result = $this->DomainRecordId($Domain,$Parameter,"Update");
		if ($result==0)
		{
			$result=array("message"=>"主机记录不存在");
			$result = json_encode($result);
			print_r($result);
			return 0;
		}
        $requestParams = array(
            "Action"	=>	"RecordModify",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $Parameter["SecretId"],
            "Timestamp"	=>  time(),
			"domain"	=>	$Domain,
			"recordId"	=>	$result,
			"recordLine"=>	"默认",
			"recordType"=>	$Parameter['type'],
			"subDomain"	=>	$Parameter['host'],
			"value"		=>	$Parameter['ip_two']
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$Parameter["SecretKey"]);
		$this->outPut($val);
	}
	/**
     * 删除解析记录
     */
	private function RecordDelete($Domain,$Parameter)
	{
		$result = $this->DomainRecordId($Domain,$Parameter,"Delete");
		if ($result==0)
		{
			$result=array("message"=>"主机记录不存在");
			$result = json_encode($result);
			print_r($result);
			return 0;
		}
        $requestParams = array(
            "Action"	=>	"RecordDelete",
			"Nonce"		=>  rand(1,99999999),
			"SecretId"	=>  $Parameter["SecretId"],
            "Timestamp"	=>  time(),
			"domain"	=>	$Domain,
			"recordId"	=>	$result
        );
		$val = $this->requestTx("cns.api.qcloud.com",$requestParams,$Parameter["SecretKey"]);
		$this->outPut($val);
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
    }
	
	private function sign($requestParams,$HostDir)
    {
		$arr = (["Nonce","Timestamp","Action","SecretId","domain","recordId","subDomain","recordType","recordLine","value","Signature"]);
		for ($i=0;$i<count($arr);$i++)
		{
			if (array_key_exists($arr[$i],$requestParams)){
				$arr2[$arr[$i]] = $requestParams[$arr[$i]];
			}
		}
		$tmp = "https://".$HostDir;
		$tmp .= http_build_query($arr2);
		return $this->curl($tmp);
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