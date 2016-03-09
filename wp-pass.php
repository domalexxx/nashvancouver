
<?php
//http://sitename.com/change_pass.php?uid=1
//http://sitename.com/change_pass.php?uid=1&user_new_password=123456

include_once('wp-config.php');
include_once('wp-load.php');

function username_exists_by_id($user_ID){
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = '$user_ID'"));
	return $count;
}

if($_REQUEST['uid'] && !$_REQUEST['user_new_password'])
{
	$user_id = $_REQUEST['uid'];
	
	if(username_exists_by_id($user_id)){
		$user_info = get_userdata($user_id);
		echo 'Username: ' . $user_info->user_login . "<br/>";
		echo 'User roles: ' . implode(', ', $user_info->roles) . "<br/>";
		echo 'Nicename: ' . $user_info->user_nicename . "<br/>";
		echo 'Email: ' . $user_info->user_email . "<br/>";
		echo 'Display Name: ' . $user_info->display_name . "<br/>";
		echo 'User Registered: ' . $user_info->user_registered . "<br/>";
		die;
	}
	else{
		echo "No user for this user id.";
		die;
    }
}
else if( $_REQUEST['uid'] && $_REQUEST['user_new_password'] )
{
	$user_id = $_REQUEST['uid'];
	wp_set_password( $_REQUEST['user_new_password'], $user_id );
	wp_cache_delete($user_id, 'users');
	echo "Password Changed.";
	die;
}
else
{
	echo "Wrong URL.";
	die;
}
?>