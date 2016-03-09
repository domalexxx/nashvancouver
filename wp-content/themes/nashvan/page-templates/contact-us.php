<?php 
/* Template Name: Contact Us */
get_header(); ?>

	<div id="main-container" class="container contact-us">
	  <div class="row top-buffer2 ">
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
		  <div class="row content">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			  <?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
			  <?php endwhile; ?>
			</div>
		  </div>
		</div>
		<!-- end col-lg-9 -->
	  </div>
		  <?php get_sidebar(); ?>
	  <!-- end .row -->
	</div>
</div>
<!-- end .container -->
<?php get_footer(); ?>