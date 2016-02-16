<?php
	class Admin{
		private $admin_id;
		private $username;
		private $pass;
		function findOne($username,$pass){
			global $db;
			return $db->queryOne('select * from `admin` where username=? and pass=?',array($username,$pass));
		}
	}
?>