<?php
require_once("../../../../wp-load.php");

if(isset($_REQUEST['noofarticle']) && !empty($_REQUEST['noofarticle']))
{
   $noofarticle = $_REQUEST['noofarticle'];
   $catid = $_REQUEST['catid'];
   query_posts(array("post_type"=>"post","post_status"=>"publish","posts_per_page"=>3,"paged"=>$noofarticle,
	  'tax_query' => array(
			   array(
				'taxonomy'=>'category','field' =>'id','terms'=>array($catid)
			   )
		   )
	   ));		

	if(have_posts()) 
	{		
	 while (have_posts()) 
	 {
 	  the_post(); 
			echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
		echo '<a class="cat" href="'.get_permalink( get_the_ID() ).'">';		
			the_post_thumbnail( 'thumbnail' );
			echo '<div class="text_block">';
				echo '<h2>'.mb_strimwidth(get_the_title(),0,44,'...').'</h2>';
				echo '<ul class="info">';
					echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';
					echo '<li>'. number_format_i18n(get_comments_number(get_the_ID())) .'</li>';
				echo '</ul>';
			echo '</div>';
		
		echo '</a>';
      echo '</div>';	  
     } 	 // end of the loop. 
	}
  else	
  {
	echo '<div class="No-moreposts clear"><h2>Новостей больше не найдено</h2></div>';
  }
   wp_reset_query();

} else if(isset($_REQUEST['noofvideos']) && !empty($_REQUEST['noofvideos'])) {
   
	$noofvideos = $_REQUEST['noofvideos'];
	$catid = $_REQUEST['catid'];
	query_posts(array("post_type"=>"videogallery","post_status"=>"publish","posts_per_page"=>4,"paged"=>$noofvideos, 'tax_query' => array( array( 'taxonomy'=>'video_cat','field' =>'id','terms'=>array($catid) ) ) ));
	
	if(have_posts()) {
	
		while (have_posts()) { the_post();
		 
			echo '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 mr-video-bx-outer">';
				if(has_post_thumbnail()) {
					echo '<a href="'. get_permalink(get_the_ID()) .'">';
						the_post_thumbnail(array(252, 252));
						echo '<div class="text_block" style="width:92%;">';
							echo '<h2>'. mb_strimwidth(get_the_title(),0,44,'...') .'</h2>';
							echo '<ul class="info">';
								echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';
								echo '<li>'. number_format_i18n(get_comments_number(get_the_ID())) .'</li>';
							echo '</ul>';
						echo '</div>';
					echo '</a>';
				}
			echo '</div>';
		}
	} else {
		echo '<div class="No-moreposts clear"><h2>Новостей больше не найдено</h2></div>';
	}
	wp_reset_query();
   
} else if(isset($_REQUEST['noofimmigration']) && !empty($_REQUEST['noofimmigration'])) {

   $noofimmigration = $_REQUEST['noofimmigration'];
   query_posts( array("post_type"=>"post","post_status"=>"publish","posts_per_page"=>3,"paged"=>$noofimmigration ) );		

	if(have_posts()) 
	{			
	 while (have_posts()) 
	 {
 	  the_post(); 
	
      echo '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">';
		echo '<a class="article-thum-out" href="'.get_permalink( get_the_ID() ).'">';		
			the_post_thumbnail( 'thumbnail' );
			echo '<div class="text_block">';
				echo '<h2>'.mb_strimwidth(get_the_title(),0,44,'...').'</h2>';
				echo '<ul class="info">';
					echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';
					echo '<li>'. number_format_i18n(get_comments_number(get_the_ID())) .'</li>';
				echo '</ul>';
			echo '</div>';
		
		echo '</a>';
      echo '</div>';	  
     } 	 // end of the loop. 
	}
  else	
  {
	echo '<div class="No-moreposts clear"><h2>Статей больше не найдено</h2></div>';
  }
   wp_reset_query();
} else if(isset($_REQUEST['noofbusiness']) && !empty($_REQUEST['noofbusiness'])) {
	
	$noofbusiness = $_REQUEST['noofbusiness'];
	
	$my_post_array = array("-99");
	$my_post_args = array( 'post_type' => 'business', 'post_status' => 'publish', 'posts_per_page' => 3, 'order' => 'DESC', "paged" => $noofbusiness );

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
	
	 query_posts(array('post_type'=>'business','post_status'=>'publish','posts_per_page'=>'3','order' => 'DESC', 'post__in' => $my_post_array)); 
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
		
		echo '<div class="row business-block">';
		echo '<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">';	
			 if(has_post_thumbnail())
			 {
			   the_post_thumbnail('thumbnail'); 
			 }
			 else
			 {
				echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
			 }
			
		echo '</div>';
		echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">';
		echo '<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">';
				
				echo '<h3 style="display:inline-block;"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
				echo '<span style="line-height:40px;" class="review">';
					if(function_exists("pvc_get_post_views")) { echo pvc_get_post_views($businessId); }
				echo '</span>';
				echo '<span style="line-height:40px;" class="glyphicon glyphicon-eye-open"></span>';
				
			echo '</div>';
			echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
				
				echo '<span style="float:left;width:100%;">Рейтинг:&nbsp;';
				if(function_exists("csr_get_overall_rating_stars")) {
					
					if(csr_get_rating_count($businessId) == 1) {
						
						echo '<div data-rateit-readonly="true" data-rateit-ispreset="true" data-rateit-value="'. csr_get_rating_count($businessId) . '" class="rateit"><button aria-controls="rateit-range-'. csr_get_rating_count($businessId) . '" aria-label="reset rating" class="rateit-reset" id="rateit-reset-'. csr_get_rating_count($businessId) . '" style="display: none;"></button><div aria-valuenow="'. csr_get_rating_count($businessId) . '" aria-valuemax="5" aria-valuemin="0" aria-owns="rateit-reset-'. csr_get_rating_count($businessId) . '" aria-label="rating" role="slider" tabindex="0" class="rateit-range" id="rateit-range-'. csr_get_rating_count($businessId) . '" style="width: 80px; height: 16px;" aria-readonly="true"><div style="height:16px;width:16px;" class="rateit-selected rateit-preset"></div><div style="height:16px" class="rateit-hover"></div></div></div>';
					} else if(csr_get_rating_count($businessId) == 2) {

						echo '<div data-rateit-readonly="true" data-rateit-ispreset="true" data-rateit-value="'. csr_get_rating_count($businessId) . '" class="rateit"><button aria-controls="rateit-range-'. csr_get_rating_count($businessId) . '" aria-label="reset rating" class="rateit-reset" id="rateit-reset-'. csr_get_rating_count($businessId) . '" style="display: none;"></button><div aria-valuenow="'. csr_get_rating_count($businessId) . '" aria-valuemax="5" aria-valuemin="0" aria-owns="rateit-reset-'. csr_get_rating_count($businessId) . '" aria-label="rating" role="slider" tabindex="0" class="rateit-range" id="rateit-range-'. csr_get_rating_count($businessId) . '" style="width: 80px; height: 16px;" aria-readonly="true"><div style="height:16px;width:32px;" class="rateit-selected rateit-preset"></div><div style="height:16px" class="rateit-hover"></div></div></div>';
					} else if(csr_get_rating_count($businessId) == 3) {
						
						echo '<div data-rateit-readonly="true" data-rateit-ispreset="true" data-rateit-value="'. csr_get_rating_count($businessId) . '" class="rateit"><button aria-controls="rateit-range-'. csr_get_rating_count($businessId) . '" aria-label="reset rating" class="rateit-reset" id="rateit-reset-'. csr_get_rating_count($businessId) . '" style="display: none;"></button><div aria-valuenow="'. csr_get_rating_count($businessId) . '" aria-valuemax="5" aria-valuemin="0" aria-owns="rateit-reset-'. csr_get_rating_count($businessId) . '" aria-label="rating" role="slider" tabindex="0" class="rateit-range" id="rateit-range-'. csr_get_rating_count($businessId) . '" style="width: 80px; height: 16px;" aria-readonly="true"><div style="height:16px;width:48px;" class="rateit-selected rateit-preset"></div><div style="height:16px" class="rateit-hover"></div></div></div>';
					} else if(csr_get_rating_count($businessId) == 4) {
						
						echo '<div data-rateit-readonly="true" data-rateit-ispreset="true" data-rateit-value="'. csr_get_rating_count($businessId) . '" class="rateit"><button aria-controls="rateit-range-'. csr_get_rating_count($businessId) . '" aria-label="reset rating" class="rateit-reset" id="rateit-reset-'. csr_get_rating_count($businessId) . '" style="display: none;"></button><div aria-valuenow="'. csr_get_rating_count($businessId) . '" aria-valuemax="5" aria-valuemin="0" aria-owns="rateit-reset-'. csr_get_rating_count($businessId) . '" aria-label="rating" role="slider" tabindex="0" class="rateit-range" id="rateit-range-'. csr_get_rating_count($businessId) . '" style="width: 80px; height: 16px;" aria-readonly="true"><div style="height:16px;width:64px;" class="rateit-selected rateit-preset"></div><div style="height:16px" class="rateit-hover"></div></div></div>';
					} else if(csr_get_rating_count($businessId) == 5) {
						
						echo '<div data-rateit-readonly="true" data-rateit-ispreset="true" data-rateit-value="'. csr_get_rating_count($businessId) . '" class="rateit"><button aria-controls="rateit-range-'. csr_get_rating_count($businessId) . '" aria-label="reset rating" class="rateit-reset" id="rateit-reset-'. csr_get_rating_count($businessId) . '" style="display: none;"></button><div aria-valuenow="'. csr_get_rating_count($businessId) . '" aria-valuemax="5" aria-valuemin="0" aria-owns="rateit-reset-'. csr_get_rating_count($businessId) . '" aria-label="rating" role="slider" tabindex="0" class="rateit-range" id="rateit-range-'. csr_get_rating_count($businessId) . '" style="width: 80px; height: 16px;" aria-readonly="true"><div style="height:16px;width:80px;" class="rateit-selected rateit-preset"></div><div style="height:16px" class="rateit-hover"></div></div></div>';
					} else {
						
						echo '<div data-rateit-readonly="true" data-rateit-ispreset="true" data-rateit-value="'. csr_get_rating_count($businessId) . '" class="rateit"><button aria-controls="rateit-range-'. csr_get_rating_count($businessId) . '" aria-label="reset rating" class="rateit-reset" id="rateit-reset-'. csr_get_rating_count($businessId) . '" style="display: none;"></button><div aria-valuenow="'. csr_get_rating_count($businessId) . '" aria-valuemax="5" aria-valuemin="0" aria-owns="rateit-reset-'. csr_get_rating_count($businessId) . '" aria-label="rating" role="slider" tabindex="0" class="rateit-range" id="rateit-range-'. csr_get_rating_count($businessId) . '" style="width: 80px; height: 16px;" aria-readonly="true"><div style="height:16px;width:0px;" class="rateit-selected rateit-preset"></div><div style="height:16px" class="rateit-hover"></div></div></div>';
					}
						
					echo "<span class='tt-ratc'>". csr_get_rating_count($businessId) . " отзыва</span>";
				}                                        
				echo '</span>';
			 
				echo '<p>Категория:  <span>';
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
				echo '</span></p>';
				echo '<p>Вебсайт: <span>';
			
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

				echo '<p>Телефон: <span>'.$busPhone.'</span></p>';
				echo '<p>Адрес:  <span>';
				if(!empty($busclsAddress['address'])) {											  
					echo '<a class="mr-gmap-location-pop" data-add-lat="'.$busclsAddress["lat"].'" data-add-long="'.$busclsAddress["lng"].'">'.$busclsAddress['address'].'</a>';
				}
			echo '</span></p>';
			echo '</div>';
			echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12">';
				echo '<p>'.get_the_excerpt().'</p>';
			echo '</div>';
		echo '</div>';
		echo '</div>';

		}
   }
   else	
   { 
	echo '<div class="No-moreposts"><h2>No more articles</h2></div>';
   }
    wp_reset_query();
}
else if(isset($_REQUEST['noofclassified']) && !empty($_REQUEST['noofclassified']))
{
	$noofclassified = $_REQUEST['noofclassified']; 
	$catarr = unserialize($_REQUEST['catarr']);
	$excludePostid = $_REQUEST['postid'];
   
  if(!empty($excludePostid))
  {
	  	$my_post_array = array("-99");
		$my_post_args = array( 'post_type' => 'classified', 'post_status' => 'publish', 'posts_per_page' => 3, 'order' => 'DESC', "paged" => $noofclassified, 'post__not_in' => array($excludePostid), 'tax_query' =>array(array('taxonomy'=>'classified_cat','field'=>'id','terms'=>$catarr)) );
	
		$my_querys = null;
		$my_querys = new WP_Query($my_post_args);
	
		if( $my_querys->have_posts() ) {
			while ($my_querys->have_posts()) : $my_querys->the_post();
			
				if( check_post_expiration( get_the_ID(), "classified" ) > 0 ) {
					array_push($my_post_array, get_the_ID());
				}
	
			endwhile;
		}
		wp_reset_query();
	  
		query_posts(array('post_type'=>'classified','post_status'=>'publish','order' =>'DESC', 'posts_per_page'=>'3', 'post__in' => $my_post_array, 'post__not_in' => array($excludePostid), 'tax_query' =>array(array('taxonomy'=>'classified_cat','field'=>'id','terms'=>$catarr)) ));
		
		if(have_posts())
	   {
			while(have_posts())
			{
				the_post();
				
			   $classId = get_the_ID(); // Classified id 
			   $clsPrice =  get_post_meta($classId,"price",true);
			   $web_site =  get_post_meta($classId,"web_site",true);
			   $clsPhone =  get_post_meta($classId,"phone",true);
			   $clsLocation = get_post_meta($classId,"location",true);
			
	 echo '<div class="row ad-block">';
	 echo '<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">';
	
			 if(has_post_thumbnail())
			 {
				the_post_thumbnail('thumbnail'); 
			 }
			 else
			 {
				echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
			 }
	
		echo '</div>';
		echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">';
		echo '<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">';
		echo '<h3><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
			echo '<span class="review">';				
				//function Post view Count
				if(function_exists("pvc_get_post_views"))
				{ 
				 echo pvc_get_post_views($classId);
				} 
			echo '</span>';
			echo '<span class="glyphicon glyphicon-eye-open"></span>';
			echo '<span class="data">';			
				  $clsadDate= get_the_date('d/m/y');
					echo $clsadDate;										 
			echo '</span>';
			echo '</div>';
			echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12 m-ads-expcnt-out">';
			echo '<p>'.get_the_excerpt().'</p>';
			echo '</div>';
			echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
			echo '<span>'.$clsPhone.'</span>';
			echo '<span>$'.$clsPrice.'</span>';
			if(!empty($clsLocation['address'])) { echo '<span>'.$clsLocation['address'].'</span>'; }
		echo '</div>';
	echo '</div>';
	echo '</div>';
			}
	   }
	  else
	  {
	  echo '<div class="No-moreposts"><h2>no more posts</h2></div>';
	  }
	  wp_reset_query();
  }
  else
  {
	  	$my_post_array = array("-99");
		$my_post_args = array( 'post_type' => 'classified', 'post_status' => 'publish', 'posts_per_page' => 7, 'order' => 'DESC', "paged" => $noofclassified );
	
		$my_querys = null;
		$my_querys = new WP_Query($my_post_args);
	
		if( $my_querys->have_posts() ) {
			while ($my_querys->have_posts()) : $my_querys->the_post();
			
				if( check_post_expiration( get_the_ID(), "classified" ) > 0 ) {
					array_push($my_post_array, get_the_ID());
				}
	
			endwhile;
		}
		wp_reset_query();
		
		query_posts( array('post_type'=>'classified','post_status'=>'publish','order' =>'DESC','posts_per_page'=>'7', 'post__in' => $my_post_array) );
		
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
			
	 echo '<div class="row ad-block">';
	 echo '<div class="col-xs-2 col-sm-2 col-md-2 col-lg-3">';
	
			 if(has_post_thumbnail())
			 {
				the_post_thumbnail('thumbnail'); 
			 }
			 else
			 {
				echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
			 }
	
		echo '</div>';
		echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">';
		echo '<div class="bg-lightblue form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">';
		echo '<h3><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
			echo '<span class="review">';				
				//function Post view Count
				if(function_exists("pvc_get_post_views"))
				{ 
				 echo pvc_get_post_views($classId);
				} 
			echo '</span>';
			echo '<span class="glyphicon glyphicon-eye-open"></span>';
			echo '<span class="data">';			
				  $clsadDate= get_the_date('d/m/y');
					echo $clsadDate;										 
			echo '</span>';
			echo '</div>';
			echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-12 m-ads-expcnt-out">';
			echo '<p>'.get_the_excerpt().'</p>';
			echo '</div>';
			echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
			echo '<span>'.$clsPhone.'</span>';
			echo '<span>$'.$clsPrice.'</span>';
			echo '<span>'.$clsLocation.'</span>';
		echo '</div>';
	echo '</div>';
	echo '</div>';
			}
	   }
	  else
	  {
	  echo '<div class="No-moreposts"><h2>no more posts</h2></div>';
	  }
	  wp_reset_query();   
  }
}
else if(isset($_REQUEST['noofevents']) && !empty($_REQUEST['noofevents']))
{
	$flag = 1;
	$noofevents = $_REQUEST['noofevents'];
	
	$my_post_array = array("-99");
	$my_post_args = array( 'post_type' => 'event', 'post_status' => 'publish', 'posts_per_page' => 3, 'order' => 'DESC', "paged" => $noofevents );

	$my_querys = null;
	$my_querys = new WP_Query($my_post_args);

	if( $my_querys->have_posts() ) {
		while ($my_querys->have_posts()) : $my_querys->the_post();
		
			if( check_post_expiration( get_the_ID(), "event" ) > 0 ) {
				array_push($my_post_array, get_the_ID());
			}

		endwhile;
	}
	wp_reset_query();
	
	query_posts(array('post_type'=>'event','post_status'=>'publish','posts_per_page'=>'3','order' =>'DESC', 'post__in' => $my_post_array));
	   
	if(have_posts())
	{
		while(have_posts())
		{
		   the_post();											
		   $eventId = get_the_ID(); // Event id 
		   $website =  get_post_meta($eventId,"website",true);
		   $evPhone =  get_post_meta($eventId,"phone",true);
		   $evLocation =  get_post_meta($eventId,"location",true);
		   $evPrice =  get_post_meta($eventId,"price",true);							   								   		
		echo '<div class="col-sm-4 col-md-4 col-lg-4 event-block">';
		  echo '<div class="event-block-thumb">';
			 echo '<div class="text_block">';
				echo '<p class="pull-left">Поделититесь с друзьями</p>';
					echo '<ul>';
						echo '<li><a href="#" class="facebook">Facebook</a></li>';
						echo '<li><a href="#" class="twitter">Twitter</a></li>';
					echo '</ul>';
			 echo '</div>';
			  echo '<a href="'.get_the_permalink().'">';					
						if(has_post_thumbnail())
						{
						the_post_thumbnail('thumbnail',false,false);
						}
						else
						{
						 echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">'; 
						}
			 echo '<div class="opacity">';
					echo '<div class="event-info text-center">';
						echo '<h3>'.get_the_title().'</h3>';
						 echo '<p>Спектакль</p>';
					echo '</div>';
					echo '</div>';
			  echo '</a>';
			 echo '</div>';
			echo '<div class="event-block-content">';
			  echo '<p class="date text-center"><strong>23</strong> Января 5:00 PM</p>';
			  echo '<p>'.get_the_excerpt().'</p>';
			echo '</div>';
			echo '<div class="bg-info event-block-location col-xs-12 col-sm-12 col-md-12 col-lg-12">';
			  echo '<p class="location">'.$evLocation.', OR</p>';
			echo '</div>';
			echo '<div class="event-buybtn-out bg-primary text-center col-xs-12 col-sm-12 
				col-md-12 col-lg-12">';
				echo '<p>Стоимость билетов от <strong>';
				 if($evPrice){ echo "$". $evPrice; }
				echo '</strong><p>';
			   echo '<button class="btn btn-primary" type="button">Купить</button>';
		  echo '</div>';
		echo '</div>';
		
		if($flag % 3 == 0) { echo '<div class="clear"></div>'; }
		$flag++;
	
		}
	}
	else
	{
	 echo '<div class="clear"></div><div class="No-moreposts"><h2>no more posts</h2></div>';
	}
	wp_reset_query();
}
else if(isset($_REQUEST['getCatIdurl']) && !empty($_REQUEST['getCatIdurl'])) // Get the category url by id
{
  $getCatId = $_REQUEST['getCatIdurl'];
  echo get_term_link(intval($getCatId ),'bussiness_cat');
}
else if(isset($_REQUEST['shownofbpage']) && !empty($_REQUEST['cpurl']))
{
  $getnPages = $_REQUEST['shownofbpage'];
  $pageUrl = $_REQUEST['cpurl'];
  $urlc= add_query_arg('nfbp',$getnPages,$pageUrl);
  echo $urlc;
}