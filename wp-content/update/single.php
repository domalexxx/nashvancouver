<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); global $post; ?>

<div class="container">
  <div class="row">
    <div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
      <div class="single-posts-page">
	  	<?php
			$args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'offset' => 0, 'order' => 'DESC', 'post__not_in' => array( $post->ID ));
			$my_query = null;
			$my_query = new WP_Query($args);
			
			if($my_query->have_posts()) {
					
				while ($my_query->have_posts()) : $my_query->the_post();
					
					echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
						echo '<a href="'.get_permalink( get_the_ID() ).'">';
						
							the_post_thumbnail( 'thumbnail' );
							echo '<div class="text_block">';
								echo '<h2>'.mb_strimwidth(get_the_title(),0,44,'...').'</h2>';
								echo '<ul class="info">';
								echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';
								echo '<li>'. number_format_i18n( get_comments_number( get_the_ID() ) ) .'</li>';
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
        <?php get_sidebar('left'); ?>
        <!-- end .left_menu -->
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-9">
		  <?php while ( have_posts() ) : the_post(); 
		    $authId = get_the_author_ID();
		    $user_info = get_userdata($authId);
            $auth_link = get_author_posts_url($authId,$authId->user_name)
		  ?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <h1><?php the_title();?></h1>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 avatar">
            <?php echo get_avatar($authId,66);?> 
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
              <p class="name pull-left">
              <a href="<?php echo $auth_link; ?>">
			   <?php echo $user_info->display_name; ?>
              </a>
              </p>
               <?php $sociallinks = get_user_meta($user_info->ID,"sociallinks",true);	?>
              <ul class="single-social pull-left">
			  	<?php if($sociallinks['dribble']) { ?><li class="dribble"><a target="_blank" href="<?php echo $sociallinks['dribble']; ?>">Dribble</a></li><?php } ?>
                <?php if($sociallinks['linkedin']) { ?><li class="linkedin"><a target="_blank" href="<?php echo $sociallinks['linkedin']; ?>">LinkedIn</a></li><?php } ?>
                <?php if($sociallinks['facebook']) { ?><li class="facebook"><a target="_blank" href="<?php echo $sociallinks['facebook']; ?>">Facebook</a></li><?php } ?>
                <?php if($sociallinks['tumblr']) { ?><li class="tumblr"><a target="_blank" href="<?php echo $sociallinks['tumblr']; ?>">Tumblr</a></li><?php } ?>
                <?php if($sociallinks['twitter']) { ?><li class="twitter"><a target="_blank" href="<?php echo $sociallinks['twitter']; ?>" >Twitter</a></li><?php } ?>
              </ul>
              <p class="clear"><?php the_category(); ?></p>
            </div>
            <div class="text-right col-xs-4 col-sm-4 col-md-4 col-lg-3"> <span class="date"><?php the_time('d F Y'); ?></span> </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <hr>
            </div>
          </div>
          <div class="row">
            <div class="text col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <?php the_content(); ?>
              <div class="commentbox-outer">
                <?php comments_template( '', true ); ?>
              </div>
            </div>
           
          </div>
		  <?php endwhile; ?>
        </div>
      </div>
      <!-- end .row -->
    </div>
    <!-- end col-lg-9 -->
    <?php get_sidebar(); ?>
  </div>
  <!-- end .row -->
</div>
<!-- end .container -->
<?php get_footer(); ?>