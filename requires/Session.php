<?
	//Session.php
	//Handles session related tasks
	class Session {
		//returns true if a session is started
		public function is_session_started(){
			if(php_sapi_name() !== 'cli'){
				if(version_compare(phpversion(), '5.4.0', '>=')){
					return session_status() === PHP_SESSION_ACTIVE ? true : false;
				} else {
					return session_id() === '' ? false : true;
				}
			}
			return false;
		}
	}
?>