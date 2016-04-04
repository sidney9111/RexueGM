<?php
require ('../include/init.inc.php');
echo "aaa||";
if (Common::isPost ()) {
	//todo>清除正在执行command，或者加条件，command执行中不运行
	echo "bbb||";
	//todo>获取站点路径
	$path = "d:\\log.txt";
	//todo>执行command
	$myfile = fopen($path, "r") or die("Unable to open file!");
	echo fread($myfile,filesize($path));
}
$path = "d:\\log.txt";
//$myfile = fopen($path, "r") or die("Unable to open file!");
//$string = fread($myfile,filesize($path));
//todo>方法的效率
//$file = file_get_contents($path);
//$lines = explode('\r\n', $file);
$fileContent = trim(file_get_contents($path));  
$lines = array_unique(explode('ab,ab', str_replace("\r\n","ab,ab",$fileContent)));  
//读取文件

// foreach($lines as $line){
// 	echo $line."</br>";
// }
print_r($lines);
//echo fread($myfile,filesize($path));

$response = "**abc";
echo $response;