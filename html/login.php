<script type="text/javascript">
	var html = document.documentElement;
	html.className = html.className+" single-page";
</script>
<div class="">
	<div class="weixin-login">
		<div class="inner">
			<div class="login-title">
				<h3>登录</h3>
			</div>
			<form id="admin-login" action="?r=adminLogin" method="post">
				<div class="input-item">
					<span class="abs-icon">
						<i class="iconfont">&#xf01d8;</i>
					</span>
					<input id="username" type="text" name="username" placeholder="账号/邮箱/手机号"/>
				</div>
				<div class="input-item">
					<span class='abs-icon'>
						<i class="iconfont">&#xf0216;</i>
					</span>
					<input id="password" type="password" name="pass" placeholder="密码" />
				</div>
				<div class="remember">
					<label>记住账号</label>
					<input type="checkbox" name="rememberme" />
				</div>
				<!-- <div class="btn" onclick="validate(this)"> -->
				<button class="btn" onclick="return validate(this)">登录</button>
				<!-- </div> -->
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	if(!''.trim){
		String.prototype.trim = function(){
			return this.replace(/(^\s*)|(\s*$)/g,'');
		}
	}
	
	function validate(othis){
		var username = document.getElementById('username').value.trim(),
			password = document.getElementById('password').value.trim(),
			form = document.getElementById('admin-login');
		if(username.length < 5){
			hint('账号太短');
			return false;
		}
		if(password.length < 6){
			hint('密码太短');
			return false;
		}
		form.submit();
	}
	(function(){
		var cookie = document.cookie;
		if(cookie){
			var username = cookie.match(/username=([^;]+)/i);
			if(username) document.getElementById('username').value = username[1];
		}
	})();
</script>
