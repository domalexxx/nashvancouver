<?php
ob_start();
//rajesh
include_once('../../../wp-config.php');
include_once('../../../wp-load.php');

if( $_REQUEST['email'] && $_REQUEST['password'] )
{
	global $wpdb;
	$email = $_REQUEST['email'];
	$password = $_REQUEST['password'];
	$username = $_REQUEST['username'];
	$activation_key = md5($email);
	$url = get_option('siteurl');

	if( isset($_REQUEST['rememberme']) && $_REQUEST['rememberme'] == 1) {
		$secure_cookie = true;
	} else {
		$secure_cookie = false;
	}

	if ( !username_exists( $username ) and email_exists( $email ) == false ) {

		$user_id = wp_create_user( $username, $_REQUEST['password'], $_REQUEST['email'] );
		wp_update_user( array ( 'ID' => $user_id, 'role' => 'author' ) );
		$wpdb->update($wpdb->users, array('user_activation_key' => $activation_key), array('user_login' => $username));
		update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
		
		$to = $email;
		$activation_link = get_option('siteurl') . "/?actcode=" . $activation_key ."&key=" . $user_id;
		$subject = "NashVancouver.com - Активация вашего аккаунта!";
		$subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
		
		$headers[] = 'Content-Type: text/html; charset=UTF-8' . '\r\n';
		$headers[] = 'From: NashVancouver.com <info@nashvancouver.com>';
		$message = '<html>
<head>
	<title>Спасибо за регистрацию</title>
	<meta charset="UTF-8">
</head>
<body style="margin: 0; padding: 0; font-family: \'Arial\';">
	<table style="background: #044b81; width: 100%;">
		<tr>
			<td>
				<table style="width: 600px; margin: auto;">
					<tr>
						<td style="text-align: center;"><a href="'.$url.'/o-vankuvere/" style="color: #fff; text-decoration: none; font-size: 12px;">О Ванкувере</a></td>
						<td style="text-align: center;"><a href="'.$url.'/blogi/" style="color: #fff; text-decoration: none; font-size: 12px;">Блоги</a></td>
						<td style="text-align: center;"><a href="'.$url.'/podderzhite-proekt/" style="color: #fff; text-decoration: none; font-size: 12px;">Поддержите проект</a></td>
						<td style="text-align: center;"><a href="'.$url.'/contact-us/" style="color: #fff; text-decoration: none; font-size: 12px;">Связь с нами</a></td>
						<td style="text-align: center;"><a href="'.$url.'/reklama/" style="color: #fff; text-decoration: none; font-size: 12px;">Реклама</a></td>
						<td style="text-align: center;"><a href="'.$url.'/faq/" style="color: #fff; text-decoration: none; font-size: 12px;">FAQ</a></td>
						<td style="text-align: center;"><a href="'.$url.'/" style="color: #fff; text-decoration: none; font-size: 12px; background:#306ea9; padding: 3px 10px; border-radius: 3px;">Вход в личный кабинет</a></td>
					</tr>
				</table>	
			</td>
		</tr>
	</table>
	<table style="text-align: center; background:#bbdef9; width: 100%;">
		<tr>
			<td><h1 style="font-family: \'Times New Roman\'; color: #044b81; font-size: 30px; margin:28px auto 0;">NashVancouver.com</h1></td>
		</tr>
		<tr>
			<td><h2 style="font-family: \'Times New Roman\'; color: #044b81; font-size: 16px; margin: 0; padding-bottom: 32px;">Ваш главный источник информации о жизни в Британской Колумбии!</h2></td>
		</tr>
	</table>
	<table style="background: #306ea9; width: 100%;">
		<tr>
			<td>
				<table style="width: 600px; margin: auto;">
					<tr>
						<td style="text-align: center;"><a href="'.$url.'/novosti/" style="color: #fff; text-decoration: none; font-size: 12px; font-weight: ">Новости</a></td>
						<td style="text-align: center;"><a href="'.$url.'/adv-desk/" style="color: #fff; text-decoration: none; font-size: 12px; font-weight: ">Доска объявлений</a></td>
						<td style="text-align: center;"><a href="'.$url.'/business-catalog/" style="color: #fff; text-decoration: none; font-size: 12px; font-weight: ">Бизнес справочник</a></td>
						<td style="text-align: center;"><a href="'.$url.'/afisha/" style="color: #fff; text-decoration: none; font-size: 12px; font-weight: ">Афиша</a></td>
						<td style="text-align: center;"><a href="'.$url.'/pro-immigraciyu/" style="color: #fff; text-decoration: none; font-size: 12px; font-weight: ">Про иммиграцию</a></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table style="width: 600px; margin:20px auto auto; border: 1px solid #bbdef9; border-radius: 10px; padding-left: 25px; font-size: 14px; color: #262626;">
		<tr>
			<td colspan="2"><h3 style="color: #103864; font-weight: 700; font-size: 24px; text-align: center; margin-bottom: 10px;">Спасибо за регистрацию '.$username.'!</h3></td>
		</tr>
		<tr>
			<td colspan="2"><p style="line-height: 25px;">Поздравляем '.$username.' Вы успешно зарегиcтрировались на сайте NashVancouver.com
Мы высылаем вам данные введенные при регистрации:</p></td>
		</tr>
		<tr>
			<td style="font-weight: 700; padding: 5px 0; width: 100px;">Ваш логин:</td>
			<td>'.$username.'</td>
		</tr>
		<tr>
			<td style="font-weight: 700; padding: 5px 0;">Ваш email:</td>
			<td>'.$email.'</td>
		</tr>
		<tr>
			<td style="font-weight: 700; padding: 5px 0;">Ваш пароль:</td>
			<td>'.$password.'</td>
		</tr>
		<tr>
			<td colspan="2"><p style="color: #4b4b4b;text-align: center; padding:10px 0;">Для начала использования своего профиля необходимо его активировать</p></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;"><a href="'.$activation_link.'" style="text-decoration:none; background: #044b81; padding: 12px 28px; color: #fff; font-size: 16px; font-weight: 700; border-radius: 5px; display: inline-block;">Активировать аккаунт</a></td>
		</tr>
		<tr>
			<td colspan="2">
				<p style="padding-top: 10px;">Вы можете позже изменить эти данные в своем <a href="" style="color: #319ae3;">Профиле</a></p>
				<p style="line-height: 25px; padding:20px 0;">Если у Вас возникнут вопросы пишите и мы постараемся помочь вам 
в самое ближайшее время !</p>
			</td>
		</tr>
		<tr>
			<td colspan="2"><p style="line-height: 25px;">С уважением<br>
Администрация сайта<br>
<a href="'.$url.'" style="color: #55a8e6;">NashVancouver.com</a>
</p></td>
		</tr>
	</table>
	<table style="width:100%; text-align: center;border-collapse: collapse; margin-top:20px;">
		<tr style="background: #e2f0fe;">
			<td style="padding:15px 0;">
				<p style="color:#044b81; font-size: 14px;">Просоединяйтесь к нам в сетях</p>
				<ul style="padding:0;">
					<li style="display:inline-block;"><a href="http://www.facebook.com/nashvancouver"><img src="http://nashvancouver.com/wp-content/themes/nashvan/images/facebook-social.png"></a></li>
					<li style="display:inline-block;"><a href="https://www.youtube.com/channel/UCTqvuWHlbvlqxNaVTFWL5pQ"><img src="http://nashvancouver.com/wp-content/themes/nashvan/images/youtube-social.png"></a></li>
					<li style="display:inline-block;"><a href=""><img src="http://nashvancouver.com/wp-content/themes/nashvan/images/twitter-social.png"></a></li>
				</ul>
			<p style="color: #103864;"><strong>NASHVANCOUVER.COM</strong></p>
			</td>
		</tr>
		<tr style="background: #d5e9fd;">
			<td style="padding: 10px 0; color: #6ca9d8; font:12px \'Arial\';">Для того чтобы отписаться от новостей с нашего сайта <a href="#">перейдите сюда</a></td>
		</tr>
	</table>
</body>
</html>';
		// $message = '<p>Привет '.$username.',</p>';
		// $message .= '<p>Спасибо за регистрацию на сайте www.nashvancouver.com</p>';
		// $message .= '<p>Ваши персональные данные для входа на сайт:</p>';
		// $message .= '<p><strong>Логин:</strong>&nbsp;'.$username.'</p>';
		// $message .= '<p><strong>Пароль:</strong>&nbsp;'.$password.'</p>';
		// $message .= '<p><strong>Чтобы активировать Ваш аккаунт, пройдите по ссылке</strong> <a href="'.$activation_link.'">'.$activation_link.'</a> <strong>или скопируйте и вставьте эту ссылку в браузер.</strong></p>';
		// $message .= '<p>С Уважением,</p>';
		// $message .= '<p>Администрация сайта www.nashvancouver.com</p>';

		wp_mail($to, $subject, $message, $headers);

		echo "1";
		die();
	} else {
		echo "0";
		die();
	}
}
?>