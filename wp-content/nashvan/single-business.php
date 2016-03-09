<?php get_header();
global $post;
$postID = $post->ID; ?>

<div class="container business-directory inside-business">
  <div class="row">
    <?php get_sidebar(); ?>
    <?php
		$str = "";
		if(isset($_GET['search']) && !empty($_GET['search'])) {
			$str = $_GET['search'];
		}
	?>
    <div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12"> <span>Бизнес справочник</span> </div>
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
            <div class="row col-xs-4 col-sm-4 col-md-4 col-lg-4">
              <select name="select_category" id="select_category" class="form-control" required>
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
					 	query_posts( array('post_type'=>'business','s'=>$str,'post_status'=>'publish','posts_per_page'=>-1) );
					   
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
							<p>Адрес:  <span>
							<?php 
							if(!empty($busclsAddress['address']))
							{
							 echo '<a class="mr-gmap-location-pop" 
							   data-add-lat="'.$busclsAddress["lat"].'" 
							   data-add-long="'.$busclsAddress["lng"].'">'.$busclsAddress['address'].'</a>';
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
								?>
				  <div id="classified">
					<div class="form-group">
					  <h1>
						<?php the_title() ?>
					  </h1>
					  <span class='review'>
					  <?php 
												//function Post view Count
												   if(function_exists("pvc_get_post_views"))
												   { 
													echo pvc_get_post_views($classId);
												   } 
												?>
					  </span><span class='glyphicon glyphicon-eye-open'></span> </div>
					<div class="row upload">
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
					  <div class="form-horizontal info col-xs-7 col-sm-7 col-md-7 col-lg-6">
						<div class="form-group">
						  <div class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
														control-label" for="">Рейтинг:</div>
						  <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
							<?php			
														 if(function_exists("csr_get_overall_rating_stars"))
														 {										
														  echo csr_get_overall_rating_stars($businessId);		
														  echo "<span class='tt-ratc'>".csr_get_rating_count($businessId)." отзыва</span>";											
														 }
														?>
						  </div>
						</div>
						<div class="form-group">
						  <div class="category text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
														control-label" for="">Категория:</div>
						  <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7"> <span>
							<?php
															   if(is_array($busCatList))
																{
																 $i=1;													 
																 foreach ($busCatList as $cat)
																 {
															 
																  array_push($businessCat,$cat->term_id); 	
															   $termlink=get_term_link(intval($cat->term_id),'bussiness_cat');
																echo '<a href="'.$termlink.'">'.$cat->name.'</a>';													
																	 if($i<count($busCatList))
																	 {echo ", ";}
																	 $i++;
																  }														 
																}
															?>
							</span> </div>
						</div>
						<div class="form-group">
						  <div class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
														control-label" for="">Вебсайт:</div>
						  <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7"> <span>
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
							</span> </div>
						</div>
						<div class="form-group">
						  <div class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
														control-label" for="">Телефон:</div>
						  <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7"> <span><?php echo $busPhone; ?></span> </div>
						</div>
						<div class="form-group">
						  <div class="location text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
														control-label" for="">Адрес:</div>
						  <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7"> <span>
							<?php 
																	if(!empty($busclsAddress['address']))
																	{														
																	   echo '<a class="mr-gmap-location-pop"
																		data-add-lat="'.$busclsAddress["lat"].'" 
																	   data-add-long="'.$busclsAddress["lng"].'">'.
																	   $busclsAddress['address'].'</a>';
																	}
																 ?>
							</span> </div>
						</div>
						<div class="text-center form-group">
						  <button type="submit" class="btn btn-primary"> Отправить сообщение владельцу бизнеса</button>
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
						<p class="title4">Адрес:</p>
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
						<p id="feedbacks" class="feedback">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					  </div>
					</div>
					<!-- FeedBacks list -->
					<?php								 
									  $cmtQry = $wpdb->get_results("select * from $wpdb->comments as a,$wpdb->commentmeta 
									  as b where a.comment_post_ID='$businessId' and a.comment_ID=b.comment_id order
									   by a.comment_ID DESC");
									  
									  if(count($cmtQry)>0)
									  {
										  foreach($cmtQry as $cmtData)
										  {
									 ?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 feedback_block">
					  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="row col-xs-3 col-sm-3 col-md-3 col-lg-2 avatar"> <?php echo get_avatar($cmtData->user_id,81); ?> </div>
						<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
						  <h4><a href="#"><?php echo $cmtData->comment_author; ?></a></h4>
						  <?php echo csr_get_rating_stars($cmtData->comment_ID); ?>
						  <div class="pull-right"> <span>
							<!--Burnaby, BC 23 Января 2015-->
							<?php
														 echo date("F, d Y",strtotime($cmtData->comment_date));
														 ?>
							</span> </div>
						</div>
						<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
						  <p><?php echo wp_strip_all_tags($cmtData->comment_content); ?></p>
						</div>
					  </div>
					</div>
					<?php 
										}
								  } // End foreach ?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-buffer2">
					  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<p class="title4 text-center">Оставьте отзыв о бизнесе</p>
						<p class="feedback_text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>
						<div class="col-centered col-xs-7 col-sm-7 col-md-7 col-lg-9">
						  <?php
											   if(isset($_SESSION['success_mess']))
											   {
												echo '<div class="alert alert-success fade in">
												Well done ! '.$_SESSION['success_mess'].'</div>';  
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
													
												   if(strlen($feedbacktext)>25)
												   {	 			
													$data = array(
														'comment_post_ID' =>$businessId,
														'comment_author' =>$current_user->display_name,
														'comment_author_email' =>$current_user->user_email,
														'comment_author_url' => '',
														'comment_content' =>$feedbacktext,
														'comment_type' => '',
														'comment_parent' => 0,
														'user_id' => $current_user->ID,
														'comment_author_IP' =>$_SERVER['REMOTE_ADDR'],
														'comment_agent' =>$_SERVER['HTTP_USER_AGENT'],
														'comment_date' =>$time,
														'comment_approved' =>1
													);
													 $feetbackid = wp_insert_comment($data);
													 update_comment_meta($feetbackid,'rating',$rating);
													 $_SESSION['success_mess']="Your feedback submitted.";
												  }?>
						  <script type="text/javascript">
													 window.location="<?php echo current_page_url();?>";
												  </script>
						  <?php
											 }
											} 
											   ?>
						  <div id="feebackerror" class="alert alert-danger fade in"></div>
						  <form id="feedbackForm" method="POST" role="form">
							<textarea name="feedbacktext" id="textareaFeedback" class="form-control" 
														rows="4" placeholder="Напишитие ваш отзыв здесь"></textarea>
							<p class="text-center">Оставьте ваш рейтинг</p>
							<div class="text-center"> <?php echo csr_get_rating_star_form();?> </div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
							  <?php
								 if(is_user_logged_in())
								 {								
								  echo '<button onClick="return feedbackValidate()"
								   type="submit" name="bussFeedbackSub" class="btn btn-primary">
										  Оставить отзыв</button>';
								 }
								 else
								 {							 
								 echo '<a class="btn btn-primary" data-target="#login" data-toggle="modal"
								  href="javascript:void(0);">Оставить отзыв</a>';
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
  </div>
</div>
<!-- end .row -->
</div>
<!-- end col-lg-9 -->
</div>
<!-- end .row -->
</div>
<!-- end .container -->
<?php
 $busclsAddress1 =  get_post_meta($postID,"address",true);	
?>
<script type="text/javascript">
 //comment form validation 
  function feedbackValidate()
  {
	var feedbackTxt = document.getElementById("textareaFeedback").value;  
	var ntxt = feedbackTxt.length;
	if(ntxt>25)
	{
	 return true;
	}
	else
	{
      document.getElementById("feebackerror").style.display="block";
	  document.getElementById("feebackerror").innerHTML="Enter minimum 25 characters";
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
		zoom: 4
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
	   
		setTimeout(function() { rinitMap(aDlat='<?php echo $busclsAddress1["lat"];?>',aDlng='<?php echo $busclsAddress1["lng"]; ?>'); },2000);
    });
									    
</script>
<?php get_footer(); ?>