<!-- <div class="footer">
	© 2015 mecaiwu.com
</div> -->
<script type="text/javascript">
	function hint(str){
		var hint = document.getElementById('hint');
		if(str){
			hint.innerHTML = str;
		}
		hint.style.display = 'block';
		setTimeout(function(){
			hint.style.display = 'none';
		},2000);
	}
	(function(){
		var str = document.getElementById('hint').innerHTML.trim();
		if(str.length > 1){
			hint();
		}
		if(zjh.getPagearea().height <= zjh.getViewport().height){
			zjh.addClass(document.documentElement,'single-page');
		}
		//menu active
		var menu = document.getElementById('menu'),
			hrefs = menu.getElementsByTagName('a'),
			url = window.location.toString().match(/\?r=[^&]+/)[0];
		for(var i = 0,len = hrefs.length;i<len;i++){
			var href = hrefs[i].getAttribute('href').trim();
			if(href == url){
				hrefs[i].className = 'active';
				break;
			}
		}
	})();

</script>
	</body>
</html>
