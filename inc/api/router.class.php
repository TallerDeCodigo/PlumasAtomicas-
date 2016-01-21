<?php
require_once('User.class.php');
class Router{

	function __construct($token = 'NOTOKEN', $attrs = array()){
		if($token == 'NOTOKEN') return FALSE;
		add_action('slim_mapping', array( &$this,'api_mapping' ));

		/* Set custom expiration if provided, default if not */
		(!empty($attrs) && $attrs['expires'] !== "") ? $this->set_expiration($attrs['expires']) : $this->set_expiration();
		$this->attrs =  array(
							'request_token' => $token,
							'method'		=> '$method',
							'data'			=> '$data'
						);
	}

	function api_mapping($slim){

		$context = $this;
		

		/*** Those damn robots ***/
		$slim->get('/rest/v1/', function() {
			wp_send_json_error('These are not the droids you are looking for, please get a token first');
			exit();
		});

		$slim->get('/rest/v1/robots/', function() {
			wp_send_json_success('These are not the droids you are looking for, please get a token first. If you\'re testing API connection, everything seems to be going smooth ;)');
			exit();
		});

		/*
		 *   _____      _              
		 *  /__   \___ | | _____ _ __  
		 *    / /\/ _ \| |/ / _ \ '_ \ 
		 *   / / | (_) |   <  __/ | | |
		 *   \/   \___/|_|\_\___|_| |_|
		 *                             
		 */

			/* 
			 * Get a passive token
			 * Generates a token, stores it into the database and returns the token as a response
			 * Implement so that tokens are generated only once, then validated and used until valid
			 * @return 	response.success Bool true is request was executed correctly
			 * @return 	response.request_token Generated passive token
			 * @see 	Validate token
			 */
			$slim->get('/rest/v1/auth/getToken/', function () use ($context){
				
			  	if (method_exists("Router", 'generateToken')){
			  		$new_token = $context->generateToken(FALSE);
			  		wp_send_json_success(array('request_token' => $new_token));
			  	}
			  	wp_send_json_error('Couldn\'t execute method');
			  	exit;
			});
			
			/* Check token for validity */
			$slim->get('/rest/v1/token/validate/', function () {
				if(!isset($_GET['token']) ) return wp_send_json_error(array("error" => "Please provide a user_id and a request token, or refer to the documentation for further support"));
			  	$device_info = (isset($_GET['device_info'])) ? $_GET['device_info'] : NULL;
				
			  	$response = $this->check_token_exists($_GET['token']);
				if($response) wp_send_json_success($response);
				wp_send_json_error();
			});

			/* Check token for validity with user data*/
			$slim->post('/rest/v1/token/validate/', function () {
				
				if(!isset($_POST['request_token']) ) return wp_send_json_error(array("error" => "Please provide a user_id and a request token, or refer to the documentation for further support"));
			  	$device_info = (isset($_POST['device_info'])) ? $_POST['device_info'] : NULL;
				
			  	$response = $this->check_token_exists($_POST['request_token']);
				if($response) wp_send_json_success($response);
				wp_send_json_error();
			});

			/*  
			 * Validate Token
			 */
			$slim->post('/rest/v1/user/validateToken/', function () {

				$token 		= (isset($_POST['token'])) 		? $_POST['token'] 	: NULL;
				$user_id 	= (isset($_POST['user_id'])) 	? $_POST['user_id'] : NULL;
				$validate_id 	= (isset($_POST['validate_id'])) ? $_POST['validate_id'] : NULL;
				$device_info 	= (isset($_POST['device_info'])) ? $_POST['device_info'] : NULL;
				
				if(!$token OR !$user_id) wp_send_json_error('Error: Not enough data provided, please check documentation');

				/* Validate token and return it as a response */
				if(!$this->check_token_valid($user_id, $token, $device_info)){
					$response = $this->update_tokenStatus($token, $user_id, 1);
					if($validate_id) $this->settokenUser($token, $validate_id, $device_info);
					if($response) wp_send_json_success(array('token' => $token, 'status' => 'valid'));
					/* Error: Something went wrong */
					wp_send_json_error('Can\'t validate token, please check your implementation. Do not send tokens directly to this endpoint');
					exit;
				}
				/* Error: Something went wrong */
				wp_send_json_error('Can\'t validate token or already valid. Please check your implementation or execute auth/user/checkToken/ endpoint to check your current token status. Do not send tokens directly to this endpoint');
				exit;
			});
				
			/*  
			 * ¡VARIATION! 
			 * Validate Token from another endpoint
			 * Differs from the '/user/validateToken/' endpoint in the way it sends a response.
			 * Instead of sending a JSON response, this one just sends a Boolean response to handle inside the other endpoint
			 */
			$slim->post('/rest/v1/auth/validateToken/', function () {

				$token 		= (isset($_POST['token'])) 		? $_POST['token'] 	: NULL;
				$user_id 	= (isset($_POST['user_id'])) 	? $_POST['user_id'] : NULL;
				$validate_id 	= (isset($_POST['validate_id'])) ? $_POST['validate_id'] : NULL;
				
				if(!$token OR !$user_id) return FALSE;

				/* Validate token and return it as a response */
				if(!$this->check_token_valid($user_id, $token)){
					$response = $this->update_tokenStatus($token, $user_id, 1);
					if($validate_id) $this->settokenUser($token, $validate_id);
					
					if($response) return TRUE;
					return FALSE;
					exit;
				}
				/* Error: Something went wrong */
				return
				exit;
			});


			/* 
			 * Create user new 
			 * @param 	$attr via $_POST {username: 'username',email: 'email',password: 'password',}
			 * @return 	response.success Bool Executed
			 * @return 	response.data User data
			 * @see 	User.class.php
			 */
			$slim->post('/rest/v1/auth/user/', function () {

				extract($_POST);
				if (!isset($username))wp_send_json_error('Please provide a username');
				
				/* Create user object */
				$User 	= new User();

				$created = $User->create_if__notExists($username, $email, $attrs, FALSE);
				if($created){
					if( isset($attrs['login_redirect']) 
						 AND (!$attrs['login_redirect'] OR $attrs['login_redirect'] == FALSE)
					  ) {
					  	mobile_pseudo_login();
						wp_send_json_success($created);
					}
						
					/* Must provide password to use this method */
					_mobile_pseudo_login($username, $attrs['password'], $attrs['request_token']);
					exit;
				}
			  	wp_send_json_error('Couldn\'t create user');
			  	exit;
			});

			/* 
			 * Check if user exists
			 * 
			 */
			$slim->get('/rest/v1/user/exists/:username', function ($username) {
				$User = new User();
				/* Create user */
				if($User->_username_exists($username)){
					$json_response = array('user_id' => $User->_username_exists($username), 'username' => $username);
					wp_send_json_success($json_response);
				}
				wp_send_json_error();
				exit;
			});



	/*
	 *   _             _       
	 *  | | ___   __ _(_)_ __  
	 *  | |/ _ \ / _` | | '_ \ 
	 *  | | (_) | (_| | | | | |
	 *  |_|\___/ \__, |_|_| |_|
	 *            |___/         
	 */
	
		/*** Get random event image for login page ***/
		$slim->get('/rest/v1/content/login/', function() {
			return get_login_content();
			exit;
		});
		
		/*** User Login ***/
		$slim->post('/rest/v1/auth/login/', function() {
			return mobile_pseudo_login();
			exit;
		});

		/* User Logout from API and invalidate token in database
		 * @param String $logged The username
		 * @param String $request_token (via $_POST) to invalidate in database
		 * @return JSON success
		 */
		$slim->post('/rest/v1/auth/:logged/logout/', function($logged) {
			return mobile_pseudo_logout($logged);
			exit;
		});


	/*     __               _ 
	 *    / _| ___  ___  __| |
	 *   | |_ / _ \/ _ \/ _` |
	 *   |  _|  __/  __/ (_| |
	 *   |_|  \___|\___|\__,_|
	 */      
	
		/*
		 * Get user's timeline feed
		 * @param String $user_login The user to retrieve timeline for
		 * @param Int $offset Number of offsetted posts pages for pagination purposes
		 * @important Timeline gets blocks of 10 activities, offset must be set according to the set of results. Ej. Page 1 is offset 0, page 2 is offset 1
		 * 
		 */
		$slim->get('/rest/v1/feed(/:offset)/', function ($offset){
			if( !isset($_GET['token']) )
				wp_send_json_error("Please include your request token as a parameter");
			if( isset($_GET['token']) AND !$this->check_token_exists($_GET['token']) )
				wp_send_json_error("ERR_ACCESS Your token is not valid");

			echo fetchFeed($offset);
			exit;
		});

	/*     __       _ _                   
	 *    / _| ___ | | | _____      __
	 *   | |_ / _ \| | |/ _ \ \ /\ / /
	 *   |  _| (_) | | | (_) \ V  V /
	 *   |_|  \___/|_|_|\___/ \_/\_/
	 *                                    
	 */
	
		/* Follow Hashtag
		 * @param Int $who_follows The active logged user
		 * @param Int $who The user ID to follow
		 * @param String $type The type of user who is following
		 * @return JSON success
		 */
		$slim->post('/rest/v1/:who_follows/follow',function($who_follows) {

			$who 	= isset($_POST['user_id']) 	? $_POST['user_id'] :  NULL;
			$type 	= isset($_POST['type']) 	? $_POST['type'] 	: 'suscriptor';
			$user  	= get_user_by('login', $who_follows);
			if( siguiendo( $who, $type, $user))
				wp_send_json_success();
			wp_send_json_error();
			exit;
		});

	/*                                    
	 *                                           _        
	 *   ___ ___  _ __ ___  _ __ ___   ___ _ __ | |_ ___  
	 *  / __/ _ \| '_ ` _ \| '_ ` _ \ / _ \ '_ \| __/ __| 
	 * | (_| (_) | | | | | | | | | | |  __/ | | | |_\__ \ 
	 *  \___\___/|_| |_| |_|_| |_| |_|\___|_| |_|\__|___/
	 *                                                   
	 */
	
		/*
		 * Post a new comment to an event
		 * @param String $logged_user The user posting the comment
		 * @param Int $event_id The event where the comment is posted (via $_POST)
		 * @param String $comment_content The content of the comment (via $_POST)
		 * @TO DO Implement replies (indented comments)
		 */
		$slim->post('/rest/v1/:logged_user/events/comments/',function($logged_user) {
			if(post_comment_to_event($logged_user)) 
				wp_send_json_success();
			wp_send_json_error('There was a problem posting your comment');
			exit;
		});
			
		/*
		 * Get comments from a certain event
		 * @param Int $event_id
		 * @TO DO Implement replies (indented comments)
		 */
		$slim->get('/rest/v1/events/comments/:event_id/:offset',function($event_id, $offset) {
			return get_event_comments($event_id, $offset);
			exit;
		});

		/*
		 * Get comments from a certain user
		 * @param Int $user_id
		 * 
		 */
		$slim->get('/rest/v1/:u_login/comments',function($user_login) {
			$user = get_user_by('login', $user_login );
			echo mobile_get_user_comments($user->ID);
			exit;
		});

			/*
		 * Upvote a comment
		 * @param String $user_login
		 * @param Int $comment_id
		 */
		$slim->post('/rest/v1/:u_login/upvote/:c_id', function( $user_login, $comment_id) {
			
			$user = get_user_by('login', $user_login );
			$comment = get_comment( $comment_id );
			
			$args = array(
						'comment_id' 		=> $comment_id,
						'parent_id' 		=> $comment->comment_post_ID,
						'comment_author_id' => $comment->user_id,
						'vote' 				=> 'up',
						'voter'				=> $user
					);
			if(vote_comment($args)) wp_send_json_success();
			exit;
		});

		/*
		 * Downvote a comment
		 * @param String $user_login
		 * @param Int $comment_id
		 */
		$slim->post('/rest/v1/:u_login/downvote/:c_id', function( $user_login, $comment_id) {
			
			$user = get_user_by('login', $user_login );
			$comment = get_comment( $comment_id );
			
			$args = array(
						'comment_id' 		=> $comment_id,
						'parent_id' 		=> $comment->comment_post_ID,
						'comment_author_id' => $comment->user_id,
						'vote' 				=> 'down',
						'voter'				=> $user
					);
			if(vote_comment($args)) wp_send_json_success();
			exit;
		});

   /*                          _     
	*  ___  ___  __ _ _ __ ___| |__  
	* / __|/ _ \/ _` | '__/ __| '_ \ 
	* \__ \  __/ (_| | | | (__| | | |
	* |___/\___|\__,_|_|  \___|_| |_|
	*/

		/*
		 * Search content
		 * @param String $s
		 * TO DO: Divide search by: people, tag, events and accept the parameter as a filter
		 */
		$slim->get('/rest/v1/user/:logged/search/:s/:offset/',function($logged, $s, $offset) {
			return search_museografo($s, $offset, $logged);
			exit;
		});



	/*    _                _       
	 *   /_\  ___ ___  ___| |_ ___ 
	 *  //_\\/ __/ __|/ _ \ __/ __|
	 * /  _  \__ \__ \  __/ |_\__ \
	 * \_/ \_/___/___/\___|\__|___/
	 * General data sets for ui controls or some assets, i love the word assets                           
	 */  

	/*
	 * Update user profile pic
	 * @param String $logged
	 * @param File $file via $_POST
	 * @return JSON success
	 * TO DO: Check token validity before actually uploading file
	 * TO DO: Generate extra tokens to upload files, like a nonce
	 */
	$slim->get('/rest/v1/assets/:asset_name/:args/', function($asset_name, $args){
		echo json_encode(museo_get_asset_by_name($asset_name, $args));
		exit;
	});


	
	} /* END Router class */
                      

	/*
	 * Set expiration for the Request token
	 * @param $exp Int (Expiration time in miliseconds)
	 * Default value is 86,400,000 (24 hours)
	 * Use 0 for no expiration token
	 * TO DO: Invalidate token after expiration
	 */
	private function set_expiration($exp = 86400000){
		$this->attrs['timestamp'] 	= microtime(FALSE);
		$this->attrs['expires'] 	= $exp;
		return $this;
	}

	/*
	 * Generate token
	 * @param $active Bool FALSE is default for passive tokens
	 * PLEASE DO NOT ACTIVATE TOKENS DIRECTLY, USE AUTH-ACTIVATE TOKEN INSTEAD
	 */
	private function generateToken($active = FALSE){
		$token = strtoupper(md5(uniqid(rand(), true)));
		$this->setToken($token, $active);
		$this->set_expiration();
		$this->saveToken_toDB();
		return $this->getToken();
	}

	/*
	 * Save Token to DB
	 * @param $user_id String Default is "none"
	 */
	private function saveToken_toDB($user_id = 'none'){
		global $wpdb;
		$result = $wpdb->query( $wpdb->prepare(" INSERT INTO _api_active_tokens
												  (user_id, token, token_status, expiration)
												  VALUES(%s, %s, %d, %d);
											   "
											 	 ,$user_id
											 	 ,$this->attrs['request_token']
											 	 ,0
											 	 ,$this->attrs['expires'] ));
		return ($result == 1) ? TRUE : FALSE; 
	}

	/*
	 * Update Token status
	 * @param $token String
	 * @param $user_id Int
	 * @param $status String
	 */
	public function update_tokenStatus( $token, $user_id = 'none', $status = 0){
		global $wpdb;
		$result = $wpdb->query( $wpdb->prepare(" UPDATE _api_active_tokens
												  SET token_status = %d
												  WHERE user_id = %s
												  AND token = %s;
											   "
											 	 ,$status
											 	 ,$user_id
											 	 ,$token));
		return ($result == 1) ? $token : FALSE; 
	}

	/*
	 * Update Token expiration time 
	 * @param $token String
	 * @param $user_id Int
	 * @param $new_expiration Int (milliseconds) Default is 86400000
	 */
	private function update_tokenExp( $token, $user_id = 'none', $new_expiration = 86400000){
		global $wpdb;
		$result = $wpdb->query( $wpdb->prepare(" UPDATE _api_active_tokens
												  SET expiration = %d
												  WHERE user_id = %s
												  AND token = %s;
											   "
											 	 ,$status
											 	 ,$user_id
											 	 ,$token));
		return ($result == 1) ? $token : FALSE; 
	}

	/*
	 * Update Token expiration time 
	 * @param $token String
	 * @param $user_id Int
	 * @param $new_timestamp Unix timestamp
	 */
	private function update_tokenTimestamp( $token, $user_id = 'none', $new_timestamp){
		global $wpdb;
		$result = $wpdb->query( $wpdb->prepare(" UPDATE _api_active_tokens
												  SET ge_timestamp = FROM_UNIXTIME(%d)
												  WHERE user_id = %s
												  AND token = %s;
											   "
											 	 ,$status
											 	 ,$user_id
											 	 ,$new_timestamp));
		return ($result == 1) ? $token : FALSE; 
	}

	/*
	 * Update Token user
	 * @param $token String
	 * @param $user_id Int The user that will be associated with the token
	 */
	public function settokenUser( $token, $user_id = 'none', $device_info = NULL){
		global $wpdb;
		$result = $wpdb->query( $wpdb->prepare(" UPDATE _api_active_tokens
												  SET user_id = %d
												  WHERE token = %s;
											   "
											 	 ,$user_id
											 	 ,$token));
		$pieces = array();
		if($result == 1 AND $device_info)
			$pieces = array(
							'data' => $device_info,
							'message' => "Token {$token} successfully assigned to the user {$user_id} connected from mobile device."
						);
		$pieces = array(
						'user_id' => $user_id,
						'error' => "Couldn't get device info"
					);
		// museo_write_log('connections', $pieces);
		return ($result == 1) ? TRUE : FALSE; 
	}

	/*  
	 * Check token validity
	 * @param user_id String (string for internal purposes, int id's only)
	 * @param token String 
	 * @param Array $device_info contains device info to write in the log 
	 */
	public function check_token_valid($user_id, $token, $device_info = NULL){
		global $wpdb;
		$result = $wpdb->get_var( $wpdb->prepare(" SELECT token_status
													FROM _api_active_tokens
													  WHERE user_id = %s
													  AND token = %s;
												   "
											 	 ,$user_id
											 	 ,$token));
		$pieces = array();
		if($result == 1 AND $device_info){
			$pieces = array(
							'request_token' => $token,
							'user_id' => $user_id,
							'data' => $device_info,
							'message' => "Token {$token} checked for validation connected from mobile device."
						);
			// museo_write_log('connections', $pieces);
			return ($result == 1) ? TRUE : FALSE; 
		}
		$pieces = array(
						'user_id' => $user_id,
						'error' => "Couldn't get device info"
					);
		// museo_write_log('connections', $pieces);
		return ($result == 1) ? TRUE : FALSE; 
	}

	/*  
	 * Check token exists
	 * @param token String 
	 * @param Bool
	 */
	public function check_token_exists( $token ){
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare(" SELECT count(*) _exists, gen_timestamp
													 FROM _api_active_tokens
													WHERE token = %s;
												   "
											 	 ,$token));
		return (!empty($result) AND $result[0]->_exists >= 1) ? $result[0]->gen_timestamp : FALSE; 
	}

	/*
	 * Token setter
	 * @param $token String
	 * @param $active Bool Default is FALSE
	 * Please DO NOT activate tokens directly, 
	 *  follow authentication process to do so
	 */
	private function setToken($token, $active = FALSE){
		$this->attrs['request_token'] = $token;
		return $this;
	}
	
	/*
	 * Token getter
	 * @return (String) Object token 
	 */
	private function getToken(){
		return $this->attrs['request_token'];
	}

}