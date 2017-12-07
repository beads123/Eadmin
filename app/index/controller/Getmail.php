<?php

$host="ssl://pop.qq.com"; 

$user="nxbskl@qq.com"; 

$pass="pzhnx514614zx!@"; 

$rec=new Pop3_asd(); 
$rec->Pop3($host,995,2);
if (!$rec->open()) die($rec->err_str); 
echo "open "; 
if (!$rec->login($user,$pass)) die($rec->err_str); 
echo "login"; 
if (!$rec->stat()) die($rec->err_str); 
echo "共有".$rec->messages."封信件，共".$rec->size."字节大小<br>";
if($rec->messages>0) 
{
    if (!$rec->listmail()) die($rec->err_str); 
    echo "有以下信件：<br>"; 
    for ($i=1;$i<=count($rec->mail_list);$i++)
    {
        echo "信件".$rec->mail_list[$i]["num"]."大小：".$rec->mail_list[$i]["size"]."<BR>";
    }
    $rec->getmail(1);
    echo "邮件头的内容：<br>"; 
    for ($i=0;$i<count($rec->head);$i++) 
        echo htmlspecialchars($rec->head[$i])."<br>\n"; 
    echo "邮件正文　：<BR>"; 
    for ($i=0;$i<count($rec->body);$i++) 
        echo htmlspecialchars($rec->body[$i])."<br>\n"; 
} 
$rec->close();
?>