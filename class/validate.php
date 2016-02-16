<?php
	require('../config.php');
	require('zjhsql.php');
	require('user.php');

	
	$db = ZjhSql::getInstance(SERVERNAME,DATABASE,USERNAME,PASSWORD);

	$user = new User;
	// echo $user->exist(1255444);
	// echo $user->save(array(
	// 	'ip'=>'192.168.10.35',
	// 	'agent'=>'mozila',
	// 	'service'=>1,
	// 	'phone'=>15258568954,
	// 	'date'=>date('Y:m:d h-s-m')
	// ));
	// echo $user->exist(123456789);
	$r =  $user->findByPhone(123456789);
	var_dump($r);

?>