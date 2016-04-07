<?php
require ('../include/init.inc.php');
require ('LogcatUtil.php');
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

if(isset($_GET["a"])){
	if($_GET["a"]=="save_logcat"){
		save_sdk();
	};
	return;
}
function save_sdk(){
	$text = $_GET["dir"] or "";
	$text = "sdk_path:".$text;
	//echo dirname(__FILE__)."\\logcat.config|";
	//echo $text;
	//session_start();
	$handle = fopen(dirname(__FILE__)."\\logcat.config", "w");
	//$text = $_POST['file_contents'];
	if(fwrite($handle, $text) == FALSE){
		//$_SESSION['error'] = ‘<span class=”redtxt”>There was an error</span>’;
		//echo "false";
	}else{
		//echo "ok";
		//$_SESSION['error'] = ‘<span class=”redtxt”>File edited successfully</span>’;
	}
	fclose($handle);
	//header(“Location: “.$_POST['page']);
	send_ret(0,"");
}
// function task1() {
//     for ($i = 1; $i <= 10; ++$i) {
//         echo "This is task 1 iteration $i.\n</br>";
//         yield;
//     }
// }

// function task2() {
//     for ($i = 1; $i <= 5; ++$i) {
//         echo "This is task 2 iteration $i.\n</br>";
//         yield;
//     }
// }
// function getTaskId() {
//     return new SystemCall(function(Task $task, Scheduler $scheduler) {
//         $task->setSendValue($task->getTaskId());
//         $scheduler->schedule($task);
//     });
// }
// function isFinished($tid){
// 	 return new SystemCall(
//         function(Task $task, Scheduler $scheduler) use ($tid) {
//             $task->setSendValue($scheduler->isFinished($tid));
//             $scheduler->schedule($task);
//         }
//     );
// }
// function task($max) {
//     $tid = (yield getTaskId()); // <-- here's the syscall!
//    $bol = (yield isFinished($tid));
//    if($bol==true){
//    	echo "task $tid is finished ? true";
//    }else{
//    	echo "task $tid is finished ? false $bol";
//    }
    
//     for ($i = 1; $i <= $max; ++$i) {
//         echo "This is task $tid iteration $i.\n</br>";
//         yield;
//     }
// }
// function loadui(){
// 	echo "load file start";
// 	yield;
// 	$sdk_path = 'D:\Android\adt-bundle-windows-x86_64-20140321\sdk\platform-tools';
// 	//version
// 	$exe_script = $sdk_path.'/adb version';
// 	//
// 	$exe_script = $sdk_path.'/adb logcat -v time -d >d:/log.txt 2>&1';
// 	$a = exec($exe_script, $out, $status);
// }
// function normalFlow(){
// 	echo  "normalFlow end";
// 	yield;
// }
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
// //开启PHP fsockopen这个函数
// //PHP fsockopen需要 PHP.ini 中 allow_url_fopen 选项开启。
// // /$errno = 1;
// //$fp = fsockopen("http://192.168.0.103:81/OSAdmin/", 80, 1,"",30);
// //Warning: fsockopen(): unable to connect to http://192.168.0.103:81/OSAdmin/:-1 (Unable to find the socket transport "http" - did you forget to enable it when you configured PHP?) in H:\xampp\htdocs\OSAdmin\api\logcat.php on line 97
// $fp = fsockopen("127.0.0.1",81,$error,$errstr,30);
// //$fp = fopen("http://192.168.0.103:81/OSAdmin/api/logcat_child.php",'r');
// if(!$fp){
// 	die('no fsockopen(hostname)');
// }
// stream_set_blocking($fp, 0);
// //stream_set_blocking($fp,0);  
// // $http = "GET /api/logcat_child.php / HTTP/1.1\r\n";
// $http = "GET /OSAdmin/api/logcat_child.php HTTP/1.1 \r\n";
// //$http = "Host:192.168.0.103/OSAdmin\r\n";
// $http .= "Host: 127.0.0.1 \r\n";
// //$http = "Connection:Close\r\n\r\n";
// $http .= "Connection: Close \r\n\r\n";
// fwrite($fp,$http);
// // while (!feof($fp)) {   	
// // 	echo fgets($fp, 128);   
// // }  
// fclose($fp);
//-------------搞了一天，最后发现，完全不用PHP特性，用ADB！！！-------------
//$sdk_path = 'D:\Android\adt-bundle-windows-x86_64-20140321\sdk\platform-tools';
$sdk_path = LogcatUtil::getSDK_Directory().'\platform-tools';
//todo>判断sdk是否存在
$exe_script = $sdk_path.'/adb devices';
$a=exec($exe_script, $out, $status); 
if($status==0){
	if($out[1]==""){
		//OSAdmin::alert("error",ErrorMessage::USER_OR_PWD_WRONG);
		//Template::Display ( 'login.tpl' );
		echo json_encode(array("ret"=>1,"msg"=>"it seems you did not connect your device"));
		die();	
	}
	
}
// //version
$exe_script = $sdk_path.'/adb version';
$exe_script = $sdk_path.'/adb logcat -v time -d >d:/log.txt';
//todo>
//1.可能被2个adb占用，暂时无法处理
//2.有2个devices的情况
$a = exec($exe_script, $out, $status);
//------------Windows COM--------------------------------------------
//$WshShell = new COM("WScript.Shell");
//$oExec = $WshShell->Run($exe_script, 0, false);

//todo> ndk analyse
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
	//print_r(json_encode($util->ReadLog()));
	send_ret(0,json_encode($util->ReadLog()));
}else{
	print_r($a);
	print_r($out);
	print_r($status);	
}

function send_ret($ret,$msg){
	print_r(json_encode(array("ret"=>$ret,"msg"=>$msg)));	
}


// $response = "**abc";
// echo $response;