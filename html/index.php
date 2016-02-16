	<div class="user-main main-bar">
		<div class="tool-bar fix">
			<div class="user-title">
				<h3>所有用户</h3>
			</div>
			<div class="tool-options">
				<form action="<?php echo HOST.'index.php'; ?>" method="get">
					<input type="hidden" name="r" value="filterUser"/>
					<label>服务</label>
					<select name="service" id="service" data-select="<?php echo $service;?>">
						<option value=''>所有</option>
						<option value="0">工商服务</option>
						<option value="1">财税服务</option>
						<option value="2">贷款服务</option>
					</select>
					<label>状态</label>
					<select name="status" id="status" data-select="<?php echo $status;?>">
						<option value=''>所有</option>
						<option value="0">未确定</option>
						<option value="1">确定</option>
					</select>
					<label>
						开始时间
					</label>
					<input type="text" name="time_start" class="laydate-icon" id="time_start" value="<?php echo $time_start;?>" />
					<label>
						结束时间
					</label>
					<input type="text" name="time_end" class="laydate-icon" id="time_end" value="<?php echo $time_end;?>" />
					<button>查询</button>
					<button onclick="return clearSearch(this);">清空</button>
				</form>
			</div>
		</div>

		<div class='user-table'>
			<table>
				<thead>
					<tr>
						<th>序号</th>
						<th>预约服务</th>
						<th>时间</th>
						<th>电话</th>
						<th>IP</th>
						<th>Referer</th>
						<th>浏览器</th>
						<th>操作/状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(!empty($reserves)){
						foreach($reserves as $key => $reserve){
					?>
						<tr>
							<td>
								<form action="?r=reserveUser" method="post" id="setusertable_<?php echo $reserve['reserve_id'];?>">
								<input type='hidden' name="reserve_id" value="<?php echo $reserve['reserve_id'];?>" />
								<?php echo $key+1;?>
							</td>
							<td>
								<?php 
									switch ($reserve['service']) {
										case 0:
											echo '工商服务';
											break;
										case 1:
											echo '财税服务';
											break;
										case 2:
											echo '贷款服务';
											break;
										default:
											echo '服务内容出错';
											break;
									}
								?>
							</td>
							<td>
								<?php echo $reserve['date']?>
							</td>
							<td>
								<?php echo $reserve['phone']?>
							</td>
							<td>
								<?php echo $reserve['ip']?>
							</td>
							<td>
								<?php echo $reserve['referer']?>
							</td>
							<td>
								<?php echo $reserve['browser']?>
							</td>
							<td>
								<?php if($reserve['status'] == 0){
									echo '<button>确定</button>';
								}else{
									echo '已确定';
								} ?>
							</td>
							<td>
								<span class="btn">
									<a class="cf" href="javascript:;" data-action="setusertable_<?php echo $reserve['reserve_id'];?>" onclick="deleMe(this)">删除</a>
								</span>
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
<script type="text/javascript" src="./js/laydate/laydate.js"></script>
<script type="text/javascript">
	function deleMe(othis){
		var r = confirm('你确定要删除么？');
		if(r){
			var formid = othis.getAttribute('data-action');
			var form = document.getElementById(formid);
			if(form){
				form.setAttribute('action','?r=deleUser');
				form.submit();
			}
		}
	}
	//清空查询条件
	function clearSearch(othis){
		window.location.href="?r=usershow";
		return false;
	}
	//初始化日期控件
	void function(){
		laydate({
		   elem: '#time_start'
		});
		laydate({
			elem:'#time_end'
		});
	}();
	//初始化查询选项
	(function(){
		var service = document.getElementById('service'),
			status = document.getElementById('status'),
			serviceSelect = service.getAttribute('data-select'),
			statusSelect = status.getAttribute('data-select'),
			serviceOptions = service.getElementsByTagName('option'),
			statusOptions = status.getElementsByTagName('option');
		if(serviceSelect !== ''){
			serviceOptions[parseInt(serviceSelect)+1].setAttribute('selected','selected');
		}
		if(statusSelect !== ''){
			statusOptions[parseInt(statusSelect)+1].setAttribute('selected','selected');
		}
	})();
</script>