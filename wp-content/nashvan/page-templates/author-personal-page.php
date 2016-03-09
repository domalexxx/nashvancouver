<?php
if(!is_user_logged_in())
{
	wp_redirect(home_url());
}
/* Template Name: author personal page */
get_header();
$userId = $current_user->ID;
global $wpdb;
$payHistorytab = $wpdb->prefix."payment_history";
						
if(isset($_REQUEST['yesdeletepost']) && $_REQUEST['yesdeletepost']) {
	wp_delete_post( $_REQUEST['deletepostid'], true );
	$_SESSION['delete_post_mess'] = "delete post successfully.";
}
?>
<div class="container author-personal-page <?php if($_GET['edit']=='profile'){echo 'author-personal-page-edit';}?>">
	<div class="row ">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php
			if(isset($_SESSION['delete_post_mess'])) {

				echo  '<div style="margin-top:18px;" class="alert alert-success fade in">';
					echo '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>';
					echo '<strong>Success!</strong> ' . $_SESSION['delete_post_mess'];
				echo '</div>';
				unset($_SESSION['delete_post_mess']);
			}
			
			if(isset($_SESSION['success_mess']))
			{
				echo  '<div style="margin-top:18px;" class="alert alert-success fade in">';
					echo '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>';
					echo '<strong>Success!</strong> Profile data updated successfully.';
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
			 if(isset($_GET['edit']) && $_GET['edit']=='profile')
			 {
				if(isset($_POST['mrauthProfileSub']))
				{
					extract($_POST);
					$sociallinks =$sociallinks;
					update_user_meta($userId,"description",$description);
					update_user_meta($userId,"description",$description);
					update_user_meta($userId,"sociallinks",$sociallinks);
					wp_update_user(array('ID' =>$userId,"display_name"=>$auth_displayName));
					
					if( !empty($_FILES['profile_picture']['name']) )
					{
						if( $_FILES['profile_picture']['size'] > 0 )
						{
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
					if(!empty($auth_newPass))
					{
						 $passCheck =wp_check_password($auth_oldPass,$current_user->user_pass,$userId); 
						 if(!$passCheck)
						 {
							$_SESSION['error_mess']="Incorrect Old Password";
						 }
						else
						{
							wp_set_password( $auth_newPass, $userId );
							wp_cache_delete($userId, 'users');
						}
					}
				  ?>
                  
					<script type="text/javascript"> window.location = '<?php echo get_permalink(get_page_by_title('Profile'));?>'; </script>
				  <?php
				}
			 ?>
			 
            
               <div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<span>Мой профайл</span>
				</div>
                        
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
						<input type="text" class="form-control" name="auth_displayName" 
                        value="<?php echo $current_user->display_name;?>">
					</div>
					<div class="form-group required">
						<textarea name="description" class="form-control" rows="8" id="author-info" 
                        placeholder="Напишите текст о себе"><?php echo get_user_meta($userId,'description',true);
						 ?></textarea>
					</div>
					<div class="form-group required">
						<div class="form-input">
                        <?php $sociallinks=get_user_meta($userId,"sociallinks",true); ?>
							<label>Мои социальные сети:</label>
							<ul class="social">
								<li class="facebook">
									<div class="form-group">
										<input type="text" name="sociallinks[facebook]" class="form-control" value="<?php if(!empty($sociallinks['facebook'])){echo($sociallinks['facebook']);} ?>">
									</div>
								</li>
								<li class="twitter">
									<div class="form-group">
										<input type="text" name="sociallinks[twitter]" class="form-control" value="<?php if(!empty($sociallinks['twitter'])){ echo($sociallinks['twitter']);} ?>">
									</div>
								</li>
								<li class="linkedin">
									<div class="form-group">
										<input type="text" name="sociallinks[linkedin]" class="form-control" value="<?php if(!empty($sociallinks['linkedin'])){ echo($sociallinks['linkedin']);} ?>">
									</div>
								</li>
								<li class="tumblr">
									<div class="form-group">
										<input type="text" name="sociallinks[tumblr]" class="form-control" value="<?php if(!empty($sociallinks['tumblr'])){  echo($sociallinks['tumblr']);} ?>">
									</div>
								</li>
								<li class="dribble">
									<div class="form-group">
										<input type="text" name="sociallinks[dribble]" class="form-control" value="<?php if(!empty($sociallinks['dribble'])){ echo($sociallinks['dribble']);} ?>">
									</div>
								</li>
								<li class="google_plus">
									<div class="form-group">
										<input type="text" name="sociallinks[google_plus]" class="form-control" value="<?php if(!empty($sociallinks['google_plus'])){ echo($sociallinks['google_plus']);} ?>">
									</div>
								</li>
								<li class="vkontakte">
									<div class="form-group">
										<input type="text" name="sociallinks[vkontakte]" class="form-control" value="<?php if(!empty($sociallinks['vkontakte'])){echo($sociallinks['vkontakte']);} ?>">
									</div>
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
             <?php	 
			 }
			 else
			 { 
			?>
			<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<span>Мой профайл</span>
				<a href="<?php echo add_query_arg('edit','profile',current_page_url());?>" 
				class="pull-right">Редактировать страницу</a>
			</div>
			<div class="row author">
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-4 avatar">
					<?php echo get_avatar($current_user->ID,230); ?> 
				</div>
				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
					<h2 class="author-name"><?php echo $current_user->display_name; ?></h2>
					<?php if(get_user_meta($userId,"description",true)) { ?><p><?php echo get_user_meta($userId,"description",true); ?></p><?php } ?>
					<p class="title6">Мои социальные сети:</p>
                    <?php $sociallinks=get_user_meta($userId,"sociallinks",true);	?>
					<ul class="author-social">
						<?php if($sociallinks['facebook']) { ?>
							<li class="facebook"><a target="_blank" href="<?php echo $sociallinks['facebook']; ?>">Facebook</a></li>
						<?php } ?>
						<?php if($sociallinks['twitter']) { ?>
							<li class="twitter"><a target="_blank" href="<?php echo $sociallinks['twitter']; ?>" >Twitter</a></li>
						<?php } ?>
						<?php if($sociallinks['linkedin']) { ?>
							<li class="linkedin"><a target="_blank" href="<?php echo $sociallinks['linkedin']; ?>">LinkedIn</a></li>
						<?php } ?>
						<?php if($sociallinks['tumblr']) { ?>
							<li class="tumblr"><a target="_blank" href="<?php echo $sociallinks['tumblr']; ?>">Tumblr</a></li>
						<?php } ?>
						<?php if($sociallinks['dribble']) { ?>
							<li class="dribble"><a target="_blank" href="<?php echo $sociallinks['dribble']; ?>">Dribble</a></li>
						<?php } ?>
						<?php if($sociallinks['google_plus']) { ?>
							<li class="google_plus"><a target="_blank" href="<?php echo $sociallinks['google_plus']; ?>">Google+</a></li>
						<?php } ?>
						<?php if($sociallinks['vkontakte']) { ?>
							<li class="vkontakte"><a target="_blank" href="<?php echo $sociallinks['vkontakte']; ?>" >Vkontakte</a></li>
						<?php } ?>
					</ul>
					<div class="clearfix"></div>
					<p class="title6">Ссылка на мою страницу:</p>
					<p class="italic"><a href="<?php echo $current_link; ?>"><?php echo $current_link; ?></a></p>
					<p class="title6">Email адрес:</p>
					<p class="author-email"><?php echo $current_user->user_email; ?></p>
				</div>
			</div>
            
			<?php
			 }
			?>
            
			<ul class="text-center nav nav-pills" role="tablist">
			    <li role="presentation" class="active"><a href="#adv" aria-controls="adv" role="tab" data-toggle="tab">Обьявления</a></li>
			    <li role="presentation"><a href="#article" aria-controls="article" role="tab" data-toggle="tab">Статьи</a></li>
			    <li role="presentation"><a href="#business" aria-controls="business" role="tab" data-toggle="tab">Бизнесы</a></li>
			    <li role="presentation"><a href="#fotogallery" aria-controls="fotogallery" role="tab" data-toggle="tab">Фотогалереи</a></li>
			    <li role="presentation"><a href="#videogallery" aria-controls="videogallery" role="tab" data-toggle="tab">Видеогалереи</a></li>
			    <li role="presentation"><a href="#events" aria-controls="events" role="tab" data-toggle="tab">Мероприятия</a></li>
			</ul>
			<div class="tab-content">
            <?php
			  $userId=trim($userId); // Current user Id
			?>
				<div role="tabpanel" class="tab-pane active classified-inside" id="adv">
				 <?php
				   query_posts(array('post_type'=>'classified','post_status'=>'publish','order' =>'DESC','posts_per_page'=>20,'author'=>$userId));
				   if(have_posts())
				   {
					  while(have_posts())
					  {
						the_post();
						$classId = get_the_ID(); // Classified id 
					  	$clsPrice =  get_post_meta($classId,"price",true);
					  	$web_site =  get_post_meta($classId,"web_site",true);
					   	$clsPhone =  get_post_meta($classId,"phone",true);
					   	$clsLocation =  get_post_meta($classId,"location",true);
						$clsadDate= get_the_date('d/m/y');
						
						$checkPayment = $wpdb->get_row("select * from $payHistorytab where postId = '$classId' and postType='classified'");
	  					if($checkPayment) {
							
							$activeDaysSec = $checkPayment->activeDays * 24 * 60 * 60;
							$activateTime = strtotime($checkPayment->date);
							$expirationTime = $activeDaysSec + $activateTime;
							$numDays = ($expirationTime - time())/60/60/24;
						}
					?>
                         <div class="row ad-block">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="row col-xs-2 col-sm-2 col-md-2 col-lg-3">
									<?php
										 if(has_post_thumbnail())
										 {
										    the_post_thumbnail('thumbnail'); 
										 }
										 else
										 {
											echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
										 }
										 ?>
								</div>
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
									<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<span class='review'>
										  <?php 
											//function Post view Count
											if(function_exists("pvc_get_post_views"))
											{ 
											 echo pvc_get_post_views($classId);
											} 
											?>
                                        </span>
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
							</div>
						</div>
						
						<div class="text-center bg-expire col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span>Размещение вашего объявления заканчивается через <?php if($numDays > 0) { echo floor($numDays); } else { echo "expire"; } ?> дня</span>
							<a class="btn-primary-2" href="<?php //echo get_permalink($classId); ?>#extendAds" data-toogle="modal">Продлить срок размещения</a>
							<div class="pull-right edit-ad">
								<?php edit_post_link('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '', '</a>', $classId); ?>
								<a href="#deleteAdsModal" class="deletepostlink" data-id="<?php echo $classId; ?>" data-toggle="modal"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</div>
						</div>
					</div>
                        <?php
					  }					   
				   }
				   else
				   {
				 ?>
                  <div class="row empty text-center">
                    <p class="text-center">У вас еще нет опубликованных объявлений</p>
                    <a href="<?php echo get_permalink(get_page_by_title('Classified add'));?>" class="btn btn-success">
                     Разместить новое объявление
                    </a>
				  </div>
                 <?php
				   }
				   wp_reset_query();
				   ?>
                  
					
				</div><!-- end #adv -->
				<div role="tabpanel" class="tab-pane" id="article">                				
                   <div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<span>Последние добавленные статьи</span>
								<div class="pull-right">
									<a href="#">Редактировать мои статьи</a>
									<a href="#">Добавить статью</a>
								</div>
							</div>
						</div>
							
						</div>
						
                        <?php
						  query_posts(array('post_type'=>'post','post_status'=>'publish',
						  'order' =>'DESC','posts_per_page'=>9,'author'=>$userId));
						  if(have_posts())
						  {
							echo '<div class="row article-top">';  
							$i=1;
							 while(have_posts())
							 {
						 	   the_post();
						       if($i==1){ echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">';}
							   else{ echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';}
							  ?>							  
								<a class="cat" href="<?php the_permalink(); ?>">
                                <?php
								 if(has_post_thumbnail())
								 {
								   if($i==1){the_post_thumbnail('medium');}else{the_post_thumbnail('thumbnail');};
								 }
								 else
								 {echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';}
								?>
									<div class="text_block">
										<h1><?php echo mb_strimwidth(get_the_title(),0,30,'...'); ?></h1>
										<p>
										 <?php 
										  if($i==1){
										  echo mb_strimwidth(get_the_excerpt(),0,120,'...');
										  }else{echo mb_strimwidth(get_the_excerpt(),0,60,'...');}
										 ?>
                                         </p>
										<ul class="info">
											<li><span class="glyphicon glyphicon-eye-open"></span> 
											<?php 
											//function Post view Count
											 if(function_exists("pvc_get_post_views"))
											 { 
											  echo pvc_get_post_views(get_the_ID());
											 } 
											?>
                                            </li>
											<li>
                                            <?php
                                              echo number_format_i18n(get_comments_number(get_the_ID())); 
											 ?>
                                            </li>
										</ul>
									</div>
								</a>
							</div>
                          <?php  
						   $i++;
							 }
							echo '</div>';   
                          }  
						  else
						  {
							?>
                             <div class="row empty text-center">
                                <p class="text-center">У вас еще нет опубликованных статей на сайте</p>
                                <a href="<?php echo admin_url(); ?>post-new.php" class="btn btn-success">
                                Разместить новую статью</a>
                            </div>
                            <?php  
						  }
						   wp_reset_query();
                          ?>  
							
				</div><!-- end #articles -->
				<div role="tabpanel" class="tab-pane business-directory" id="business">
                 <?php
				    query_posts(array('post_type'=>'business','post_status'=>'publish','order' =>'DESC','posts_per_page'=>6,'author'=>$userId));
					if(have_posts())
					{
						while(have_posts())
						{
						   the_post();
						   $businessId = get_the_ID(); // Business id 	
						   $busweb_site =  get_post_meta($businessId,"website",true);
						   $busPhone =  get_post_meta($businessId,"phone",true);
						   $busclsAddress = get_post_meta($businessId,"address",true);		
						   $busCatList = get_the_terms($businessId,'bussiness_cat'); // Categories
				 ?>				
					<div class="row business-block">
						<div class="row col-xs-2 col-sm-2 col-md-2 col-lg-3">
							<?php
                            if(has_post_thumbnail())
							 {the_post_thumbnail('thumbnail');}
							 else{echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';}
							?>
                           
						</div>
						<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
							<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<p>Рейтинг:<img src="<?php bloginfo('template_url');?>/images/star.png" />
                                <p>Категория:  <span>
											<?php   
											if(is_array($busCatList))
											{
											 $i=1;													 
											 foreach ($busCatList as $cat)
											 {
											   $termlink=get_term_link(intval($cat->term_id),'bussiness_cat');
											   echo '<a href="'.$termlink.'">'.$cat->name.'</a>';												
												if($i<count($busCatList))
												 {echo ", ";}
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
										   echo '<a class="mr-gmap-location-pop" 
										   data-add-lat="'.$busclsAddress["lat"].'" 
										   data-add-long="'.$busclsAddress["lng"].'">'.$busclsAddress['address'].'</a>';
										  }											  
										 ?>
                                            </span></p>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12">
											<p><?php the_excerpt(); ?></p>
										</div>
						</div>
						<div class="text-center bg-expire col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span>Размещение вашего бизнеса заканчивается через 62 дня</span>
							<a class="btn-primary-2" href="<?php //echo get_permalink($businessId); ?>#extendBusiness" data-toogle="modal">Продлить срок размещения</a>
							<div class="pull-right edit-ad">
								<?php edit_post_link('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '<a>', '</a>', $businessId); ?>
								<a href="#deleteAdsModal" class="deletepostlink" data-id="<?php echo $businessId; ?>" data-toggle="modal"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</div>
						</div>
					</div>
                    <?php
						}
					}
					else
					{
					  echo '<div class="row empty text-center">';
					  echo '<p class="text-center">У вас еще нет размещенных бизнесов</p>';
					  echo	'<a href="'.get_permalink(get_page_by_title("Business Directory Add")).'" 
                        class="btn btn-success">Разместить новый бизнес</a>';
					  echo '</div>';	
					}
					wp_reset_query();
					?>
                    
                    
				</div><!-- end #business -->
				<div role="tabpanel" class="tab-pane" id="fotogallery">
                 <?php
				  query_posts(array('post_type'=>'photogallery','post_status'=>'publish',
				  'order'=>'DESC','posts_per_page'=>6,'author'=>$userId));
				  
				  if(have_posts())
				  {?>
                    <div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<span>Последние добавленные</span>
								<div class="pull-right">
									<a href="<?php echo admin_url(); ?>post-new.php?post_type=photogallery">
                                    Добавить фото</a>
								</div>
							</div>
							<div class="row mr-photo-gallery-out">
	                            					
				   <?php	  
					while(have_posts())
					{
						the_post();
						 
						?>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mr-photo-bx-outer">
                        <a href="<?php the_permalink(); ?>">
                        <div class="mr-galleryphoto">
                         <?php
						    if(has_post_thumbnail())
								 {
								   the_post_thumbnail('medium');
								 }
							 else
							 {echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';}
						 ?>
                        </div>
                        <div class="pgallery-content-box">
                        <h2 class="pgtitle"><?php echo mb_strimwidth(get_the_title(),0,35,'...'); ?></h2>
                        <div class="pgsummry-txt">
                            <p><?php echo mb_strimwidth(get_the_excerpt(),0,200,'...'); ?></p>
                        </div>
                        <div class="pg-metadata">
                            <span class="date"><?php echo get_the_date('d.m.y'); ?></span>
                            <span class="name">Иванов Иван</span>
                            <span class="pull-right view"><div class="glyphicon glyphicon-eye-open" aria-hidden="true">
                            </div> 
						       <?php 		//function Post view Count
                                   if(function_exists("pvc_get_post_views"))
                                   { echo pvc_get_post_views($classId);} 
                                 ?>
                                 </span>
                        </div>
                        </div>
                        </a>
                        </div>
                        <?php
					}
						
					  echo '</div>';
					 echo '</div>';
				    echo '</div>';
				  }
				  else
				  {
				 ?>
				<div class="row empty text-center">
					<p class="text-center">У вас еще нет опубликованных фотогалерей</p>
					<a href="<?php echo admin_url(); ?>post-new.php?post_type=photogallery" class="btn btn-success">
                    Создать новую фотогалерею</a>
				</div>
                <?php
                 }wp_reset_query();
                ?>
					
				</div><!-- end #fotogallery -->
				<div role="tabpanel" class="tab-pane" id="videogallery">                
				  <?php				 
				  query_posts(array('post_type'=>'videogallery','post_status'=>'publish',
				  'order'=>'DESC','posts_per_page'=>6,'author'=>$userId));
				  
				  if(have_posts())
				  {?>
			        <div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
			            <span>Последние добавленные</span>
			            <a href="<?php echo admin_url(); ?>post-new.php?post_type=videogallery" 
                        class="pull-right">Добавить видео</a>
			        </div>                  
				    <div class="row">
				        <div class="mr-video-gallery-out">
	                            					
				   <?php	  
					while(have_posts())
					{
						the_post();
						?>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mr-video-bx-outer">
				                <?php
								 if(has_post_thumbnail()) 
								  {
									echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail().'</a>';	 
								  }
								 ?> 
				            </div>
                        <?php
					 }
					  echo '<div class="clearfix"></div>';
					 echo '</div>';
				    echo '</div>';
				  }
				  else
				  {
				 ?>
                 <div class="row empty text-center">
			        	<p class="text-center">У вас еще нет опубликованных видео</p>
			        	<a href="<?php echo admin_url(); ?>post-new.php?post_type=videogallery" class="btn btn-success">
                        Опубликовать новое видео</a>
			      </div>
                <?php
                 }wp_reset_query();
                ?>       	
				</div><!-- end #videogallery -->
                
				<div role="tabpanel" class="tab-pane event-inside" id="events">
                 <?php  
				   query_posts(array('post_type'=>'event','post_status'=>'publish','order'=>'DESC','posts_per_page'=>6,'author'=>$userId));
				  if(have_posts())
				  {
					  echo '<div class="event">';
					  while(have_posts())
					  {
						the_post();
						$eventId = get_the_ID(); // Event id 
					    $website =  get_post_meta($eventId,"website",true);
					    $evPhone =  get_post_meta($eventId,"phone",true);
					    $evLocation =  get_post_meta($eventId,"location",true);
					    $evPrice =  get_post_meta($eventId,"price",true);	
						 ?>
                          <div class="col-sm-4 col-md-4 col-lg-4 event-block">
						    <div class="hover">
						        <div class="edit text-center">
						            <ul>
						                <li>
                                        <a href="#">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>Редактировать
                                        </a></li>
						                <li>
                                        <a href="#">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>Удалить</a></li>
						            </ul>
						        </div>
						    </div>
						    <div class="event-block-thumb">
						        <div class="text_block">
						            <p class="pull-left">Поделититесь с друзьями</p>
						            <ul>
						                <li><a href="#" class="facebook">Facebook</a></li>
						                <li><a href="#" class="twitter">Twitter</a></li>
						            </ul>
						        </div>
						       <a href="<?php the_permalink(); ?>">
								 <?php 
                                    if(has_post_thumbnail())
                                    {
                                    the_post_thumbnail('thumbnail',false,false);
                                    }
                                    else
                                    {
                                     echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">'; 
                                    }
                                    ?> 
                                    <div class="opacity">
                                        <div class="event-info text-center">
                                            <h3><?php the_title(); ?></h3>
                                            <p>Спектакль</p>
                                        </div>
                                    </div>
                                </a>
						    </div>
						    <div class="event-block-content">
						        <p class="date text-center"><strong>23</strong> Января 5:00 PM</p>
						        <p><?php the_excerpt(); ?></p>
						    </div>
						     <div class="bg-info event-block-location col-xs-12 col-sm-12 col-md-12 col-lg-12">
						       <p class="location">
							   <?php if(!empty($evLocation['address']))
							   {
							      echo $evLocation['address'].", OR"; }
							   ?></p>
						    </div>
						    <div class="event-buybtn-out bg-primary text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
						        <p>Стоимость билетов от <strong><?php if($evPrice){ echo "$". $evPrice; }?></strong><p>
						        <button class="btn btn-primary" type="button">Купить</button>
						    </div>
						</div>
                         <?php 
					  }
					  echo '</div>';
				  }
				  else
				  {
					?>
                     <div class="row empty text-center">
			        	<p class="text-center">У вас еще нет опубликованных мероприятий</p>
			        	<a href="<?php echo get_permalink(get_page_by_title('Event Add')); ?>" 
                        class="btn btn-success">Создать новое мероприятие</a>
			        </div>
                    <?php  
				  }
				 ?>					
				</div><!-- end #events -->
			</div>
		</div>
	</div>
	<div class="modal fade" id="deleteAdsModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdsModalLabel">
		<div class="modal-dialog" role="document">
			<form name="deleteform" id="deleteform" method="post">
				<div class="modal-content text-center">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
						<h4 class="title4">Ваше объявление будет удалено!</h4>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="deletepostid" id="deletepostid" />
						<button type="submit" id="yesdeletepost" class="btn btn-danger" name="yesdeletepost" value="Да я подтверждаю">Да я подтверждаю</button>
						<button type="button" class="btn btn-default cancel" data-dismiss="modal">Отменить</button>
					</div>
				</div>
			</form>
		</div>
	</div>
<div class="modal fade" id="extendAds" tabindex="-1" role="dialog" aria-labelledby="extendAdsModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
        <h4 class="title4 text-center">Выберите варианты продления</h4>
        <div class="col-centered col-xs-7 col-sm-7 col-md-7 col-lg-7">
        	<form action="" method="POST" role="form">
        		<div class="radio">
        		  <label>
        		    <input type="radio" name="extendAds" id="extendAds1" value="" checked>
        		    Продлить объявление на 30 дней за $3
        		  </label>
        		</div>
        		<div class="radio">
        		  <label>
        		    <input type="radio" name="extendAds" id="extendAds2" value="">
        		    Продлить объявление на 60 дней за $5
        		  </label>
        		</div>
        		<div class="radio">
        		  <label>
        		    <input type="radio" name="extendAds" id="extendAds3" value="">
        		    Продлить объявление на 90 дней за $10
        		  </label>
        		</div>        
        	</form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger">Да я подтверждаю</button>
        <button type="button" class="btn btn-link cancel" data-dismiss="modal">Отменить</button>
      </div>
    </div>
  </div>
</div>
<!-- extendAds end -->
<div class="modal fade" id="extendBusiness" tabindex="-1" role="dialog" aria-labelledby="extendBusinessModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
        <h4 class="title4 text-center">Выберите варианты продления</h4>
        <div class="col-centered col-xs-7 col-sm-7 col-md-7 col-lg-9">
        	<form action="" method="POST" role="form">
        		<div class="radio">
        		  <label>
        		    <input type="radio" name="extendBusiness" id="extendBusiness1" value="" checked>
        		    Продлить срок размещения бизнеса на 30 дней за $3
        		  </label>
        		</div>
        		<div class="radio">
        		  <label>
        		    <input type="radio" name="extendBusiness" id="extendBusiness2" value="">
        		    Продлить срок размещения бизнеса на 60 дней за $5
        		  </label>
        		</div>
        		<div class="radio">
        		  <label>
        		    <input type="radio" name="extendBusiness" id="extendBusiness3" value="">
        		    Продлить срок размещения бизнеса на 90 дней за $10
        		  </label>
        		</div>        
        	</form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger">Да я подтверждаю</button>
        <button type="button" class="btn btn-link cancel" data-dismiss="modal">Отменить</button>
      </div>
    </div>
  </div>
</div>
<!-- extendBusiness end -->
	<?php get_sidebar(); ?>
	</div>
</div> 
<script type="text/javascript">
 jQuery(document).ready(function(e) {
 	 
	 jQuery(".deletepostlink").click(function(){
		jQuery("#deletepostid").val(jQuery(this).attr("data-id"));
	 });
	
	 jQuery("#editAuthor").submit(function(e)
	 {
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
<?php //get_footer(); ?>