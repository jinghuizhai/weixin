<?php
	require_once('config.php');
	require_once('./class/handle.php');

	$server = &$_SERVER["REQUEST_URI"];
	preg_match('/\?\s*r\s*[=]\s*([^&]+)/',$server,$matches);
	
	if(count($matches)){
		$route = $matches[1];
	}else{
		$route = 'reserve';
	}
	// var_dump($route);exit;
	switch ($route) {
		case 'reserve'://默认显示预约页面
			reserve();
			break;
		case 'login'://登录页面
			login();
			break;
		case 'adminLogin'://处理登录页面
			adminLogin();
			break;
		case 'logout'://登出
			adminLogout();
			break;
		case 'usershow'://用户列表
			usershow();
			break;
		case 'reserveUser'://将用户状态改为确定
			reserveUser();
			break;
		case 'deleUser'://删除预约用户
			deleUser();
			break;
		case 'addUser'://添加预约用户
			addUser();
			break;
		case 'reserveSuccess'://预约成功
			reserveSuccess();
			break;
		case 'filterUser'://按条件查询用户
			filterUser();
			break;
		case 'comUser'://查询企业用户
			comUser();
			break;
		case 'jobUser'://查询兼职用户
			jobUser();
			break;
		case 'addComUser'://添加企业用户
			addComUser();
			break;
		case 'findUserById':
			findUserById();
			break;
		case 'editComUser':
			editComUser();
			break;
		case 'addJobUser'://添加兼职用户
			addJobUser();
			break;
		case 'editJobUser'://编辑兼职用户
			editJobUser();
			break;
		case 'editUserStatus'://更改用户状态,包括兼职用户和企业用户
			editUserStatus();
			break;
		case 'deleteUser'://删除正式用户
			deleteUser();
			break;
		case 'recommendShow'://显示推荐用户
			recommendShow();
			break;
		case 'updateRecommendStatus':
			updateRecommendStatus();
			break;
		case 'deleRecommend'://删除推荐用户
			deleRecommend();
			break;
		case 'rebate'://展示佣金记录
			rebate();
			break;
		case 'updateRebateStatus':
			updateRebateStatus();
			break;
		case 'countRecommend'://某个人一共推荐了多少用户
			countRecommend();
			break;
		default:
			echo '您访问的页面不存在！';
			break;
	}
?>