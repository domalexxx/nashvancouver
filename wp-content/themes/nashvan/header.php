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
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	
   <script src="<?php echo get_template_directory_uri();?>/js/jquery.min.js"></script>
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script src="//instansive.com/widget/js/instansive.js"></script>
    <script>
	  tinymce.init({
		  selector:'#classified #inputDescription',
		  content_css : "<?php bloginfo('template_url');?>/css/tinymce-editor.css",   
		  menubar:false,
		  onchange_callback: function(editor) {
			  tinyMCE.triggerSave();
			  $("#" + editor.id).valid();
		  },
		  toolbar: 'styleselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent'
      });
	  
	   tinymce.init({
			selector:'#classified #eventInputDescription',
			content_css : "<?php bloginfo('template_url');?>/css/tinymce-editor.css",   
			menubar:false,
			onchange_callback: function(editor) {
				tinyMCE.triggerSave();
				$("#" + editor.id).valid();
			},
			toolbar: 'styleselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent'
      });

		tinymce.init({
			selector:'#classified #businessInputDescription',
			content_css : "<?php bloginfo('template_url');?>/css/tinymce-editor.css",   
			menubar:false,
			onchange_callback: function(editor) {
				tinyMCE.triggerSave();
				$("#" + editor.id).valid();
			},
			toolbar: 'styleselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent'
		});

	  tinymce.init({
		  selector:'.author-personal-page #author-info',
		  content_css : "<?php bloginfo('template_url');?>/css/tinymce-editor.css",   
		  menubar:false,
		  plugins: ['','',''],
		  toolbar: 'styleselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent'
      });
    </script>
    <!-- Enable to go to tabs in URL -->
      <script type="text/javascript">
        jQuery(function() {
          // Javascript to enable link to tab
          var hash = document.location.hash;
          if (hash) {
            jQuery('.nav-pills a[href='+hash+']').tab('show');
          }
          jQuery('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            window.location.hash = e.target.hash;
          });
        });
    </script>
   <script type="text/javascript">
<!--
if (screen.width <= 800) {
document.location = "http://nashvancouver.com/wp-content/themes/nashvan/mobile/index.php";
}
//-->
</script>
   <link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/dropzone.css" />
   <link href="<?php echo get_template_directory_uri();?>/css/owl.carousel.css" rel="stylesheet">
<?php wp_head(); ?>
  <link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/bootstrap-select.css" type="text/css" media="screen" charset="utf-8" />
  <link href='https://fonts.googleapis.com/css?family=PT+Serif:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
  <script src="<?php bloginfo('template_url') ?>/js/bootstrap-select.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body <?php body_class(); ?>>

<?php include("top-header.php"); ?>
<?php global $current_user;?>
<header>
  <nav id="navprimaryheader" class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
	    <?php wp_nav_menu( array( 'theme_location' => 'top-menu', 'menu_class' => 'nav navbar-nav' ) ); ?>
        <form class="navbar-form navbar-left" name="search-form" method="get" action="<?php echo get_permalink(get_page_by_title('Search')); ?>" role="search">
          <div class="form-group">
            <input name="search" type="text" class="search form-control" value="<?php if($_GET['search']) { echo $_GET['search']; } ?>" placeholder="поиск по сайту...">
          </div>
        </form>
        <ul class="nav navbar-nav navbar-login">
			<?php if ( is_user_logged_in() ) 
			{
			 $current_link = get_author_posts_url($current_user->ID,$current_user->user_name);
			?>
			        <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $current_user->display_name; ?> <span class="caret"></span></a>
					<ul class="dropdown-menu">
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>">Профиль</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>#adv">Объявления</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>#article">Статьи</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>#business">Бизнесы</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>#fotogallery">Фотогалереи</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>#videogallery">Видеогалереи</a></li>
					  <li><a href="<?php echo get_permalink(get_page_by_title('Profile')); ?>#events">Мероприятия</a></li>
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
  <div id="logo-container" class="container">
   <div class="data pull-left">
        <p><?php echo current_time( 'd' ); ?></p>
        <p><?php echo the_russian_time( current_time( 'F' ) ); ?></p>
        <p><?php echo current_time( 'g:i A' ); ?></p>
      </div>
    <div class="row">
      <div class="col-xs-7 col-sm-4 col-md-4 col-lg-5 pull-left header-image">
        <?php if ( get_header_image() ) : ?>
        	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pull-left"><img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
        <?php endif; ?>
        <span class="text-center">Ваш главный источник<br>
информации о жизни<br>
в Британской Колумбии!
</span>
      </div>
	  <?php if ( is_active_sidebar( 'sidebar-top-head' ) ) : ?>
	  		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-7 pull-right banner-header">
				<?php dynamic_sidebar( 'sidebar-top-head' ); ?>
			</div>
	  <?php endif; ?>
    </div>
  </div>
  <nav id="navsecondaryheader" class="navbar navbar-main navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2" aria-expanded="false" aria-controls="navbar2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
     <!--  <div class="data pull-left">
        <p><?php echo current_time( 'd' ); ?></p>
        <p><?php echo the_russian_time( current_time( 'F' ) ); ?></p>
        <p><?php echo current_time( 'g:i A' ); ?></p>
      </div> -->
      <div id="navbar2" class="collapse navbar-collapse">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav navbar-nav' ) ); ?>
      </div>
      <!--/.nav-collapse -->
    </div>
  </nav>
</header>