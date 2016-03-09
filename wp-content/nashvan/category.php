<?php
/**
 * The template for displaying Category pages
 *
 * Used to display archive-type pages for posts in a category.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); $queried_object = get_queried_object(); ?>

<div class="container category">
	<div class="row">
    	<div class="left_category">
		  <div class="category_columns">			
			<div class="left_column col-xs-6 col-sm-6 col-md-6 col-lg-6">
			  <p class="title"><?php echo $queried_object->name; ?></p>
				<?php
					$args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 1, 'offset' => 0, 'order' => 'DESC', 'tax_query' => array( array( 'taxonomy' => 'category', 'field' => 'id', 'terms' => array($queried_object->term_id) ) ));
					$my_query = null;
					$my_query = new WP_Query($args);
			
					if( $my_query->have_posts() ) {
						
						while ($my_query->have_posts()) : $my_query->the_post();

							echo '<a class="cat" href="'.get_permalink( get_the_ID() ).'">';
							the_post_thumbnail( 'medium' );
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
			  <ul class="links"><li><a href="<?php echo get_permalink( get_page_by_title("Добавить новую статью") ); ?>">Добавить новость</a></li></ul>
			  <?php
					$args1 = array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 2, 'offset' => 1, 'order' => 'DESC', 'tax_query' => array( array( 'taxonomy' => 'category', 'field' => 'id', 'terms' => array($queried_object->term_id) ) ));
					$my_query = null;
					$my_query = new WP_Query($args1);
			
					if( $my_query->have_posts() ) {
						
						echo '<div class="col col-xs-6 col-sm-6 col-md-6 col-lg-6">';
						while ($my_query->have_posts()) : $my_query->the_post();
							
							echo '<a href="'.get_permalink( get_the_ID() ).'">';
							
								the_post_thumbnail( 'thumbnail' );
								echo '<div class="text_block">';
									echo '<h2>'. mb_strimwidth(get_the_title(),0,44,'...').'</h2>';
									echo '<ul class="info">';
										echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';
										echo '<li>'. number_format_i18n( get_comments_number( get_the_ID() ) ) .'</li>';
									echo '</ul>';
								echo '</div>';

							echo '</a>';
						
						endwhile;
						echo '</div>';
					}
					wp_reset_query();
				?>
			</div>
			<div class="clear"></div>

			<?php
				$args1 = array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'offset' =>3, 'order' => 'DESC', 'tax_query' => array( array( 'taxonomy' => 'category', 'field' => 'id', 'terms' => array($queried_object->term_id) ) ));
				$my_query = null;
				$my_query = new WP_Query($args1);
		
				if( $my_query->have_posts() ) {
					
					echo '<div class="col more-category-container">';
					while ($my_query->have_posts()) : $my_query->the_post();
						
						echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
							echo '<a href="'.get_permalink( get_the_ID() ).'">';
							
								the_post_thumbnail( 'thumbnail' );
								echo '<div class="text_block">';
									echo '<h2>'. mb_strimwidth(get_the_title(),0,44,'...') .'</h2>';
									echo '<ul class="info">';
										echo '<li><span class="glyphicon glyphicon-eye-open"></span>'.pvc_get_post_views(get_the_ID()).'</li>';
										echo '<li>'. number_format_i18n( get_comments_number( get_the_ID() ) ) .'</li>';
									echo '</ul>';
								echo '</div>';
	
							echo '</a>';
						echo '</div>';

					endwhile;
					echo '<div class="clear"></div>';
					echo '</div>';
					
					
				}
				wp_reset_query();
			?>
                 <div class="clear text-center more">
                    <a class="mr-more-article-btn"  
                    data-cat-id="<?php echo $queried_object->term_id;?>" id="mr-more-article-btn" href="#">
                    Показать еще статьи<img src="<?php bloginfo('template_url');?>/images/arrow_down.png"></a>
                </div>
		  </div>
          
    </div>

    <?php get_sidebar(); ?>
  </div>
</div>
<script type="text/javascript">
// Code for load more posts
 jQuery(document).ready(function()
 {
	var pageno =3;
	
	jQuery(".mr-more-article-btn").click(function(event)
	{
			
		 var catid = jQuery(this).attr('data-cat-id');
		 
	     event.preventDefault();		
	
	  //jQuery(".post-loder").show();
	  jQuery.ajax({
		  url: "<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?noofarticle="+pageno+"&catid="+catid, 
		  method: "POST",
		  dataType: "html",
		  success: function(result)
	      {			
		   // jQuery(".post-loder").hide();
			if(jQuery(".more-category-container").children('.No-moreposts').length>0)
			{		
				jQuery('#mr-more-article-btn').hide();			
			}
			else
			{
			 jQuery(result).appendTo(".more-category-container").show(2000);	
			}
	      }
	 });
	  pageno++;  
	});
	
 });
</script>
<?php get_footer(); ?>