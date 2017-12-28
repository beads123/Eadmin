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
namespace first\second;

class AliDomain 
{
	/**
	 *	起始
	 */
	public function __construct($Status,$Action,$Domain="",$Parameter)
	{
		date_default_timezone_set("GMT");
		if ($Status=="POST"){
			switch($Action)
			{
				case "DomainList":
					$this->DescribeDomains($Parameter);
					break;
				case "Select":
					$this->DescribeDomainRecords($Domain,$Parameter);
					break;
				case "Create":
					$this->AddDomainRecord($Domain,$Parameter);
					break;
				case "Update":
					$this->UpdateDomainRecord($Domain,$Parameter);
					break;
				case "Delete":
					$this->DeleteSubDomainRecords($Domain,$Parameter);
					break;
				default:
					return "Error start";
			}
		}else if ($Status=="GET"){
			$this->DescribeDomains($Parameter);
		}else 
			return "请求的参数不正确！";
	}
	
	/**
	 * 获取域名列表
	 * 作用:管理页面修改域名解析的时候，这里查询账号下拥有域名
	 */
    private function DescribeDomains($Parameter)
    {
		date_default_timezone_set("GMT");
        $requestParams = array(
            "Action"    =>  "DescribeDomains",
        );
        $val =  $this->requestAli($requestParams,$Parameter);
        $val=json_decode($val,true);
		$DomainList=array(
			$val["TotalCount"]
		);
		for ($i=0;$i<$val["TotalCount"];$i++)
		{
			$DomainList[$i+1]=$val["Domains"]["Domain"][$i]["DomainName"];
		}
		$DomainList = json_encode($DomainList);
		print_r($DomainList);
    }
	
	/**
	 * 获取解析记录列表
	 */
    private function DescribeDomainRecords($Domain,$Parameter)
    {
        $requestParams = array(
            "Action"    =>  "DescribeDomainRecords",
            "DomainName"    =>  $Domain
        );
        $val =  $this->requestAli($requestParams,$Parameter);
        $this->outPut($val);
    }
	
	/**
	 * 获取解析记录信息
	 */
    private function DescribeDomainRecords_two($Domain,$Parameter)
    {
        $requestParams = array(
            "Action"    =>  "DescribeDomainRecords",
            "DomainName"    =>  $Domain
        );
        $val = $this->requestAli($requestParams,$Parameter);
		$val = json_decode($val,true);
		for ($i=0;$i<$val["PageSize"];$i++){
			if ($val["DomainRecords"]["Record"][$i]["RR"]==$Parameter['host']){
				return $val["DomainRecords"]["Record"][$i]["RecordId"];
				break;
			}
		}
    }

	/**
     * 添加解析记录
     */
    private function AddDomainRecord($Domain,$Parameter)
    {
        $requestParams = array(
            "Action"        =>  "AddDomainRecord",
            "DomainName"    =>  $Domain,
            "RR"            =>  $Parameter['host'],
            "Type"          =>  $Parameter['type'],
            "Value"         =>  $Parameter['ip']
        );
        $val = $this->requestAli($requestParams,$Parameter);
        $this->outPut($val);
    }
	
    /**
     * 修改解析记录
     */
    private function UpdateDomainRecord($Domain,$Parameter)
    {
        $requestParams = array(
            "Action"        =>  "UpdateDomainRecord",
            "RecordId"      =>  $this->DescribeDomainRecords_two($Domain,$Parameter),
            "RR"            =>  $Parameter['host'],
            "Type"          =>  $Parameter['type'],
            "Value"         =>  $Parameter['ip']
        );
        $val = $this->requestAli($requestParams,$Parameter);
        $this->outPut($val);
    }
	
	/**
     * 删除解析记录
     */
    private function DeleteDomainRecord($Domain)
    {
        $requestParams = array(
            "Action"        =>  "DeleteDomainRecord",
            "RecordId"      =>  $this->DescribeDomainRecords_two($Domain)
        );
        $val =  $this->requestAli($requestParams,$Parameter);
        $this->outPut($val);
    }
	
	/*
	 * 删除主机记录对应的解析记录
	 */
	private function DeleteSubDomainRecords($Domain,$Parameter)
	{
        $requestParams = array(
            "Action"        =>  "DeleteSubDomainRecords",
			"DomainName"	=>	$Domain,
            "RR"            =>  $Parameter["host"],
			"Type"			=>	$Parameter["type"]
        );
        $val = $this->requestAli($requestParams,$Parameter);
        $this->outPut($val);
	}

    private function requestAli($requestParams,$Parameter)
    {
        $publicParams = array(
            "Format"        =>  "JSON",
            "Version"       =>  "2015-01-09",
            "AccessKeyId"   =>  $Parameter["accessKeyId"],
            "Timestamp"     =>  date("Y-m-d\TH:i:s\Z"),
            "SignatureMethod"   =>  "HMAC-SHA1",
            "SignatureVersion"  =>  "1.0",
            "SignatureNonce"    =>  substr(md5(rand(1,99999999)),rand(1,9),14),
        );

        $params = array_merge($publicParams, $requestParams);
        $params['Signature'] =  $this->sign($params, $Parameter["accessSecrec"]);
        $uri = http_build_query($params);
        $url = 'http://alidns.aliyuncs.com/?'.$uri;
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
		// return json($msg);
    }
}