<?php

    /* 
    *功能：下载远程图片保存到本地 
    *参数： 
    *$url：需要下载的文件url, 
    *$path：下载下来的文件需要保存到的目录 
    *$fileName：保存文件名称，当保存文件名称为空时则使用远程文件原来的名称 
    *type：使用的下载方式 
    */  
namespace first\second;

class Download 
{
    public function getImage($url,$save_dir='',$filename='',$type=0){  
        $ext=".jpg";//以jpg的格式结尾  
        clearstatcache();//清除文件缓存  
        if(trim($url)==''){  
            return array('file_name'=>'','save_path'=>'','error'=>1);  
        }  
        if(trim($save_dir)==''){  
            $save_dir='image';  
        }  
        if(trim($filename)==''){//保存文件名  
            $filename=time().$ext;  
        }else{  
            $filename = $filename.$ext;  
        }  
        if(0!==strrpos($save_dir,'/')){  
            $save_dir.='/';  
        }  
        //创建保存目录  
        if(!is_dir($save_dir)){//文件夹不存在，则新建  
            //print_r($save_dir."文件不存在");  
            mkdir(iconv("UTF-8", "GBK", $save_dir),0777,true);  
            //mkdir($save_dir,0777,true);  
        }  
        //获取远程文件所采用的方法   
        if($type){  
            $ch=curl_init();  
            $timeout=3;  
            curl_setopt($ch,CURLOPT_URL,$url);  
            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);  
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
            $img=curl_exec($ch);  
            curl_close($ch);  
        }else{  
            ob_start();   
            readfile($url);  
            $img=ob_get_contents();   
            ob_end_clean();   
        }  
        $size=strlen($img);  
        //文件大小   
        //var_dump("文件大小:".$size);  
        $fp2=@fopen($save_dir.$filename,'w');  
        fwrite($fp2,$img);  
        fclose($fp2);  
        unset($img,$url);  
        return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);  
	}
}
?>
