<?php
if ( !defined('CMS_EXEC') ) { exit; }


class DashboardModel extends Model {



	public function getItems($user_id) {

		

		$db = Factory::getDb();
	

		// $q = "SELECT a.active_item,a.lesson_id,  b.title AS lessonTitle FROM `users_lessons` AS a ";
		// $q .= "LEFT JOIN `lessons` AS b ON b.id = a.lesson_id ";
		// $q .= "WHERE a.user_id = ".(int)$user_id;
		
		// $q = 'SELECT * FROM users';
		// return $db->query($q);

		return $db->select('users', 'userID, userName as name');

		// return [1,2,3];

	}



}

?>
