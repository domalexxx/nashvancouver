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



<div class="container">

  <div class="row mr-home-firstblock">	

	<div class="left_column col-xs-6 col-sm-6 col-md-6 col-lg-6">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<span>Последние новости</span>
			</div>
		</div>

		<?php

			$args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 1, 'offset' => 0, 'order' => 'DESC');

			$my_query = null;

			$my_query = new WP_Query($args);



			if( $my_query->have_posts() ) {



				while ($my_query->have_posts()) : $my_query->the_post();



					echo '<a href="'.get_permalink( get_the_ID() ).'">';

					the_post_thumbnail([522,true] );

					echo '</a>';


					echo '<a href="'.get_permalink( get_the_ID() ).'">';
					echo '<div class="text_block">';

					echo '<h1>'. mb_strimwidth(get_the_title(),0,44,'...').'</h1>';

					echo '<p>'.get_the_excerpt().'</p>';
				
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

	

    <div class="right_column col-xs-6 col-sm-6 col-md-6 col-lg-6">

	  <div class="row">        

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
           <a href="<?php echo get_permalink( get_page_by_title("Посмотреть все новости") ); ?>">Посмотреть все новости</a>          </li>
        </ul>

		<?php

			$args1 = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 2, 'offset' => 1, 'order' => 'DESC');

			$my_query = null;

			$my_query = new WP_Query($args1);



			if( $my_query->have_posts() ) {



				while ($my_query->have_posts()) : $my_query->the_post();



					echo '<div class="col col-xs-6 col-sm-6 col-md-6 col-lg-6">';

						echo '<a href="'.get_permalink( get_the_ID() ).'">';
						the_post_thumbnail( [252,true] );
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

					

					echo '<div class="col col-xs-6 col-sm-6 col-md-6 col-lg-6">';
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

<div class="bg-primary block2 text-center">

  <p class="title2">Следите за нами в социальных сетях</p>

</div>

<div class="container">
  <div class="left_column col-xs-6 col-sm-6 col-md-6 col-lg-6">
   <p class="title">Последние объявления</p>
  </div> 
  <div class="right_column col-xs-6 col-sm-6 col-md-6 col-lg-6">
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
 <div class="clearfix"></div>  
  <div class="row adv mradv-slider">
  <?php
     query_posts(array('post_type'=>'classified','post_status'=>'publish','posts_per_page'=>'10'));
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

    <div class="row">

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center"> <span class="title">
      Русскоязычные бизнесы Ванкувера</span>
        <ul class="links">
          <li><!--<a href="#">Разместить свой бизнес</a>-->
           <?php
			  if(is_user_logged_in())
			  {
			  echo '<a href="'.get_permalink(get_page_by_title('Business Directory Add')).'">Разместить свой бизнес</a>';
			  }
			 else
			  {							 
			    echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);"
				class="pull-right">Разместить свой бизнес</a>';
			  }
		    ?>
          </li>
        </ul>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 banner">
        <!--<img src="http://dummyimage.com/500x85/4d494d/686a82.gif&text=BANNER 500X85" alt="BANNER 500X85">-->
          <?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
          <?php endif; ?>
         </div>
        </div>

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center"> 
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
			 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);"
			 class="pull-right">Разместить мероприятие</a>';
			}?>
         <!--<a href="#">Разместить мероприятие</a>--></li>
        </ul>

        <div class="banner">
        <?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-5' ); ?>
        <?php endif; ?>
        </div>
      </div>

    </div>

    <div class="row">

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
         <?php
			 //Category list 
			$taxonomy ='bussiness_cat';
			$CatQryarg = array('parent'=>0,'number'=>10); 							
			$businessTerms = get_terms($taxonomy ,$CatQryarg);
			if(is_array($businessTerms))
			{
			echo '<ul class="catalog">';
			$i=1;
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
				<li>
				 <a class="auto" style="background:url(<?php echo $catImgurl; ?>)" 
				   href="<?php echo get_term_link(intval($catID),$taxonomy);?>">
				   <?php echo $catName;?>
				 </a>
				</li>
			   <?php
			   
			 }	
			echo '</ul>';	
			}
		  ?>
        <ul class="links text-center">

          <li><a href="<?php echo get_permalink(get_page_by_title('Business Directory' )); ?>">
           Посмотреть весь каталог бизнесов</a>
          </li>
          

        </ul>

      </div>

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

        <ul class="events">
		  <?php
			query_posts(array('post_type'=>'event','post_status'=>'publish','posts_per_page'=>'4'));
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
            <div class="event text-center">
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
			  <?php 
			      
			      $rb_event_time = get_post_meta ($eventId, 'event_start_time', true ); 
				  $rb_event_date = get_post_meta($eventId,"event_date",true);
				  if(!empty($rb_event_date))
				  {
				    $evntDate = $rb_event_date."/".get_the_time('Y');
				    $evntYear = date('Y',strtotime($rb_event_date."/".get_the_time('Y'))); 
				    echo '<p>'.$evntDate.'</p>';
				  }
			    ?>
			  <p>
			   <?php
			    echo $rb_event_date; ?>
			  </p>             
              <p> <?php  if (!empty($rb_event_time)){echo date("h:i:a",$rb_event_time); } ?></p>
			  <div class="mr-trans-cover"></div>
            </div>

            <div>
              <div class="bg-info">
                <h4>"<?php the_title(); ?>"</h4>
                <p><?php echo get_excerpt();?></p>
                <p class="location"><?php echo $evLocation['address']; ?>, OR</p>
              </div>
              <p class="bg-primary"><span>Спектакль</span><span class="button">Купить билеты</span><span class="price">Стоимость от <?php if($evPrice){echo "$". $evPrice; }?></span></p>
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

<div class="container position">
  <div class="maple"></div>
  <div class="row title">
    <span>Фотогалерея</span>
    <div class="pull-right">
        <a href="#" class="back">Добавить фото</a>
        <a href="#" class="pull-right">Посмотреть все альбомы</a>
    </div>
  </div>
  <div class="row fotogallery">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
      <img src="<?php bloginfo('template_url')?>/images/foto_main.jpg" />
      <div class="text_block">
        <h5 class="title4">Концерт Группы “БИ 2”</h5>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
      </div>
      <div class="row">
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="#"><img src="<?php bloginfo('template_url')?>/images/slide1.jpg"></a>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="#"><img src="<?php bloginfo('template_url')?>/images/slide2.jpg"></a>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="#"><img src="<?php bloginfo('template_url')?>/images/slide1.jpg"></a>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="#"><img src="<?php bloginfo('template_url')?>/images/slide2.jpg"></a>
          </div>
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <img src="<?php bloginfo('template_url')?>/images/foto_main.jpg" />
        <div class="text_block"> 
        <h5 class="title4">Концерт Группы “БИ 2”</h5>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
      </div>
      <div class="row">
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="#"><img src="<?php bloginfo('template_url')?>/images/slide1.jpg"></a>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="#"><img src="<?php bloginfo('template_url')?>/images/slide2.jpg"></a>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="#"><img src="<?php bloginfo('template_url')?>/images/slide1.jpg"></a>
          </div>
          <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="#"><img src="<?php bloginfo('template_url')?>/images/slide2.jpg"></a>
          </div>
        </div>
    </div>
  </div>
<div class="row title">
    <span>Наш инстаграмм</span>
</div>
  <div class="instagram col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <iframe src="http://snapwidget.com/sc/?u=bmFzaHZhbmNvdXZlcnxpbnwxNTB8M3wzfHxub3w1fG5vbmV8b25TdGFydHx5ZXN8bm8=&ve=161215" title="Instagram Widget" class="snapwidget-widget" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:1074px; height:150px"></iframe>
        <p class="text-center">Если хотите, чтобы Ваши фотографии размещались в нашей инстаграм ленте, то добавляйте к ним хэштэг #nashvancouver</p>
  </div>
  <div class="clearfix"> </div>
  <div class="row title">
    <span>Видеогалерея</span>
    <div class="pull-right">
        <!--<a href="#" class="back">Добавить видео</a>-->
        <?php
			if(is_user_logged_in())
			{
			  echo '<aclass="back"  href="'.admin_url().'post-new.php?post_type=videogallery">Добавить видео</a>';
			}
			else
			{							 
			 echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);"
			 class="back">Добавить видео</a>';
			}?>
        <a href="<?php echo get_permalink(get_page_by_title('video gallery'));?>" class="pull-right">Посмотреть все видео</a>
    </div>
  </div>
  <div class="row videogallery">  
   <div id="homevgslider">
      <?php
	    $theQry = query_posts(array("post_type"=>"videogallery","post_status"=>"publish","posts_per_page"=>-1));
		$ttVideo = count($theQry);
		
		for($i=0;$i<3;$i++)
		{
			if($i>0) 
			{
			 $moffset = $i*6;
			}
			else
			{
			 $moffset = 1;	
			}
			  query_posts(array("post_type"=>"videogallery","post_status"=>"publish","offset"=>$moffset,"posts_per_page"=>6));
				if(have_posts())
				{
				  echo '<div class="slide-video-item">';
					while(have_posts())
					{
						the_post();
				   ?>         
					  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
					   <a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail();?>
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

<?php get_footer(); ?>