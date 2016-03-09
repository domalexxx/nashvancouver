<?php
if(!is_user_logged_in()) {
	wp_redirect(home_url());
}

/* Template Name: author personal page */

get_header();

$userId = $current_user->ID;

global $wpdb;

$payHistorytab = $wpdb->prefix."payment_history";

$classifiedCostArr = get_option('mrAdCostOptions');
$adCostOptionsArr = $classifiedCostArr['adCostOptions'];

$BusinssCostArr = get_option('mrBusinssCostOptions'); 
$businessCostOptArr = $BusinssCostArr['businessCostOptions'];

$eventCostArr = get_option('mrEventCostOptions'); 
$eventCostOptArr = $eventCostArr['eventCostOptions'];

$mrPaypalsetting = get_option('mrPaypalsetting',true);
$currency_code = $mrPaypalsetting['currency_code'];
$paypalAction_url = $mrPaypalsetting['paypalurl'];

$currentDate = current_time('mysql');

/* extend the classified event */
if(isset($_REQUEST['extendsmyadddays']) && $_REQUEST['extendsmyadddays'] == "Extend Days" && $_REQUEST['addpostid'] > 0) {

	$extadddays = $_REQUEST['extadddays'];
	$addpostid = $_REQUEST['addpostid'];
	
	//Insert data in trasection history	
	$wpdb->query("insert into $payHistorytab set postId='$addpostid', activeDays='$extadddays', payAmt='0', userId='$userId', payStatus='pending', postType='classified', postStatus='pending', date='$currentDate'");
	$lastInsertedId = $wpdb->insert_id;
	
	//PayPal settings
	$paypal_email = $mrPaypalsetting['paypalEmail'];
	$return_url = get_permalink( get_page_by_title("Profile") );
	$cancel_url = get_permalink( get_page_by_title("Profile") );
	$notify_url = get_permalink( get_page_by_title("Profile") );
	$item_name = get_the_title($addpostid);

	if($extadddays)
	{
		$item_amount = "";
		for($i=0; $i<count($adCostOptionsArr); $i++)
		{
			$postAdDays = $adCostOptionsArr[$i]['postAdDays'];
			$postAdcost = $adCostOptionsArr[$i]['postAdcost'];
			if(trim($postAdDays) == trim($extadddays)) {
				$item_amount = $postAdcost;
			}
		}
	}

	$ccUserName = $current_user->display_name;
	
	//Check if paypal request or response
	if($item_amount == 0 || $item_amount == 'free') {

		changePoststatus($addpostid,'publish'); // Update post status
		$wpdb->query("update $payHistorytab set payStatus='Completed', payAmt='$item_amount', postStatus='active', date='$currentDate' where postId='$addpostid' AND userId='$userId' AND tid = '$lastInsertedId'");
	 ?>
	<script type="text/javascript">
		jQuery(document).ready(function(e) {
			 jQuery('#mrclose-model').click(function(){
				jQuery("#confirmModal").removeClass('in');
				jQuery("#confirmModal").hide();
				jQuery("#confirmModal").after('');
				window.location="<?php echo get_permalink($addpostid);?>";
			 });
		
			 jQuery("#confirmModal").addClass('in');
			 jQuery("#confirmModal").show();
			 jQuery("#confirmModal").after('<div class="modal-backdrop fade in"></div>');
			 setTimeout(function(){ window.location="<?php echo get_permalink($addpostid);?>"; },5000);
		});
	</script>	
	<?php } else if($item_amount > 0) {

		$querystring ='';
		$querystring .= "?business=".urlencode($paypal_email)."&";
		$querystring .= "item_name=".urlencode('classifiedadd')."&";		
		$querystring .= "item_number=".urlencode($addpostid)."&";

		$querystring .= "amount=".urlencode($item_amount)."&";
		$querystring .= "currency_code=".urlencode($currency_code)."&";
		$querystring .= "cmd=".urlencode('_xclick')."&";
		$querystring .= "first_name=".urlencode($ccUserName)."&";

		//Append paypal return addresses
		$querystring .= "return=".urlencode(stripslashes($return_url))."&";
		$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
		$querystring .= "notify_url=".urlencode($notify_url);
		
		//Redirect to paypal IPN
	?>
		<script type="text/javascript">
			window.location="<?php echo $paypalAction_url.$querystring; ?>";						
		</script>
	<?php
	}
}

/* extend the business event */
if(isset($_REQUEST['extendsbusinessadddays']) && $_REQUEST['extendsbusinessadddays'] == "Extend Business Days" && $_REQUEST['businessaddpostid'] > 0) {

	$extbadddays = $_REQUEST['extbadddays'];
	$businessaddpostid = $_REQUEST['businessaddpostid'];

	//Insert data in trasection history	
	$wpdb->query("insert into $payHistorytab set postId='$businessaddpostid', activeDays='$extbadddays', payAmt='0', userId='$userId', payStatus='pending', postType='business', postStatus='pending', date='$currentDate'");
	$lastInsertedId = $wpdb->insert_id;

	//PayPal settings
	$paypal_email = $mrPaypalsetting['paypalEmail'];
	$return_url = get_permalink( get_page_by_title("Profile") );
	$cancel_url = get_permalink( get_page_by_title("Profile") );
	$notify_url = get_permalink( get_page_by_title("Profile") );
	$item_name = get_the_title($businessaddpostid);

	if($extbadddays)
	{
		$item_amount = "";
		for($j=0; $j<count($businessCostOptArr); $j++)
		{
			$postBusinssDays = $businessCostOptArr[$j]['postBusinssDays'];
			$postBusinsscost = $businessCostOptArr[$j]['postBusinsscost'];
			if(trim($postBusinssDays)==trim($extbadddays))
			{
				$item_amount = $postBusinsscost;
			}
		}
	}
	
	$ccUserName = $current_user->display_name;
	
	//Check if paypal request or response
	if($item_amount == 0 || $item_amount == 'free') {

		changePoststatus($businessaddpostid,'publish'); // Update post status
		$wpdb->query("update $payHistorytab set payStatus='Completed', payAmt='$item_amount', postStatus='active', date='$currentDate' where postId='$businessaddpostid' AND userId='$userId' AND tid = '$lastInsertedId'");
	 ?>
	<script type="text/javascript">
		jQuery(document).ready(function(e) {
			 jQuery('#mrclose-model').click(function(){
				jQuery("#confirmModal").removeClass('in');
				jQuery("#confirmModal").hide();
				jQuery("#confirmModal").after('');
				window.location="<?php echo get_permalink($businessaddpostid);?>";
			 });
		
			 jQuery("#confirmModal").addClass('in');
			 jQuery("#confirmModal").show();
			 jQuery("#confirmModal").after('<div class="modal-backdrop fade in"></div>');
			 setTimeout(function(){ window.location="<?php echo get_permalink($businessaddpostid);?>"; },5000);
		});
	</script>	
	<?php } else if($item_amount > 0) {

		$querystring ='';
		$querystring .= "?business=".urlencode($paypal_email)."&";
		$querystring .= "item_name=".urlencode('businessdirectoryadd')."&";		
		$querystring .= "item_number=".urlencode($businessaddpostid)."&";

		$querystring .= "amount=".urlencode($item_amount)."&";
		$querystring .= "currency_code=".urlencode($currency_code)."&";
		$querystring .= "cmd=".urlencode('_xclick')."&";
		$querystring .= "first_name=".urlencode($ccUserName)."&";

		//Append paypal return addresses
		$querystring .= "return=".urlencode(stripslashes($return_url))."&";
		$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
		$querystring .= "notify_url=".urlencode($notify_url);
		
		//Redirect to paypal IPN
	?>
		<script type="text/javascript">
			window.location="<?php echo $paypalAction_url.$querystring; ?>";						
		</script>
	<?php
	}
}

/* extend the events */
if(isset($_REQUEST['extendseventsadddays']) && $_REQUEST['extendseventsadddays'] == "Extend Event Days" && $_REQUEST['eventaddpostid'] > 0) {

	$exteadddays = $_REQUEST['exteadddays'];
	$eventaddpostid = $_REQUEST['eventaddpostid'];

	//Insert data in trasection history	
	$wpdb->query("insert into $payHistorytab set postId='$eventaddpostid', activeDays='$exteadddays', payAmt='0', userId='$userId', payStatus='pending', postType='event', postStatus='pending', date='$currentDate'");
	$lastInsertedId = $wpdb->insert_id;

	//PayPal settings
	$paypal_email = $mrPaypalsetting['paypalEmail'];
	$return_url = get_permalink( get_page_by_title("Profile") );
	$cancel_url = get_permalink( get_page_by_title("Profile") );
	$notify_url = get_permalink( get_page_by_title("Profile") );
	$item_name = get_the_title($eventaddpostid);

	if($exteadddays)
	{
		$item_amount = "";
		for($j=0; $j<count($eventCostOptArr); $j++)
		{
			$postEventDays = $eventCostOptArr[$j]['postEventDays'];
			$postEventcost = $eventCostOptArr[$j]['postEventcost'];
			if(trim($postEventDays)==trim($exteadddays))
			{
				$item_amount = $postEventcost;
			}
		}
	}
	
	$ccUserName = $current_user->display_name;
	
	//Check if paypal request or response
	if($item_amount == 0 || $item_amount == 'free') {

		changePoststatus($eventaddpostid,'publish'); // Update post status
		$wpdb->query("update $payHistorytab set payStatus='Completed', payAmt='$item_amount', postStatus='active', date='$currentDate' where postId='$eventaddpostid' AND userId='$userId' AND tid = '$lastInsertedId'");
	 ?>
	<script type="text/javascript">
		jQuery(document).ready(function(e) {
			 jQuery('#mrclose-model').click(function(){
				jQuery("#confirmModal").removeClass('in');
				jQuery("#confirmModal").hide();
				jQuery("#confirmModal").after('');
				window.location="<?php echo get_permalink($eventaddpostid);?>";
			 });
		
			 jQuery("#confirmModal").addClass('in');
			 jQuery("#confirmModal").show();
			 jQuery("#confirmModal").after('<div class="modal-backdrop fade in"></div>');
			 setTimeout(function(){ window.location="<?php echo get_permalink($eventaddpostid);?>"; },5000);
		});
	</script>	
	<?php } else if($item_amount > 0) {

		$querystring ='';
		$querystring .= "?business=".urlencode($paypal_email)."&";
		$querystring .= "item_name=".urlencode('eventadd')."&";		
		$querystring .= "item_number=".urlencode($eventaddpostid)."&";

		$querystring .= "amount=".urlencode($item_amount)."&";
		$querystring .= "currency_code=".urlencode($currency_code)."&";
		$querystring .= "cmd=".urlencode('_xclick')."&";
		$querystring .= "first_name=".urlencode($ccUserName)."&";

		//Append paypal return addresses
		$querystring .= "return=".urlencode(stripslashes($return_url))."&";
		$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
		$querystring .= "notify_url=".urlencode($notify_url);
		
		//Redirect to paypal IPN
	?>
		<script type="text/javascript">
			window.location="<?php echo $paypalAction_url.$querystring; ?>";						
		</script>
	<?php
	}
}

/* check papal status */
if(isset($_GET['tx']) && isset($_GET['st'])) {

	extract($_GET);
	$st = trim($st);
	if($st=="Completed") {
		changePoststatus($item_number,'publish'); // Update post status
		$_SESSION['success_mess'] = 1;
	}

	$lasttransid = $wpdb->get_row("select tid from $payHistorytab where postId = '$item_number' and payStatus = 'pending' ORDER BY tid DESC limit 0,1")->tid;
	$wpdb->query("update $payHistorytab set payStatus='$st', payAmt='$amt', postStatus='active', date='$currentDate' where postId='$item_number' and tid='$lasttransid'"); ?>
	<script type="text/javascript">
	jQuery(document).ready(function(e) {
		jQuery('#mrclose-model').click(function(){
			jQuery("#confirmModal").removeClass('in');
			jQuery("#confirmModal").hide();
			jQuery("#confirmModal").after('');
			window.location="<?php echo get_permalink($item_number);?>";
		});
		
		jQuery("#confirmModal").addClass('in');
		jQuery("#confirmModal").show();
		jQuery("#confirmModal").after('<div class="modal-backdrop fade in"></div>');
		setTimeout(function(){ window.location="<?php echo get_permalink($item_number);?>"; },5000);
	});
	</script>
	<?php
}


if(isset($_REQUEST['yesdeletepost']) && $_REQUEST['yesdeletepost']) {
	
	wp_delete_post( $_REQUEST['deletepostid'], true );
	
	if(get_post_type( $_REQUEST['deletepostid'] ) == "business") {
		$_SESSION['delete_post_mess'] = "Ваш бизнес успешно удален!";
	} else if(get_post_type( $_REQUEST['deletepostid'] ) == "videogallery") {
		$_SESSION['delete_post_mess'] = "Ваше видео успешно удалено!";
	} else if(get_post_type( $_REQUEST['deletepostid'] ) == "event") {
		$_SESSION['delete_post_mess'] = "Ваше мероприятие успешно удалено!";
	} else {
		$_SESSION['delete_post_mess'] = "Ваш бизнес будет удален из справочника!";
	}
}
?>

<div id="main-container" class="container author-personal-page <?php if($_GET['edit']=='profile'){echo 'author-personal-page-edit';}?>">

	<div class="row top-buffer2">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
			
			<div class="overflow">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="title pull-left"><span>Мой профиль</span></div>
					<ul class="links pull-right">
					<li> <a href="<?php echo add_query_arg('edit','profile',current_page_url());?>">Редактировать страницу</a></li></ul>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            	
				<?php
					if(isset($_SESSION['delete_post_mess'])) {

						echo '<div style="margin-top:18px;" class="alert alert-success fade in">';
							echo '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>';
							echo $_SESSION['delete_post_mess'];
						echo '</div>';
						unset($_SESSION['delete_post_mess']);
					}
			
					if(isset($_SESSION['success_mess'])) {

						echo  '<div style="margin-top:18px;" class="alert alert-success fade in">';
							echo '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>';
							echo '<strong>Поздравляем!</strong> Изменения Вашего профиля успешно сохранены.';
						echo '</div>';
						unset($_SESSION['success_mess']);
					}

					if(isset($_SESSION['error_mess']))
					{
						echo '<div style="margin-top:18px;" class="alert alert-danger fade in">';
							echo '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>';
							echo '<strong>Error!</strong> '.$_SESSION['error_mess'];
						echo '</div>';
						unset($_SESSION['error_mess']);
					}

					$current_link = get_author_posts_url($current_user->ID,$current_user->user_name);

			 		if(isset($_GET['edit']) && $_GET['edit']=='profile') {

						if(isset($_POST['mrauthProfileSub'])) {
							
							extract($_POST);
		
							$sociallinks = $sociallinks;
							update_user_meta($userId,"description",$description);
							update_user_meta($userId,"description",$description);
							update_user_meta($userId,"sociallinks",$sociallinks);
							wp_update_user(array('ID' =>$userId,"display_name"=>$auth_displayName));
					
							if( !empty($_FILES['profile_picture']['name']) ) {

								if( $_FILES['profile_picture']['size'] > 0 ) {

									$extension = $_FILES['profile_picture']['name'];

									$path = wp_upload_dir();
									$picture_new_name = rand(100000,99999999999).$extension;
									$upload_path = $path['path']."/".$picture_new_name;
									move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $upload_path );
									$profile_picture_path = $path['url'].'/'.$picture_new_name;
									update_user_meta( $userId, 'profile_picture_path', $profile_picture_path );
								}
							}

							//Social links
							$_SESSION['success_mess']=1;

							if(!empty($auth_newPass)) {

						 		$passCheck = wp_check_password($auth_oldPass,$current_user->user_pass,$userId); 
						 		if(!$passCheck) {
									$_SESSION['error_mess']="Incorrect Old Password";
								} else {
									wp_set_password( $auth_newPass, $userId );
									wp_cache_delete($userId, 'users');
								}
							}
						?>
						<script type="text/javascript"> window.location = '<?php echo get_permalink(get_page_by_title('Profile'));?>'; </script>
				<?php } ?>

			   <div class="row author">
					
					<form id="editAuthor" action="<?php echo current_page_url(); ?>" method="post" role="form" enctype="multipart/form-data">
						
						<div class="col-xs-3 col-sm-3 col-md-3 col-lg-4 avatar">
							<div class="form-group">
								<script type="text/javascript">
									jQuery(document).ready(function() {
										jQuery("#imgInp").change(function(){
											readURL(this);
										});
									});
		
									function readURL(input) {
										if (input.files && input.files[0]) {
											var reader = new FileReader();
											reader.onload = function (e) {
												$('.avatar').attr('src', e.target.result);
											}
											reader.readAsDataURL(input.files[0]);
										}
									}
								</script>
								<?php echo get_avatar($current_user->ID, 230); ?><br>
								<input type="file" id="imgInp" class="form-control userimage chooser" name="profile_picture">
							</div>

							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-input">
								<div class="form-group required">
									<label for="email">Email адрес:</label>
									<input type="text" class="form-control" name="auth_email" disabled value="<?php echo $current_user->user_email; ?>">
								</div>
								<div class="form-group required">
									<label for="oldPass">Старый пароль:</label>
									<input type="password" id="oldPass" class="form-control" name="auth_oldPass" value="">
								</div>
								<div class="form-group">
									<label for="newPass">Новый пароль:</label>
									<input type="password" id="newPass" class="form-control" name="auth_newPass" value="">
								</div>
								<div class="form-group">
									<label for="confirmPass">Повторите новый пароль:</label>
									<input type="password" id="confirmPass" class="form-control" name="auth_confirmPass" value="">
								</div>
							</div>						
						</div>

						<div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
							<div class="form-group">
								<input type="text" class="form-control" name="auth_displayName" value="<?php echo $current_user->display_name;?>">
							</div>
							<div class="form-group required">
								<textarea name="description" class="form-control" rows="8" id="author-info" placeholder="Напишите текст о себе"><?php echo get_user_meta($userId,'description',true); ?></textarea>
							</div>
							<div class="form-group required">
								<div class="form-input">
                        			<?php $sociallinks=get_user_meta($userId,"sociallinks",true); ?>
									<label>Мои социальные сети:</label>

									<ul class="social">
										<li class="facebook">
											<div class="form-group"><input type="text" name="sociallinks[facebook]" class="form-control" value="<?php if(!empty($sociallinks['facebook'])){echo($sociallinks['facebook']);} ?>"></div>
										</li>
										<li class="twitter">
											<div class="form-group"><input type="text" name="sociallinks[twitter]" class="form-control" value="<?php if(!empty($sociallinks['twitter'])){ echo($sociallinks['twitter']);} ?>"></div>
										</li>
										<li class="linkedin">
											<div class="form-group"><input type="text" name="sociallinks[linkedin]" class="form-control" value="<?php if(!empty($sociallinks['linkedin'])){ echo($sociallinks['linkedin']);} ?>"></div>
										</li>
										<li class="tumblr">
											<div class="form-group"><input type="text" name="sociallinks[tumblr]" class="form-control" value="<?php if(!empty($sociallinks['tumblr'])){  echo($sociallinks['tumblr']);} ?>"></div>
										</li>

										<li class="dribble">
											<div class="form-group"><input type="text" name="sociallinks[dribble]" class="form-control" value="<?php if(!empty($sociallinks['dribble'])){ echo($sociallinks['dribble']);} ?>"></div>
										</li>
										<li class="google_plus">
											<div class="form-group"><input type="text" name="sociallinks[google_plus]" class="form-control" value="<?php if(!empty($sociallinks['google_plus'])){ echo($sociallinks['google_plus']);} ?>"></div>
										</li>
		
										<li class="vkontakte">
											<div class="form-group"><input type="text" name="sociallinks[vkontakte]" class="form-control" value="<?php if(!empty($sociallinks['vkontakte'])){echo($sociallinks['vkontakte']);} ?>"></div>
										</li>
									</ul>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group required">
								<div class="form-input">
									<label for="authorPage">Ссылка на мою страницу:</label>
									<input type="text" name="authorPageUrl" id="authorPage" class="form-control" value="<?php echo $current_link; ?>" disabled="disabled">
								</div>
							</div>

							<div class="form-group">
								<button type="submit" name="mrauthProfileSub" class="btn btn-primary">Сохранить изменения</button>
								<a href="<?php echo $current_link; ?>">
								<button type="button" class="btn btn-default">Отменить</button></a>
							</div>
						</div>

					</form>
				</div>

             <?php } else { ?>

				<div class="row author">

					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-4 avatar">
						<?php echo get_avatar($current_user->ID,230); ?> 
					</div>
					<div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
						<h2 class="author-name"><?php echo $current_user->display_name; ?></h2>
						<?php if(get_user_meta($userId,"description",true)) { ?><p><?php echo get_user_meta($userId,"description",true); ?></p><?php } ?>
						<p class="title6">Мои социальные сети:</p>
                   		<?php $sociallinks=get_user_meta($userId,"sociallinks",true);	?>
					
						<?php if($sociallinks) { ?>
					
							<ul class="author-social">
								
								<?php if($sociallinks['facebook']) { ?>
									<li class="facebook"><a target="_blank" href="<?php echo $sociallinks['file://///RBINFO2-PC/htdocs/clickwebstudio/wp-content/themes/nashvan/page-templates/facebook']; ?>">Facebook</a></li>
								<?php } ?>
								
								<?php if($sociallinks['twitter']) { ?>
									<li class="twitter"><a target="_blank" href="<?php echo $sociallinks['file://///RBINFO2-PC/htdocs/clickwebstudio/wp-content/themes/nashvan/page-templates/twitter']; ?>" >Twitter</a></li>
								<?php } ?>
		
								<?php if($sociallinks['linkedin']) { ?>
									<li class="linkedin"><a target="_blank" href="<?php echo $sociallinks['file://///RBINFO2-PC/htdocs/clickwebstudio/wp-content/themes/nashvan/page-templates/linkedin']; ?>">LinkedIn</a></li>
								<?php } ?>
		
								<?php if($sociallinks['tumblr']) { ?>
									<li class="tumblr"><a target="_blank" href="<?php echo $sociallinks['file://///RBINFO2-PC/htdocs/clickwebstudio/wp-content/themes/nashvan/page-templates/tumblr']; ?>">Tumblr</a></li>
								<?php } ?>
		
								<?php if($sociallinks['dribble']) { ?>
									<li class="dribble"><a target="_blank" href="<?php echo $sociallinks['file://///RBINFO2-PC/htdocs/clickwebstudio/wp-content/themes/nashvan/page-templates/dribble']; ?>">Dribble</a></li>
								<?php } ?>
		
								<?php if($sociallinks['google_plus']) { ?>
									<li class="google_plus"><a target="_blank" href="<?php echo $sociallinks['file://///RBINFO2-PC/htdocs/clickwebstudio/wp-content/themes/nashvan/page-templates/google_plus']; ?>">Google+</a></li>
								<?php } ?>
		
								<?php if($sociallinks['vkontakte']) { ?>
									<li class="vkontakte"><a target="_blank" href="<?php echo $sociallinks['file://///RBINFO2-PC/htdocs/clickwebstudio/wp-content/themes/nashvan/page-templates/vkontakte']; ?>" >Vkontakte</a></li>
								<?php } ?>
							</ul>
						<?php } ?>

						<div class="clearfix"></div>

						<p class="title6">Ссылка на мою страницу:</p>

						<p class="italic"><a href="<?php echo $current_link; ?>"><?php echo $current_link; ?></a></p>

						<p class="title6">Email адрес:</p>

						<p class="author-email"><?php echo $current_user->user_email; ?></p>

					</div>

				</div> <!-- .author end -->

			<?php } ?>

			
			
			<ul class="text-center nav nav-pills" role="tablist">
			    <li role="presentation" class="active"><a href="#adv" aria-controls="adv" role="tab" data-toggle="tab">Объявления</a></li>
			    <li role="presentation"><a href="#article" aria-controls="article" role="tab" data-toggle="tab">Статьи</a></li>
			    <li role="presentation"><a href="#business" aria-controls="business" role="tab" data-toggle="tab">Бизнесы</a></li>
			    <li role="presentation"><a href="#fotogallery" aria-controls="fotogallery" role="tab" data-toggle="tab">Фотогалереи</a></li>
			    <li role="presentation"><a href="#videogallery" aria-controls="videogallery" role="tab" data-toggle="tab">Видеогалереи</a></li>
			    <li role="presentation"><a href="#events" aria-controls="events" role="tab" data-toggle="tab">Мероприятия</a></li>
			</ul>

			<div class="tab-content">

            	<?php $userId = trim($userId); // Current user Id ?>

				<div role="tabpanel" class="tab-pane active classified-inside" id="adv">
					
					<div class="overflow">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
							<div class="title pull-left">
								<span>Последние добавленные объявления</span>
							</div>
							
							<ul class="links pull-right">
								<li>
									<?php if(is_user_logged_in()) { echo '<a href="'.get_permalink(get_page_by_title('Classified add')).'">Добавить новое</a>'; }
									else { echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить новое</a>'; }?>
								</li>
							</ul>
						</div>
					</div>
				 	
					<?php
				   	query_posts(array('post_type'=>'classified','post_status'=>array('publish','pending'),'order' =>'DESC','posts_per_page'=>-1,'author'=>$userId));
				   	
					if(have_posts()) {

					  while(have_posts()) {

						the_post();
						$classId = get_the_ID(); // Classified id 
					  	$clsPrice =  get_post_meta($classId,"price",true);
					  	$web_site =  get_post_meta($classId,"web_site",true);
					   	$clsPhone =  get_post_meta($classId,"phone",true);
					   	$clsLocation =  get_post_meta($classId,"location",true);
						$clsadDate = get_the_date('d/m/y');
						$checkPayment = $wpdb->get_row("select * from $payHistorytab where postId = '$classId' and postType='classified' ORDER BY tid DESC limit 0,1");
						$numDays = 0;

						if($checkPayment) {
							
							if(!$checkPayment->activeDays) {
								$addactiveDays = 0;
							} else {
								$addactiveDays = $checkPayment->activeDays;
							}
							
							$activeDaysSec = $addactiveDays * 24 * 60 * 60;
							$activateTime = strtotime($checkPayment->date);
							$expirationTime = $activeDaysSec + $activateTime;
							$numDays = ($expirationTime - time())/60/60/24;
						}
					?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="row ad-block">
							
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
								<?php
								if(has_post_thumbnail()) {
									the_post_thumbnail('thumbnail'); 
								} else {
									echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
								}
								?>
							</div> <!-- .col-lg-3 -->

							<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
								<div id="thepostid-<?php echo get_the_ID(); ?>" class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<span class='review'><?php if(function_exists("pvc_get_post_views")) { echo pvc_get_post_views($classId); } ?></span>
									<span class='glyphicon glyphicon-eye-open'></span>
									<span class='data'><?php echo $clsadDate; ?></span>
								</div>
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12">
									<p><?php echo mb_strimwidth(get_the_excerpt(),0,200,'...'); ?></p>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<span><?php echo $clsPhone; ?></span><span>$<?php echo $clsPrice; ?></span>
									<span>Burnaby</span>
								</div>
							</div>
								
							<div class="text-center bg-expire col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php if($numDays > 0) { ?>
									<span>Размещение вашего объявления заканчивается через <?php echo ceil($numDays); ?> дня</span>
								<?php } else { ?>
									<span>Срок размещения закончился. Выберите новый срок размещения</span>
									<a class="btn-primary-2 openExtendAds" href="javascript:void(0);" data-post-id="<?php echo get_the_ID(); ?>" data-toggle="modal">Продлить срок размещения</a>
								<?php } ?>
								
								<div class="pull-right edit-ad">
									<?php edit_post_link('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '', '</a>', $classId); ?>
									<a href="javascript:void(0)" class="deletepostlink" data-id="<?php echo $classId; ?>" data-toggle="modal"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								</div>
								
								<?php if ( get_post_status ( get_the_ID() ) == 'pending' ) { ?>
									<span class="label label-right"><strong>На рассмотрении</strong></span>
								<?php } ?>
	
							</div> <!-- .bg-expire end -->
						</div> <!-- .ad-block end -->
					</div> <!-- .col-lg-12 end -->
					<?php } } else { ?>
					  <div class="row empty text-center">
						<p class="text-center">У вас еще нет опубликованных объявлений</p>
						<a href="<?php echo get_permalink(get_page_by_title('Classified add'));?>" class="btn btn-success">Разместить новое объявление</a>
					  </div>
                 <?php } wp_reset_query(); ?>
				</div> <!-- #adv end -->

				<div role="tabpanel" class="tab-pane" id="article">
  
				  <div class="overflow">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					  <div class="title pull-left"> <span>Последние добавленные статьи</span> </div>
					  <ul class="links pull-right">
						<li> <a href="#" class="pull-right">Редактировать мои статьи</a> </li>
						<li> <a href="#" class="pull-right">Добавить статью</a> </li>
					  </ul>
					</div>
				  </div>
				  <?php
					query_posts(array('post_type'=>'post','post_status' => array('publish','pending'), 'order' =>'DESC','posts_per_page'=>3,'author'=>$userId));
					if(have_posts()) {
						
						echo '<div class="article-top author-articles">';  
							echo '<div class="wrapper">';
							
							$i=1;
							while(have_posts()) { the_post();
												
								if($i==1) { echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">';}
								else { echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">'; } ?>
								
								<a class="cat" href="<?php the_permalink(); ?>">
								<?php
									if(has_post_thumbnail()) { if($i==1){ the_post_thumbnail('medium'); } else { the_post_thumbnail('thumbnail'); } }
									else {echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';}
								?>
								<div class="text_block">
									<?php 
										if($i==1){
												echo '<h4>'.mb_strimwidth(get_the_title(),0,30,'...').'</h4>';
												echo '<p>'.mb_strimwidth(get_the_excerpt(),0,120,'...').'</p>';
										} else {
												echo '<p>'.mb_strimwidth(get_the_title(),0,45,'...').'</p>';
										}
									 ?>
									<ul class="info">
										<li><span class="glyphicon glyphicon-eye-open"></span>
										<?php if(function_exists("pvc_get_post_views")) { echo pvc_get_post_views(get_the_ID()); } ?>
										</li>
										<li><?php echo number_format_i18n(get_comments_number(get_the_ID())); ?></li>
									</ul>
								</div>
								</a>
							</div>
						<?php $i++; }
						echo '</div>';

						query_posts(array('post_type'=>'post','post_status' => array('publish','pending'), 'order' =>'DESC','posts_per_page'=>-1,'author'=>$userId,'offset'=>3));  
						$i=1;
						while(have_posts()) { the_post();
											   
							echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 the-margin-class">'; ?>
							<a class="cat" href="<?php the_permalink(); ?>">
								<?php
									if(has_post_thumbnail()) {
									if($i==1){ the_post_thumbnail('medium'); } else { the_post_thumbnail('thumbnail'); } }
									else { echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">'; }
								?>
								<div class="text_block">
									<p><?php echo mb_strimwidth(get_the_title(),0,30,'...'); ?></p>
									<ul class="info">
										<li><span class="glyphicon glyphicon-eye-open"></span>
										<?php if(function_exists("pvc_get_post_views")) { echo pvc_get_post_views(get_the_ID()); } ?>
										</li>
										<li> <?php echo number_format_i18n(get_comments_number(get_the_ID())); ?> </li>
									</ul>
								</div>
							</a>
							</div>
						<?php  $i++; }
				
						echo '</div>';
				
					} else { ?>
						<div class="row empty text-center">
							<p class="text-center">У вас еще нет опубликованных статей на сайте</p>
							<a href="<?php echo admin_url(); ?>post-new.php" class="btn btn-success">Разместить новую статью</a> </div>
					<?php } wp_reset_query(); ?>
				</div><!-- end #articles -->

				<div role="tabpanel" class="tab-pane business-directory" id="business">
				
					<div class="overflow">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
							<div class="title pull-left">
								<span>Последние добавленные бизнесы</span>
							</div>
							
							<ul class="links pull-right">
								<li>
									<?php if(is_user_logged_in()) { echo '<a href="'.get_permalink(get_page_by_title('Business Directory Add')).'">Разместить свой бизнес</a>'; }
									else { echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить новое</a>'; }?>
								</li>
							</ul>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
							<?php
								query_posts(array('post_type'=>'business','post_status' => array('publish','pending'),'order' =>'DESC','posts_per_page'=>-1,'author'=>$userId));
								
								if(have_posts()) {
									while(have_posts()) {
	
									   the_post();
									   $businessId = get_the_ID(); // Business id 	
									   $busweb_site =  get_post_meta($businessId,"website",true);
									   $busPhone =  get_post_meta($businessId,"phone",true);
									   $busclsAddress = get_post_meta($businessId,"address",true);		
									   $busCatList = get_the_terms($businessId,'bussiness_cat'); // Categories
									   $businessPayment = $wpdb->get_row("select * from $payHistorytab where postId = '$businessId' and postType='business' ORDER BY tid DESC limit 0,1");
									   $numbusinessDays = 0;
							   
									if($businessPayment) {
										if(!$businessPayment->activeDays) {
											$businessactiveDays = 0;
										} else {
											$businessactiveDays = $businessPayment->activeDays;
										}
									
										$activebusinessDaysSec = $businessactiveDays * 24 * 60 * 60;
										$activatebusinessTime = strtotime($businessPayment->date);
										$expirationbusinessTime = $activebusinessDaysSec + $activatebusinessTime;
										$numbusinessDays = ($expirationbusinessTime - time())/60/60/24;
									}
								?>
	
								<div class="row ad-block business-block">
									<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
										<?php
										if(has_post_thumbnail())
										 {the_post_thumbnail('thumbnail');}
										 else{echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';}
										?>
									</div>
	
									<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
										<div id="thepostid-<?php echo get_the_ID(); ?>" class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<h3 style="display:inline-block;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
											<span style="line-height:40px;" class='review'><?php if(function_exists("pvc_get_post_views")) { echo pvc_get_post_views($classId); } ?></span>
											<span style="line-height:40px;" class='glyphicon glyphicon-eye-open'></span>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text">
	
											<p>Рейтинг:
											<?php
												if(function_exists("csr_get_overall_rating_stars")) {										
												  echo csr_get_overall_rating_stars($businessId);		
												  echo "<span class='tt-ratc'>";
												  echo csr_get_rating_count($businessId)." отзыва</span>";
												}
											?>
											</p>
											<p>Категория:  <span>
											<?php   
												if(is_array($busCatList)) { $i=1;													 
												 
												 foreach ($busCatList as $cat) {
			
												   $termlink=get_term_link(intval($cat->term_id),'bussiness_cat');
												   echo '<a href="'.$termlink.'">'.$cat->name.'</a>';												
													if($i<count($busCatList))
													 { echo ", "; }
													 $i++;
			
												  }														 
												} 
											?>
											</span></p>
		
											<p>Вебсайт: <span>
											<?php
											if(!empty($busweb_site))
											{
												   $parsed = parse_url($busweb_site);
													if (empty($parsed['scheme'])){
														$urlStr = 'http://'.ltrim($busweb_site,'/');
													}
													else{$urlStr= $busweb_site;}
													echo '<a target="_blank" href="'.$urlStr.'">'.$busweb_site.'</a>';	
											}
											?>
											</span></p>
											<p>Телефон: <span><?php echo $busPhone; ?></span></p>
											<p>Адрес:  <span>
											<?php 
											  if(!empty($busclsAddress['address']))
											  {											  
											   echo '<a class="mr-gmap-location-pop" data-add-lat="'.$busclsAddress["lat"].'" data-add-long="'.$busclsAddress["lng"].'">'.$busclsAddress['address'].'</a>';
											  }											  
											 ?>
											</span></p>
	
										</div> <!-- .col-lg-12 -->
	
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12"><?php the_excerpt(); ?><div class="clearfix"></div></div>
									</div> <!-- .col-lg-9 end -->
	
									<div class="text-center bg-expire col-xs-12 col-sm-12 col-md-12 col-lg-12">
								
										<?php if($numbusinessDays > 0) { ?>
											<span>Размещение вашего бизнеса заканчивается через <?php echo floor($numbusinessDays); ?> дня</span>
										<?php } else { ?>
											<span>Размещение вашего бизнеса закончилось</span>										
											<a data-toggle="modal" href="javascript:void(0);" data-post-id="<?php echo get_the_ID(); ?>" class="btn-primary-2 openExtendBusiness">Продлить срок размещения</a>
										<?php } ?>
	
										<div class="pull-right edit-ad">
											<?php edit_post_link('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '<a>', '</a>', $businessId); ?>
											<a href="javascript:void(0)" class="deletepostlink" data-id="<?php echo $businessId; ?>" data-toggle="modal"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
										</div>
										
										<?php if ( get_post_status ( get_the_ID() ) == 'pending' ) { ?>
											<span class="label label-right">На рассмотрении</span>
										<?php } ?>
	
									</div> <!-- .bg-expire end -->
								</div>                    
						<?php } }
	
								else {
									echo '<div class="row empty text-center">';
										echo '<p class="text-center">У вас еще нет размещенных бизнесов</p>';
										echo '<a href="'.get_permalink(get_page_by_title("Business Directory Add")).'" class="btn btn-success">Разместить новый бизнес</a>';
									echo '</div>';	
								}
	
								wp_reset_query();
							?>
							</div>
						</div><!-- .col-lg-12 end -->
				</div><!-- .col-lg-12 end-->
				</div>
				
				<div role="tabpanel" class="tab-pane" id="fotogallery">
					<div class="row empty text-center underconstruction">
						<p class="text-center">В разработке.<br>Мы оповестим вас первыми о запуске данного раздела!</p>
					</div>
				</div><!-- end #fotogallery -->

				<div role="tabpanel" class="tab-pane" id="videogallery">
					
					<div class="overflow">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="title pull-left">
								<span>Последние добавленные</span>
							</div>
							<ul class="links pull-right">
								<li> <a href="<?php echo admin_url(); ?>post-new.php?post_type=videogallery">Добавить видео</a> </li>
							</ul>
						</div>
					</div>

					<?php
					query_posts(array('post_type'=>'videogallery','post_status' => array('publish','pending'), 'order'=>'DESC','posts_per_page'=>-1,'author'=>$userId));
				  	if(have_posts()) { ?>

						<div class="mr-video-gallery-out">
							<?php while(have_posts()) { the_post(); ?>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mr-video-bx-outer">
									<?php if(has_post_thumbnail()) { echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail().'</a>'; } ?> 
								</div>
							<?php
					 }
					 	echo '<div class="clearfix"></div>';
						echo '</div>';
				  }
				  else {  ?>
					 <div class="row empty text-center">
						<p class="text-center">У вас еще нет опубликованных видео</p>
						<a href="<?php echo admin_url(); ?>post-new.php?post_type=videogallery" class="btn btn-success">Опубликовать новое видео</a>
					  </div>
               		<?php } wp_reset_query(); ?>       	
				</div><!-- end #videogallery -->
				
				<div role="tabpanel" class="tab-pane event-inside" id="events">
					<div class="overflow">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="title pull-left">
								<span>Последние добавленные события</span>
							</div>
							<ul class="links pull-right">
							  <li> <a href="<?php echo get_permalink(get_page_by_title("Event Add")); ?>">Добавить новое событие</a> </li>
							</ul>
						</div>
					</div>
                 <?php  
				   query_posts(array('post_type'=>'event','post_status' => array('publish','pending'),'order'=>'DESC','posts_per_page'=>-1,'author'=>$userId));
				  	if(have_posts()) {

					  echo '<div class="row event">';
					  
					  while(have_posts()) {

						the_post();
						$eventId = get_the_ID(); // Event id 
					    $website =  get_post_meta($eventId,"website",true);
					    $evPhone =  get_post_meta($eventId,"phone",true);
					    $evLocation =  get_post_meta($eventId,"location",true);
					    $evPrice =  get_post_meta($eventId,"price",true);
						$evlink = get_post_meta($eventId,"link",true);
						
						$businessPayment = $wpdb->get_row("select * from $payHistorytab where postId = '$eventId' and postType='event' ORDER BY tid DESC limit 0,1");
						
					    $numbusinessDays = 0;
						if($businessPayment) {
							if(!$businessPayment->activeDays) {
								$businessactiveDays = 0;
							} else {
								$businessactiveDays = $businessPayment->activeDays;
							}
						
							$activebusinessDaysSec = $businessactiveDays * 24 * 60 * 60;
							$activatebusinessTime = strtotime($businessPayment->date);
							$expirationbusinessTime = $activebusinessDaysSec + $activatebusinessTime;
							$numbusinessDays = ($expirationbusinessTime - time())/60/60/24;
						}
					?>

						<div class="col-sm-4 col-md-4 col-lg-4 event-block">
						    <div class="hover">
						        <div class="edit text-center">
						            <ul>
						                <li><a href="#"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>Редактировать</a></li>
						                <li><a href="#"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>Удалить</a></li>
						            </ul>
						        </div>
						    </div>

						    <div class="event-block-thumb">
						       <a href="<?php the_permalink(); ?>">
								 <?php if(has_post_thumbnail()){ the_post_thumbnail('thumbnail',false,false); } else {
                                     echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">'; 
									} ?> 
                                    <div class="opacity">
                                        <div class="event-info text-center">
                                            <h3><?php the_title(); ?></h3>
                                            <p>Спектакль</p>
                                        </div>
                                    </div>
                                </a>
						    </div>

						    <div class="event-block-content">
						<?php
							$countrows = $wpdb->get_row("SELECT count(*) as countevent FROM $wpdb->postmeta WHERE 1 AND post_id = '$eventId' AND meta_key LIKE 'event_date_%'");
							if( intval($countrows->countevent) > 0 ){
									
								for($counter=0; $counter < intval($countrows->countevent); $counter++){
									
									echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 date">';
										echo '<span><strong>'. get_post_meta($eventId, 'event_date_'.$counter, true) .'</strong>&nbsp;'. the_russian_time( monthName(get_post_meta($eventId, 'event_month_'.$counter, true)) ) .'&nbsp;'.get_post_meta ($eventId, 'evnt_time_start_hours_0', true ) .':'. get_post_meta ($eventId, 'evnt_time_start_minutes_0', true ) . '&nbsp;'. get_post_meta ($eventId, 'event_start_time_0', true ).'</span>';
									echo '</div>';
								}
							}
						?>
        						<div class="clearfix"></div>
						        <p><?php the_excerpt(); ?></p>
						    </div>
							<div class="bg-expire-3 text-center">
                            	
                                <?php if($numbusinessDays > 0) { ?>
                                    <span style="color:#e21922;">Размещение истекает через <?php echo ceil($numbusinessDays); ?> дня</span>
                                <?php } ?>
								<?php if ( get_post_status ( get_the_ID() ) == 'pending' ) { ?>
									<span class="label label-right" style="color:#e21922;"><strong>На рассмотрении</strong></span>
								<?php } ?>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    	<?php if($numbusinessDays < 0) { ?>
                                            <a class="btn btn-blue btn-primary openExtendEvent" href="javascript:void(0);" data-post-id="<?php echo get_the_ID(); ?>" data-toggle="modal">Продлить</a>
                                        <?php } ?>
										<div class="ad-block pull-right">
                                            <div class="edit-ad">
                                              <?php edit_post_link('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '', '</a>', $eventId); ?>
                                              <a href="javascript:void(0)" class="deletepostlink" data-id="<?php echo $eventId; ?>" data-toggle="modal">
                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                              </a>
                                            </div>
         								</div>
									</div>
								</div>
								
							</div>
						     <div class="bg-info event-block-location col-xs-12 col-sm-12 col-md-12 col-lg-12">
						       <p class="location"><?php if(!empty($evLocation['address'])) { echo mb_strimwidth($evLocation['address'],0,40,'...').', OR'; } ?></p>
						    </div>
						    <div class="event-buybtn-out bg-primary text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
						        <p>Стоимость билетов от <strong><?php if($evPrice){ echo "$". $evPrice; }?></strong><p>
						        <a href="<?php if($evPrice){ echo "http://". $evlink; }?>"><button class="btn btn-primary" type="button">Купить</button></a>
						    </div>
						</div>
                         <?php 
					  }
					  echo '</div>';
				  } else { ?>

                     <div class="row empty text-center">
			        	<p class="text-center">У вас еще нет опубликованных мероприятий</p>
			        	<a href="<?php echo get_permalink(get_page_by_title('Event Add')); ?>" class="btn btn-success">Создать новое мероприятие</a>
			        </div>
                    <?php } ?>					

				</div><!-- end #events -->

			</div><!-- end #tab-content -->
			

		</div>
	</div>
	
	</div>
	
	<?php get_sidebar(); ?>

<div class="modal fade" id="extendAds" tabindex="-1" role="dialog" aria-labelledby="extendAdsModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">
	  
	  <form method="POST" name="extend-add-days" id="extend-add-days" role="form">
		<input type="hidden" name="addpostid" value="0" id="addpostid" />
	  
		  <div class="modal-header">
	
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
	
			<h4 class="title4 text-center">Выберите варианты продления</h4>
	
			<div class="col-centered col-xs-7 col-sm-7 col-md-7 col-lg-7">
	
				<?php
					//Dispay Ads options
					if(count($adCostOptionsArr)>0) {
					
						for($i=0;$i<count($adCostOptionsArr);$i++)
						{
							if($adCostOptionsArr[$i]['postAdOptTitle'])
							{ ?>
							<div class="radio">                
								<label>
									<input type="radio" name="extadddays" id="inputDays<?php echo $i;?>" value="<?php echo $adCostOptionsArr[$i]['postAdDays']; ?>" checked="checked">
									<?php echo $adCostOptionsArr[$i]['postAdOptTitle']; ?></span>
								</label>
							</div>
							<?php }
						}
					}
				?>
	
			</div>
	
		  </div>

		  <div class="modal-footer">
	
			<button type="submit" class="btn btn-danger" id="extendsmyadddays" name="extendsmyadddays" value="Extend Days">Да я подтверждаю</button>
	
			<button type="button" class="btn btn-link cancel" data-dismiss="modal">Отменить</button>
	
		  </div>
		
		</form>
    </div>

  </div>

</div>

<!-- extendAds end -->
<div class="modal fade" id="extendBusiness" tabindex="-1" role="dialog" aria-labelledby="extendBusinessModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">
		
		<form method="POST" name="extend-business-add-days" id="extend-business-add-days" role="form">
		 <input type="hidden" name="businessaddpostid" value="0" id="businessaddpostid" />
		 <div class="modal-header">

        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>

        	<h4 class="title4 text-center">Выберите варианты продления</h4>

        	<div class="col-centered col-xs-8 col-sm-8 col-md-8 col-lg-10">
				<?php
					//Cost settins data
					if(count($businessCostOptArr)>0) {
						
						for($i=0;$i<count($businessCostOptArr);$i++) {
							
							if($businessCostOptArr[$i]['postBusinssOptTitle']) { ?>
							
								<div class="radio">                
									<label>
										<input type="radio" name="extbadddays" id="inputBDays<?php echo $i;?>" value="<?php echo $businessCostOptArr[$i]['postBusinssDays']; ?>" checked="checked"><?php echo $businessCostOptArr[$i]['postBusinssOptTitle']; ?></span>
									</label>
								</div>
				<?php } } } ?>
			</div>
		  </div>

		  <div class="modal-footer">
			<button type="submit" class="btn btn-danger" id="extendsbusinessadddays" name="extendsbusinessadddays" value="Extend Business Days">Да я подтверждаю</button>
			<button type="button" class="btn btn-link cancel" data-dismiss="modal">Отменить</button>
		  </div>
	  
	  </form>

    </div>

  </div>

</div>

<!-- extendBusiness end -->

<!-- extendEvents start -->
<div class="modal fade" id="extendEvents" tabindex="-1" role="dialog" aria-labelledby="extendEventsModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">
		
		<form method="POST" name="extend-events-add-days" id="extend-events-add-days" role="form">
		 <input type="hidden" name="eventaddpostid" value="0" id="eventaddpostid" />
		 <div class="modal-header">

        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>

        	<h4 class="title4 text-center">Выберите варианты продления</h4>

        	<div class="col-centered col-xs-8 col-sm-8 col-md-8 col-lg-10">
				<?php
					//Cost settins data
					if(count($eventCostOptArr)>0) {

						for($i=0;$i<count($eventCostOptArr);$i++) {
							
							if($eventCostOptArr[$i]['postEventOptTitle']) { ?>
							
								<div class="radio">                
									<label>
										<input type="radio" name="exteadddays" id="inputEDays<?php echo $i;?>" value="<?php echo $eventCostOptArr[$i]['postEventDays']; ?>" checked="checked"><?php echo $eventCostOptArr[$i]['postEventOptTitle']; ?></span>
									</label>
								</div>
				<?php } } } ?>
			</div>
		  </div>

		  <div class="modal-footer">
			<button type="submit" class="btn btn-danger" id="extendseventsadddays" name="extendseventsadddays" value="Extend Event Days">Да я подтверждаю</button>
			<button type="button" class="btn btn-link cancel" data-dismiss="modal">Отменить</button>
		  </div>
	  
	  </form>

    </div>

  </div>

</div>

<!-- extendEvents end -->

	</div>

</div>
	<!-- delete modal -->
	<div class="modal fade" id="deleteAdsModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdsModalLabel">
		<div class="modal-dialog" role="document">
			<form name="deleteform" id="deleteform" method="post">
				<div class="modal-content text-center">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
						<h4 class="title4">Вы уверены что хотите удалить?<br><span class="text-danger">Это нельзя будет восстановить!</h4>
					</div>
					<div class="modal-footer text-center">
						<input type="hidden" name="deletepostid" id="deletepostid" />
						<button type="submit" id="yesdeletepost" class="btn btn-danger" name="yesdeletepost" value="Да я подтверждаю">Да я подтверждаю</button>
						<button type="button" class="btn btn-default cancel" data-dismiss="modal">Отменить</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- end of delete modal -->

<script type="text/javascript">

 jQuery(document).ready(function(e) {

 	 
	jQuery(".openExtendAds").click(function(){
		jQuery("#addpostid").val( jQuery(this).attr("data-post-id") );
		jQuery("#extendAds").modal('show');
	});

	jQuery(".openExtendBusiness").click(function(){
		jQuery("#businessaddpostid").val( jQuery(this).attr("data-post-id") );
		jQuery("#extendBusiness").modal('show');
	});

	 jQuery(".deletepostlink").click(function(){
		jQuery("#deleteAdsModal").modal("show");
		jQuery("#deletepostid").val(jQuery(this).attr("data-id"));
	 });

	jQuery(".openExtendEvent").click(function(){
		jQuery("#eventaddpostid").val( jQuery(this).attr("data-post-id") );
		jQuery("#extendEvents").modal('show');
	});

	 jQuery("#editAuthor").submit(function(e) {

		var newPass =jQuery('#newPass').val();

		var newPassLen=newPass.length;

		if(newPassLen>0)

		{

		 var oldPass= jQuery('#oldPass').val(); 	

		 if((oldPass.length)<3)

		 {

		   jQuery("#oldPass").focus();		   

		   return false; 

		 }

		 else

		 {

			jQuery("#oldPass").removeClass('input-error'); 

		 }

		 var confirmPass= jQuery('#confirmPass').val();

		 if((confirmPass.length)<3)

		 {

		   jQuery("#confirmPass").focus();		   

		   return false;

		 }

		 if(confirmPass==newPass)

		 {

			return true;

		 }

		 else

		 {

			 alert('Password does not match the confirm password.');

			 return false;  

		 }

		}



	 });

});
</script>

<!--Classified Modal Box-->
<div aria-labelledby="confirmModalLabel" role="dialog" tabindex="-1" id="confirmModal" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" id="mrclose-model" class="close" type="button"><span aria-hidden="true"></span></button>
        <h4 class="title4">Спасибо!</h4>
      </div>
      <div class="modal-body">
        	<p>Ваше объявление было успешно размещено на сайте<br> и мы покажем вам его через <strong>5 сек.</strong></p>
      </div>
    </div>
  </div>
</div>
<!--End of Classified Modal Box-->
<?php get_footer(); ?>