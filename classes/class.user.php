<?php
	class User{
		private $dbConn;
		
		public function __construct($dbConn){
			$this->dbConn=$dbConn;
		}
		
		public function is_logged_in(){
			if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']===TRUE){
				return TRUE;
			}
		}
		
		public function create_hash($value){
			return $hash=crypt($value, '$2a$12.substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22))';
		}
		
		private function verify_hash($password, $hash){
			return $hash==crypt($password, $hash);
		}
		
		public function get_user_hash($username){
			try{
				$stmt=$this->dbConn->prepare('SELECT password FROM blog_members WHERE username=:username');
				$stmt->bindParam(':username', $username);
				$stmt->execute();
				$row=$stmt->fetch();
				return $row['password'];
			} catch(PDOException $ex){
				echo '<p class="error">'.$ex->getMessage().'</p>';
			}
		}
		
		public function login($username, $password){
			$hashed=$this->get_user_hash($username);
			if($this->verify_hash($password, $hashed)==1){
				$_SESSION['loggedin']=true;
				return true;
			}
		}
		
		public function logout(){
			session_destroy();
		}
	}
?>
