<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>米蝶财务</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
		<link rel="stylesheet" type="text/css" href="./css/weixinlink.css">
	</head>
	<body>
		<div class="logo">
			<img src="">
		</div>
		<div class="body-wrap">
			<h1 class="main-title">免费预约</h1>
			<div id="hint">
				<?php echo getHint(); ?>
			</div>
			<div class="main">
				<div class="container">
					<div class="service-chose">
						<span class="active">工商服务</span>
						<span>财税服务</span>
						<span>贷款服务</span>
					</div>
					<form id="form" action="?r=addUser" method="post">
						<input value="0" name="service" id="service-input" />
						<div class="phone">
							<input name='phone' id="phone" placeholder='电话' />
						</div>
						<a class="submit" href="javscript:;" ontouchend="return validateMe(this)">立即预约</a>
					</form>
				</div>
			</div>
			<footer>
				© 2015 mecaiwu.com
			</footer>
		</div>
		<script type="text/javascript">
			function validateMe(othis){
				var phone = document.getElementById('phone').value.trim();
				if(!/(^0\d{11})|(^1\d{10})/.test(phone)){
					alert('请填入正确的座机或手机号');
				}else{
					document.getElementById('form').submit();
				}
			}
			function hasClass(el,clazz){
				if(el.length) el = el[0];
				var className = el.className;
				if(className === ''){
					return false;
				}
				var arr = className.split(' ');
				for(var i = 0,len = arr.length;i < len;i++){
					if(arr[i] == clazz) return true;
				}
				return false;
			}

			function addClass(el,clazz){
				if(el.length){
					for(var i = 0,len = el.length;i<len;i++){
						addClass(el[i],clazz);
					}
				}else{
					if(!hasClass(el,clazz)){
						el.className = el.className+' '+clazz;
					}
				}
			}

			function removeClass(el,clazz){
				if(el.length){
					for(var i = 0,len = el.length;i<len;i++){
						removeClass(el[i],clazz);
					}
				}else{
					var className = ' '+el.className+' ';
					el.className = className.replace(new RegExp('\\s'+clazz+'\\s'),'');
				}
			}
			(function(){
				var service = document.querySelector('.service-chose'),
					serviceInput = document.getElementById('service-input'),
					footer = document.getElementsByTagName('footer')[0];
					spans = service.getElementsByTagName('span');
				for(var i = 0,len = spans.length;i<len;i++){
					(function(i){
						spans[i].addEventListener('touchend',function(){
							serviceInput.value = i+"";
							removeClass(spans,'active');
							addClass(this,'active');
						});
					})(i);
				}
			})();
		</script>
	</body>
</html>