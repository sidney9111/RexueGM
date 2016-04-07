<?php
if(!defined('ACCESS')) {exit('Access denied.');}
class LogcatUtil {
	public static function getSDK_Directory(){
		$fileName = dirname(__FILE__) . "\\logcat.config";
		$handle = fopen($fileName, "r");
		while (!feof($handle)){
			$sdk_directory = $text.fgets($handle);
		}
		//todo>分离: and 提示是否有sdk
		//$strs = explode(':', $sdk_directory);
		$i = strpos($sdk_directory,":");	
		return substr($sdk_directory,$i+1);
	}
}