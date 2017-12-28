<?php
	   /**声明**\
	  /          \
	 /            \
	/              \
	\****niexin****/
#作者：9527_xiaotaoyan
#博客：https://www.liarme.com/
#时间：2017年12月24日
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Loader;
use first\second\Smtp;
use first\second\Pop3;

class Getmail extends Controller
{
	public function index(){
		$file_path = "newfile.txt";
		if(file_exists($file_path)){
			$file_arr = file($file_path);
			for($i=0;$i<count($file_arr);$i++){//逐行读取文件内容
				// echo $file_arr[$i]."<br />";
				// 取出编码格式
				$regex = "/^Content-Transfer-Encoding:/i";
				if (preg_match($regex ,$file_arr[$i])){
					// $str = $this->decode_mime($file_arr[$i]);
					// $bm = mb_detect_encoding($str, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
					// if ($bm!="UTF-8"){
						$bm = str_replace("Content-Transfer-Encoding:","",$file_arr[$i]);
						$xp = array();
						$oo = 0;
						for ($p=$i;$p<count($file_arr);$p++){
							// $regex = "//";
							if (strlen($file_arr[$p])==1){
								// echo "0";
								$xp[$oo]=$p;
								$oo++;
							}
						}
						// print_r($xp);
						$con = "";
						// echo $bm;
						// if ($bm=="base64"){
							// echo 0;
							for ($o=$xp[0];$o<$xp[1];$o++){
								$con .= $file_arr[$o];
							}
						// }
						echo iconv("gb2312","utf-8//IGNORE",base64_decode($con));
				}
			}
		}
	}
	// 中国招商银行
	public function Cmb(){
		$file_path = "newfile.txt";
		if(file_exists($file_path)){
			$file_arr = file($file_path);
			for($i=0;$i<count($file_arr);$i++){//逐行读取文件内容
				// echo $file_arr[$i]."<br />";
				$regex = "/^From:/i";
				if (preg_match($regex ,$file_arr[$i])){
					$str = $this->decode_mime($file_arr[$i]);
					$bm = mb_detect_encoding($str, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
					if ($bm!="UTF-8"){
						$str1 = str_replace("From:","",iconv($bm,"utf-8//IGNORE",$str));
						$str1 = str_replace('"',"",$str1);
						for ($o=0;$o<mb_strlen($str1);$o++){
							if ($str1[$o]=="<"){
								echo substr($str1,0,$o)."<br />";
								break;
							}
						}
					}
				}
			}
		}
	}
	// 亚马逊购物网站
	public function Amazon(){
		$file_path = "newfile.txt";
		$str = "";
		if(file_exists($file_path)){
			$file_arr = file($file_path);
			for($i=0;$i<count($file_arr);$i++){//逐行读取文件内容
				// echo $file_arr[$i]."<br />";
				$regex = "/^Subject:| =\?UTF-8\?/i";
				if (preg_match($regex ,$file_arr[$i])){
					$str .= $this->decode_mime($file_arr[$i]);
				}
			}
			$str1 = str_replace("Subject:","",$str);
			$str1 = str_replace("\n","",$str1);
			$str1 = str_replace(" ","",$str1);
			echo $str1;
		}
	}
	
	public function test(){
		$MailInfo=Db::name('webinfo')->select();
		$host=$MailInfo[0]["getmailser"]; 
		$user=$MailInfo[0]["getname"]; 
		$pass=$MailInfo[0]["getpwd"]; 
		$rec=new Pop3(); 
		$rec->to($host,$MailInfo[0]["getport"],2);
		if (!$rec->open()) die($rec->err_str); 
		// echo "open "; 
		if (!$rec->login($user,$pass)) die($rec->err_str); 
		// echo "login"; 
		if (!$rec->stat()) die($rec->err_str); 
		// echo "共有".$rec->messages."封信件，共".$rec->size."字节大小<br>";
		if($rec->messages>0) 
		{
			if (!$rec->listmail()) die($rec->err_str); 
			// echo "有以下信件：<br>"; 
			// for ($i=1;$i<=count($rec->mail_list);$i++)
			// {
				// echo "信件".$rec->mail_list[$i]["num"]."大小：".$rec->mail_list[$i]["size"]."<BR>";
			// }
			$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
			// for ($x=1;$x<10;$x++){
				$rec->getmail(4);
				// echo base64_decode($rec->body[$i])
				// print_r($rec->head);
				// for ($i=0;$i<count($rec->head);$i++){
					// $regex = "/^Form:/i";
					// if (preg_match($regex,$rec->head[$i])){
						// echo $this->decode_mime($rec->head[15]);
						// echo $i;
						// break;
					// }
				// }
				
				for ($i=0;$i<count($rec->head);$i++){
					fwrite($myfile, $rec->head[$i]."\n");
				}
				for ($i=0;$i<count($rec->body);$i++){
					fwrite($myfile, $rec->body[$i]."\n");
				}
				
			// }
			fclose($myfile);
				// $Subject = iconv("gb2312","utf-8//IGNORE",$rec->head[19]);
				// $bodyqwe = "";
				// for ($i=5;$i<count($rec->body)-4;$i++){
					// $bodyqwe.=base64_decode($rec->body[$i]);
					// echo iconv("gb2312","utf-8//IGNORE",$bodyqwe);
				// }
				// $content = iconv("gb2312","utf-8//IGNORE",$bodyqwe);
				// $data = ["Sender"=>$rec->head[9],"Subject"=>$Subject,"content"=>$content];
				// $mailstatus=Db::name('mail')->insert($data);
				// if (!$mailstatus){
					// return "NO";
				// }
			// }
			// echo iconv("gb2312","utf-8//IGNORE",base64_decode($rec->body[9]));
			// echo base64_decode($rec->head[12]);
			// print_r($rec->body);
			// $bodynumber=count($rec->head);
			// for ($i=0;$i<$bodynumber-1;$i++){
				// echo base64_decode($rec->head[$i]);
			// }
			// $bodynumber=count($rec->body);
			// for ($i=0;$i<$bodynumber;$i++){
				// echo quoted_printable_decode($rec->body[$i]);
				// echo iconv("gb2312","utf-8//IGNORE",$bodyqwe);
			// }
			// echo "邮件头的内容：<br>"; 
			// for ($i=0;$i<count($rec->head);$i++) 
				// echo htmlspecialchars($rec->head[$i])."<br>\n"; 
			// echo "邮件正文　：<BR>"; 
			// for ($i=0;$i<count($rec->body);$i++) 
				// echo htmlspecialchars($rec->body[$i])."<br>\n"; 
		} 
		$rec->close();
	}
	
	private function decode_mime($string) 
	{ 
		$pos = strpos($string, '=?'); 
		if (!is_int($pos)) { 
			return $string; 
		}
		$preceding = substr($string, 0, $pos); // save any preceding text 
		$search = substr($string, $pos+2); /* the mime header spec says this is the longest a single encoded word can be */ 
		$d1 = strpos($search, '?'); 
		if (!is_int($d1)) { 
			return $string; 
		} 
		$charset = substr($string, $pos+2, $d1); //取出字符集的定义部分 
		$search = substr($search, $d1+1); //字符集定义以后的部分＝>$search; 
		$d2 = strpos($search, '?'); 
		if (!is_int($d2)) { 
			return $string; 
		} 
		$encoding = substr($search, 0, $d2); ////两个?　之间的部分编码方式　：ｑ　或　ｂ　 
		$search = substr($search, $d2+1); 
		$end = strpos($search, '?='); //$d2+1 与 $end 之间是编码了　的内容：=> $endcoded_text; 
		if (!is_int($end)) { 
			return $string; 
		} 
		$encoded_text = substr($search, 0, $end); 
		$rest = substr($string, (strlen($preceding . $charset . $encoding . $encoded_text)+6)); //+6 是前面去掉的　=????=　六个字符 
		switch ($encoding) { 
			case 'Q': 
			case 'q': 
			//$encoded_text = str_replace('_', '%20', $encoded_text); 
			//$encoded_text = str_replace('=', '%', $encoded_text); 
			//$decoded = urldecode($encoded_text); 
				$decoded=quoted_printable_decode($encoded_text); 
				if (strtolower($charset) == 'windows-1251') { 
					$decoded = convert_cyr_string($decoded, 'w', 'k'); 
				} 
			break; 
			case 'B': 
			case 'b': 
				$decoded = base64_decode($encoded_text); 
				if (strtolower($charset) == 'windows-1251') { 
					$decoded = convert_cyr_string($decoded, 'w', 'k'); 
				} 
			break; 
			default: 
				$decoded = '=?' . $charset . '?' . $encoding . '?' . $encoded_text . '?='; 
			break; 
		} 
		return $preceding . $decoded . $this->decode_mime($rest); 
	}
}
?>