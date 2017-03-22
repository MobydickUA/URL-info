<?php

	require 'config/DB.php';

	class Controller_migration
	{
		public function action_index()
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
			$q[0] = "CREATE DATABASE url_info;";
			$q[1] = "USE url_info;";
			$q[2] = "CREATE TABLE `requests` (
			`id` int(11) NOT NULL AUTO_INCREMENT, 
			`url` varchar(500) NOT NULL, 
			`title` varchar(200) NULL, 
			`status` varchar(3) NULL, 
			`created_at` datetime DEFAULT CURRENT_TIMESTAMP, 
			PRIMARY KEY (`id`)) 
			DEFAULT CHARSET=utf8;";
			foreach ($q as $query) {
				if($db->query($query) === false) 
					echo($this->db->error);
			}
			echo('<a href="/info">To site</a>');
		}
	}