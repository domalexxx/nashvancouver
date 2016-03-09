<?php

/**

 * Template Name: Front Page Template

 *

 * Description: A page template that provides a key component of WordPress as a CMS

 * by meeting the need for a carefully crafted introductory page. The front page template

 * in Twenty Twelve consists of a page content area for adding text, images, video --

 * anything you'd like -- followed by front-page-only widgets in one or two columns.

 *

 * @package WordPress

 * @subpackage Twenty_Twelve

 * @since Twenty Twelve 1.0

 */



get_header(); ?>



<div id="main-container" class="container">
<div class="overflow">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-buffer2">
							<div class="title pull-left">
								<span>Последние новости</span>
							</div>
							<ul class="links pull-right">

							  <li>
							   <?php if ( is_user_logged_in() ) { ?>
										<a href="<?php echo admin_url(); ?>post-new.php">Добавить новую статью</a>
									<?php } else { ?>
										<a href="javascript:void(0);" data-toggle="modal" data-target="#login" type="button">
									   Добавить новую статью</a>
									<?php } ?>
							  </li>
							  <li>
							   <a href="<?php echo get_permalink( get_page_by_title("Посмотреть все новости") ); ?>">Посмотреть все новости</a></li>
							</ul>
						</div>
						</div>
  <div class="row mr-home-firstblock">	

	<div class="left_column col-xs-12 col-sm-6 col-md-6 col-lg-6">
		

		<?php

			$args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 1, 'offset' => 0, 'order' => 'DESC');

			$my_query = null;

			$my_query = new WP_Query($args);



			if( $my_query->have_posts() ) {



				while ($my_query->have_posts()) : $my_query->the_post();



					echo '<a href="'.get_permalink( get_the_ID() ).'">';

					the_post_thumbnail(array(522, 522));

					echo '</a>';


					echo '<a href="'.get_permalink( get_the_ID() ).'">';
					echo '<div class="text_block">';

					echo '<h1>'. mb_strimwidth(get_the_title(),0,44,'...').'</h1>';

					echo '<p>'.mb_strimwidth(get_the_excerpt(),0,130,'...').'</p>';
				
					echo '<ul class="info">';

					echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';

					echo '<li>'. number_format_i18n( get_comments_number( get_the_ID() ) ) .'</li>';

					echo '</ul>';

					echo '</div>';
					echo '</a>';


				endwhile;

			}

			wp_reset_query();

		?>

    </div>

	

    <div class="right_column col-xs-12 col-sm-12 col-md-6 col-lg-6">
		
	  <div class="row">        

		<?php

			$args1 = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 2, 'offset' => 1, 'order' => 'DESC');

			$my_query = null;

			$my_query = new WP_Query($args1);



			if( $my_query->have_posts() ) {



				while ($my_query->have_posts()) : $my_query->the_post();



					echo '<div class="col col-xs-12 col-sm-6 col-md-6 col-lg-6">';

						echo '<a href="'.get_permalink( get_the_ID() ).'">';
						the_post_thumbnail(array(252, 252));
						echo '</a>';


						echo '<a href="'.get_permalink( get_the_ID() ).'">';
						echo '<div class="text_block">';
							echo '<h2>'. mb_strimwidth(get_the_title(),0,44,'...').'</h2>';
							echo '<ul class="info">';
								echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';					echo '<li>'. number_format_i18n( get_comments_number( get_the_ID() ) ) .'</li>';
							echo '</ul>';
						echo '</div>';
						echo '</a>';
					echo '</div>';
				

				endwhile;

			}

			wp_reset_query();

		?>

      </div>

      

	  <div class="row">

	  

	    <?php

			$args1 = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 2, 'offset' => 3, 'order' => 'DESC');

			$my_query = null;

			$my_query = new WP_Query($args1);

	

			if( $my_query->have_posts() ) {

				

				while ($my_query->have_posts()) : $my_query->the_post();

					

					echo '<div class="col col-xs-12 col-sm-12 col-md-6 col-lg-6">';
						echo '<a href="'.get_permalink( get_the_ID() ).'">';
						the_post_thumbnail( 'thumbnail' );
						echo '</a>';						
						echo '<a href="'.get_permalink( get_the_ID() ).'">';
						echo '<div class="text_block">';
							echo '<h2>'. mb_strimwidth(get_the_title(),0,44,'...') .'</h2>';
							echo '<ul class="info">';
								echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';					echo '<li>'. number_format_i18n( get_comments_number( get_the_ID() ) ) .'</li>';
							echo '</ul>';
						echo '</div>';
						echo '</a>';
					echo '</div>';
				endwhile;
			}
			wp_reset_query();
		?>

      </div>

    </div>

  </div>

</div>

<div class="text-center bg-info block">

  <div class="container">

    <div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">			
      <p class="title2">Подпишитесь на еженедельную рассылку от нашего сайта</p>

      <p class="title3">Оставайтесь всегда в курсе последних новостей, происходящих в Ванкувере и Канаде</p>
	  
      <!-- Begin MailChimp Signup Form -->
      <div id="mc_embed_signup">
        <form role="form" action="//nashvancouver.us12.list-manage.com/subscribe/post?u=0810fd4893e767495da87d9cd&amp;id=3fed80c408" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate form-inline" target="_blank" novalidate>
          <div id="mc_embed_signup_scroll">
            <div class="mc-field-group form-group">
              <input type="text" value="" name="FNAME" class="form-control" id="mce-FNAME" placeholder="Ваше имя">
            </div>
            <div class="mc-field-group form-group">
              <input type="email" value="" name="EMAIL" class="form-control" id="mce-EMAIL" placeholder="Ваш email адрес">
            </div>
            <button type="submit" name="subscribe" id="mc-embedded-subscribe" class="button btn btn-danger">
            Подписаться</button>
            <div id="mce-responses" class="clear">
              <div class="response" id="mce-error-response" style="display:none"></div>
              <div class="response" id="mce-success-response" style="display:none"></div>
            </div>
            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
            <div style="position: absolute; left: -5000px;">
              <input type="text" name="b_0810fd4893e767495da87d9cd_3fed80c408" tabindex="-1" value="">
            </div>
          </div>
        </form>
      </div>
      <!--End mc_embed_signup-->
		</div>

    </div>

  </div>

</div>

<div class="bg-primary block2 text-center">
	<div class="container">
			  <span class="title2">Следите за нами в социальных сетях</span>
			<?php echo do_shortcode('[easy-fans hide_title="1" columns="4" template="flat" effects="essbfc-no-effect" show_total="0"]'); ?> 
	</div>
</div>

<div class="container">
<div class="overflow">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-buffer2">
							<div class="title pull-left">
								<span>Последние объявления</span>
							</div>
							<ul class="links pull-right">
          <li>
             <?php
            if(is_user_logged_in())
            {
             echo '<a  href="'.get_permalink(get_page_by_title("Classified add")).'">Добавить новое</a>';
            }
            else
            {							 
             echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">
			 Добавить новое</a>';
            }?>
             </li>
          <li>
           <a href="<?php echo get_permalink(get_page_by_title("Classifieds"));?>">Посмотреть все</a></li>
        </ul>      
						</div>
						</div>    
  <div class="row adv mradv-slider">
  <?php
  	$my_post_array = array("-99");
	$my_post_args = array( 'post_type' => 'classified', 'post_status' => 'publish', 'posts_per_page' => 10 );

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
	
     query_posts(array('post_type'=>'classified','post_status'=>'publish','posts_per_page'=>'10', 'post__in' => $my_post_array));
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
     ?>
    <div class="col">
      <h3 class="bg-primary"><a href="<?php the_permalink();?>">
	   <?php echo mb_strimwidth(get_the_title(), 0, 30, "..."); ?>
      </a></h3>
      <div class="bg-warning block3 mr-lads-thumb">
        <a href="<?php the_permalink();?>">
          <?php
		  if(has_post_thumbnail())
		  {
		   the_post_thumbnail('thumbnail',array('class' =>'pull-left')); 
		  }
		  else
		  {
			echo '<img width="60" height="60" src="'.get_template_directory_uri().'/images/empty-pic.png" alt="image" class="pull-left">';
		  }
		?>
         
        </a>
        <p><?php the_excerpt();?></p>
      </div>
      <div class="block3 bg-primary"><span class="view"><span class="glyphicon glyphicon-eye-open"></span>
	    <?php 
		//function Post view Count
		if(function_exists("pvc_get_post_views"))
		{ 
		echo pvc_get_post_views($classId);
		} 
		?>
        </span>
        <span class="pull-right">
	    <?php 
			$clsadDate= get_the_date('d/m/y');
															 
		?>
        
<?php printf( _x('%s назад','%s= human-readable time difference','clickwebstudio' ),human_time_diff(get_the_time('U'), current_time('timestamp'))); ?>


        </span>
      </div>
    </div>
  <?php
	 }
    }
	wp_reset_query();
  ?>		

  </div>

</div>

<div class="container-fluid bg-warning top-buffer">
<div class="container">

    <div class="row top-buffer2">

      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center bussiness-block"> <span class="title">
      Русскоязычные бизнесы Ванкувера</span>
        <ul class="links">
          <li><!--<a href="#">Разместить свой бизнес</a>-->
           <?php
			  if(is_user_logged_in()) {
			  	echo '<a href="'.get_permalink(get_page_by_title('Business Directory Add')).'">Разместить свой бизнес</a>';
			  } else {							 
			    echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Разместить свой бизнес</a>';
			  }
		    ?>
          </li>
        </ul>
        <div class="banner">
          <?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
          <?php endif; ?>
          <!-- <img src="https://api.fnkr.net/testimg/528x120/e9e9e9/6ca9d8/?text=BANNER+528X120"> -->
         </div>
         <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
         <?php
			 //Category list 
			$taxonomy ='bussiness_cat';
			$CatQryarg = array('parent'=>0,'number'=>0); 							
			$businessTerms = get_terms($taxonomy ,$CatQryarg);
			if(is_array($businessTerms))
			{
			echo '<div class="catalog">';
			 foreach($businessTerms as $businessCat)
			 {
				 
				 $catID = $businessCat->term_id;								
				 $catName = $businessCat->name;
				 $catImgurl = "";
				 if(function_exists('z_taxonomy_image_url'))
				 {
					  $catImgurl=z_taxonomy_image_url($catID);  
					  
				 }
			   ?>
			   <div class="col-xs-12 col-sm-8 col-md-3 col-lg-6">
			   	<a class="auto" style="background:url(<?php echo $catImgurl; ?>) center / cover no-repeat" 
			   	  href="<?php echo get_term_link(intval($catID),$taxonomy);?>">
			   	  <?php echo $catName;?>
			   	</a>
			   </div>
			   <?php
			 }	
			echo '</div>';	
			}
		  ?>
        <ul class="links text-center">

          <li><a href="<?php echo get_permalink(get_page_by_title('Business Directory' )); ?>">Посмотреть весь каталог бизнесов</a>
          </li>
          

        </ul>

      </div>
      </div>
        </div>
		
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center event-block"> 
      <span class="title">Ближайшие мероприятия в Ванкувере</span>

        <ul class="links">
         <li>
           <?php
			if(is_user_logged_in())
			{
			  echo '<a href="'.get_permalink(get_page_by_title('Event Add')).'">Разместить мероприятие</a>';
			}
			else
			{
			 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Разместить мероприятие</a>';
			}
			?>
         </li>
        </ul>

        <div class="banner">
        <?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-5' ); ?>
        <?php endif; ?>
                  <!-- <img src="https://api.fnkr.net/testimg/528x120/e9e9e9/6ca9d8/?text=BANNER+528X120"> -->
        </div>
		<div class="row">
			        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <ul class="events">
		  <?php
			$my_post_array = array("-99");
			$my_post_args = array( 'post_type' => 'event', 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => '7' );
		
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
			
			query_posts( array('post_type'=>'event', 'post_status'=>'publish', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => '5', 'post__in' => $my_post_array));
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
			?>
           <li>
            <a href="<?php the_permalink(); ?>">
            <div class="event text-center col-xs-12">
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
			 
			  
            </div>
            <div class="row">
            	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
	
              <div class="bg-info">
                <h4>"<?php the_title(); ?>"</h4>
                <p><?php echo mb_strimwidth(get_the_excerpt(),0,110,'...');?></p>
                <!-- <p><?php //echo get_excerpt();?></p> -->
                <p class="location text-left"><?php echo mb_strimwidth($evLocation['address'],0,60,'...'); ?>, OR</p>
              </div>
			  
              <div class="bg-primary">
				<?php
					$countrows = $wpdb->get_row("SELECT count(*) as countevent FROM $wpdb->postmeta WHERE 1 AND post_id = '$eventId' AND meta_key LIKE 'event_date_%'");
					if( intval($countrows->countevent) > 0 ){

						for($counter=0; $counter < intval($countrows->countevent); $counter++){
								echo '<strong class="data">'. get_post_meta($eventId, 'event_date_'.$counter, true) .'&nbsp;'. the_russian_time( monthName(get_post_meta($eventId, 'event_month_'.$counter, true)) ) .'&nbsp;'.date ("Y") .'</strong>&nbsp;';
						}
					}
				?>
			  </div>
			  
              <p class="bg-primary">
			  	<span>
					<?php
						$terms = get_the_terms($eventId, 'event_cat');
						if ($terms) {
							$out = array();
							foreach ($terms as $term) {
								$out[] = $term->name;
							}
							echo join( ', ', $out );
						}
					?>
				</span>
				
				<span class="button" style="display:none;">Купить билеты</span><span class="price">Стоимость от <?php if($evPrice){echo "$". $evPrice; }?></span></p>
            </div>
            </div>

            </a></li>
			<?php
            }
          } 
		  wp_reset_query();
        ?>
        </ul>
        <ul class="links text-center">
          <li><a href="<?php echo get_permalink(get_page_by_title('Events')); ?>">
          Посмотреть все мероприятия</a></li>
        </ul>
      </div>
      </div>
      </div>
</div>
    </div>
</div>
<div class="container-fluid position">
	  <div class="maple"></div>
</div>
<div class="container position">
   <div class="overflow">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-buffer2">
							<div class="title pull-left">
								<span>Фотогалерея</span>
							</div>
						</div>
						</div>
						<div class="row text-center underconstruction empty">
										<p class="text-center">Данный раздел находится в процессе разработки.<br>
Подпишитесь на нашу еженедельную рассылку и мы оповестим Вас<br> как только он начнет работать!</p>
					</div>
<div class="overflow">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-buffer2">
							<div class="title pull-left">
								<span>Наш инстаграмм</span>
							</div>
						</div>
						</div>
  <div class="instagram col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- <iframe src="http://snapwidget.com/sc/?u=bmFzaHZhbmNvdXZlcnxpbnwxNTB8M3wzfHxub3w1fG5vbmV8b25TdGFydHx5ZXN8bm8=&ve=161215" title="Instagram Widget" class="snapwidget-widget" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; height:150px"></iframe> -->
        <!-- INSTANSIVE WIDGET -->
       <!-- LightWidget WIDGET --><!-- LightWidget WIDGET --><script src="//lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/220642bb43f85a518dfd8d2dcc81df94.html" id="lightwidget_220642bb43" name="lightwidget_220642bb43"  scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width: 100%; border: 0; overflow: hidden;"></iframe>
        <p class="text-center" style="margin-top:160px; font-size:16px;">Для того, чтобы Ваши фотографии отображались в нашей ленте, добавляйте к ним хэштег <strong>#нашванкувер</strong> или <strong>#nashvancouver</strong>.</p>
  </div>
  <div class="clearfix"> </div>
  <div class="overflow">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-buffer2">
							<div class="title pull-left">
								<span>Видеогалерея</span>
							</div>
							<ul class="links pull-right">

								          <li>
								           <?php
								           			if(is_user_logged_in())
								           			{
								           			  echo '<a href="'.admin_url().'post-new.php?post_type=videogallery">Добавить видео</a>';
								           			}
								           			else
								           			{							 
								           			 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);">Добавить видео</a>';
								           			}?>
								          </li>
								          <li>
								                   <a href="<?php echo get_permalink(get_page_by_title('video gallery'));?>">Посмотреть все видео</a>
          </li>
								        </ul>
						</div>
						</div>
  <div class="row videogallery">  
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
  	   <div id="homevgslider">
      <?php
	    $theQry = query_posts(array("post_type"=>"videogallery","post_status"=>"publish","posts_per_page"=>-1));
		$ttVideo = count($theQry);
		
		for($i=0;$i<3;$i++)
		{
			if($i>0) 
			{
			 $moffset = $i*8;
			}
			else
			{
			 $moffset = 0;	
			}
			  query_posts(array("post_type"=>"videogallery","post_status"=>"publish","offset"=>$moffset,"posts_per_page"=>8));
				if(have_posts())
				{
				  echo '<div class="slide-video-item">';
					while(have_posts())
					{
						the_post();
				   ?>
					  <div class="col col-xs-12 col-sm-12 col-md-12 col-lg-3" style="margin-bottom:10px;">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(array(252, 252)); ?>
							<div class="text_block" style="width:84.5%;">
								<h2><?php echo mb_strimwidth(get_the_title(),0,44,'...'); ?></h2>
								<ul class="info">
									<li><span class="glyphicon glyphicon-eye-open"></span><?php echo pvc_get_post_views(get_the_ID()); ?></li>
									<li><?php echo number_format_i18n( get_comments_number( get_the_ID() ) ); ?></li>
								</ul>
							</div>
						</a>
					  </div>
				  <?php
				   }
				   echo '</div>';
				}	
	    } 
	   ?>
   </div>  
     </div>
  </div>
      <!-- <script type="text/javascript" src="https://clickwebstudio.atlassian.net/s/f9ce863b29a28b49dbebac4fb376194e-T/en_US-vb6ats/72000/b6b48b2829824b869586ac216d119363/2.0.12/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&data-wrm-key=com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector&data-wrm-batch-type=resource&collectorId=f4b64d29"></script> -->

</div>

<?php get_footer(); ?>