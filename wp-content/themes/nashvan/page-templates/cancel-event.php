<?php
/**
  * Template Name: Cancel Event Template
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); unset($_SESSION['success_mess']); ?>

<div id="main-container" class="container">
  <div class="row">
    <div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
      <?php while ( have_posts() ) : the_post(); ?>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <h1>
            <?php the_title();?>
          </h1>
        </div>
      </div>
      <div class="row">
        <div class="row empty text-center underconstruction">
          <p style="color:#000;">Вы отменили оплату вашего объявления, но вы можете <a style="color:#306ea9;" href="<?php echo get_permalink(get_page_by_title('Profile')); ?>">перейти в свой профиль</a> чтобы его отредактировать.</p>
        </div>
      </div>
      <?php endwhile; ?>
      <!-- end .row -->
    </div>
    <!-- end col-lg-9 -->
    <?php get_sidebar(); ?>
  </div>
  <!-- end .row -->
</div>
<!-- end .container -->
<?php get_footer(); ?>