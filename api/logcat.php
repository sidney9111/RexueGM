<?php
require ('../include/init.inc.php');
//echo "aaa||";
if (Common::isPost ()) {
	//todo>清除正在执行command，或者加条件，command执行中不运行
	echo "bbb||";
	//todo>获取站点路径
	$path = "d:\\log.txt";
	//todo>执行command
	$myfile = fopen($path, "r") or die("Unable to open file!");
	echo fread($myfile,filesize($path));
}
$sdk_path = 'D:\Android\adt-bundle-windows-x86_64-20140321\sdk\platform-tools';
//version
$exe_script = $sdk_path.'/adb version';
//
$exe_script = $sdk_path.'/adb logcat -v time -d >d:/log.txt';
$a = exec($exe_script, $out, $status);

class AndroidUtil {
	function ReadLog()
	{
		$path = "d:\\log.txt";
		//$myfile = fopen($path, "r") or die("Unable to open file!");
		//$string = fread($myfile,filesize($path));
		//todo>方法的效率
		//$file = file_get_contents($path);
		//$lines = explode('\r\n', $file);
		$fileContent = trim(file_get_contents($path));  
		//$lines = array_unique(explode('ab,ab', str_replace("\r\n","ab,ab",$fileContent)));  
		$lines = explode('ab,ab', str_replace("\r\n","ab,ab",$fileContent));  

		//读取文件
		$arr = array();
		foreach($lines as $line){
		//	echo $line."</br>";
			//push 和 add 明显不同！！
			array_push($arr, $line);
			//$arr[count($arr)]=$line;
		}
		//echo fread($myfile,filesize($path));
		return $arr;
		//return $lines;
		//print_r($lines);
		
	}
}
if($status==0){
	$util = new AndroidUtil();
	//print_r(json_encode($util->ReadLog()));
	print_r(json_encode($util->ReadLog()));
}else{
	print_r($a);
	print_r($out);
	print_r($status);	
}



// $response = "**abc";
// echo $response;