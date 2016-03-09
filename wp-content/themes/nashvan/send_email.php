<?php
ob_start();

include_once('../../../wp-config.php');
include_once('../../../wp-load.php');

if( $_REQUEST['receiver_email'] && $_REQUEST['your_email'] ) {

	$receiver_email = $_REQUEST['receiver_email'];
	$your_email     = $_REQUEST['your_email'];
	$your_name      = $_REQUEST['your_name'];
	$your_message   = $_REQUEST['your_message'];
	
	$to = $receiver_email;
	$subject = "Click Web Studio – Enquiry Email";
	$subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
	
	$headers = 'Content-Type: text/html; charset=UTF-8' . '\r\n';
	$headers .= 'From: Click Web Studio <info@nashvan.clickwebstudio.com>';
	
	$message = '<p><strong>Name: </strong> '.$your_name.',</p>';
	$message .= '<p><strong>Email: </strong> '.$your_email.',</p>';
	$message .= '<p><strong>Message: </strong> '.$your_message.',</p>';
	$message .= '<p>С Уважением,</p>';
	$message .= '<p>Администрация сайта www.nashvancouver.com</p>';

	wp_mail($to, $subject, $message, $headers);

	echo "1";
	die();
}
?>