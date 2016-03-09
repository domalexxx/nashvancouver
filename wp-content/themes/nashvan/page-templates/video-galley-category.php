<?php  /* Template Name: Video Gallery Category */ ?>
<?php get_header(); global $post; ?>

<div id="main-container" class="container classified-inside videogallery-inside">
  <div class="row top-buffer2">
    <div class="col-xs-9 col-sm-12 col-md-9 col-lg-9">

      <div class="mr-video-gallery-outer" id="mr-videopost-outer-allpost">

	  	<?php $categories = get_terms( 'video_cat', array('include' => get_post_meta( $post->ID, 'select_video_category', true)) ); $i=1; ?>
	  	<?php foreach($categories as $category) { ?>
			
			<div class="mr-video-gallery-outer" id="mr-videopost-outer-<?php echo $i; ?>">

				<div class="overflow">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="title pull-left"><span><?php echo $category->name; ?></span></div>
						<ul class="links text-right pull-right">
							<li>
							<?php
								if(is_user_logged_in()) {
									echo '<a class="pull-right" href="'.admin_url().'post-new.php?post_type=videogallery">Добавить новое</a>';
								} else {							 
									echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить видео</a>';
								}
							?>
							</li>
						</ul>
					</div>
				</div>

				<div class="row article-top myart">
					<div class="mr-video-gallery-out wrapper"> 
						<?php								
							$args = array( 'post_type' => 'videogallery', 'post_status' => 'publish', 'posts_per_page' => 4, 'offset' => 0, 'order' => 'DESC', 'tax_query' => array( array( 'taxonomy' => 'video_cat', 'field' => 'id', 'terms' => array($category->term_id) ) ));
							$my_query = null;
							$my_query = new WP_Query($args);

							if( $my_query->have_posts() ) {
								
								while ($my_query->have_posts()) : $my_query->the_post();
		
									$videoId = get_the_ID(); ?>

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
							<?php			
								endwhile;
							}
							wp_reset_query();
						?>
						<div class="clearfix"></div>
					</div>
					<div class="mr-video-gallery-out wrapper mr-morevideo-wrap"></div>
					<div class="clear text-center more">
						<a class="mr-more-article-btn" data-block-id="<?php echo $i; ?>" data-cat-id="<?php echo $category->term_id;?>" id="mr-more-article-btn-<?php echo $i; ?>" href="#">Показать еще статьи<img src="<?php bloginfo('template_url')?>/images/arrow_down.png"></a>
					</div>
				</div>
			</div>
		<?php $i++; } ?>
      </div>
    </div>
    <!-- end .row -->
    <?php get_sidebar(); ?>
  </div>
  <!-- end col-lg-9 -->
</div>
<!-- end .row -->
</div>
<!-- end .container -->
<script type="text/javascript">
// Code for load more posts
jQuery(document).ready(function() {

	var pageno = 2;
	var mrartblock = "";
	jQuery(".mr-more-article-btn").click(function(event) {
			
		var catid = jQuery(this).attr('data-cat-id');
		var blockid = jQuery(this).attr('data-block-id');
		event.preventDefault();
		
		if(blockid==mrartblock)
		{ mrartblock = blockid; }
		else {
			mrartblock =blockid;
			pageno = 2;	  
		}
		
		jQuery.ajax({
			url: "<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?noofvideos="+pageno+"&catid="+catid, 
			method: "POST",
			dataType: "html",
			success: function(result) {
				
				if(jQuery("#mr-videopost-outer-"+blockid+" .mr-morevideo-wrap").children('.No-moreposts').length > 0) {
					jQuery('#mr-more-article-btn-'+blockid).hide();			
				} else {
					jQuery(result).appendTo("#mr-videopost-outer-"+blockid+" .mr-morevideo-wrap").show(2000);	
				}
			}
		});
		pageno++;  
	});
});
</script>
<?php get_footer(); ?>
