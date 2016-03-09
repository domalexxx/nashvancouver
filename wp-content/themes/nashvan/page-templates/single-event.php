<?php get_header();
global $post;
$postID = $post->ID;

if(isset($_REQUEST['yesdeletepost']) && $_REQUEST['yesdeletepost']) {

	wp_delete_post( $_REQUEST['deletepostid'], true );
	$_SESSION['delete_post_mess'] = "Ваше мероприятие успешно удалено!";	
	$run = get_permalink(get_page_by_title('Events' ));
	echo '<script type="text/javascript">';
	echo "location.replace('$run');";
	echo '</script>';
}
?>

<div id="main-container" class="container classified-inside event-inside">
  <div class="row top-buffer2">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
     <div class="overflow">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="title pull-left">
				<span>Афиша событий</span>
			</div>
				<ul class="links pull-right">
				  <li><a href="<?php echo get_permalink(get_page_by_title("Events")); ?>" class="back">Вернуться к списку всех мероприятий</a></li>
				  <li><a href="<?php echo get_permalink(get_page_by_title("Event Add")); ?>">Добавить новое</a></li>
				</ul>
			</div>
		</div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <!-- Event content-->
              <?php
				 $eventCat = array();
				 if(have_posts())
				 {
					  while(have_posts())
					  {
					   the_post();								
					   $eventId = get_the_ID(); // Event id 
					   $website =  get_post_meta($eventId,"website",true);
					   $evPhone =  get_post_meta($eventId,"phone",true);
					   $organiser =  get_post_meta($eventId,"organiser",true);
					   $evPrice =  get_post_meta($eventId,"price",true);
					   $evLocation =  get_post_meta($eventId,"location",true);								   
					   $event_date = get_post_meta($eventId,'event_date',true); //Event Date 
					   $event_start_time = get_post_meta($eventId,'event_start_time',true); // Event Start time
					   $event_end_time = get_post_meta($eventId,'event_end_time',true); // Event End time
					   $google_map = get_post_meta($eventId,"google_map",true); // Google map
					   $eventCatList = get_the_terms($eventId,'event_cat'); // Categories
					   $emailAddress = get_post_meta($eventId,"emailaddress",true);
					   $evlink = get_post_meta($eventId,"link",true);
			   ?>
              <div id="classified">
                <div class="form-group event-name"  style="margin-bottom: 0;">
                  <h1>
                    <?php the_title(); ?>
                  </h1>
                  <span class="review">
                  	<?php 
					   if(function_exists("pvc_get_post_views"))
					   { 
						echo pvc_get_post_views($eventId);
					   } 
					?>
                  </span> <span class="glyphicon glyphicon-eye-open"></span> </div>
				
   		
				<?php $postdata = get_post( $eventId, ARRAY_A ); ?>
				<?php if( is_user_logged_in () && get_current_user_id() == $postdata['post_author']) { ?>
				  <div class="bg-expire-2">Размещение вашего мероприятия заканчивается через 62 дня   <?php if ( get_post_status ( get_the_ID() ) == 'pending' ) { ?>
									<span class="label label-right" style="color:#e21922;"><strong>На рассмотрении</strong></span>
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
				<?php } ?>
				  
                <div class="row upload" style="margin-top: 15px;">
                  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-5">
                    <div class="image mr-classified-ad-thumb">
                      <?php 
						  if(has_post_thumbnail())
						  {
						   the_post_thumbnail('full');
						  }
						  else
						  {
							 echo '<img alt="" src="'.get_template_directory_uri().'/images/empty-pic.png">'; 
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
							// Event gallery attachment id's "fg_temp_metadata"
							$galleryAttids= get_post_meta($eventId,"fg_temp_metadata",true);
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
                   
                    <?php if(is_array($eventCatList)) { ?>
                        <div class="form-group">
                          <div class="category text-left col-xs-10 col-sm-10 col-md-10 col-lg-12 control-label">Категория: <span>
                            <?php // Events categories
                             $i=1;													 
                             foreach ($eventCatList as $eventC)
                             {
                                array_push($eventCat,$eventC->term_id); 	
                                $termlink=get_term_link(intval($eventC->term_id),'event_cat');
                                echo '<a href="'.$termlink.'">'.$eventC->name.'</a>';
                                 if($i<count($eventCatList))
                                 { echo ", "; }
                                 $i++;
                              }														 
                            ?>
                            </span> </div>
                        </div>
					<?php } ?>
                    
                    <!--Стоимость билетов от-->
                    <?php if(!empty($website)) { ?>
                    <div class="form-group">
                      <div class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-12 control-label" for="">Вебсайт: <span>
                        <?php
						   $parsed = parse_url($website);
							if (empty($parsed['scheme'])){
								$urlStr = 'http://' . ltrim($website, '/');
							}
							else
							{
							  $urlStr= $website;
							}
							echo '<a target="_blank" href="'.$urlStr.'">'.mb_strimwidth($website,0,25,'...').'</a>';	
						?>
                        </span> </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(!empty($organiser)) { ?>
                        <div class="form-group">
                          <div class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-12 control-label" for="">Организаторы: <span><?php echo $organiser; ?></span> </div>
                        </div>
                    <?php } ?>
                    
                    <?php if(!empty($evPhone)) { ?>
                    <div class="form-group">
                      <div class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-12 control-label" for="">Телефон: <span><?php echo $evPhone; ?></span> </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(!empty($evPrice)) { ?>
                    <div class="form-group">
                      <div class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-12 control-label">Стоимость: <span class="event-price">
                        	<?php echo "от $".$evPrice."</span>"; ?>
							<?php if(empty($evlink)) { ?>
                        	<a href=""><button class='btn btn-primary buy-ticket-event pull-right'>Kупить билеты</button></a>
                        	<?php } ?>
                       </div>
                    </div>
                    <?php } ?>
                    
                    <!--<div class="event-buy-btn-out">
                      <div class="text-center form-group"> <a href="#"><button type="submit" class="btn btn-primary">Отправить сообщение</button></a></div>
                    </div>-->
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 error-container" style="display:none;">
						<h4>There are serious errors in your form submission, please see below for details.</h4>
						<ol>
							<li><label for="eventTitle" class="error">Please enter your name.</label></li>
							<li><label for="websiteUrl" class="error">Please enter your email.</label></li>
							<li><label for="organiser" class="error">Please enter your comment.</label></li>
						</ol>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 single-business-form">
						<form role="form" name="contact-us-form" id="contact-us-form" method="post">
							<input type="hidden" value="<?php if($emailAddress) { echo $emailAddress; } else { echo get_option('admin_email'); } ?>" name="receiver_email" id="receiver_email">
							<div id="sentemail" class="alert alert-success" style="display:none;"><?php _e( 'Mail sent successfully.', 'nashvan' ); ?></div>
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
								<button type="submit" class="btn btn-primary" value="Отправить сообщение владельцу бизнеса" name="sendmessage" id="sendmessage">Отправить сообщение владельцу Мероприятие</button>
							</div>
						</form>
					</div>
					
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="text col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p>
                      <?php the_content(); ?>
                    </p>
                  </div>
                </div>
                <div class="top-buffer3 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p class="title4">Расписание:</p>
                    <div class="row">
					  <?php
					  	$countrows = $wpdb->get_row("SELECT count(*) as countevent FROM $wpdb->postmeta WHERE 1 AND post_id = '$postID' AND meta_key LIKE 'event_date_%'");
						
						if( intval($countrows->countevent) > 0 ){
								
							for($counter=0; $counter < intval($countrows->countevent); $counter++){
								
								echo '<div class="schedule col-xs-2 col-sm-2 col-md-2 col-lg-2">';
								echo '<p>'. get_post_meta($eventId, 'event_date_'.$counter, true) .'</p>';
								echo '<p>'. the_russian_time( monthName(get_post_meta($eventId, 'event_month_'.$counter, true)) ) .'</p>';
								echo '<p>'. get_post_meta($eventId, 'evnt_time_start_hours_'.$counter, true) . ':' . get_post_meta($eventId, 'evnt_time_start_minutes_'.$counter, true) . '&nbsp;' . strtoupper(get_post_meta($eventId, 'event_start_time_'.$counter, true)) . '</p>';
								echo '</div>';
							}
						}
					   ?>
                    </div>
                  </div>
                </div>
                <div class="top-buffer col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p class="title4">Адрес:</p>
                    <p class="location"> <img src="<?php bloginfo('template_url')?>/images/location.png">
                      <?php 
						 if(is_array($google_map)) {
							 echo $google_map['address'];
						 } else {
							 if(!empty($evLocation)) {
								echo $evLocation['address'];
							 }
						 }
						?>
                    </p>
                  </div>
                </div>
                <div class="event-map-out">
                  <div id="eventMap"></div>
                </div>
                <p class="mapInfo">Вход слева после галереи</p>
              </div>
              <?php }
						   }
						  ?>
              <!-- Event content block close-->
              <div class="overflow">
                         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                             <div class="title pull-left">
                                 <span>Похожие Мероприятия</span>
                             </div>
                         </div>
               </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row event">
                  <?php
								  query_posts( array('post_type'=>'event', 'post__not_in' => array( $postID ), 'post_status'=>'publish', 'posts_per_page'=>'3',
								          'tax_query' => array(
											array(
												'taxonomy' => 'event_cat',
												'field'    => 'id',
												'terms'    => $eventCat,
											),
									     )));
								   
								   if(have_posts())
								   {
									    while(have_posts())
										{
										   the_post();											
										   $eventId = get_the_ID(); // Event id 
										   $website =  get_post_meta($eventId,"website",true);
										   $evPhone =  get_post_meta($eventId,"phone",true);
										   $evLocation =  get_post_meta($eventId,"location",true);
										   $evPrice =  get_post_meta($eventId,"price",true); ?>
                  <div class="col-sm-4 col-md-4 col-lg-4 event-block">
                    <div class="event-block-thumb">
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
                          <h3>
                            <?php the_title(); ?>
                          </h3>
                          <p>Спектакль</p>
                        </div>
                      </div>
                      </a> </div>
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
						<p><?php the_excerpt();?></p>
                    </div>
                    <div class="bg-info event-block-location col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p class="location">
                      <?php if(!empty($evLocation['address'])) {
											   echo mb_strimwidth($evLocation['address'],0,40,'...').', OR';
											   }?>
                    </p>
                    </div>
                    <div class="event-buybtn-out bg-primary text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <p>Стоимость билетов от <strong>
                        <?php if($evPrice){
													 echo "$". $evPrice; }?>
                        </strong>
                      <p>
                        <button class="btn btn-primary" type="button">Купить</button>
                    </div>
                  </div>
                  <?php
                                         }								  
                                    }
                                   ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	<?php get_sidebar(); ?>
    <!-- end .row -->
	
	<!-- delete modal -->
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
	<!-- end of delete modal -->
	
  </div>
  <!-- end col-lg-9 -->
</div>
<?php $eventAddress1 =  get_post_meta($postID,"location",true); ?>
<script type="text/javascript">

	function rinitMap(aDlat,aDlng) { 
		
		if(aDlat)
	   {aDlat=aDlat;}else{aDlat=62.120327;}		   
	  
	  if(aDlng)
	   {aDlng=aDlng;}else{aDlng=99.25048828125;}		   
	
		map = new google.maps.Map(document.getElementById('eventMap'), {
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
		
		<?php if( !empty($eventAddress1) ) { ?>
			setTimeout(function() { rinitMap(aDlat='<?php echo $eventAddress1["lat"]; ?>',aDlng='<?php echo $eventAddress1["lng"]; ?>'); },2000);
		<?php } ?>
		
		/* send contact email */
		/*jQuery("#contact-us-form").submit(function( event ) {
			
			var receiver_email = jQuery('#receiver_email').val();
			var your_name      = jQuery('#your_name').val();
			var your_email     = jQuery('#your_email').val();
			var your_message   = jQuery('#your_message').val();

			jQuery.ajax({
				type:'post',
				url:'<?php //echo get_bloginfo('stylesheet_directory'); ?>/send_email.php',
				data: {receiver_email:receiver_email,your_name:your_name,your_email:your_email,your_message:your_message},
				success:function(result) {
					jQuery( "#sentemail" ).show().fadeOut(5000);
					jQuery("#contact-us-form")[0].reset();
				}
			});
			
			event.preventDefault();
		});*/
		/* end of send contact email */
		
		/* delete business code */
		 jQuery(".deletepostlink").click(function(){			
		 	jQuery("#deleteAdsModal").modal("show");
			jQuery("#deletepostid").val( jQuery(this).attr("data-id") );
		 });
		 
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
				your_name: "Please enter your name.",
				your_message: "Please enter your email.",
				your_email: "Please enter your comment."
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