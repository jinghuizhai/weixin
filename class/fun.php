<?php
	function redirect($fn){
		header('location:'.HOST.'index.php?r='.$fn);
		exit;
	}
	function logs($str){
		$file = fopen(DIR.'weixinlog/'.format(datenow(),'_').'.log','a');
		fwrite($file,$str.'--'.datenow()."\n");
		fclose($file);
	}
	function setHint($str){
		setcookie('hint',$str,time()+50);
	}
	function getHint(){
		$cookie = $_COOKIE['hint'];
		if(empty($cookie)){
			return '';
		}else{
			clearHint();
			return $cookie;
		}
	}
	function clearHint(){
		setcookie('hint','',time()-3600);
	}
	//日期格式化
	function format($date,$separator="_"){
		return date('Y'.$separator.'m'.$separator.'d',strtotime($date));
	}
	//返回今天的日期
	function datenow(){
		return date('Y-m-d H:i:s');
	}
	//分析浏览器类型
	function analyse($agent){
		if(!empty($agent)){
			if(preg_match('/xiaomi/i', $agent)) return '小米';
			if(preg_match('/ipone/i', $agent)) return '苹果手机';
			if(preg_match('/ipad/i', $agent)) return 'Ipad';
			if(preg_match('/windows/i', $agent)) return 'Windows pc';
			if(preg_match('/mac/i', $agent)) return 'mac';
			if(preg_match('/qqbrowser/i', $agent)) return 'QQ浏览器/IM';
			if(preg_match('/samsung/i', $agent)) return '三星';
			if(preg_match('/lenovo/i', $agent)) return '联想';
			if(preg_match('/oppo/i', $agent)) return 'oppo';
			if(preg_match('/meizu/i', $agent)) return '魅族';
			if(preg_match('/huawei/i', $agent)) return '华为';
			if(preg_match('/android/i', $agent)) return '安卓';
			return '未知品牌';
		}else{
			return '';
		}
	}
	function getvar($name, $type = 'REQUEST') {
		$array = &$GLOBALS[strtoupper("_$type")];

		if (isset($array[$name])) {
			return $array[$name];
		} else {
			return null;
		}
	}
	function getIP(){
      $ip = 'unknow';
      if(getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
      else if(getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
      else if(getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
      return $ip;
   }
	function setvar($key,$value){
		session_start();
		$_SESSION[$key] = $value;
	}
	function template($template,$data=array(),$needhead=true){
		if($needhead){
			include(TEMPLATE_DIR.'header.php');
		}

		$file = TEMPLATE_DIR.$template;

		extract($data);
		ob_start();

		if(is_file($file)){
			require($file);
		}else{
			trigger_error('template file path is error!');
		}

		$output = ob_get_contents();

		ob_end_clean();

		echo $output;

		if($needhead){
			include(TEMPLATE_DIR.'footer.php');
		}
	}
?>