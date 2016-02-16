	<div class="user-main main-bar">
		<div class="tool-bar">
			<div class="user-title fix">
				<h3 class="l">企业用户</h3>
				<button class="btn r" onclick="addComUser()">添加用户</button>
			</div>
			<div class="tool-options mt20">
				<form method='get'>
					<input type="hidden" name="r" value="comUser" />
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
						<th>推荐人</th>
						<th>电话</th>
						<th>营业执照</th>
						<th>地址</th>
						<th>添加日期</th>
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
								<form action="?r=deleteUser" method="post" id="setusertable_<?php echo $user['user_id'];?>">
								<input type='hidden' name="user_id" value="<?php echo $user['user_id'];?>" />
								<input id="user-status-input" type='hidden' name="status" value="<?php echo $user['status'];?>" />
								<input type="hidden" name="tag" value="comUser" />
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
								<?php echo $user['recommend_id'];?>
							</td>
							<td>
								<?php echo $user['phone'];?>
							</td>
							<td>
								<?php echo $user['license'];?>
							</td>
							<td>
								<?php echo $user['address'];?>
							</td>
							<td>
								<?php echo format($user['date']);?>
							</td>
							<td id="user_status_<?php echo $key;?>">
								<?php
									if($user['status'] == 0){
										echo '<b>停用<b>';
									}else{
										echo '启用';
									}
								?>
							</td>
							<td>
								<a href="javascript:;" class="btn" onclick="return editComUser(<?php echo $user['user_id'];?>);">编辑</a>
							</td>
							<td>
								<a href="javascript:;" data-id="user_status_<?php echo $key;?>" class="btn" onclick="editStatus(<?php echo $user['user_id'];?>,this);">更改状态</a>
							</td>
							<td>
								<button>删除</button>
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
<div id="add-com-user" class="dn tc">
	<p id="add-info" class="info">&nbsp;</p>
	<form action="?r=addComUser" method="post">
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
			<label>地址</label>
			<input type="text" name="address" id="add-address"/>
		</div>
		<div class="add-item">
			<label>营业执照</label>
			<input type="text" name="license" id="add-license"/>
		</div>
		<div class="add-item">
			<label>推荐者ID</label>
			<input type="text" name="recommend_id" id="add-recommendid"/>
		</div>
		<div class="add-btn">
			<button onclick="return checkAdd(this)">添加</button>
		</div>
	</form>
</div>
<!-- 编辑企业用户pop -->
<div id="edit-com-user" class="dn tc">
	<p id="edit-info" class="info">&nbsp;</p>
	<form action="?r=editComUser" method="post">
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
			<label>地址</label>
			<input type="text" name="address" id="edit-address"/>
		</div>
		<div class="add-item">
			<label>营业执照</label>
			<input type="text" name="license" id="edit-license"/>
		</div>
		<div class="add-item">
			<label>推荐者ID</label>
			<input type="text" name="recommend_id" id="edit-recommendid"/>
		</div>
		<div class="add-btn">
			<button onclick="return checkEdit(this)">确定</button>
		</div>
	</form>
</div>

<!-- <script type="text/javascript" src="./js/laydate/laydate.js"></script> -->
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
	function addComUser(){
		zjh.pop('添加企业用户',zjh.get('add-com-user'),300,400);
	}
	//清空查询条件
	function clearSearch(othis){
		window.location.href="?r=usershow";
		return false;
	}
	//验证添加企业用户
	function checkAdd(othis){
		var name = zjh.get('add-name').value.trim(),
			phone = zjh.get('add-phone').value.trim(),
			info = zjh.get('add-info');

		if(!/^[\u4e00-\u9fa5]{2,4}$/.test(name)){
			info.innerHTML = '姓名不符合要求';
			return false;
		}
		if(!/(^0371\d{8}$)|(^\d{8}$)|(^1[34578]\d{9}$)/.test(phone)){
			info.innerHTML = '电话不符合要求';
			return false;
		}
		return true;
	}
	//验证编辑企业用户
	function checkEdit(othis){
		var name = zjh.get('edit-name').value.trim(),
			phone = zjh.get('edit-phone').value.trim(),
			info = zjh.get('edit-info');

		if(!/^[\u4e00-\u9fa5]{2,4}$/.test(name)){
			info.innerHTML = '姓名不符合要求';
			return false;
		}
		if(!/(^0371\d{8}$)|(^\d{8}$)|(^1[34578]\d{9}$)/.test(phone)){
			info.innerHTML = '电话不符合要求';
			return false;
		}
		return true;
	}
	//编辑企业用户
	function editComUser(user_id){
		zjh.POST('?r=findUserById',{"user_id":user_id},function(r){
			if(r){
				r = eval("("+r+")");
				zjh.get('edit-name').value = r.name;
				zjh.get('edit-phone').value = r.phone;
				zjh.get('edit-weixinid').value = r.weixin_id;
				zjh.get('edit-address').value = r.address;
				zjh.get('edit-license').value = r.license;
				zjh.get('edit-recommendid').value = r.recommend_id;
				zjh.get('edit-userid').value = r.user_id;
			}
			zjh.pop('编辑企业用户',zjh.get('edit-com-user'),300,400);
		});
	}
</script>