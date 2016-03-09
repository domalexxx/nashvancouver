<?php 
/*
	Template Name: Search Template
*/
get_header(); ?>

<div id="main-container" class="container search">
  <div class="all-news">
    <div class="row top-buffer2">
      <div class="col-xs-9 col-sm-12 col-md-9 col-lg-9">
        <p class="title2 text-center">Задайте вашу поисковую фразу</p>
        <div class="col-centered col-xs-12 col-sm-12 col-md-12 col-lg-6 form-search">
          <form method="get" name="the-search-form" action="<?php echo get_permalink(get_page_by_title('Search')); ?>" role="search" style="position:relative;">
            <input name="search" type="text" class="form-control" value="<?php if($_GET['search']) { echo $_GET['search']; } ?>" placeholder="Что бы вы хотели найти?">
            <p id="mr-search-frm-btn"></p>
          </form>
        </div>
        <p class="title5 text-center">Что нам удалось найти по результатам вашего запроса</p>
        
        <div class="mr-catpost-outer top-buffer3" id="mr-catpost-outer-allpost">
          <div class="mr-catpost-outer">
          
            <?php
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$str = $_GET['search'];
				query_posts( array('s' => $str, 'post_type'=> 'post', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
			} else {
				query_posts( array('post_type'=> 'post', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
			}
		
			if(have_posts()) {
				
				global $wp_query;
				
				echo '<div class="overflow">';
				  echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
					echo '<div class="title pull-left"> <span>Найдено <span>'.$wp_query->found_posts.'</span> материалов в разделе НОВОСТИ И СТАТЬИ</span> </div>';
				  echo '</div>';
				echo '</div>';
				
				echo '<div class="article-top myart disable-last-margin">';
				
				while(have_posts()) { the_post();

			?>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                	<a href="<?php echo get_permalink( get_the_ID() ); ?>" class="cat">
                    <?php the_post_thumbnail(array(252, 252)); ?>
                	<div class="text_block">
                		<h2><?php echo mb_strimwidth(get_the_title(), 0, 44, '...'); ?></h2>
                		<ul class="info">
                			<?php
								echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';
								echo '<li>'. number_format_i18n( get_comments_number( get_the_ID() ) ) .'</li>';
							?>
                		</ul>
                	</div>
                	</a>
                </div>
          <?php } echo '</div>'; } ?>
          </div>
        </div>
        
		<div class="classified-inside">
			<?php
            	if(isset($_GET['search']) && !empty($_GET['search'])) {
					$str = $_GET['search'];
					query_posts( array('s' => $str, 'post_type'=> 'classified', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
				} else {
					query_posts( array('post_type'=> 'classified', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
				}

				if(have_posts()) {
					
					global $wp_query; 
					
					echo '<div class="overflow">';
					  echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
						echo '<div class="title pull-left"> <span>Найдено <span>'.$wp_query->found_posts.'</span> материалов в разделе ДОСКА ОБЪЯВЛЕНИЙ</span> </div>';
					  echo '</div>';
					echo '</div>';
					
					echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';

					while(have_posts()) { the_post();
					
						$classId = get_the_ID(); // Classified id 
						$clsPrice =  get_post_meta($classId,"price",true);
						$web_site =  get_post_meta($classId,"web_site",true);
						$clsPhone =  get_post_meta($classId,"phone",true);
						$clsLocation =  get_post_meta($classId,"location",true);
			?>
			<div class="row ad-block">
				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
					<?php
                        if(has_post_thumbnail()) {
                            the_post_thumbnail('thumbnail'); 
                        } else { 
                            echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
                        }
                    ?>
				</div>
				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
					<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                  		<span class='review'>
                  		<?php if(function_exists("pvc_get_post_views")) { echo pvc_get_post_views($classId); } ?>
                  		</span>
                        <span class='glyphicon glyphicon-eye-open'></span>
						<span class='data'><?php $clsadDate= get_the_date('d/m/y'); echo $clsadDate; ?></span>
					</div>
                	<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12 m-ads-expcnt-out">
                  		<p>
							<?php
                                $adexpcontent = trim(get_the_excerpt());
                                if(strlen($adexpcontent)>350) {
                                    echo substr($adexpcontent,0,350)."...";
                                } else { echo $adexpcontent;}
                            ?>
                  		</p>
                	</div>
                	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    	<span><?php echo $clsPhone; ?></span> <span>$<?php echo $clsPrice; ?></span> <span><?php echo $clsLocation; ?></span>
                   	</div>
              	</div>
			</div>
            <?php } echo '</div>'; } ?>
		<div class="clearfix"></div>
		</div>
        
        <div class="row business-directory top-buffer3">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$str = $_GET['search'];
				query_posts( array('s' => $str, 'post_type'=> 'business', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
			} else {
				query_posts( array('post_type'=> 'business', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
			}
			
			if(have_posts()) {
				
				global $wp_query;
										   
				echo '<div class="overflow top-buffer2">';
					echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
						echo '<div class="title pull-left"> <span>Найдено <span>'.$wp_query->found_posts.'</span> материал в разделе БИЗНЕС СПРАВОЧНИК</span> </div>';
					echo '</div>';
				echo '</div>';
				
				echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
				
				while(have_posts()) { the_post();
					$businessId = get_the_ID(); // Business id 	
					$busweb_site =  get_post_meta($businessId,"website",true);
					$busPhone =  get_post_meta($businessId,"phone",true);
					$busclsAddress =  get_post_meta($businessId,"address",true);		
					$busCatList = get_the_terms($businessId,'bussiness_cat'); // Categories
			?>
			<div class="row ad-block business-block">
            	<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">
					<?php
                        if(has_post_thumbnail()) { the_post_thumbnail('thumbnail'); }
                        else { echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">'; }
                    ?>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
					<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    	<h3 style="display:inline-block;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<span style="line-height:40px;" class="review"><?php if(function_exists("pvc_get_post_views")) { echo pvc_get_post_views($businessId); } ?></span>
                        <span style="line-height:40px;" class='glyphicon glyphicon-eye-open'></span>
                  	</div>
                  	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text">
                    	<p>Рейтинг: <?php if(function_exists("csr_get_overall_rating_stars")) {										
											echo csr_get_overall_rating_stars($businessId);
											echo "<span class='tt-ratc'>";
											echo csr_get_rating_count($businessId)." отзыва</span>";
										}
									?>
                    	</p>
                    	<p>Категория: <span>
                      	<?php
                        	if(is_array($busCatList)) {
								$i=1;													 
								foreach ($busCatList as $cat) {
									
									$termlink = get_term_link(intval($cat->term_id),'bussiness_cat');
									echo '<a href="'.$termlink.'">'.$cat->name.'</a>';												
									
									if($i<count($busCatList)) { echo ", "; }
									$i++;
								}
							} 
						?>
                      </span></p>
					  <p>Вебсайт: <span>
                      <?php
                      	if(!empty($busweb_site)) {
							$parsed = parse_url($busweb_site);
							
							if (empty($parsed['scheme'])){
								$urlStr = 'http://' . ltrim($busweb_site, '/');
							} else { $urlStr= $busweb_site; }
							echo '<a target="_blank" href="'.$urlStr.'">'.$busweb_site.'</a>';	
						}
					  ?>
                      </span></p>
                      <p>Телефон: <span><?php echo $busPhone; ?></span></p>
                      <p>Адрес: <span>
                      <?php
						if(!empty($busclsAddress['address'])) {											  
							echo '<a class="mr-gmap-location-pop" data-add-lat="'.$busclsAddress["lat"].'" data-add-long="'.$busclsAddress["lng"].'">'.$busclsAddress['address'].'</a>';
					  	}
					  ?>
                      </span></p>
                  </div>
                  <div class="col-xs-9 col-sm-9 col-md-9 col-lg-12"><p><?php the_excerpt(); ?></p></div>
                </div>
              </div>
              <?php } echo '</div>'; }
			?>
          </div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 event-inside top-buffer3">
			<?php
				$flag = 1;
				if(isset($_GET['search']) && !empty($_GET['search'])) {
					$str = $_GET['search'];
					query_posts( array('s' => $str, 'post_type'=> 'event', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
				} else {
					query_posts( array('post_type'=> 'event', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
				}

				if(have_posts()) {
					
					global $wp_query;
					
					echo '<div class="overflow">';
						echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
							echo '<div class="title pull-left"> <span>Найдено <span>'.$wp_query->found_posts.'</span> материалов в разделе АФИША</span> </div>';
						echo '</div>';
					echo '</div>';
					
					echo '<div class="row event mr-more-events-block">';
					
					while(have_posts()) {

						the_post();											
						$eventId = get_the_ID(); // Event id 
						$website =  get_post_meta($eventId,"website",true);
						$evPhone =  get_post_meta($eventId,"phone",true);
						$evLocation =  get_post_meta($eventId,"location",true);
						$evPrice =  get_post_meta($eventId,"price",true);
					?>
            		<div class="col-sm-4 col-md-4 col-lg-4 event-block">
              			<div class="event-block-thumb">
                        <a href="<?php the_permalink(); ?>">
						<?php 
							if(has_post_thumbnail()) { the_post_thumbnail('thumbnail',false,false); }
							else { 
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
                  <?php the_excerpt(); ?>
                </p>
              </div>
              <div class="bg-info event-block-location col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p class="location">
                  <?php if(!empty($evLocation['address'])) { echo mb_strimwidth($evLocation['address'],0,40,'...').', OR'; } ?>
                </p>
              </div>
              <div class="event-buybtn-out bg-primary text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p>Стоимость билетов от <strong>
                  <?php if($evPrice){ echo "$". $evPrice; }?>
                  </strong>
                <p>
				<button class="btn btn-primary" type="button">Купить</button>
              </div>
            </div>
            <?php
				if($flag % 3 == 0) { echo '<div class="clear"></div>'; }
				$flag++;
			}
			echo '</div>';
			}
			wp_reset_query();
		?>
          <div class="clearfix"></div>
        </div>
        
        <div class="row videogallery-inside top-buffer3">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <?php
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$str = $_GET['search'];
				query_posts( array('s' => $str, 'post_type'=> 'videogallery', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
			} else {
				query_posts( array('post_type'=> 'videogallery', 'post_status' => 'publish','order' => 'DESC', 'posts_per_page' => '-1') );
			}

			if(have_posts()) {
				
				global $wp_query;
                           
				echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
				  echo '<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12"> <span>Найдено <span>'.$wp_query->found_posts.'</span> материалов в разделе ВИДЕОГАЛЕРЕЯ</span> </div>';
				echo '</div>';
            
            	echo '<div class="row">';
              		echo '<div class="mr-video-gallery-out">';

				while(have_posts()) { the_post();
					$videoId = get_the_ID();
			?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mr-video-bx-outer">
                  <?php if(has_post_thumbnail()) { ?>
                  <a href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail(array(252, 252)); ?>
                  <div class="text_block" style="width:92%;">
                    <h2><?php echo mb_strimwidth(get_the_title(),0,44,'...'); ?></h2>
                    <ul class="info">
                      <li><span class="glyphicon glyphicon-eye-open"></span><?php echo pvc_get_post_views(get_the_ID()); ?></li>
                      <li><?php echo number_format_i18n( get_comments_number( get_the_ID() ) ); ?></li>
                    </ul>
                  </div>
                  </a>
                  <?php } ?>
                </div>
                <?php } echo '</div>'; echo '</div>'; } ?>
                <div class="clearfix"></div>
              </div>
            </div>
		</div>
      <!-- .col-lg-9 end-->
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>