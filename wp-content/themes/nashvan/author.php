<?php
get_header(); 
$authID = $author; // Author Id
$current_link = get_author_posts_url($authID,get_userdata($authID)->user_login);

?>
<div id="main-container" class="container mr-single-author-page">
	<div class="row block4 top-buffer2">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
		<div class="overflow">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="title">
					<span>Авторы сайта</span>
				</div>
			</div>
				</div>					
			<div class="row author">
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-4 avatar">
					<?php echo get_avatar($authID,230); ?>
				</div>
				<div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
					<h2><?php echo get_the_author(); ?></h2>
					<p><?php echo get_user_meta($authID,"description",true);?></p>
					<p class="title6">Автор в социальных сетях:</p>
					<?php $sociallinks = get_user_meta($authID,"sociallinks",true); ?>
                    <?php if($sociallinks) { ?>
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
                    <?php } ?>

					<p class="title6">Ссылка на страницу автора:</p>
					<p><a href="<?php echo $current_link; ?>"><em><?php echo $current_link; ?></em></a></p>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
						<a href="mailto:<?php echo get_userdata($authID)->user_email;?>" class="btn btn-primary-2">Отправить сообщение автору</a>
					</div>
				</div>
			</div>
			<div class="wrapper">

				<div class="overflow">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="title">
							<span>Статьи автора</span>
						</div>
						</div>
					</div>
			<div class="article-top myart">
				<div class="wrapper">
            <?php
			  query_posts(array('post_type'=>'post','post_status'=>'publish','order' =>'DESC',
			  'posts_per_page'=>3,'offset'=>0,'author'=>$authID));
			  if(have_posts())
			  {   $i=1;
				  while(have_posts())
				  {
				   the_post();			
                   if($i==1){echo '<div class="col-xs-7 col-sm-7 col-md-7 col-lg-8">';}
				   else{ echo '<div class="col-xs-7 col-sm-7 col-md-7 col-lg-4">';}
				?>
				 <a class="cat" href="<?php the_permalink(); ?>">
                  	<?php
						if(has_post_thumbnail()) {
							if($i==1){ the_post_thumbnail('medium'); } else{ the_post_thumbnail('thumbnail'); }
						} else {
							echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';
						}
					?>
                    <div class="text_block">
						<?php
							if($i==1){ echo '<h1>'.mb_strimwidth(get_the_title(),0,44,'...').'</h1>';}
							else{echo '<h2>'.mb_strimwidth(get_the_title(),0,44,'...').'</h2>';}

							if($i==1) { echo '<p>'.mb_strimwidth(get_the_excerpt(),0,120,'...').'</p>'; }
						?> 
                        <ul class="info">
                            <li><span class="glyphicon glyphicon-eye-open"></span>
							<?php 
								//function Post view Count
								if(function_exists("pvc_get_post_views")) { echo pvc_get_post_views(get_the_ID()); } 
							?>
                            </li>
                            <li><?php echo number_format_i18n(get_comments_number(get_the_ID()));?></li>
                        </ul>
                    </div>
			     </a>
                </div>
			<?php $i++; } } wp_reset_query(); ?>
			</div>
              <?php
			    query_posts(array('post_type'=>'post','post_status'=>'publish','order' =>'DESC', 'posts_per_page'=>6,'offset'=>3,'author'=>$authID));
			   	
				if(have_posts())
			  	{ $flag = 1;  
				  while(have_posts())
				  {
				   the_post();	
			  ?>
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
					<a href="<?php the_permalink();?>">
                    <?php
                    if(has_post_thumbnail())
					 {the_post_thumbnail('thumbnail');}
					 else
					 {echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">';}
                      ?>
						<div class="text_block">
							<h2><?php echo mb_strimwidth(get_the_title(),0,60,'...');?></h2>
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
                                <li><?php echo number_format_i18n(get_comments_number(get_the_ID()));?></li>
                            </ul>
						</div>
						</a>
				</div>
              <?php //if($flag%3 == 0) { echo '<div style="clear:both;"></div>'; }
				  $flag++; } 
			  } wp_reset_query();
			  ?>
			</div>
			                </div>

		<div class="text-center more"><a href="#">Показать еще статьи<img src="<?php bloginfo('template_url')?>/images/arrow_down.png"></a></div>
		<p class="title">Другие авторы сайта</p>
			<div class="row text-center other_authors">
            <?php
				$getauthors = $wpdb->get_col("SELECT post_author FROM $wpdb->posts WHERE 1 AND post_status = 'publish' AND post_type = 'post' Group by post_author");
		  		$usrArgs = array('include'=>$getauthors);
		 		$user_query = new WP_User_Query($usrArgs);

				// User Loop
				if(!empty($user_query->results))
				 {
					foreach($user_query->results as $user) 
					{ 
					  $auth_link = get_author_posts_url($user->ID,$user->user_login);
						?>
                         <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 avatar">
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
				<!--<div class="clear text-center more"><a href="#">Показать еще авторов<img src="<?php //bloginfo('template_url')?>/images/arrow_down.png"></a></div>-->
			</div>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>