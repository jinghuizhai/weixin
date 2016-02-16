<?php
	class Recommend{
		private $recommend_id;
		private $user_id;
		private $name;
		private $phone;
		private $status;// 0==未处理 | 1==已处理

		function add($arr){
			global $db;
			return $db->exec('insert into `recommend` set 
				user_id=:user_id,
				name=:name,
				phone=:phone,
				status=:status,
				date=:date
			',$arr);
		}
		function findById($recommend_id){
			global $db;
			return $db->queryOne('select * from `recommend` where recommend_id=?',$recommend_id);
		}
		function updateStatus($recommend_id,$status){
			global $db;
			return $db->exec('update `recommend` set status=? where recommend_id=?',array($status,$recommend_id));
		}
		function find($name=null,$page){
			global $db;
			$sql = 'select 
			r.recommend_id,
			r.user_id,
			r.name,
			r.phone,
			r.status,
			r.date,
			u.name as name2,
			u.tag 
			from `recommend` r,`user` u where r.user_id=u.user_id';
			if(!empty($name)){
				$sql = $sql." and r.name like ?";
			}
			$sql = $sql.' order by r.recommend_id desc';
			return $db->query($sql,$name,$page);
		}
		function findDetailById($weixin_id){
			global $db;
			return $db->query('select  
				u2.name,
				u2.date,
				u2.status,
				u2.user_id
				from `user` u,`user` u2 
				where 
				u.user_id = u2.recommend_id 
				and u.weixin_id=?',$weixin_id);
		}
		function dele($recommend_id){
			global $db;
			return $db->exec('delete from `recommend` where recommend_id=?',$recommend_id);
		}
	}
?>