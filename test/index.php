<?php
/*
	require('../config.php');
	require('../class/handle.php');
	echo post_recommend('fjsdlkfldksjflk','14852335895','张明亮');
	*/

	require('../class/zjhsql.php');
	$db = ZjhSql::getInstance('localhost','zjh','root','');
	$post = &$_POST;
	// if(count($post)){
	// 	$name = $post['name'];
	// 	$db->exec('insert into `user_01` set name=?',$name);
	// }
	
	$result = $db->queryOne('select * from `user_01`');
	var_dump($result);
	$db->exec('update `user_01` set name=?','haha');

	// function getName($length=3){
	// 	$str = "abcdefghijklmnopqrstuvwxyz";
	// 	$ret = "";
	// 	for($i = 0;$i<$length;$i++){
	// 		$pos = rand(0,25);
	// 		$ret = $ret.substr($str,$pos,1);
	// 	}
	// 	return $ret;
	// }
	// function getPhone($length=11){
	// 	$str = "1234567890";
	// 	$ret = "1";
	// 	for($i = 0;$i<$length-1;$i++){
	// 		$pos = rand(0,9);
	// 		$ret = $ret.substr($str,$pos,1);
	// 	}
	// 	return $ret;
	// }
	// $db = ZjhSql::getInstance('localhost','zjh','root','');
	// $total = 100000;
	// set_time_limit(0);
	// $time = time();
	// for($i = 0;$i < $total;$i++){
	// 	$sql = 'insert into `user` set name=?,phone=?,address=?';
	// 	$db->exec($sql,array(getName(10),getPhone(),getName(20)));	
	// }
	// echo time()-$time;
?>
<form action="" method="post">
	<textarea style="width:200px;height:200px;" name="name"></textarea>
	<button>OK</button>
</form>