<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#时间：2017年12月21日
namespace app\weixin\controller;
use think\Controller;
use think\Db;
use think\Request;
use first\second\Translate;
use first\second\JDvalue;
use first\second\Download;

class Start extends Controller
{
	public function index()
	{
		header('Content-type:text');
		if (Request::instance()->param("echostr")) {
			$this->valid();
		}else{
			$this->responseMsg();
			// $this->test();
		}
	}
	
    private function valid()
    {
        $echoStr = Request::instance()->param("echostr");
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }
	
	private function database($openid,$id,$name){
		switch ($id){
			case "1":
				$sql = Db::name('weixinuser')->where("weixin_openid",$openid)->count(0);
				break;
			case "2":
				$data = ["weixin_openid" => $openid];
				$sql = Db::name('weixinuser')->insert($data);
				break;
			case "3":
				$sql = Db::name('weixinuser')->where('weixin_openid',$openid)->update(['weixin_name'=>$name]);
				break;
			case "4":
				$sql = Db::name('weixinuser')->where('weixin_openid',$openid)->delete();
				break;
			default:
		}
		return $sql;
	}

    private function checkSignature()
    {
        $signature = Request::instance()->param('signature');
        $timestamp = Request::instance()->param('timestamp');
        $nonce = Request::instance()->param('nonce');

        $token = Db::name('accesskey')->find();
        $tmpArr = array($token["wxapptoken"], $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    private function responseMsg()
    {
        $postStr = Request::instance()->getInput();
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName; //发送方微信号
			$toUsername = $postObj->ToUserName;		//开发者微信公众帐号
			$MsgType = $postObj->MsgType;
			$time = time();
			if ($MsgType == "text"){
				$keyword = trim($postObj->Content);
				$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
				$keyword_one = mb_substr($keyword,0,5);
				switch ($keyword_one)
				{
					case "我要注册":
						if ($this->database($fromUsername,"1","")<=0){
							$this->database($fromUsername,"2","");
							$contentStr = "注册成功! 给账号一个昵称吧!\n回复:name=想要的昵称";
						}else {
							$contentStr = new Translate();
							$contentStr = $contentStr->go($keyword,"zh","en");
							$contentStr .= "\n已经有账号了!";
						}
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $contentStr);
						echo $resultStr;
						break;
					case "name=":
						$nu=mb_strlen($keyword,'utf-8');
						if ($this->database($fromUsername,"1","")>0){
							if ($this->database($fromUsername,"3",mb_substr($keyword,5,$nu))){
								$contentStr = "你好!".mb_substr($keyword,5,$nu);
							}
						}else {
							$contentStr = new Translate();
							$contentStr = $contentStr->go($keyword,"zh","en");
						}
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $contentStr);
						echo $resultStr;
						break;
					case "我要注销":
						if ($this->database($fromUsername,"1","")>0){
							$this->database($fromUsername,"4","");
							$contentStr = "很遗憾，可能是我不好看！没有足够吸引你的地方，我会快速成长的，再见！";
						}else {
							$contentStr = new Translate();
							$contentStr = $contentStr->go($keyword,"zh","en");
							$contentStr .= "\n你还没有注册账号,注册一个账号吧!注册后可以享受更多好玩、有趣的功能哦!\n回复:我要注册!";
						}
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $contentStr);
						echo $resultStr;
						break;
					case "京东商品:":
						$nu=mb_strlen($keyword,'utf-8');
						$top=mb_substr($keyword,5,$nu);
						if (is_numeric($top)){
							$jd_Price=new JDvalue($top);
							$jd_Price=$jd_Price->go($top);
							$contentStr = "现价:".$jd_Price[0]["op"]."\n";
							$contentStr .= "原价:".$jd_Price[0]["m"]."\n";
						}else
							$contentStr = "\"".$top."\"不是正确的产品ID";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $contentStr);
						echo $resultStr;
						break;
					case "http:":
					case "https":
						$start = strrpos($keyword, '/');
						$stop = strrpos($keyword, '.');
						$stop=$stop - $start - 1;
						$jd_commodityid = substr($keyword , $start+1,$stop);
						if (is_numeric($jd_commodityid)){
							$jd_Price= new JDvalue();
							$jd_Price= $jd_Price->go($jd_commodityid);
							$contentStr = "现价:".$jd_Price[0]["op"]."\n";
							$contentStr .= "原价:".$jd_Price[0]["m"]."\n";
						}else
							$contentStr = "\"".$keyword."\"不是正确的产品页面";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $contentStr);
						echo $resultStr;
						break;
					default:
						if (!preg_match("/[\x7f-\xff]/",$keyword)){
							$contentStr = new Translate();
							$contentStr = $contentStr->go($keyword,"zh","en");
						}elseif (preg_match("/[\x7f-\xff]/",$keyword)){
							$contentStr = new Translate();
							$contentStr = $contentStr->go($keyword,"zh","en");
						}else 
							$contentStr = "不支持该语言的翻译";
						if ($this->database($fromUsername,"1","")<=0){
							if (rand(1,10)<2){
								$contentStr .= "\n注册一个账号吧!注册后可以享受更多好玩、有趣的功能哦!\n回复:我要注册";
							}
						}
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $contentStr);
						echo $resultStr;
				}
			}elseif ($MsgType == "image"){
				$PicUrl = $postObj->PicUrl; //存储用户发来的图片链接地址,通过这个地址可以将图片另存为本地。
				// $MsgType = $postObj->MsgType;   //消息的类型
				$MediaID = $postObj->MediaId;   //图片消息媒体ID，根据这个值，可以发送图片信息
				$CreateTime = intval($postObj->CreateTime); //消息的创建时间,并且把这个时间转换成整数。
				$formTime = date("Y-m-d H:i:s",$CreateTime);
				
				//返回给微信服务器的模版
				$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";

				if ($MsgType == "image") {
					
					/*
					$msg  = "开发者id: ".$toUsername."\n";
					$msg .= "用户id: ".$fromUsername."\n";
					$msg .= "消息id: ".$MsgId."\n";
					$msg .= "消息发送过来的时间戳: ".$CreateTime."\n";
					$msg .= "消息类型: ".$MsgType."\n";
					$msg .= "图片消息链接地址 : ".$PicUrl."\n";
					$msg .= "图片消息媒体的id :".$MediaID."\n";
					*/

					$modeurl = "图片已经保存,可以访问:".Request::instance()->domain()."/";
					$msgType = "text";
					$contentStr = new Download();
					$contentStr = $contentStr->getImage($PicUrl);
					$contentStr = $modeurl.$contentStr["save_path"];
					$resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
					echo $resultStr;
					exit;
				}
			} else {
				exit;
			}
        }else{
			echo "";
            exit;
        }
	}
	private function test(){
		$keyword = "123";
		$contentStr = new Translate();
		$contentStr = $contentStr->go($keyword,"zh","en");
		print_r($contentStr);
	}
}
?>