<?php 
/* Template Name: All News Templates */
 ?>
<?php get_header(); ?>

<div class="container all-news">
  <div class="row">
	<?php get_sidebar(); ?>
	  
	  <?php $categories = get_terms( 'category' ); $i=1; ?>
	  <?php foreach($categories as $category) { ?>
	  
		  <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="mr-catpost-outer" id="mr-catpost-outer-<?php echo $i; ?>">
		   		
				<div class="row">
			   		<div class="title col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<span><?php echo $category->name; ?></span>
						<?php if ( is_user_logged_in() ) { ?>
							<a href="<?php echo admin_url(); ?>post-new.php" class="pull-right">Добавить новость</a>
						<?php } else { ?>
							<a href="javascript:void(0);" data-toggle="modal" data-target="#login" class="pull-right" type="button">Добавить новость</a>
						<?php } ?>
					</div>
				</div>
				  
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
						<?php
							$args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 1, 'offset' => 0, 'order' => 'DESC', 'tax_query' => array( array( 'taxonomy' => 'category', 'field' => 'id', 'terms' => array($category->term_id) ) ));
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
					
					<?php
						/* Next two posts */
						$args1 = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' =>2, 'offset' =>1, 'order' => 'DESC','tax_query' => array(array('taxonomy'=>'category','field' =>'id','terms'=>array($category->term_id))));
						$my_query = null;
						$my_query = new WP_Query($args1);
				
						if( $my_query->have_posts() ) {
							
							while ($my_query->have_posts()) : $my_query->the_post();

								echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
									echo '<a href="'.get_permalink( get_the_ID() ).'" class="cat">';
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
						}
						wp_reset_query();
					?>
				</div>
				  
				<div class="row">
					<?php
						/* Next three posts */
						$args2 = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' =>3, 'offset' =>3, 'order' => 'DESC','tax_query' => array(array('taxonomy'=>'category','field' =>'id','terms'=>array($category->term_id))));
						$my_query = null;
						$my_query = new WP_Query($args2);
				
						if( $my_query->have_posts() ) {
							
							while ($my_query->have_posts()) : $my_query->the_post();

								echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">';
									echo '<a href="'.get_permalink( get_the_ID() ).'" class="cat">';
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
						}
						wp_reset_query();
					?>
				</div>
				  
				<!--Load More Articles -->
				<div class="mr-morepost-wrap"></div>
				
				<div class="clear text-center more">
					<a class="mr-more-article-btn" data-block-id="<?php echo $i; ?>" data-cat-id="<?php echo $category->term_id;?>" id="mr-more-article-btn-<?php echo $i; ?>" href="#">
					Показать еще статьи<img src="<?php bloginfo('template_url')?>/images/arrow_down.png"></a>
				</div>
			</div>
		   </div>

	  <?php $i++; } ?>

  </div>
</div>
<script type="text/javascript">
// Code for load more posts
 jQuery(document).ready(function()
 {
	var pageno =3;
	var mrartblock ="";
	jQuery(".mr-more-article-btn").click(function(event)
	{
			
		 var catid = jQuery(this).attr('data-cat-id');
		 var blockid = jQuery(this).attr('data-block-id');
	     event.preventDefault();
		  
		 if(blockid==mrartblock)
		 {
		  mrartblock =blockid;
		 }
		 else
		 {
		   mrartblock =blockid;
		   pageno =3;	  
		 }
	
	  //jQuery(".post-loder").show();
	  jQuery.ajax({
		  url: "<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?noofarticle="+pageno+"&catid="+catid, 
		  method: "POST",
		  dataType: "html",
		  success: function(result)
	      {	
		
		   // jQuery(".post-loder").hide();
			if(jQuery("#mr-catpost-outer-"+blockid+" .mr-morepost-wrap").children('.No-moreposts').length>0)
			{		
				jQuery('#mr-more-article-btn-'+blockid).hide();			
			}
			else
			{
			 jQuery(result).appendTo("#mr-catpost-outer-"+blockid+" .mr-morepost-wrap").show(2000);	
			}
	      }
	 });
	  pageno++;  
	});
	
 });
</script>
<?php get_footer(); ?>