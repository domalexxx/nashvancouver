<?php
ob_start();
include_once('../../../wp-config.php');
include_once('../../../wp-load.php');

if( $_REQUEST['email'] && $_REQUEST['password'] )
{
	$email = $_REQUEST['email'];
	$password = $_REQUEST['password'];
	$username = $_REQUEST['username'];

	if( isset($_REQUEST['rememberme']) && $_REQUEST['rememberme'] == 1) {
		$secure_cookie = true;
	} else {
		$secure_cookie = false;
	}
	
	if ( !username_exists( $username ) and email_exists( $email ) == false ) {
		
		$user_id = wp_create_user( $username, $_REQUEST['password'], $_REQUEST['email'] );
		wp_update_user( array ( 'ID' => $user_id, 'role' => 'author' ) );
		update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
		
		$creds = array();
		$creds['user_login'] = $username;
		$creds['user_password'] = $_REQUEST['password'];
		$user = wp_signon( $creds, $secure_cookie );

		wp_setcookie($username, $password, true);
		wp_set_current_user($user->data->ID, $username);

		echo "1";
		die();
	} else {
		echo "0";
		die();
	}
}
?>