<?php
/**
 * Template Name: Submit New Article Page Template
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

if(!is_user_logged_in())
{ wp_redirect(home_url()); }

get_header(); ?>

<div id="main-container" class="container">
  <div class="row">
    <div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
      <div class="row">
        <?php get_sidebar('left'); ?>
        <!-- end .left_menu -->
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-9">
		  <?php while ( have_posts() ) : the_post(); ?>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <h1><?php the_title(); ?></h1>
            </div>
            <div class="text col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <?php the_content(); ?>
            </div>
			
			<div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php if ( is_user_logged_in() ) { ?>
					<a href="<?php echo admin_url(); ?>post-new.php" class="btn btn-danger" type="button">Submit a New Article</a>
				<?php } else { ?>
					<a href="javascript:void(0);" class="btn btn-danger" data-toggle="modal" data-target="#login" type="button">Submit a New Article</a>
				<?php } ?>
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
<?php get_footer(); ?>