<?php
get_header();
global $post;
$postID = $post->ID;
global $wpdb;
$payHistorytab = $wpdb->prefix."payment_history";

if( check_post_expiration( $postID, "business" ) <= 0 ) {
	
	$run = get_option('siteurl');
	echo '<script type="text/javascript">';
	echo "location.replace('$run');";
	echo '</script>';
}

if(isset($_REQUEST['yesdeletepost']) && $_REQUEST['yesdeletepost']) {

	wp_delete_post( $_REQUEST['deletepostid'], true );
	$_SESSION['delete_post_mess'] = "Ваш бизнес успешно удален!";	
	$run = get_permalink(get_page_by_title('Business Directory' ));
	echo '<script type="text/javascript">';
	echo "location.replace('$run');";
	echo '</script>';
}
?>

<div id="main-container" class="container business-directory inside-business">
  <div class="row top-buffer2">
    <?php
		$str = "";
		if(isset($_GET['search']) && !empty($_GET['search'])) {
			$str = $_GET['search'];
		}
	?>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
            <span>Бизнес справочник</span> 
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="col-centered col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <form method="get" id="search-form" style="position:relative;">
                  <input type="text" name="search" id="search" class="form-control" value="<?php echo $str; ?>" placeholder="Кого бы вы хотели найти?">
                  <p id="mr-search-frm-btn"></p>
                </form>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
              <select name="select_category" id="select_category" class="form-control selectpicker" data-style="btn-info" required>
                <option value="0">Выберите категорию</option>
                <?php
					$business_catarr = get_terms('bussiness_cat', array('hide_empty' => true ));
					foreach($business_catarr as $buscat) {
						echo '<option value="'.get_term_link( $buscat ).'">'.$buscat->name.'</option>';
					}
				?>
              </select>
            </div>
            <div class="center row text-center col-xs-12 col-sm-12 col-md-12 col-lg-4">
              <?php
				if(is_user_logged_in())
				{
				  echo '<a class="btn btn-danger" href="'.get_permalink(get_page_by_title("Business Directory Add")).'">Разместить свой бизнес</a>';
				}
				else
				{							 
				 echo '<a class="btn btn-danger" data-target="#login" data-toggle="modal" href="javascript:void(0);">Разместить свой бизнес</a>';
				}
			  ?>
            </div>
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-4"> <a href="<?php echo get_permalink(get_page_by_title('Business Directory'));?>" class="btn btn-primary">Все категории</a> </div>
          </div>
		  
		  <?php if(isset($_GET['search']) && !empty($_GET['search'])) { ?>
		  	
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					 <?php
					 	$my_post_array = array("-99");
						$my_post_args = array( 'post_type' => 'business', 's' => $str, 'post_status' => 'publish', 'posts_per_page' => -1 );

						$my_querys = null;
						$my_querys = new WP_Query($my_post_args);

						if( $my_querys->have_posts() ) {
							while ($my_querys->have_posts()) : $my_querys->the_post();
							
								if( check_post_expiration( get_the_ID(), "business" ) > 0 ) {
									array_push($my_post_array, get_the_ID());
								}

							endwhile;
						}
						wp_reset_query();
								
					 	query_posts( array('post_type' => 'business', 's' => $str, 'post_status' => 'publish', 'posts_per_page' => -1, 'post__in' => $my_post_array) );
					   
					   if(have_posts())
					   {
							while(have_posts())
							{
							   the_post();
							   $businessId = get_the_ID(); // Business id
							   $busweb_site =  get_post_meta($businessId,"website",true);
							   $busPhone =  get_post_meta($businessId,"phone",true);
							   $emailAddress =  get_post_meta($businessId,"emailaddress",true);
							   $busclsAddress =  get_post_meta($businessId,"address",true);
							   $busCatList = get_the_terms($businessId,'bussiness_cat'); // Categories
								?>
					
						  <div class="row business-block">
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
							<p><span class='glyphicon glyphicon-eye-open'></span>
								<?php 
								//function Post view Count
								if(function_exists("pvc_get_post_views"))
								{ 
								 echo pvc_get_post_views($businessId);
								} 
								?>
								</p>
						</div>
						<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
						<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<p>Рейтинг: 
							<?php			
							 if(function_exists("csr_get_overall_rating_stars"))
							 {										
							  echo csr_get_overall_rating_stars($businessId);		
							  echo "<span class='tt-ratc'>";
							  echo csr_get_rating_count($businessId)." отзыва</span>";											
							 }
							?>                                       
							</p>
							<p>Категория:  <span>
							<?php
								
								   if(is_array($busCatList))
									{
									 $i=1;													 
									 foreach ($busCatList as $cat)
									 {
										// array_push($classifiedCat,$cat->term_id); 	
										 $termlink=get_term_link(intval($cat->term_id),'bussiness_cat');
										  echo '<a href="'.$termlink.'">'.$cat->name.'</a>';	
										 //echo $cat->name;													
										 if($i<count($busCatList))
										 {echo ", ";}
										 $i++;
									  }														 
									}
								?></span></p>
							<p>Вебсайт: <span>
							<?php
							if(!empty($busweb_site))
							{
							   $parsed = parse_url($busweb_site);
								if (empty($parsed['scheme'])){
									$urlStr = 'http://' . ltrim($busweb_site, '/');
								}
								else
								{
								  $urlStr= $busweb_site;
								}
							echo '<a target="_blank" href="'.$urlStr.'">'.$busweb_site.'</a>';	
							}
							?>
							
							</span></p>
							<p>Телефон: <span><?php echo $busPhone; ?></span></p>
							<p>Расположение:  <span>
							<?php 
							if(!empty($busclsAddress['address']))
							{
							 echo '<a class="mr-gmap-location-pop" data-add-lat="'.$busclsAddress["lat"].'" data-add-long="'.$busclsAddress["lng"].'">'.$busclsAddress['address'].'</a>';
							}
							?></span></p>
						</div>
						<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12">
							<p><?php the_excerpt(); ?></p>
						</div>
					</div>
						  </div>
					  <?php
							}
					   } else {
							echo '<div class="row search-error">';
								echo '<div class="row bg-warning col-xs-12 col-sm-12 col-md-12 col-lg-12">';
									echo '<p>Не удалось ничего найти по результатам вашего запроса</p>';
									echo '<p>Попробуйте изменить фразу поиска</p>';
								echo '</div>';
							echo '</div>';
					   }
					?>
					
				</div>
			</div>
		  
		  <?php }  else { ?>
          
			  <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				  <?php
								 $businessCat = array();
								  if(have_posts())
								  {
									  while(have_posts())
									  {
									   the_post();
									   $businessId = get_the_ID(); // Business id 	
									   
									   $busweb_site =  get_post_meta($businessId,"website",true);
									   $busPhone =  get_post_meta($businessId,"phone",true);
									   $busclsAddress =  get_post_meta($businessId,"address",true);
									   $busCatList = get_the_terms($businessId,'bussiness_cat'); // Categories
									   $mapinfo = get_post_meta($businessId,"info",true);
									   $emailAddress = get_post_meta($businessId,"emailaddress",true);
									   $checkPayment = $wpdb->get_row("select * from $payHistorytab where postId = '$businessId' and postType='business' ORDER BY tid DESC limit 0,1");
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
							$numDays = ($expirationTime - time())/60/60/24; }
								?>
				  <div id="classified">
					<div class="form-group business-name">
					  <h1>
						<?php the_title() ?>
					  </h1>
					  <span class='review'>
					  <?php 
						//function Post view Count
						   if(function_exists("pvc_get_post_views"))
						   { 
							echo pvc_get_post_views($businessId);
						   }
						?>
					  </span><span class='glyphicon glyphicon-eye-open'></span> 
					 </div>
					 <?php $postdata = get_post( $businessId, ARRAY_A ); ?>
					 <?php if( is_user_logged_in () && get_current_user_id() == $postdata['post_author'] ) { ?>
					  <div class="bg-expire-2">Размещение вашего бизнеса заканчивается через <?php echo ceil($numDays); ?> дня
				  <?php if ( get_post_status ( get_the_ID() ) == 'pending' ) { ?>
									<span class="label label-right" style="color:#e21922;"><strong>На рассмотрении</strong></span>
								<?php } ?>
					 
						 <div class="ad-block pull-right">
							<div class="pull-right edit-ad">
								<?php edit_post_link('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '', '</a>', $businessId); ?>
								<a href="javascript:void(0)" class="deletepostlink" data-id="<?php echo $businessId; ?>" data-toggle="modal">
									<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
								</a>
							</div>
						</div>
						</div>
					<?php } ?>
					
					<div class="row upload" style="margin-top:15px;">
					  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-5">
						<div class="image mr-classified-ad-thumb">
						  <?php 
							 if(has_post_thumbnail())
							 {
							  the_post_thumbnail('full');
							 }
							 else
							 {
							  echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">'; 
							 }										  
							?>
						</div>
					  </div>
					  <div class="no-padding col-xs-1 col-sm-1 col-md-1 col-lg-1">
						<ul class="images mrclGlists">
						  <li>
							<?php $classified_attachment = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "thumbnail", true ); ?>
							<a data-thumb-src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>" href="#"> <img width="56px" height="56px" src="<?php echo $classified_attachment[0]; ?>"> </a> </li>
						  <?php
													// Business gallery attachment id's "fg_temp_metadata"
													$galleryAttids= get_post_meta($businessId,"fg_temp_metadata",true);
													if($galleryAttids)
													{
													  $galleryidsArr = explode(",",$galleryAttids);
													  if(count($galleryidsArr)>0)
													  {
														foreach($galleryidsArr as $gAttid)
														{	
														 $attmentSrc =  wp_get_attachment_image_src($gAttid,array(56,56),true);
														  
														  $attmentFullUrl=wp_get_attachment_url($gAttid);
														  ?>
						  <li> <a href="#"  data-thumb-src="<?php echo $attmentFullUrl;?>"> <img src="<?php echo $attmentSrc[0];?>" height="56px" width="56px"> </a> </li>
						  <?php
														}
													  }
													}									
												  ?>
						</ul>
					  </div>
					  <div class="form-horizontal info col-xs-7 col-sm-7 col-md-7 col-lg-6 single-business">
						
                        <?php if(function_exists("csr_get_overall_rating_stars")) {	?>
                            <div class="form-group">
                                <div class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Рейтинг:</div>
                                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-8" style="height: 20px;">
									<?php			
                                        echo csr_get_overall_rating_stars($businessId);		
                                        echo "<span class='tt-ratc'>".csr_get_rating_count($businessId)." отзыва</span>";											
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php if(!empty($busCatList)) { ?>
							<div class="form-group">
						  		<div class="category text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label">Категория:</div>
						  		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-8"> <span>
								<?php
									if(is_array($busCatList)) {
										$i=1;													 
										foreach ($busCatList as $cat) {
	
											array_push($businessCat,$cat->term_id); 	
											$termlink=get_term_link(intval($cat->term_id),'bussiness_cat');
											echo '<a href="'.$termlink.'">'.$cat->name.'</a>';													
											if($i<count($busCatList))
											{ echo ", "; }
											$i++;
										}
									}
								?>
							</span> </div>
						</div>
                        <?php } ?>
                        
                        <?php if(!empty($busweb_site)) { ?>
                            <div class="form-group">
                              <div class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label">Вебсайт:</div>
                              <div class="col-xs-10 col-sm-10 col-md-10 col-lg-8"> <span>
                                <?php
                                    $parsed = parse_url($busweb_site);
                                    if (empty($parsed['scheme'])){
                                        $urlStr = 'http://' . ltrim($busweb_site, '/');
                                    } else {
                                        $urlStr= $busweb_site;
                                    }
                                    echo '<a target="_blank" href="'.$urlStr.'">'.$busweb_site.'</a>';
                                ?>
                                </span> </div>
                            </div>
                        <?php } ?>
                        
                        <?php if(!empty($busPhone)) { ?>
                            <div class="form-group">
                              <div class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label">Телефон:</div>
                              <div class="col-xs-10 col-sm-10 col-md-10 col-lg-8"> <span><?php echo $busPhone; ?></span> </div>
                            </div>
                        <?php } ?>
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 error-container" style="display:none;">
							<h4 style="font-size:17px;" class="error">Не заполнены обязательные поля</h4>
							<ol>
								<li><label for="eventTitle" class="error">Please enter your name.</label></li>
								<li><label for="websiteUrl" class="error">Please enter your email.</label></li>
								<li><label for="organiser" class="error">Please enter your comment.</label></li>
							</ol>
						</div>
					
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 single-business-form">
                        	<form role="form" name="contact-us-form" id="contact-us-form" method="post">
								<input type="hidden" value="<?php if($emailAddress) { echo $emailAddress; } else { echo get_option('admin_email'); } ?>" name="receiver_email" id="receiver_email">
								<div id="sentemail" class="alert alert-success" style="display:none;"><?php _e( 'Ваше письмо успешно отправлено', 'nashvan' ); ?></div>
                        		<div class="form-group">
                        			<input type="text" class="form-control" id="your_name" name="your_name" placeholder="Ваше имя" required />
                        		</div>
                        		<div class="form-group">
                        			<input type="email" class="form-control" id="your_email" name="your_email" placeholder="Ваш email" required />
                        		</div>
                        		<div class="form-group">
                        			<textarea id="your_message" rows="5" name="your_message" class="form-control" placeholder="Введите текст вашего сообщения" required></textarea>
                        		</div>
                        		<div class="text-center form-group">
                        	  		<button type="submit" class="btn btn-primary" value="Отправить сообщение владельцу бизнеса" name="sendmessage" id="sendmessage">Отправить сообщение владельцу бизнеса</button>
                        		</div>
                        	</form>
                        </div>
					  </div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					  <div class="text col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<p>
						  <?php the_content();?>
						</p>
					  </div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<p class="title4">Расположение:</p>
						<p class="location"> <img src="<?php echo bloginfo('template_url'); ?>/images/location.png">
						  <?php 
												
													if(!empty($busclsAddress['address']))
													{														
										   echo '<span class="mr-gmap-location-pop1">'.$busclsAddress['address'].'</span>';
													}
												 ?>
						</p>
					  </div>
					</div>
					<div class="clearfix"></div>
					<div id="sbussiness-google-map"></div>
					<p class="mapInfo"><?php echo $mapinfo; // Mapinfo ?></p>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<p class="title4 no-margin">Отзывы:</p>
					  </div>
					</div>
					<!-- FeedBacks list -->
					<?php
						$comments = get_comments( array( 'status' => 'approve', 'post_id' => $businessId ) );
						//$cmtQry = $wpdb->get_results("select * from $wpdb->comments as a,$wpdb->commentmeta as b where a.comment_post_ID='$businessId' and a.comment_ID=b.comment_id order by a.comment_ID DESC");
						
						if(count($comments)>0) {
						
							foreach($comments as $cmtData) { ?>
							
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 feedback_block">
								  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="row col-xs-3 col-sm-3 col-md-3 col-lg-2 avatar"> <?php echo get_avatar($cmtData->user_id,81); ?> </div>
									<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
									  <h4><a href="<?php echo get_author_posts_url($cmtData->user_id); ?>"><?php echo $cmtData->comment_author; ?></a></h4>
									  <?php echo csr_get_rating_stars($cmtData->comment_ID); ?>
									  <div class="pull-right"> <span><?php echo date("F, d Y",strtotime($cmtData->comment_date)); ?></span></div>
									</div>
									<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
									  <p><?php echo wp_strip_all_tags($cmtData->comment_content); ?></p>
									</div>
								  </div>
								</div>
					<?php } } // End foreach ?>
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-buffer2">
					  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<p class="title4 text-center">Оставьте отзыв о бизнесе</p>
						<div class="col-centered col-xs-7 col-sm-7 col-md-7 col-lg-9">
						  		<?php
										   if(isset($_SESSION['success_mess'])) {
												echo '<div class="alert alert-success fade in">Поздравляем!  '.$_SESSION['success_mess'].'</div>';  
												unset($_SESSION['success_mess']);
										   }
											   
											 global $current_user;
											 $time = current_time('mysql');											   
												
											 if(is_user_logged_in())
											 {
												 if(isset($_POST['bussFeedbackSub']))
												 {
													$feedbacktext = $_POST['feedbacktext'];	 
													$rating = $_POST['rating'];
													
												   if(strlen($feedbacktext)>1)
												   {	 			
													$data = array(
														'comment_post_ID' =>$businessId,
														'comment_author' => $current_user->display_name,
														'comment_author_email' => $current_user->user_email,
														'comment_content' => $feedbacktext,
														'comment_parent' => 0,
														'user_id' => $current_user->ID,
														'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
														'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
														'comment_date' => $time,
														'comment_approved' => 1
													);
													 $feetbackid = wp_insert_comment($data);
													 update_comment_meta($feetbackid,'rating',$rating);
													 $_SESSION['success_mess'] = "Ваш отзыв успешно отправлен.";
												  }
												  ?>
						  						  <script type="text/javascript">
													 window.location="<?php echo current_page_url();?>";
												  </script>
						  <?php
											 }
											} 
											   ?>
						  <div id="feebackerror" class="alert alert-danger fade in"></div>
						  <form id="feedbackForm" method="POST" role="form">
							<textarea name="feedbacktext" id="textareaFeedback" class="form-control" rows="4" placeholder="Напишитие ваш отзыв здесь"></textarea>
							<p class="text-center">Оставьте ваш рейтинг</p>
							<div class="text-center"> <?php echo csr_get_rating_star_form(); ?> </div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
							  <?php
								 if(is_user_logged_in()) {								
								  echo '<button onClick="return feedbackValidate()" type="submit" name="bussFeedbackSub" class="btn btn-primary">Оставить отзыв</button>';
								 } else {							 
								 	echo '<a class="btn btn-primary" data-target="#login" data-toggle="modal" href="javascript:void(0);">Оставить отзыв</a>';
								 }
							  ?>
							</div>
						  </form>
						</div>
					  </div>
					</div>
				  </div>
				  <?php
									  } // End while
								  } //End if
								  ?>
				</div>
			  </div>
		  <?php } ?>
        </div>
      </div>
    </div>
	<?php get_sidebar(); ?>
	
	<!-- delete modal -->
	<div class="modal fade" id="deleteAdsModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdsModalLabel">
		<div class="modal-dialog" role="document">
			<form name="deleteform" id="deleteform" method="post">
				<div class="modal-content text-center">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
						<h4 class="title4">Ваш бизнес будет удален из справочника!</h4>
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
	<!-- end of delete modal -->
	
  </div>
</div>
<!-- end .row -->

</div>
<!-- end col-lg-9 -->
</div>
<!-- end .row -->
</div>
<!-- end .container -->
<?php $busclsAddress1 =  get_post_meta($postID,"address",true); ?>
<script type="text/javascript">
 //comment form validation 
  function feedbackValidate()
  {
	var feedbackTxt = document.getElementById("textareaFeedback").value;  
	var ntxt = feedbackTxt.length;
	if(ntxt>1)
	{
	 return true;
	}
	else
	{
      document.getElementById("feebackerror").style.display="block";
	  document.getElementById("feebackerror").innerHTML="Enter minimum 1 characters";
	  return false;	
	}
  }
 
	function rinitMap(aDlat,aDlng)
	{ 
	   if(aDlat)
	   {aDlat=aDlat;}else{aDlat=62.120327;}		   
	  
	  if(aDlng)
	   {aDlng=aDlng;}else{aDlng=99.25048828125;}		   
	
		map = new google.maps.Map(document.getElementById('sbussiness-google-map'), {
		center: {lat:parseFloat(aDlat), lng:parseFloat(aDlng)},
		zoom: 10
	  });	  
		
		marker = new google.maps.Marker({
			map: map,
			draggable: true,
			animation: google.maps.Animation.DROP,
			position: {lat:parseFloat(aDlat), lng: parseFloat(aDlng)}
		  });
	}
	jQuery(document).ready(function(e) {
		
		jQuery("#select_category").change(function(){
			window.location = jQuery(this).val();
		});
		
		//Submit form 
	   jQuery("#mr-search-frm-btn").click(function(){
		 jQuery("#search-form").submit();
	   });
	   
	   <?php if( !empty($busclsAddress1) ) { ?>
		setTimeout(function() { rinitMap(aDlat='<?php echo $busclsAddress1["lat"];?>',aDlng='<?php echo $busclsAddress1["lng"]; ?>'); },2000);
	   <?php } ?>
		
		/* delete business code */
		 jQuery(".deletepostlink").click(function(){			
		 	jQuery("#deleteAdsModal").modal("show");
			jQuery("#deletepostid").val( jQuery(this).attr("data-id") );
		 });
		 
		/* add contact form validation */
		var container = jQuery('div.error-container');
		// validate the form when it is submitted
		var validator = jQuery("#contact-us-form").validate({
			rules: {
				your_name: "required",
				your_message: "required",
				your_email: {
					required: true,
					email: true
				},
				your_message: "required"
			},
			messages: {
				your_name: "Пожалуйста введите ваше имя",
				your_message: "Пожалуйста введите ваше сообщение",
				your_email: "Пожалуйста введите ваш email"
			},
			errorContainer: container,
			errorLabelContainer: jQuery("ol", container),
			wrapper: 'li',
			submitHandler: function() {
				
				var receiver_email = jQuery('#receiver_email').val();
				var your_name      = jQuery('#your_name').val();
				var your_email     = jQuery('#your_email').val();
				var your_message   = jQuery('#your_message').val();
		
				jQuery.ajax({
					type:'post',
					url:'<?php echo get_bloginfo('stylesheet_directory'); ?>/send_email.php',
					data: {receiver_email:receiver_email,your_name:your_name,your_email:your_email,your_message:your_message},
					success:function(result) {
						jQuery( "#sentemail" ).show().fadeOut(5000);
						jQuery("#contact-us-form")[0].reset();
					}
				});
			}
		});
    });
									    
</script>
<?php get_footer(); ?>