<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>米蝶财务</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
		<link rel="stylesheet" href="./fonts/iconfont.css">
		<style>
			html,body{
				height:100%;
			}
			body{
				margin:0;
				padding:0;
				font: 16px/1.5 font-family:Heiti SC,Helvetica,Droidsansfallback;
				background-color: #c40000;
				color:#fff;
				position: relative;
				overflow: hidden;
			}

			p,h1,h2,h3,h4,h5,h6{
				margin:0;
			}
			h1{
				font-size: 16px;
				font-weight: 300;
			}
			h2{
				margin:20px 0;
			}
			.thanks-title{
				padding-top:50px;
				text-align: center;
			}
			.off-icon i{
				font-size: 200px;
			}
			@-webkit-keyframes flip {
			  0% {
			    -webkit-transform: perspective(400px) rotate3d(0, 1, 0, -360deg);
			    transform: perspective(400px) rotate3d(0, 1, 0, -360deg);
			    -webkit-animation-timing-function: ease-out;
			    animation-timing-function: ease-out;
			  }
			  40% {
			    -webkit-transform: perspective(400px) translate3d(0, 0, 150px) rotate3d(0, 1, 0, -190deg);
			    transform: perspective(400px) translate3d(0, 0, 150px) rotate3d(0, 1, 0, -190deg);
			    -webkit-animation-timing-function: ease-out;
			    animation-timing-function: ease-out;
			  }
			  50% {
			    -webkit-transform: perspective(400px) translate3d(0, 0, 150px) rotate3d(0, 1, 0, -170deg);
			    transform: perspective(400px) translate3d(0, 0, 150px) rotate3d(0, 1, 0, -170deg);
			    -webkit-animation-timing-function: ease-in;
			    animation-timing-function: ease-in;
			  }
			  80% {
			    -webkit-transform: perspective(400px) scale3d(0.95, 0.95, 0.95);
			    transform: perspective(400px) scale3d(0.95, 0.95, 0.95);
			    -webkit-animation-timing-function: ease-in;
			    animation-timing-function: ease-in;
			  }
			  100% {
			    -webkit-transform: perspective(400px);
			    transform: perspective(400px);
			    -webkit-animation-timing-function: ease-in;
			    animation-timing-function: ease-in;
			  }
			}
			@-webkit-keyframes round{
			  0%{-webkit-transform:rotate(0deg)}
			  100%{-webkit-transform:rotate(359deg)}
			}
			.flip{
				-webkit-animation:flip 1s linear;
			}
			.round{
				-webkit-animation:round 1s linear;
			}
			.off-icon{
				text-align: center;
			}
			footer{
				text-align: center;
				line-height: 2em;
				position: absolute;
				width: 100%;
				left:0;
				bottom:0;
			}
		</style>
	</head>
	<body>
		<div class="thanks-title">
			<h1>感谢，您已经预约成功<br/>我们将稍候和您联系.</h1>
			<h2>同时,您获得<span id="renminbi">100</span>元优惠券</h2>
		</div>
		<div class="off-icon" ontouchend="animateMe(this)">
			<i class="iconfont">&#xf020c;</i>
		</div>
		<script type="text/javascript">
			var money = document.getElementById('renminbi');
			function animateMe(othis){
				othis.className = 'off-icon flip';
				money.className = 'round';
				setTimeout(function(){
					othis.className = 'off-icon';
					money.className = '';
				},1000);
			}
		</script>
		<footer>
		© 2015 mecaiwu.com
		</footer>
	</body>
</html>