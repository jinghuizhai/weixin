<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>米蝶微信</title>
		<link rel="stylesheet" href="./fonts/iconfont.css">
		<link rel="stylesheet" type="text/css" href="./css/common.css">
		<link rel="stylesheet" type="text/css" href="./css/plugin.css">
		<script type="text/javascript" src="./js/zjhlib-1.0.js"></script>
	</head>
	<body>
		<div class="header">
			<div class="inner container rel">
				<a href="" class="logo">
				</a>
				<div class="header-tag">
					<a href="?r=login">登录</a>
					<a href="?r=logout">注销</a>
				</div>
			</div>
		</div>
		<div id="hint"><?php echo getHint();?></div>
		<div class="container">
		<?php if(!empty($data)){ ?>
		<div id="menu">
			<dl>
				<dt>查看信息</dt>
				<dd><a href="?r=jobUser">兼职用户</a></dd>
				<dd><a href="?r=comUser">企业用户</a></dd>
				<dd><a href="?r=rebate">佣金记录</a></dd>
				<dd><a href="?r=recommendShow">推荐记录</a></dd>
				<dd><a href="?r=usershow">预约记录</a></dd>
			</dl>
		</div>
		<?php } ?>