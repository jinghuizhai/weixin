<?php
	require_once 'fun.php';
	require_once 'zjhsql.php';
	require_once 'reserve.php';
	require_once 'user.php';
	require_once 'admin.php';
	require_once 'recommend.php';
	require_once 'rebate.php';
	session_start();
	date_default_timezone_set('PRC');
	$db = ZjhSql::getInstance(SERVERNAME,DATABASE,USERNAME,PASSWORD);

	//预约页面
	function reserve(){
		echo template('reserve.php',array(),false);
	}
	function login(){
		echo template('login.php');
	}
	function adminLogin(){
		$reservename = getvar('username','post');
		$pass = getvar('pass','post');
		if(strlen($reservename) >= 5 && strlen($pass) > 5){
			$admin = new Admin;
			$row = $admin->findOne($reservename,md5($pass));
			if($row){
				setvar('admin',$row['username']);//session
				if(getvar('rememberme','post')) setcookie('username',$reservename,time()+3600*24*7);//账号默认保存七天
				setHint('欢迎管理员');
				redirect('usershow');
			}else{
				setHint('账号或密码有误');
				redirect('login');
			}
		}else{
			setHint('账号或密码不符合要求');
			redirect('login');
		}
	}
	function adminLogout(){
		session_destroy();
		setHint('注销成功');
		redirect('login');
	}
	function reserveUser(){
		if(getvar('admin','session')){
			$reserve_id = getvar('reserve_id','post');
			$reserve = new Reserve;
			$r = $reserve->updateStatus($reserve_id);
			if($r == 1){
				setHint('你修改了一个用户状态');
			}else{
				setHint('用户状态修改失败');
			}
			redirect('usershow');
		}
	}
	function deleUser(){
		if(getvar('admin','session')){
			$reserve_id = getvar('reserve_id','post');
			$reserve = new Reserve;
			$r = $reserve->dele($reserve_id);
			if($r){
				setHint('你删除了一个用户');
			}else{
				setHint('删除失败');
			}
			redirect('usershow');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	//删除正式用户：包括兼职用户和企业用户
	function deleteUser(){
		if(getvar('admin','session')){
			$user_id = getvar('user_id','post');
			$tag = getvar('tag','post');
			$user = new User;
			$r = $user->delete($user_id);
			if($r){
				setHint('你删除了一个用户');
			}else{
				setHint('删除失败');
			}
			redirect($tag);
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function addUser(){
		$phone = getvar('phone','post');
		if(preg_match('/(^0\d{11})|(^1\d{10})/',$phone,$matches)){
			//查看电话是否已经被注册
			$reserve = new Reserve;
			$have = $reserve->findByPhone($matches[0]);
			if(!$have){
				$arr = array(
					"ip" => getIP(),
					"agent" => $_SERVER['HTTP_USER_AGENT'],
					"referer" => $_SERVER['HTTP_REFERER'],
					'browser' => analyse($_SERVER['HTTP_USER_AGENT']),
					"service" => (int)getvar('service','post'),
					"phone" => $matches[0],
					"status" => 0,
					"date" => date('Y-m-d H:m:s')
				);
				$r = $reserve->add($arr);
				if($r){
					redirect('reserveSuccess');
				}
			}else{
				setHint('您输入的电话号码已经存在，请重新输入');
				redirect('reserve');
			}
		}else{
			setHint('电话号码有误，请重新输入');
			redirect('reserve');
		}
	}
	function reserveSuccess(){
		template('reserve_success.php',array(),false);
	}
	function filterUser(){
		if(getvar('admin','session')){
			$service = getvar('service','get');
			$time_start = getvar('time_start','get');
			$time_end = getvar('time_end','get');
			$status = getvar('status','get');
			$current = getvar('p','get');
			$current = empty($current) ? 1 : $current;
			$params = array();//除日期之外的参数
			$date = array();//日期参数
			$url = '?r=filterUser';

			if(strcmp($service,'') !== 0) $params["service"] = (int)$service;
			if(strcmp($status,'') !== 0) $params["status"] = (int)$status;

			$time_start = strcmp($time_start,'') !== 0 ? $time_start : SITE_START_TIME;
			$time_end = $time_end = strcmp($time_end,'') !== 0 ? $time_end : date('Y-m-d H:m:i');
			$date["time_start"] = $time_start;
			$date["time_end"] = $time_end;

			if(strcmp($service,'') !== 0) $url = $url . "&service=" . $service;
			if(strcmp($time_start,'') !== 0) $url = $url . "&time_start=" . $time_start;
			if(strcmp($time_end,'') !== 0) $url = $url . "&time_end=" . $time_end;
			if(strcmp($status,'') !== 0) $url = $url . "&status=" . $status;

			$reserve = new Reserve;
			$data = $reserve->filterUser($params,$date,array($current,$url,10));
			global $db;

			template('index.php',array(
				'reserves'   => $data,
				'pagination' => $db->getPage(),
				'service'    => $service,
				'status'     => $status,
				'time_start' => $time_start,
				'time_end'   => $time_end
 			));

		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function usershow(){
		if(getvar('admin','session')){
			$reserve = new Reserve;
			$current = getvar('p','get');
			$current = empty($current) ? 1 : $current;
			$data = $reserve->findAll(array($current,'?r=usershow',10));
			global $db;
			$datas = array();
			$datas['reserves'] = $data;
			$datas['pagination'] = $db->getPage();
			template('index.php',$datas);
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}

	function comUser(){
		if(getvar('admin','session')){
			$user = new User;
			$current = getvar('p','get');
			$current = empty($current) ? 1 : $current;
			$name = getvar('name','get');
			$params = array('com');
			if(!empty($name)){
				$params[] = "%".$name."%";
			}
			$data = $user->findByTag($params,array($current,'?r=comUser',15));
			global $db;
			$datas = array();
			$datas['users'] = $data;
			$datas['pagination'] = $db->getPage();
			template('com_user.php',$datas);
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function jobUser(){
		if(getvar('admin','session')){
			$user = new User;
			$current = getvar('p','get');
			$current = empty($current) ? 1 : $current;
			$name = getvar('name','get');
			$params = array('job');
			if(!empty($name)){
				$params[] = '%'.$name.'%';
			}
			$data = $user->findByTag($params,array($current,'?r=jobUser',15));
			global $db;
			$datas = array();
			$datas['users'] = $data;
			$datas['pagination'] = $db->getPage();
			template('job_user.php',$datas);
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function addComUser(){
		if(getvar('admin','session')){
			//验证参数
			$name = getvar('name','post');
			$phone = getvar('phone','post');
			$weixin_id = getvar('weixin_id','post');
			$recommend_id = getvar('recommend_id','post');
			$address = getvar('address','post');
			$license = getvar('license','post');
			$user = new User;
			if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,}/u', $name)){
				setHint('姓名至少为两个字的中文');
				redirect('comUser');
			}
			if(!preg_match('/(^0371\d{8}$|^1[34578]\d{9}$)/', $phone)){
				setHint('电话号码格式不正确');
				redirect('comUser');
			}
			// if(!empty($weixin_id)) $weixin_id = (int)$weixin_id;
			if(!empty($recommend_id)) $recommend_id = (int)$recommend_id;

			$result = $user->addComUser(array(
				'name'       => $name,
				'phone'      => $phone,
				'weixin_id'  => $weixin_id,
				'recommend_id'=>$recommend_id,
				'address'    => $address,
				'license'    => $license,
				'date'       => date('Y-m-d H:i:s'),
				'tag'        => 'com',
				'status'     => 1
			));

			if($result){
				setHint('添加用户成功');
			}else{
				setHint('添加用户失败');
			}
			redirect('comUser');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function addJobUser(){
		if(getvar('admin','session')){
			//验证参数
			$name = getvar('name','post');
			$phone = getvar('phone','post');
			$weixin_id = getvar('weixin_id','post');
			$idcard = getvar('idcard','post');
			$paycount = getvar('paycount','post');
			$user = new User;
			if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,}/u', $name)){
				setHint('姓名至少为两个字的中文');
				redirect('comUser');
			}
			if(!preg_match('/(^0371\d{8}$|^1[34578]\d{9}$)/', $phone)){
				setHint('电话号码格式不正确');
				redirect('comUser');
			}

			$result = $user->addJobUser(array(
				'name'       => $name,
				'phone'      => $phone,
				'weixin_id'  => $weixin_id,
				'idcard'	 => $idcard,
				'paycount'   => $paycount,
				'date'       => date('Y-m-d H:i:s'),
				'tag'        => 'job',
				'status'     => 1
			));
			if($result){
				setHint('添加用户成功');
			}else{
				setHint('添加用户失败');
			}
			redirect('jobUser');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function findUserById(){
		header('Content-type: application/json');
		if(getvar('admin','session')){
			$user_id = getvar('user_id','post');
			if(!empty($user_id)){
				$user = new User;
				$result = $user->findById((int)$user_id);
				if($result){
					echo json_encode($result);
				}else{
					echo '没有查询出正确的结果';
				}
			}
		}else{
			echo '请先登录';
		}
	}
	function editComUser(){
		if(getvar('admin','session')){
			//验证参数
			$user_id = getvar('user_id','post');
			$name = getvar('name','post');
			$phone = getvar('phone','post');
			$weixin_id = getvar('weixin_id','post');
			$recommend_id = getvar('recommend_id','post');
			$address = getvar('address','post');
			$license = getvar('license','post');
			$user = new User;
			if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,}/u', $name)){
				setHint('姓名至少为两个字的中文');
				redirect('comUser');
			}
			if(!preg_match('/(^0371\d{8}$|^1[34578]\d{9}$)/', $phone)){
				setHint('电话号码格式不正确');
				redirect('comUser');
			}
			if(!empty($recommend_id)) $recommend_id = (int)$recommend_id;
			if(!empty($user_id)) $user_id = (int)$user_id;

			$result = $user->editInfo(array(
				'name'       => $name,
				'phone'      => $phone,
				'weixin_id'  => $weixin_id,
				'recommend_id'=>$recommend_id,
				'address'    => $address,
				'license'    => $license,
				'user_id'    => $user_id
			));

			if($result){
				setHint('编辑用户成功');
			}else{
				setHint('编辑用户失败');
			}
			redirect('comUser');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function editComStatus(){
		if(getvar('admin','session')){
			$user_id = getvar('user_id','post');
			$status = getvar('status','post');
			if($status == 0){
				$status = 1;
			}else{
				$status = 0;
			}
			$user = new User;
			$result = $user->editComStatus((int)$user_id,$status);
			if($result){
				setHint('修改状态成功');
			}else{
				setHint('修改状态失败');
			}
			redirect('comUser');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function editJobUser(){
		if(getvar('admin','session')){
			//验证参数
			$name = getvar('name','post');
			$phone = getvar('phone','post');
			$weixin_id = getvar('weixin_id','post');
			$idcard = getvar('idcard','post');
			$paycount = getvar('paycount','post');
			$user_id = getvar('user_id','post');
			$user = new User;
			if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,}/u', $name)){
				setHint('姓名至少为两个字的中文');
				redirect('comUser');
			}
			if(!preg_match('/(^0371\d{8}$|^1[34578]\d{9}$)/', $phone)){
				setHint('电话号码格式不正确');
				redirect('comUser');
			}

			$result = $user->editJobUser(array(
				'name'       => $name,
				'phone'      => $phone,
				'weixin_id'  => $weixin_id,
				'idcard'	 => $idcard,
				'paycount'   => $paycount,
				'user_id'    => (int)$user_id
			));
			if($result){
				setHint('编辑用户成功');
			}else{
				setHint('编辑用户失败');
			}
			redirect('jobUser');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function editUserStatus(){
		header("Content-type: application/json");
		$ret = array();
		if(getvar('admin','session')){
			$user_id = getvar('user_id','post');
			$status = getvar('status','post');
			if($status == 0){
				$status = 1;
			}else{
				$status = 0;
			}
			$user = new User;
			$result = $user->editUserStatus((int)$user_id,$status);
			if($result){
				$ret['tag'] = 'success';
				if($status == 1){
					$ret['text'] = '启用';
				}else{
					$ret['text'] = '<b>停用</b>';
				}
			}else{
				$ret['tag'] = 'fail';
				$ret['text'] = '修改状态失败';
			}
		}else{
			$ret['tag'] = 'fail';
			$ret['text'] = '请先登录';
		}
		echo json_encode($ret);
	}
	function want_recommend($fromUser){
		$user = new User;
		$result = $user->findByWeixin($fromUser);
		if($result){
			return '请输入您推荐的信息，格式如下：150****1234/李晓明';
		}else{
			return '对不起，您还没有登记，请联系米蝶财务037186099000';
		}
	}
	function post_recommend($weixin_id,$phone,$name){
		$user = new User;
		$result = $user->findByWeixin($weixin_id);
		if($result){
			$recommend = new Recommend;
			$result2 = $recommend->add(array(
				'user_id'=>$result['user_id'],
				'phone'=>$phone,
				'name'=>$name,
				'date'=>date('Y-m-d H:i:m'),
				'status'=>0
			));
			if($result2){
				return '感谢您的推荐!';
			}else{
				return '由于系统原因，没有推荐成功，请联系米蝶财务0371-86089000';
			}
		}else{
			return '您还没有登记，暂时不能推荐，请联系米蝶财务0371-86089000';
		}
	}
	function recommendShow(){
		if(getvar('admin','session')){
			$current = getvar('p','get');
			$current = empty($current) ? 1 : $current;
			$recommend = new Recommend;
			$name = getvar('name','get');
			if(!empty($name)){
				$name = '%'.$name.'%';
			}
			$data = $recommend->find($name,array($current,'?r=recommendShow',15));
			global $db;
			template('recommend.php',array('recommends'=>$data,'pagination'=>$db->getPage()));
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function updateRecommendStatus(){
		if(getvar('admin','session')){
			$recommend_id = (int)getvar('recommend_id','post');
			// $status = (int)getvar('status','post');
			$recommend = new Recommend;
			$result = $recommend->updateStatus($recommend_id,1);
			if($result){
				setHint('变更用户状态成功');
			}else{
				setHint('变更用户状态失败');
			}
			redirect('recommendShow');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function deleRecommend(){
		if(getvar('admin','session')){
			$recommend_id = getvar('recommend_id','post');
			if(!empty($recommend_id)){
				$recommend = new Recommend;
				$result = $recommend->dele($recommend_id);
				if($result){
					setHint('删除成功');
				}else{
					setHint('删除失败');
				}
			}else{
				setHint('需要输入正确的ID');
			}
			redirect('recommendShow');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function rebate(){
		if(getvar('admin','session')){
			$rebate = new Rebate;
			$year = getvar('year','get');
			if(empty($year)){
				$year = (int)date('Y');
			}else{
				$year = (int)$year;
			}
			$current = getvar('p','get');
			$current = empty($current)?1:$current;
			$data = $rebate->find($year,array($current,'?r=rebate&year='.$year,15));
			global $db;
			$pagination = $db->getPage();
			template('rebate.php',array('rebates'=>$data,'pagination'=>$pagination));
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function updateRebateStatus(){
		if(getvar('admin','session')){
			$user_id = (int)getvar('user_id','post');
			// $year = (int)getvar('year','post');
			$sums = (int)getvar('sums','post');

			$rebate = new Rebate;
			$result = $rebate->findByUser($user_id);
			if($result){
				//update
				$result2 = $rebate->updateStatus((int)$result['rebate_id'],$sums);
			}else{
				//insert
				$result2 = $rebate->add(array(
					"money"=>$sums,
					"status"=>1,
					"date"=>datenow(),//更新时间
					"user_id"=>$user_id
				));
			}
			if(!empty($result2)){
				setHint('结算成功');
			}else{
				setHint('结算失败');
			}
			redirect('rebate');
		}else{
			setHint('请先登录');
			redirect('login');
		}
	}
	function countRecommend(){
		header('Content-type: application/json');
		$user_id = (int)getvar('user_id','post');
		$rebate = new Rebate;
		$result = $rebate->countRecommend($user_id);
		if($result){
			echo json_encode($result);
		}else{
			echo 'false';
		}
	}
	function requestUserInfo($weixin_id){
		$user = new User;
		$result = $user->findByWeixin($weixin_id);
		if($result){
			return "您好，您是".$result['name'].",您的电话是".$result['phone'].',您是'.($result['tag'] == 'com'?'企业用户,您的许可证是.'.$result['license']:'兼职用户').',您的注册日期为:'.$result['date'];
		}else{
			return '您还没有登记，暂无个人信息，请联系米蝶财务0371-86089000';
		}
	}
	function recordRecommend($weixin_id){
		$user = new User;
		$result = $user->findByWeixin($weixin_id);
		if($result){
			$recommend = new Recommend;
			$result = $recommend->findDetailById($weixin_id);
			$str = "";
			foreach($result as $key => $value){
				$str = $str.$value['name'];
				if($value['status'] == 0){
					$str = $str."(此用户已经停用)";
				}
				$str = $str.",".$value['date']."\n";
			}
			if(empty($str)){
				return '对不起，您还没有推荐记录，加油！';
			}else{
				return "您的推荐记录为：\n".$str;
			}
		}else{
			return '您还没有登记，登记后才能推荐，请联系米蝶财务0371-86089000';
		}
	}
	function recordRebate($year,$weixin_id){
		$user = new User;
		$result = $user->findByWeixin($weixin_id);
		if($result){
			$rebate = new Rebate;
			$result = $rebate->findBywexin($year,$weixin_id);
			if($result){
				$str = "";
				foreach($result as $key => $value){
					$str = $str."您".$year.'年度推荐为：'.$value['sums'].'位用户,佣金数额请查看佣金规则菜单。';
				}
				return $str;
			}else{
				return '您还没有佣金记录，加油!';
			}
		}else{
			return '您还没有登记，登记后才会有佣金记录，请联系米蝶财务0371-86089000';
		}
	}
	function tuling($weixin_id,$info){
		$apiKey = "67e9a940581c5682034d4a589ca1aa89"; 
		$apiURL = "http://www.tuling123.com/openapi/api?key=KEY&info=INFO";
		header("Content-type: text/html; charset=utf-8"); 
		$url = str_replace("INFO", $info, str_replace("KEY", $apiKey, $apiURL)); 
	    $res = file_get_contents($url); 
	    $json = json_decode($res);
	    if($json->code == '100000'){
	    	return $json->text;
	    }else if($json->code == '305000'){
	    	$list = $json->list;
	    	$html = "";
	    	foreach($list as $key => $value){
	    		$html = $html.$value->trainnum.":".$value->start."(".$value->starttime.")->".$value->terminal."(".$value->endtime.")";
	    	}
	    	return $html;
	    }else{
	    	return '亲，您输入了敏感信息';
	    }
	}
?>