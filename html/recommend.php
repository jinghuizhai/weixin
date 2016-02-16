	<div class="user-main main-bar">
		<div class="tool-bar">
			<div class="user-title fix">
				<h3 class="l">推荐记录</h3>
			</div>
			<div class="tool-options mt20">
				<form method='get'>
					<input type="hidden" name="r" value="recommendShow" />
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
						<th>推荐人性质</th>
						<th>推荐人</th>
						<th>被推荐人</th>
						<th>电话</th>
						<th>添加日期</th>
						<th>操作</th>
						<th>删除</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(!empty($recommends)){
						foreach($recommends as $key => $recommend){
					?>
						<tr>
							<td>
								<form action="?r=updateRecommendStatus" method="post" id="setusertable_<?php echo $recommend['user_id'];?>">
								<input type='hidden' name="recommend_id" value="<?php echo $recommend['recommend_id'];?>" />
								<input type='hidden' name="status" value="<?php echo $recommend['status'];?>" />
								<input type="hidden" name="tag" value="<?php echo $recommend['tag'];?>" />
								<?php echo $key+1;?>
							</td>
							<td>
								<?php
									if($recommend['tag'] == 'job'){
										echo '兼职用户';
									}else{
										echo '企业用户';
									}
								?>
							</td>
							<td>
								<!-- <?php echo $recommend['user_id'];?> -->
								<?php echo $recommend['name2'];?>
							</td>
							<td>
								<?php echo $recommend['name'];?>
							</td>
							<td>
								<?php echo $recommend['phone'];?>
							</td>
							<td>
								<?php echo format($recommend['date']);?>
							</td>
							<td>
								<?php
									if($recommend['status'] == 0){
										echo '<button class="btn" onclick="return handleRecommend();">确定</button>';
									}else{
										echo '已处理';
									}
								?>
							</td>
							<td>
								<button data-form="setusertable_<?php echo $recommend['user_id'];?>" onclick="return deleteRecommend(this)">删除</button>
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
<script type="text/javascript">
	function clearSearch(){
		window.location.href = '?r=recommendShow';
		return false;
	}
	function handleRecommend(){
		var r = confirm('确定么？');
		if(r){
			return true;
		}
		return false;
	}
	function deleteRecommend(othis){
		var r = confirm('除非这条信息是假的，否则不建议您删除，您确定要删除么?');
		if(r){
			var form = othis.getAttribute('data-form');
			zjh.get(form).setAttribute('action','?r=deleRecommend');
			return true;
		}
		return false;
	}
</script>