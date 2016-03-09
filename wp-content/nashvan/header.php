<?php
session_start();
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	
   <script src="<?php echo get_template_directory_uri();?>/js/jquery.min.js"></script>
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>tinymce.init({selector:'#classified #inputDescription',menubar:false,
      plugins: ['','',''],
      toolbar: 'styleselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent'
      });
	  tinymce.init({selector:'.author-personal-page #author-info',menubar:false,
      plugins: ['','',''],
      toolbar: 'styleselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent'
      });
    </script>
   <link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/dropzone.css" />
   <link href="<?php echo get_template_directory_uri();?>/css/owl.carousel.css" rel="stylesheet">
<?php wp_head(); ?>
  <link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/bootstrap-select.css" type="text/css" media="screen" charset="utf-8" />
  <script src="<?php bloginfo('template_url') ?>/js/bootstrap-select.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body <?php body_class(); ?>>
<?php include("top-header.php"); ?>
<?php global $current_user;?>
<header>
  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
	    <?php wp_nav_menu( array( 'theme_location' => 'top-menu', 'menu_class' => 'nav navbar-nav' ) ); ?>
        <form class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input name="search" type="text" class="search form-control" placeholder="поиск по сайту...">
          </div>
        </form>
        <ul class="nav navbar-nav navbar-login">
			<?php if ( is_user_logged_in() ) 
			{
			 $current_link = get_author_posts_url($current_user->ID,$current_user->user_name);
			?>
			        <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $current_user->display_name; ?> <span class="caret"></span></a>
					<ul class="dropdown-menu">
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>">Профайл</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>/#adv">Объявления</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>/#article">Статьи</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>/#business">Бизнесы</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>/#fotogallery">Фотогалереи</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>/#videogallery">Видеогалереи</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>/#events">Мероприятия</a></li>
					  <li class="divider"></li>
					  <li><a href="<?php echo wp_logout_url( get_option('siteurl') ); ?>">Выход</a></li>
					</ul>
				  </li>
			     <!--	<li><a href="<?php echo wp_logout_url( get_option('siteurl') ); ?>">Logout</a></li>-->
			<?php } 
			else { ?>
				<li><a title="Login" href="javascript:void(0);" data-toggle="modal" data-target="#login">Логин</a></li>
				<li>|</li>
				<li><a title="Registration" href="javascript:void(0);" data-toggle="modal" data-target="#registration">Регистрация</a></li>
			<?php } ?>
        </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-xs-3 col-sm-4 col-md-4 col-lg-4 pull-left">
        <?php if ( get_header_image() ) : ?>
        	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>" class="header-image" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
        <?php endif; ?>
      </div>
	  <?php if ( is_active_sidebar( 'sidebar-top-head' ) ) : ?>
	  		<div class="col-xs-9 col-sm-8 col-md-8 col-lg-8 pull-right">
				<?php dynamic_sidebar( 'sidebar-top-head' ); ?>
			</div>
	  <?php endif; ?>
    </div>
  </div>
  <nav class="navbar navbar-main">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2" aria-expanded="false" aria-controls="navbar2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div class="data pull-left">
        <p><?php echo current_time( 'd' ); ?></p>
        <p><?php echo the_russian_time( current_time( 'F' ) ); ?></p>
        <p><?php echo current_time( 'g:i A' ); ?></p>
      </div>
      <div id="navbar2" class="collapse navbar-collapse">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav navbar-nav' ) ); ?>
      </div>
      <!--/.nav-collapse -->
    </div>
  </nav>
</header>