	<div class="user-main main-bar">
		<div class="tool-bar">
			<div class="user-title fix">
				<h3 class="l">佣金记录</h3>
				<div class="r">
					<form action="?r=clearRebate" method="post" id="clear-form">
						<input type="hidden" name="clearpass" value="" id="clear-pass" />
						<button onclick="return checkClear(this)">年末清零</button>
					</form>
				</div>
			</div>
			<!-- <div class="tool-options mt20">
				<form method='get'>
					<input type="hidden" name="r" value="rebate" />
					<select name="year" id="select-year">
						<?php
							$year = (int)date('Y');
							$faryear = $year-2015;
							for($i = 0;$i<=$faryear;$i++){
								echo "<option value=".($i+2015).">".($i+2015)."</option>";
							}
						?>
					</select>
					<button>查询</button>
				</form>
			</div> -->
		</div>
		<div class='user-table'>
			<table>
				<thead>
					<tr>
						<th>序号</th>
						<th>用户ID</th>
						<th>姓名</th>
						<th>性质</th>
						<th>加入日期</th>
						<th>推荐成功数量（用户均在使用服务且超过一年）</th>
						<th>已经结算数量</th>
						<th>结算</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(!empty($rebates)){
						foreach($rebates as $key => $rebate){
					?>
						<tr>
							<td>
								<form action="?r=updateRebateStatus" method="post" id="setusertable_<?php echo $rebate['rebate_id'];?>">
								<input type='hidden' name="user_id" value="<?php echo $rebate['user_id'];?>" />
								<input type='hidden' name="year" value="<?php echo $rebate['date'];?>" />
								<input type='hidden' name="sums" value="<?php echo $rebate['sums'];?>" />
								<?php echo $key+1;?>
							</td>
							<td>
								<?php echo $rebate['user_id'];?>
							</td>
							<td>
								<?php echo $rebate['name'];?>
							</td>
							<td>
								<?php
									if($rebate['tag'] == 'job'){
										echo '兼职用户';
									}else{
										echo '企业用户';
									}
								?>
							</td>
							<td>
								<?php echo format($rebate['date2']);?>
							</td>
							<td>
								<?php echo $rebate['sums'];?>
								<a data-name="<?php echo $rebate['name'];?>" data-userid="<?php echo $rebate['user_id'];?>" onclick="countRecommend(this)" href="javascript:;" class="ml20 cg">查看详情</a>
							</td>
							<td>
								<?php echo empty($rebate['money'])?0:$rebate['money'];?>
							</td>
							<td>
								<?php
									if(empty($rebate['money'])){
										echo '<button data-form="setusertable_<?php echo $rebate[\'rebate_id\'];?>" onclick="return resetMoney(this)">结算</button>';
									}else{
										if($rebate['sums'] - $rebate['money'] == 0){
											echo '<button disabled="true" data-form="setusertable_<?php echo $rebate[\'rebate_id\'];?>" onclick="return resetMoney(this)">结算</button>';
										}else{
											echo '<button data-form="setusertable_<?php echo $rebate[\'rebate_id\'];?>" onclick="return resetMoney(this)">结算</button>';
										}
									}
								?>
								</form>
							</td>
						</tr>
					<?php
						}}
					?>
				</tbody>
			</table>
		</div>
		<?php if(!empty($pagination)){echo $pagination;} ?>
	</div>
</div>
<div id="count-tmp">
	<div class="count-tmp">
		<span>{{name}}</span>
		<span>{{phone}}</span>
		<span>{{tag}}</span>
		<span>{{date}}</span>
	</div>
</div>
<div id='count'></div>
<script type="text/javascript">
	function resetMoney(othis){
		var r = confirm('您确定要结算么？');
		if(r){
			var id = othis.getAttribute('data-form');
			var form = zjh.get(id);
			form.submit();
		}else{
			return false;
		}
	}
	/*
	(function(){
		var select = zjh.get('select-year'),
			options = select.getElementsByTagName('option'),
			url = window.location.href;
		var year = url.match(/year=(\d{4})/);
		if(year){
			year = year[1];
			for(var i = 0,len = options.length;i<len;i++){
				var op = options[i];
				if(op.value == year){
					op.setAttribute('selected','selected');
				}
			}
		}
	})();
	*/
	function checkClear(othis){
		var pass = prompt('您确定要进行年末清零么?请输入密码');
		if(pass == null){
			return false;
		}
		if(pass && /\S{3,}/.test(pass)){
			zjh.get('clear-pass').value = pass;
			return true;
		}else{
			alert('密码不正确');
		}
		return false;
	}
	function getTmp(id,data,fn){
		var obj = zjh.get(id),
			html = obj.innerHTML,
			result = '',
			reg = new RegExp('{{([^{}]+)}}','gi'),
			thtml = "",
			params = [];

		while((result = reg.exec(html))!=null){
			params.push(result[1]);
		}
		data.forEach(function(ele){
			var html2 = html.slice(0);
			params.forEach(function(el){
				html2 = html2.replace('{{'+el+'}}',ele[el]);
			});
			thtml = thtml+html2;
		});
		if(fn){
			fn(thtml);
		}
	}
	function countRecommend(othis){
		var user_id = othis.getAttribute('data-userid');
		if(user_id){
			zjh.POST('?r=countRecommend',{"user_id":user_id},function(r){
				if(r){
					r = eval("("+r+")");
					getTmp('count-tmp',r,function(html){
						var count = zjh.get('count');
						count.innerHTML = html;
						zjh.pop(othis.getAttribute('data-name')+"的推荐",count,700,100+50*r.length);
					});
				}
			});
		}else{
			alert('数据出错！');
		}
	}
</script>