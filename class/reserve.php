<?php
	class Reserve{
		private $reserve_id;
		private $ip;
		private $agent;
		private $service;
		private $phone;
		private $date;

		function add($arr){

			global $db;

			$count = $db->exec('insert into `reserve` set
				ip = :ip,
				agent = :agent,
				referer = :referer,
				service = :service,
				browser = :browser,
				phone = :phone,
				status = :status,
				date = :date
			',$arr);
			return $count;
		}
		function findByPhone($phone){

			global $db;
			return $db->queryOne('select * from `reserve` where phone=?',$phone);
		}
		function findById($reserve_id){
			global $db;
			return $db->queryOne('select * from `reserve` where reserve_id=?',$reserve_id);
		}
		function findAll($page){
			global $db;
			return $db->query('select * from `reserve` order by date desc',null,$page);
		}
		function updateStatus($reserve_id){
			global $db;
			return $db->exec('update `reserve` set status = 1 where reserve_id = ?',$reserve_id);
		}
		function dele($reserve_id){
			global $db;
			return $db->exec('delete from `reserve` where reserve_id = ?',$reserve_id);
		}
		function filterUser($params,$date,$page){
			global $db;
			$sql = "";
			$new_params = array();
			foreach($params as $key => $value){
				$new_params[] = $key . "=:" . $key;
			}
			$sql = implode(' and ', $new_params);
			if(count($date)){
				if(strcmp($sql,'') !== 0) $sql = $sql." and";
				$sql = $sql . ' date>=:time_start and date<=:time_end';
				$params['time_start'] = $date['time_start'];
				$params['time_end'] = $date['time_end'];
			}

			if(strcmp($sql,'') == 0){
				$sql = 'select * from `reserve`';
			}else{
				$sql = 'select * from `reserve` where '.$sql;
			}
			return $db->query($sql,$params,$page);
		}
	}
?>