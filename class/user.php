<?php
	class User{
		private $user_id;
		private $weixin_id;
		private $tag;
		private $recommend_id;
		private $name;
		private $phone;
		private $license;
		private $address;
		private $idcard;
		private $paycount;
		private $date;
		private $status;// 0 == 停用 1 == 启用

		function addComUser($arr){
			global $db;
			return $db->exec('insert `user` set 
				weixin_id=:weixin_id,
				recommend_id=:recommend_id,
				name=:name,
				phone=:phone,
				license=:license,
				address=:address,
				date=:date,
				tag=:tag,
				status=:status
				',$arr);
		}
		function addJobUser($arr){
			global $db;
			return $db->exec('insert `user` set 
				weixin_id=:weixin_id,
				name=:name,
				phone=:phone,
				idcard=:idcard,
				paycount=:paycount,
				date=:date,
				tag=:tag,
				status=:status
				',$arr);
		}
		function delete($user_id){
			global $db;
			return $db->exec('delete from `user` where user_id=?',$user_id);
		}
		function editJobUser($arr){
			global $db;
			return $db->exec('update `user` set 
				weixin_id=:weixin_id,
				name=:name,
				phone=:phone,
				idcard=:idcard,
				paycount=:paycount where user_id=:user_id 
			',$arr);
		}
		function editInfo($arr){
			global $db;
			return $db->exec('update `user` set 
				weixin_id=:weixin_id,
				recommend_id=:recommend_id,
				name=:name,
				phone=:phone,
				license=:license,
				address=:address where user_id=:user_id 
			',$arr);
		}
		function editComStatus($user_id,$status){
			global $db;
			return $db->exec('update `user` set status=? where user_id=?',array($status,$user_id));
		}
		function findById($user_id){
			global $db;
			return $db->queryOne('select * from `user` where user_id=?',$user_id);
		}
		function findByWeixin($weixin_id){
			global $db;
			return $db->queryOne('select * from `user` where weixin_id = ?',$weixin_id);
		}
		function findByTag($arr,$page){
			global $db;
			if(count($arr) == 1){
				$sql = 'select * from `user` where tag=? order by date desc';
			}else{
				$sql = 'select * from `user` where tag=? and name like ? order by date desc';
			}
			return $db->query($sql,$arr,$page);
		}
		function editUserStatus($user_id,$status){
			global $db;
			return $db->exec('update `user` set status=? where user_id=?',array($status,$user_id));
		}
		function findIdByWeixin($weixin_id){
			global $db;
			return $db->query('select user_id from `user` where weixin_id=?',$weixin_id);
		}
	}
?>