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
function task1() {
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i.\n</br>";
        yield;
    }
}

function task2() {
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i.\n</br>";
        yield;
    }
}
function getTaskId() {
    return new SystemCall(function(Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());
        $scheduler->schedule($task);
    });
}
function isFinished($tid){
	 return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($tid) {
            $task->setSendValue($scheduler->isFinished($tid));
            $scheduler->schedule($task);
        }
    );
}
function task($max) {
    $tid = (yield getTaskId()); // <-- here's the syscall!
   $bol = (yield isFinished($tid));
   if($bol==true){
   	echo "task $tid is finished ? true";
   }else{
   	echo "task $tid is finished ? false $bol";
   }
    
    for ($i = 1; $i <= $max; ++$i) {
        echo "This is task $tid iteration $i.\n</br>";
        yield;
    }
}
function loadui(){
	echo "load file start";
	yield;
	$sdk_path = 'D:\Android\adt-bundle-windows-x86_64-20140321\sdk\platform-tools';
	//version
	$exe_script = $sdk_path.'/adb version';
	//
	$exe_script = $sdk_path.'/adb logcat -v time -d >d:/log.txt 2>&1';
	$a = exec($exe_script, $out, $status);
}
function normalFlow(){
	echo  "normalFlow end";
	yield;
}
//--------------------------coroutinue scheduler------------------------------------
// //php的奇淫技巧
// //todo> 添加PHP版本判断，因为这个技巧基于5.5的coroutinue
// $scheduler = new Scheduler;
// // $scheduler->newTask(task1());
// // $scheduler->newTask(task2());

// $scheduler->newTask(loadui());
// $scheduler->newTask(normalFlow());
// //$scheduler->newTask(task(10));
// //$scheduler->newTask(task(5));
// $scheduler->run();
//--------------------------pcntl_fork()------------------------------------
// //this function could only use in cgi module
// $pid = pcntl_fork();
// if ($pid == -1) {
//      die('could not fork');
// } else if ($pid) {
//      // we are the parent
//      //pcntl_wait($status); //Protect against Zombie children
// 	echo "parent";
// } else {
//      // we are the child
// }
//---------------------------fsockopen()-------------------------------------
//开启PHP fsockopen这个函数
//PHP fsockopen需要 PHP.ini 中 allow_url_fopen 选项开启。
// $errno = 1;
// $fp = fsockopen("http://192.168.0.103/OSAdmin/", 80, $errno,$errstr,30);  
// if (!$fp) die('error fsockopen');  
// stream_set_blocking($fp,0);  
// $http = "GET ./api/logcat_child.php  / HTTP/1.1\r\n";      
// $http .= "Host: 192.168.0.103/OSAdmin\r\n";      
// $http .= "Connection: Close\r\n\r\n";  
// fwrite($fp,$http);  
// while (!feof($fp)) {   
// 	echo fgets($fp, 128);   
// }  
// fclose($fp);  



	$sdk_path = 'D:\Android\adt-bundle-windows-x86_64-20140321\sdk\platform-tools';
	//version
	$exe_script = $sdk_path.'/adb version';
	//
	$exe_script = $sdk_path.'/adb logcat -v time -d >d:/log.txt &';
	echo "child";
	ini_set("max_execution_time",5); 
	$a = exec($exe_script, $out, $status);


//$WshShell = new COM("WScript.Shell");
//$oExec = $WshShell->Run($exe_script, 0, false);

echo "parent end";


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
		//一、若你使用的是FastCGI模式，使用fastcgi_finish_request()能马上结束会话，但PHP线程继续在跑。
		//http://www.4wei.cn/archives/1002336/comment-page-1#comment-125898
		//fastcgi_finish_request();

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