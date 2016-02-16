	<div class="user-main main-bar">
		<div class="tool-bar">
			<div class="user-title fix">
				<h3 class="l">兼职用户</h3>
				<button class="btn r" onclick="addJobUser()">添加用户</button>
			</div>
			<div class="tool-options mt20">
				<form method='get'>
					<input type="hidden" name="r" value="jobUser" />
					<input type="text" name="name" placeholder="姓名" value="<?php echo $_GET['name'];?>"/>
					<button>查询</button>
					<button onclick="return clearSearch()">清空</button>
				</form>
			</div>
		</div>
		<div class='user-table'>
			<table>
				<thead>
					<tr>
						<th>序号</th>
						<th>ID</th>
						<th>微信ID</th>
						<th>用户名</th>
						<th>电话</th>
						<th>添加日期</th>
						<th>身份证号</th>
						<th>支付账号</th>
						<th>状态</th>
						<th>操作</th>
						<th>操作</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(!empty($users)){
						foreach($users as $key => $user){
					?>
						<tr>
							<td>
								<!-- editJobStatus -->
								<form action="?r=deleteUser" method="post" id="setusertable_<?php echo $user['user_id'];?>">
								<input type='hidden' name="user_id" value="<?php echo $user['user_id'];?>" />
								<input id="user-status-input" type='hidden' name="status" value="<?php echo $user['status'];?>" />
								<input type="hidden" name="tag" value="jobUser" />
								<?php echo $key+1;?>
							</td>
							<td>
								<?php echo $user['user_id'];?>
							</td>
							<td>
								<?php echo $user['weixin_id'];?>
							</td>
							<td>
								<?php echo $user['name'];?>
							</td>
							<td>
								<?php echo $user['phone'];?>
							</td>
							<td>
								<?php echo format($user['date']);?>
							</td>
							<td>
								<?php echo $user['idcard'];?>
							</td>
							<td>
								<?php echo $user['paycount'];?>
							</td>
							<td id="user_status_<?php echo $key;?>">
								<?php
									if($user['status'] == 0){
										echo '<b>停用</b>';
									}else{
										echo '启用';
									}
								?>
							</td>
							<td>
								<a href="javascript:;" class="btn" onclick="return editJobUser(<?php echo $user['user_id'];?>);">编辑</a>
							</td>
							<td>
								<a href="javascript:;" data-id="user_status_<?php echo $key;?>" class="btn" onclick="editStatus(<?php echo $user['user_id'];?>,this);">更改状态</a>
							</td>
							<td>
								<button onclick="return confirm('真的要删除？')">删除</button>
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
<!-- 添加企业用户pop -->
<div id="add-job-user" class="dn tc">
	<p id="add-info" class="info">&nbsp;</p>
	<form action="?r=addJobUser" method="post">
		<div class="add-item">
			<label>姓名</label>
			<input type="text" name="name" id="add-name"/>
		</div>
		<div class="add-item">
			<label>电话</label>
			<input type="text" name="phone" id="add-phone"/>
		</div>
		<div class="add-item">
			<label>微信ID</label>
			<input type="text" name="weixin_id" id="add-weixinid"/>
		</div>
		<div class="add-item">
			<label>身份证号</label>
			<input type="text" name="idcard" id="add-idcard"/>
		</div>
		<div class="add-item">
			<label>支付账户</label>
			<input type="text" name="paycount" id="add-paycount"/>
		</div>
		<div class="add-btn">
			<button onclick="return checkAdd(this)">添加</button>
		</div>
	</form>
</div>
<!-- 编辑企业用户pop -->
<div id="edit-job-user" class="dn tc">
	<p id="edit-info" class="info">&nbsp;</p>
	<form action="?r=editJobUser" method="post">
		<input type="hidden" value="" name="user_id" id="edit-userid"/>
		<div class="add-item">
			<label>姓名</label>
			<input type="text" name="name" id="edit-name"/>
		</div>
		<div class="add-item">
			<label>电话</label>
			<input type="text" name="phone" id="edit-phone"/>
		</div>
		<div class="add-item">
			<label>微信ID</label>
			<input type="text" name="weixin_id" id="edit-weixinid"/>
		</div>
		<div class="add-item">
			<label>身份证号</label>
			<input type="text" name="idcard" id="edit-idcard"/>
		</div>
		<div class="add-item">
			<label>支付账户</label>
			<input type="text" name="paycount" id="edit-paycount"/>
		</div>
		<div class="add-btn">
			<button onclick="return checkEdit(this)">确定</button>
		</div>
	</form>
</div>
<script type="text/javascript">
	function editStatus(user_id,othis){
		var input = zjh.get('user-status-input');
		var value = input.value.trim();
		zjh.POST('?r=editUserStatus',{'user_id':user_id,'status':value},function(r){
			r = zjh.json(r);
			if(r.tag == 'success'){
				zjh.get(othis.getAttribute('data-id')).innerHTML = r.text;
				input.value = value == 1 ? 0 : 1;
			}else{
				alert(r.text);
			}
		});
	}
	function addJobUser(){
		zjh.pop('添加兼职用户',zjh.get('add-job-user'),300,320);
	}
	//验证添加企业用户
	function checkAdd(othis){
		var name = zjh.get('add-name').value.trim(),
			phone = zjh.get('add-phone').value.trim(),
			weixin = zjh.get('add-weixinid').value.trim(),
			idcard = zjh.get('add-idcard').value.trim(),
			paycount = zjh.get('add-paycount').value.trim(),
			info = zjh.get('add-info');

		if(!/^[\u4e00-\u9fa5]{2,4}$/.test(name)){
			info.innerHTML = '姓名不符合要求';
			return false;
		}
		if(!/(^0371\d{8}$)|(^\d{8}$)|(^1[34578]\d{9}$)/.test(phone)){
			info.innerHTML = '电话不符合要求';
			return false;
		}
		if(!/\S{5,}/.test(weixin)){
			info.innerHTML = '微信ID不符合要求';
			return false;
		}
		if(!/^\d{17}[x\d]{1}$/.test(idcard)){
			info.innerHTML = '身份证号码不符合要求';
			return false;
		}
		if(!/\S{5,}/.test(paycount)){
			info.innerHTML = '账号不符合要求';
			return false;
		}
		return true;
	}
	//验证编辑企业用户
	function checkEdit(othis){
		var name = zjh.get('edit-name').value.trim(),
			phone = zjh.get('edit-phone').value.trim(),
			weixin = zjh.get('edit-weixinid').value.trim(),
			idcard = zjh.get('edit-idcard').value.trim(),
			paycount = zjh.get('edit-paycount').value.trim(),
			info = zjh.get('edit-info');
		// console.log(name,phone,weixin,idcard,paycount,info);
		if(!/^[\u4e00-\u9fa5]{2,4}$/.test(name)){
			info.innerHTML = '姓名不符合要求';
			return false;
		}
		if(!/(^0371\d{8}$)|(^\d{8}$)|(^1[34578]\d{9}$)/.test(phone)){
			info.innerHTML = '电话不符合要求';
			return false;
		}
		if(!/\S{5,}/.test(weixin)){
			info.innerHTML = '微信ID不符合要求';
			return false;
		}
		if(!/^\d{17}[x\d]{1}$/.test(idcard)){
			info.innerHTML = '身份证号码不符合要求';
			return false;
		}
		if(!/\S{5,}/.test(paycount)){
			info.innerHTML = '账号不符合要求';
			return false;
		}
		return true;
	}
	//编辑兼职用户
	function editJobUser(user_id){
		zjh.POST('?r=findUserById',{"user_id":user_id},function(r){
			if(r){
				r = eval("("+r+")");
				zjh.get('edit-name').value = r.name;
				zjh.get('edit-phone').value = r.phone;
				zjh.get('edit-weixinid').value = r.weixin_id;
				zjh.get('edit-idcard').value = r.idcard;
				zjh.get('edit-paycount').value = r.paycount;
				zjh.get('edit-userid').value = r.user_id;
				zjh.pop('编辑兼职用户',zjh.get('edit-job-user'),300,320);
			}else{
				zjh.pop('提示','没有此人');
			}
		});
	}
	function clearSearch(){
		window.location.href = '?r=jobUser';
		return false;
	}
</script>