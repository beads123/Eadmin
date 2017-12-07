<?php
include('./mail.php');
$checkmail  = 'nxbskl@qq.com';
$array_all  = array();

$array_sohu = array(
        "host"      => 'pop3.sohu.com',
        "port"      => 110, 
        "user"      => 'ganjizyf',
        "password"  => '111221111',
        "saveFile"  => 'result/R_sohu.com',
        "checkmail" => $checkmail    
);

$array_163 = array(
        "host"      => 'pop.163.com',
        "port"      => 110, 
        "user"      => 'franksin',
        "password"  => '1111111111111',
        "saveFile"  => 'result/R_163.com',
        "checkmail" => $checkmail    
);
$array_qq = array(
        "host"      => 'pop.qq.com',
        "port"      => 110, 
        "user"      => 'nxbskl@qq.com',
        "password"  => 'pzhnx514614zx!@',
        "saveFile"  => 'result/R_qq.com',
        "checkmail" => $checkmail    
);
$array_21cn = array(
        "host"      => 'pop.21cn.com',
        "port"      => 110, 
        "user"      => 'ganjizyf',
        "password"  => '1111111111111',
        "saveFile"  => 'result/R_21cn.com',
        "checkmail" => $checkmail    
);
$array_tom = array(
        "host"      => 'pop.tom.com',
        "port"      => 110, 
        "user"      => 'ganjizyf',
        "password"  => '11111111111111111',
        "saveFile"  => 'result/R_tom.com',
        "checkmail" => $checkmail    
);

$array_sina = array(
        "host"      => 'pop.sina.com',
        "port"      => 110, 
        "user"      => 'ganjizyf',
        "password"  => 'test0122225',
        "saveFile"  => 'result/R_sina.com',
        "checkmail" => $checkmail
);

$array_gmail = array(
        "host"      => 'ssl://pop.gmail.com',
        "port"      => 995,
        "user"      => 'ganjizyf@gmail.com',
        "password"  => 'test0152220',
        "saveFile"  => 'result/R_gmail.com',
        "checkmail" => $checkmail
);

//array_push($array_all, $array_sohu);
//array_push($array_all, $array_163);
array_push($array_all, $array_qq);
//array_push($array_all, $array_21cn);
//array_push($array_all, $array_tom);
// array_push($array_all, $array_sina);
// array_push($array_all, $array_gmail);


foreach($array_all as $item)
{
echo "===============================================\n";
echo "===============================================\n";
echo "===============================================\n";
echo "Start get {$item['host']} mail..\n";


ganji_get_test_mail($item) . "\n";

echo "Get {$item['host']} maili finished..\n";
echo "===============================================\n";
echo "===============================================\n";

}
?>