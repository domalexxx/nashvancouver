<?php 
/* Template Name: All Blogi Templates */
 ?>
<?php get_header(); ?>

<div id="main-container" class="container all-news">
  <div class="row" style="margin-top:15px;">
  	<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9 padding-right-empty">
	  	<p class="title">Авторы сайта</p>
		<div class="row text-center other_authors">
		<?php
		  $getauthors = $wpdb->get_col("SELECT post_author FROM $wpdb->posts WHERE 1 AND post_status = 'publish' AND post_type = 'post' Group by post_author");
		  //$usrArgs= array('role'=>'Author','include'=>$getauthors);
		  $usrArgs= array('include'=>$getauthors);
		  $user_query = new WP_User_Query($usrArgs);
			// User Loop
			if(!empty($user_query->results))
			 {
				foreach($user_query->results as $user) 
				{ 
				  $auth_link = get_author_posts_url($user->ID,$user->user_login);
					?>
					 <div class="col-xs-12 col-sm-5 col-md-3 col-lg-3 avatar">
						<a class="text-center" href="<?php echo $auth_link; ?>">
							<?php echo $user->display_name; ?>
						   <?php echo get_avatar($user->ID,168); ?>
							<p>Всего статей на сайте</p>
							<p><?php echo count_user_posts($user->ID,'post'); ?></p>
						</a> 
					</div>
				  <?php
				}
			 }
			else
			{
				echo 'No users found.';
			}
		?>
			<!--<div class="clear text-center more"><a href="#">Показать еще статьи<img src="<?php bloginfo('template_url')?>/images/arrow_down.png"></a></div>-->
		</div>
	  </div>
	  <?php get_sidebar(); ?>
  </div>
</div>
<?php get_footer(); ?>