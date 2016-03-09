<?php
ob_start();

include_once('../../../wp-config.php');
include_once('../../../wp-load.php');

if( $_REQUEST['login_username'] && $_REQUEST['login_password'] )
{
	$userdata = get_user_by( 'email', $_REQUEST['login_username'] );
	
	if( isset($_REQUEST['rememberme']) && $_REQUEST['rememberme'] == 1) {
		$secure_cookie = true;
	} else {
		$secure_cookie = false;
	}

	$creds = array();
	$creds['user_login'] = $userdata->user_login;
	$creds['user_password'] = $_REQUEST['login_password'];
	$user = wp_signon( $creds, $secure_cookie );

	if( is_wp_error($user) ) {
		echo "0";
		die();
	} else {
		wp_setcookie($userdata->user_login, $_REQUEST['login_password'], true);
		wp_set_current_user($user->data->ID, $userdata->user_login);
		echo "1";
		die();
	}
}
?>