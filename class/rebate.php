<?php
	class Rebate{
		private $rebate_id;
		private $user_id;
		private $year;
		private $money;
		private $status;// 0==未结算 | 1==已结算
		/*
		function add($user_id){
			$user_id = (int)$user_id;
			$year = (int)date('Y');
			$result = $this->findByYear($year,0,$user_id);
			if(empty($result)){
				$result2 = $this->findByYear($year,1,$user_id);
				if(empty($result2)){
					//insert
					return $this->insert(array(
						'year' => $year,
						'money'=> REBATEMONEY,
						'status'=> 0,
						'user_id'=>$user_id,
						'date' => datenow()
					));
				}else{
					//用户$year佣金已经领取，现在要更新数据和状态
					$money = (float)$result2['money']+REBATEMONEY;
					return $this->update(array(
						'money'=>$money,
						'status'=>0,
						'year'=>$year,
						'user_id'=>$user_id,
						'date'=>datenow()
					));
				}
			}else{
				//更新指定年数据
				$money = (float)$result['money']+REBATEMONEY;
				return $this->update(array(
					'money'=>$money,
					'status'=>0,
					'year'=>$year,
					'user_id'=>$user_id,
					'date'=>datenow()
				));
			}
		}
		*/
		function add($arr){
			return $this->insert($arr);
		}
		function insert($arr){
			global $db;
			return $db->exec('insert into `rebate` set 
				year=:year,
				money=:money,
				status=:status,
				date=:date,
				user_id=:user_id
				',$arr);
		}
		function update($arr){
			global $db;
			return $db->exec('update `rebate` set 
				money=:money,
				status=:status,
				date=:date,
				year=:year 
				where user_id=:user_id
				',$arr);
		}
		function find($year,$page){
			$year = (int)$year;
			global $db;
			return $db->query('select 
				u3.user_id,
				u3.name,
				u3.tag,
				? date,
				u3.date date2,
				sum(u3.counts) sums,
				r.money
				from 
				(select  
					u.user_id,
					u.name,
					u.tag,
					u.phone,
					u.date,
					u2.name name2,
					count(u2.name)  counts 
					from `user` u,`user` u2 
					where 
					u.user_id = u2.recommend_id 
					and u2.status=1 
					and (to_days(now())-to_days(u.date)) >= 365
					group by u2.name
				) u3 left join `rebate` r on 
				r.user_id = u3.user_id

					group by u3.name 
					order by sums desc',array($year),$page);
		}
		function findBywexin($year,$weixin_id){
			$year = (int)$year;
			global $db;
			return $db->query('select 
				u3.user_id,
				u3.name,
				u3.tag,
				? date,
				u3.date date2,
				sum(u3.counts) sums,
				r.money
				from 
				(select  
					u.user_id,
					u.name,
					u.tag,
					u.phone,
					u.date,
					u2.name name2,
					count(u2.name)  counts 
					from `user` u,`user` u2 
					where 
					u.user_id = u2.recommend_id 
					and u2.status=1 
					and (to_days(now())-to_days(u.date)) >= 365
					and u.weixin_id=?
					group by u2.name
				) u3 left join `rebate` r on 
				r.user_id = u3.user_id

					group by u3.name 
					order by sums desc',array($year,$weixin_id));
		}
		function findByYear($user_id,$year){
			global $db;
			return $db->queryOne('select * from `rebate` where year=? and user_id=?',array($year,$user_id));
		}
		function findByUser($user_id){
			global $db;
			return $db->queryOne('select * from `rebate` where user_id=?',$user_id);
		}
		function updateStatus($rebate_id,$money){
			global $db;
			return $db->exec('update `rebate` set money=? where rebate_id=?',array($money,$rebate_id));
		}
		function countRecommend($user_id){
			global $db;
			return $db->query('select * from `user` where recommend_id=?',$user_id);
		}
		function dele($rebate_id){

		}
		
	}
?>