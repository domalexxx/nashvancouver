<?php get_header(); ?>

<div class="container classified-inside event-inside">
  <div class="row">
    <?php get_sidebar(); ?>
    <div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12"> <span>Страница мероприятия</span>
              <div class="pull-right">
				<a href="<?php echo get_permalink(get_page_by_title("Events")); ?>" class="back">Вернуться к списку всех мероприятий</a>
			  	<a href="<?php echo get_permalink(get_page_by_title("Event Add")); ?>" class="pull-right">Добавить новое</a>
			  </div>
            </div>
          </div>
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
					   $evPrice =  get_post_meta($eventId,"price",true);
					   $evLocation =  get_post_meta($eventId,"location",true);								   
					   $event_date = get_post_meta($eventId,'event_date',true); //Event Date 
					   $event_start_time = get_post_meta($eventId,'event_start_time',true); // Event Start time
					   $event_end_time = get_post_meta($eventId,'event_end_time',true); // Event End time
					   $google_map = get_post_meta($eventId,"google_map",true); // Google map
					   $eventCatList = get_the_terms($eventId,'event_cat'); // Categories		
			   ?>
              <div id="classified">
                <div class="form-group">
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
                    <div class="form-group">
                      <div class="category text-left col-xs-10 col-sm-10 col-md-10 
                                            col-lg-4 control-label" for="">Категория:</div>
                      <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7"> <span>
                        <?php // Events categories
						   if(is_array($eventCatList))
							{													
							 $i=1;													 
							 foreach ($eventCatList as $eventC)
							 {
								 array_push($eventCat,$eventC->term_id); 	
								 //echo $eventC->name;
							 $termlink=get_term_link(intval($eventC->term_id),'event_cat');
							  echo '<a href="'.$termlink.'">'.$eventC->name.'</a>';													
							  
								 if($i<count($eventCatList))
								 {echo ", ";}
								 $i++;
							  }														 
							}
						?>
                        </span> </div>
                    </div>
                    <!--Стоимость билетов от-->
                    <div class="form-group">
                      <div class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Вебсайт:</div>
                      <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7"> <span>
                        <?php
														if(!empty($website))
														{
														   $parsed = parse_url($website);
															if (empty($parsed['scheme'])){
																$urlStr = 'http://' . ltrim($website, '/');
															}
															else
															{
															  $urlStr= $website;
															}
														echo '<a target="_blank" href="'.$urlStr.'">'.$website.'</a>';	
														}
														?>
                        </span> </div>
                    </div>
                    <div class="form-group">
                      <div class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Организаторы:</div>
                      <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7"> <span><?php echo $evPhone; ?></span> </div>
                    </div>
                    <div class="form-group">
                      <div class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Стоимость:</div>
                      <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7"> <span>
                        <?php 
                                                  if($evPrice)
                                                  { 
                                                   echo "от $".$evPrice."<b class='title4'>&nbsp;&nbsp;&nbsp;Kупить</b>";
                                                  } 
                                                  ?>
                        </span> </div>
                    </div>
                    <div class="event-buy-btn-out">
                      <div class="text-center form-group"> <a href="#">
                        <button type="submit" class="btn btn-primary">Отправить сообщение</button>
                        </a> </div>
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
                      <div class="schedule col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <?php
										  
										?>
                        <p>23</p>
                        <p>Января</p>
                        <p>5:00-6:00 PM</p>
                      </div>
                      <div class="schedule col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <p>23</p>
                        <p>Января</p>
                        <p>5:00-6:00 PM</p>
                      </div>
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
							echo $evLocation; 
						 }
						?>
                    </p>
                  </div>
                </div>
                <div class="event-map-out"> <img src="http://dummyimage.com/803x266/4d494d/686a82.gif&text=google+map" alt="google+map">
                  <!--<div id="eventMap"></div>-->
                </div>
                <p class="mapInfo">Вход слева после галереи</p>
              </div>
              <?php }
						   }
						  ?>
              <!-- Event content block close-->
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12"> <span>Похожие Мероприятия</span>
                  <!--<hr style="margin-top:0px;">-->
                  <br>
                </div>
                <div class="row event">
                  <?php
								
								  query_posts(
								   array('post_type'=>'event','post_status'=>'publish','posts_per_page'=>'3',
								          'tax_query' => array(
											array(
												'taxonomy' => 'event_cat',
												'field'    => 'id',
												'terms'    =>$eventCat,
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
										   $evPrice =  get_post_meta($eventId,"price",true);							   								   		?>
                  <div class="col-sm-4 col-md-4 col-lg-4 event-block">
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
                          <h3>
                            <?php the_title(); ?>
                          </h3>
                          <p>Спектакль</p>
                        </div>
                      </div>
                      </a> </div>
                    <div class="event-block-content">
                      <p class="date text-center"><strong>23</strong> Января 5:00 PM</p>
                      <p>
                        <?php the_excerpt();?>
                      </p>
                    </div>
                    <div class="bg-info event-block-location col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <?php if(!empty($evLocation['address'])) {
											   echo '<p class="location">'.$evLocation['address'].', OR</p>';
											   }?>
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
    <!-- end .row -->
  </div>
  <!-- end col-lg-9 -->
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	   var map;
        function map_initialize() {
            var myLatlng = new google.maps.LatLng(24.18061975930,79.36565089010);
            var myOptions = {
                zoom:7,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("eventMap"), myOptions);
            // marker refers to a global variable
            marker = new google.maps.Marker({
                position: myLatlng,
                map: map
            });

            google.maps.event.addListener(map, "click", function(event) {
                // get lat/lon of click
                var clickLat = event.latLng.lat();
                var clickLon = event.latLng.lng();

                // show in input box
                document.getElementById("lat").value = clickLat.toFixed(5);
                document.getElementById("lon").value = clickLon.toFixed(5);

                  var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(clickLat,clickLon),
                        map: map
                     });
            });
    }   
    
	</script>
<?php get_footer(); ?>
