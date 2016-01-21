<?php

/* Create tokens table on theme switch */
function create_tokenTable(){
	global $wpdb;
	return $wpdb->query(" CREATE TABLE IF NOT EXISTS _api_active_tokens (
							  id int(12) unsigned NOT NULL AUTO_INCREMENT,
							  user_id varchar(12) NOT NULL,
							  token varchar(32) NOT NULL,
							  token_status tinyint(1) NOT NULL DEFAUlT 0,
							  expiration bigint(20) unsigned NOT NULL,
							  token_salt varchar(32),
							  gen_timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
							  PRIMARY KEY (id)
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;
						");
}
add_action('switch_theme', 'create_tokenTable');

/* Via POST 
 * Check login data matches, activate token and return user data
 * DISABLING TOKEN RESULTS IN DENIED PROTECTED REQUESTS BUT CAN STILL BE USED AS A PASSIVE TOKEN
 * @param String @user_login (via $_POST) The username
 * @param String @user_password (via $_POST) the password matching the user
 * @return JSON encoded user data to store locally
 * @see get User basic data
 */
function mobile_pseudo_login() {
	
	if(!isset($_POST['user_login']) && !isset($_POST['user_password'])) return wp_send_json_error();
	
	global $rest;
	extract($_POST);
	$creds = array();
	$creds['user_login'] = $user_login;
	$creds['user_password'] = $user_password;
	$creds['remember'] = true;
	$SignTry = wp_signon( $creds, false );

	if( !is_wp_error($SignTry)){
		
		$user_id 	= $SignTry->ID;
		$user_login = $SignTry->user_login;
		$role 		= $SignTry->roles[0];
		$user_name 	= $SignTry->display_name;

		/* Validate token before sending response */
		if(!$rest->check_token_valid('none', $request_token)){
			$response = $rest->update_tokenStatus($request_token, 'none', 1);
			if($user_id) $rest->settokenUser($request_token, $user_id);
			
			/* Return user info to store client side */
			if($response){
				wp_send_json_success(array(
										'user_id' 		=> $user_id,
										'user_login' 	=> $user_login,
										'user_name' 	=> $user_name,
										'role' 			=> $role
									));
				exit;
			}
			/* Error: Something went wrong */
			return wp_send_json_error();
			exit;
		}
	}
	/* There was an error processing auth request */
	wp_send_json_error("Couldn't sign in using the data provided");
}

/* Check login data matches, activate token and return user data
 * DISABLING TOKEN RESULTS IN DENIED PROTECTED REQUESTS BUT CAN STILL BE USED AS A PASSIVE TOKEN
 * @param String @user_login (via $_POST) The username
 * @param String @user_password (via $_POST) the password matching the user
 * @return JSON encoded user data to store locally
 * @see get User basic data
 */
function _mobile_pseudo_login($user_login, $user_password, $request_token) {
	
	if(!isset($user_login) && !isset($user_password)) return wp_send_json_error();
	
	global $rest;
	$creds = array();
	$creds['user_login'] = $user_login;
	$creds['user_password'] = $user_password;
	$creds['remember'] = true;
	$SignTry = wp_signon( $creds, false );

	if( !is_wp_error($SignTry)){
		
		$user_id 	= $SignTry->ID;
		$user_login = $SignTry->user_login;
		$role 		= $SignTry->roles[0];
		$user_name 	= $SignTry->display_name;

		/* Validate token before sending response */
		if(!$rest->check_token_valid('none', $request_token)){
			$response = $rest->update_tokenStatus($request_token, 'none', 1);
			if($user_id) $rest->settokenUser($request_token, $user_id);
			
			/* Return user info to store client side */
			if($response){
				wp_send_json_success(array(
										'user_id' 		=> $user_id,
										'user_login' 	=> $user_login,
										'user_name' 	=> $user_name,
										'role' 			=> $role
									));
				exit;
			}
			/* Error: Something went wrong */
			return FALSE;
			exit;
		}
	}
	/* There was an error processing auth request */
	wp_send_json_error("Couldn't sign in using the data provided");
}

/* Disable token in database for the logged user
 * DISABLING TOKEN RESULTS IN DENIED PROTECTED REQUESTS BUT CAN STILL BE USED AS A PASSIVE TOKEN
 * @param String @logged The username
 * @param String @request_token (via $_POST) the active request token for this user
 */
function mobile_pseudo_logout($logged){
	$user = get_user_by('slug', $logged);
	if(!isset($_POST['request_token']) || !$user) return wp_send_json_error();

	global $rest;
	/* Validate token before sending response */
	if($rest->check_token_valid($user->ID, $_POST['request_token'])){
		$response = $rest->update_tokenStatus($_POST['request_token'], $user->ID, 0);
		
		/* Return user info to store client side */
		if($response){
			wp_send_json_success();
			exit;
		}
		/* Error: Something went wrong */
		wp_send_json_error();
	}
	exit;
}

// CATEGORIES
function follow_category($user_login){
	
	$user = get_user_by('login', $user_login);
	if(museografo_follow_category($user)) 
		wp_send_json_success();
	wp_send_json_error('Problem while following category');
}
add_action('wp_ajax_follow_category', 'follow_category');
add_action('wp_ajax_nopriv_follow_category', 'follow_category');

function unfollow_category($user_login){
	
	$user = get_user_by('login', $user_login);
	if(museografo_unfollow_category($user)) 
		wp_send_json_success();
	wp_send_json_error('Problem while following category');
}
add_action('wp_ajax_unfollow_category', 'unfollow_category');
add_action('wp_ajax_nopriv_unfollow_category', 'unfollow_category');


function fetchFeed($offset = NULL){
	$offset = ($offset) ? $offset : 0;

	$args = array(
				"post_type" 	=> "videos",
				"posts_per_page" => 10,
				"posts_status" 	=> "publish",
				"orderby" 		=> "modified"
			);
	$query = new WP_Query($args);
	$return = array();
	if($query->have_posts())
		foreach ($query->posts as $each_post) {
			$attachment = wp_get_attachment_image_src( get_post_thumbnail_id( $each_post->ID ), 'full' );
			$return[] = 	array(
							"nid" 			=> $each_post->ID,
							"title" 		=> $each_post->post_title,
							"kicker" 		=> get_post_meta( $each_post->ID, 'kicker', true ),
							"publishing_date" => $each_post->post_date,
							"hashtag" 		=> get_post_meta( $each_post->ID, 'hashtag', true ),
							"featured" 		=> FALSE,
							"created" 		=> FALSE,
							"author"		=> FALSE,
							"card_type" 	=> "video_card",
							"card_count" 	=> 1,
							"followed" 		=> TRUE,
							"comments" 		=> array(
													"count" => 0,
													"pool" => array()
												),
							"image" 		=> $attachment[0],
							"video_url" 	=> get_post_meta( $each_post->ID, 'video_url', true ),
							"descripcion" 	=> $each_post->post_content,
							"stacks"		=> array(),
						);
		}
		return json_encode( $return );
	return FALSE;
}