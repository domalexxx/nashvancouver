<?php
/**
 * Nashvan functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development and
 * https://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link https://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Nashvan
 * @since Nashvan 1.0
 */

// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 1074;
	
/**
 * Nashvan custom header.
 *
**/

function nashvan_custom_header_setup() {
	$args = array(
		// Text color and image (empty to use none).
		'default-text-color'     => 'ffffff',
		'default-image' 		 => get_template_directory_uri() . '/images/logo.png',
		'uploads'       		 => true,

		// Set height and width, with a maximum value for the width.
		'height'                 => 100,
		'width'                  => 200,
		'max-width'              => 1047,

		// Support flexible height and width.
		'flex-height'            => true,
		'flex-width'             => true,

		// Random image rotation off by default.
		'random-default'         => false
	);

	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'nashvan_custom_header_setup' );

/**
 * Nashvan setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Nashvan supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Nashvan 1.0
 */
function nashvan_setup() {
	/*
	 * Makes Nashvan available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Nashvan, use a find and replace
	 * to change 'nashvan' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'nashvan', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'nashvan' ) );
	register_nav_menu( 'top-menu', __( 'Top Menu', 'nashvan' ) );
	// Author menu
	register_nav_menu( 'author-menu', __( 'Author Menu', 'nashvan' ) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop

	add_image_size( 'crop', 522, 320 );
}
add_action( 'after_setup_theme', 'nashvan_setup' );

/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since Nashvan 1.2
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function nashvan_get_font_url() {
	$font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'nashvan' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'nashvan' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Nashvan 1.0
 */
function nashvan_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'nashvan-bootstrap',get_template_directory_uri().'/js/bootstrap.min.js', array( 'jquery' ), '20140711', true );
	wp_enqueue_script( 'prettyPhoto-bootstrap', get_template_directory_uri().'/js/jquery.prettyPhoto.js', array( 'jquery' ), '20140711', true );
	wp_enqueue_script( 'select-bootstrap', get_template_directory_uri().'/js/jbootstrap-select.min.js', array( 'jquery' ), '20140711', true );

	wp_register_style( 'nashvan-bootstrap-min-css',get_template_directory_uri().'/css/bootstrap.min.css' );
    wp_enqueue_style( 'nashvan-bootstrap-min-css' );
	
	wp_register_style( 'nashvan-fonts-css', get_template_directory_uri() . '/css/fonts.css' );
    wp_enqueue_style( 'nashvan-fonts-css' );

    // wp_register_style( 'nashvan-mobile-css', get_template_directory_uri() . '/css/mobile.css', array(),'','screen and (max-device-width: 480px)' );
    // wp_enqueue_style( 'nashvan-mobile-css' );

    // wp_register_style( 'nashvan-tablet-css', get_template_directory_uri() . '/css/tablet.css', array(),'','screen and (max-device-width: 768px)' );
    // wp_enqueue_style( 'nashvan-tablet-css' );
	
	wp_register_style( 'nashvan-prettyPhoto-css', get_template_directory_uri() . '/css/prettyPhoto.css' );
    wp_enqueue_style( 'nashvan-prettyPhoto-css' );
	
	// Loads our main stylesheet.
	wp_enqueue_style( 'nashvan-style', get_stylesheet_uri() );
	
	// dropzone js
	//wp_enqueue_script( 'dropzone',get_template_directory_uri() . '/js/dropzone.js',true);
	
	// validation js
	wp_register_script( 'jquery-validate', get_template_directory_uri() . '/js/jquery.validate.js', array( 'jquery' ) );
	wp_register_script( 'jquery-additional-methods', get_template_directory_uri() . '/js/additional-methods.js', array( 'jquery' ) );
	
	wp_enqueue_script( 'jquery-validate' );
	wp_enqueue_script( 'jquery-additional-methods' );
}
add_action( 'wp_enqueue_scripts', 'nashvan_scripts_styles' );

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses nashvan_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since Nashvan 1.2
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function nashvan_mce_css( $mce_css ) {
	$font_url = nashvan_get_font_url();

	if ( empty( $font_url ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'nashvan_mce_css' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Nashvan 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function nashvan_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'nashvan' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'nashvan_wp_title', 10, 2 );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Nashvan 1.0
 */
function nashvan_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'nashvan_page_menu_args' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Nashvan 1.0
 */
function nashvan_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Top Head Right Sidebar', 'nashvan' ),
		'id' => 'sidebar-top-head',
		'description' => __( 'Appears on top head right sidebar section, which has its own widgets', 'nashvan' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'nashvan' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'nashvan' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'nashvan' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'nashvan' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	// footer sidebar
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 1', 'nashvan' ),
		'id' => 'footer-sidebar-1',
		'description' => __( 'Appears in the footer', 'nashvan' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Widget Area 2', 'nashvan' ),
		'id' => 'footer-sidebar-2',
		'description' => __( 'Appears in the footer', 'nashvan' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 3', 'nashvan' ),
		'id' => 'footer-sidebar-3',
		'description' => __( 'Appears in the footer', 'nashvan' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 4', 'nashvan' ),
		'id' => 'footer-sidebar-4',
		'description' => __( 'Appears in the footer', 'nashvan' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 5', 'nashvan' ),
		'id' => 'footer-sidebar-5',
		'description' => __( 'Appears in the footer', 'nashvan' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 6', 'nashvan' ),
		'id' => 'footer-sidebar-6',
		'description' => __( 'Appears in the footer', 'nashvan' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	// Front page Left Ad
	register_sidebar( array(
		'name' => __( 'Front Page Ad Left Widget Area', 'nashvan' ),
		'id' => 'sidebar-4',
		'description' => __( 'Appears when using the Page Ad Left Widget Area', 'nashvan' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'Front Page Ad Right Widget Area', 'nashvan' ),
		'id' => 'sidebar-5',
		'description' => __( 'Appears when using the Page Ad Left Widget Area', 'nashvan' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'nashvan_widgets_init' );

if ( ! function_exists( 'nashvan_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Nashvan 1.0
 */
function nashvan_content_nav( $html_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo esc_attr( $html_id ); ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'nashvan' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'nashvan' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'nashvan' ) ); ?></div>
		</nav><!-- .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'nashvan_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own nashvan_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Nashvan 1.0
 */
function nashvan_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'nashvan' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'nashvan' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'nashvan' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'nashvan' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'nashvan' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'nashvan' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'nashvan' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'nashvan_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own nashvan_entry_meta() to override in a child theme.
 *
 * @since Nashvan 1.0
 */
function nashvan_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'nashvan' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'nashvan' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'nashvan' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'nashvan' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'nashvan' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'nashvan' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Nashvan 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function nashvan_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) )
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
			$classes[] = 'custom-background-white';
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'nashvan-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';
		
	$classes = array_diff($classes, array('category'));
	return $classes;
}
add_filter( 'body_class', 'nashvan_body_class' );

/**
 * Register postMessage support.
 *
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Nashvan 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function nashvan_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'nashvan_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Nashvan 1.0
 */
function nashvan_customize_preview_js() {
	wp_enqueue_script( 'nashvan-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20141120', true );
}
add_action( 'customize_preview_init', 'nashvan_customize_preview_js' );


/* the russian time function */
function the_russian_time($tdate = '') {
     
	 if ( substr_count($tdate , '---') > 0 ) return str_replace('---', '', $tdate);

     $treplace = array (
		 "Январь " => "Января ",
		 "Февраль " => "Февраля ",
		 "Март " => "Марта ",
		 'Апрель ' => 'Апреля ',
		 "Май " => "Мая ",
		 "Июнь " => "Июня ",
		 "Июль " => "Июля ",
		 "Август " => "Августа ",
		 "Сентябрь " => "Сентября ",
		 "Октябрь " => "Октября ",
		 "Ноябрь " => "Ноября ",
		 "Декабрь " => "Декабря ",
	
		 "January" => "Января",
		 "February" => "Февраля",
		 "March" => "Марта",
		 "April" => "Апреля",
		 "May" => "Мая",
		 "June" => "Июня",
		 "July" => "Июля",
		 "August" => "Августа",
		 "September" => "Сентября",
		 "October" => "Октября",
		 "November" => "Ноября",
		 "December" => "Декабря",     
	
		 "Sunday" => "воскресенье",
		 "Monday" => "понедельник",
		 "Tuesday" => "вторник",
		 "Wednesday" => "среда",
		 "Thursday" => "четверг",
		 "Friday" => "пятница",
		 "Saturday" => "суббота",
	
		 "Sun" => "воскресенье",
		 "Mon" => "понедельник",
		 "Tue" => "вторник",
		 "Wed" => "среда",
		 "Thu" => "четверг",
		 "Fri" => "пятница",
		 "Sat" => "суббота",
	
		 "th" => "",
		 "st" => "",
		 "nd" => "",
		 "rd" => "",

		 "days"=>"дней"
     );

	return strtr($tdate, $treplace);
}
add_filter('date', 'the_russian_time');
add_filter('the_time', 'the_russian_time');
add_filter('get_the_time', 'the_russian_time');
add_filter('the_date', 'the_russian_time');
add_filter('get_the_date', 'the_russian_time');
add_filter('the_modified_time', 'the_russian_time');
add_filter('get_the_modified_date', 'the_russian_time');
add_filter('get_post_time', 'the_russian_time');
add_filter('get_comment_date', 'the_russian_time');
add_filter('date_i18n', 'the_russian_time');

function monthName($month_int) {
	$month_int = (int)$month_int;
	$months = array("","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	return $months[$month_int];
}

function custom_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function new_excerpt_more( $more ) {
	return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');


/*############################ Classified section #############################*/


/*###############  Get the category postcount ################*/
function wp_get_cat_postcount($id,$taxonomy="category")
 {
    $cat = get_term_by('id',$id,$taxonomy);
    $count = (int) $cat->count;
    $args = array(
      'child_of' => $id,
    );
    $tax_terms = get_terms($taxonomy,$args);
    foreach ($tax_terms as $tax_term) {
        $count +=$tax_term->count;
    }
    return $count;
}
/* ############## END FUNCTION ####################*/


add_action( 'init', 'codex_book_init' );
/*
 * Register custom posts.
*/

function codex_book_init() 
{
	$labels = array(
		'name'               =>'Classifieds',
		'singular_name'      =>'Classified',
		'menu_name'          =>'Classifieds',
		'name_admin_bar'     =>'Classified',
		'add_new'            =>'Add New',
		'add_new_item'       =>'Add New Classified',
		'new_item'           =>'New Classified',
		'edit_item'          =>'Edit Classified',
		'view_item'          =>'View Classified',
		'all_items'          =>'All Classifieds',
		'search_items'       =>'Search Classifieds',
		'parent_item_colon'  =>'Parent Classifieds:',
		'not_found'          =>'No classifieds found.',
		'not_found_in_trash' =>'No classifieds found in Trash.'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug'=>'classified'),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'supports'           => array('title','editor','author','thumbnail','excerpt')
	);
	
//Bussiness Directory posts Businesses
	$bslables = array(
		'name'               =>'Businesses',
		'singular_name'      =>'Business',
		'menu_name'          =>'Businesses',
		'name_admin_bar'     =>'Business',
		'add_new'            =>'Add New',
		'add_new_item'       =>'Add New Business',
		'new_item'           =>'New Business',
		'edit_item'          =>'Edit Business',
		'view_item'          =>'View Business',
		'all_items'          =>'All Businesses',
		'search_items'       =>'Search Businesses',
		'parent_item_colon'  =>'Parent Businesses:',
		'not_found'          =>'No businesses found.',
		'not_found_in_trash' =>'No businesses found in Trash.'
	);

	$bsargs = array(
		'labels'             => $bslables,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug'=>'business'),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 7,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
	);	

//Events posts 
	$evntlables = array(
		'name'               =>'Events',
		'singular_name'      =>'Event',
		'menu_name'          =>'Events',
		'name_admin_bar'     =>'Event',
		'add_new'            =>'Add New',
		'add_new_item'       =>'Add New Event',
		'new_item'           =>'New Event',
		'edit_item'          =>'Edit Event',
		'view_item'          =>'View Event',
		'all_items'          =>'All Events',
		'search_items'       =>'Search Events',
		'parent_item_colon'  =>'Parent Events:',
		'not_found'          =>'No events found.',
		'not_found_in_trash' =>'No events found in Trash.'
	);

	$evntargs = array(
		'labels'             => $evntlables,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug'=>'event'),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 7,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
	);	
	
//Video Galelry posts 
	$videolables = array(
		'name'               =>'Videos',
		'singular_name'      =>'Video',
		'menu_name'          =>'Video Galleries',
		'name_admin_bar'     =>'Video',
		'add_new'            =>'Add New',
		'add_new_item'       =>'Add New Video',
		'new_item'           =>'New Video',
		'edit_item'          =>'Edit Video',
		'view_item'          =>'View Video',
		'all_items'          =>'All Video Galleries',
		'search_items'       =>'Search Videos',
		'parent_item_colon'  =>'Parent Videos:',
		'not_found'          =>'No videos found.',
		'not_found_in_trash' =>'No videos found in Trash.'
	);

	$videoGargs = array(
		'labels'             => $videolables,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug'=>'video'),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 14,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
	);	
	
//Photo Gallery posts 
	$photolables = array(
		'name'               =>'Photos',
		'singular_name'      =>'Photo',
		'menu_name'          =>'Photo Galleries',
		'name_admin_bar'     =>'Photo',
		'add_new'            =>'Add New',
		'add_new_item'       =>'Add New Photo',
		'new_item'           =>'New Photo',
		'edit_item'          =>'Edit Photo',
		'view_item'          =>'View Photo',
		'all_items'          =>'All Photo Galleries',
		'search_items'       =>'Search Photos',
		'parent_item_colon'  =>'Parent Photos:',
		'not_found'          =>'No photos found.',
		'not_found_in_trash' =>'No photos found in Trash.'
	);

	$photoGargs = array(
		'labels'             => $photolables,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug'=>'photo'),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 14,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
	);			
	
   //Code register custom posts
    register_post_type('photogallery',$photoGargs); // Video Gallery
    register_post_type('videogallery',$videoGargs); // Video Gallery
	register_post_type('event',$evntargs); // Bussiness
 	register_post_type('business',$bsargs); // Bussiness	 
	register_post_type('classified',$args); // Classified
}


add_action('init','custom_taxonomies');
function custom_taxonomies() 
{	  
 //Classified taxonomy
	register_taxonomy('classified_cat','classified',
		array(
			'label' => __( 'Classified Categories' ),
			'rewrite' => array( 'slug' => 'classifiedcat'),
			'hierarchical' => true,
		)
	);
	
//Bussiness taxonomy
  register_taxonomy('bussiness_cat','business',
		array('label' => __('Bussiness Categories'),
			'rewrite' => array('slug'=>'bussinesscat'),
			'hierarchical'=>true,
		));	
		
 //Event taxonomy
  register_taxonomy('event_cat','event',
		array('label' => __('Event Categories'),
			'rewrite' => array('slug'=>'event_category'),
			'hierarchical'=>true,
		));	
		
  //Video gallery taxonomy
     register_taxonomy('video_cat','videogallery',
		array('label' => __('Video Categories'),
			'rewrite' => array('slug'=>'videogalleries'),
			'hierarchical'=>true,
		));	
		
 //Photo gallery taxonomy
    register_taxonomy('photo_cat','photogallery',
		array('label' => __('Photos Category'),
			'rewrite' => array('slug'=>'photosgallery'),
			'hierarchical'=>true,
	));					
		
}
/*********** End Classifides section ******************/

//GET The Current Page Url 
function current_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) ) {
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}


add_action('init', 'remove_admin_bar');
function remove_admin_bar() 
{
	global $current_user;
	 $user_roles = $current_user->roles;
	 //print_r($user_roles);
if($user_roles[0]=='author')
  {
   show_admin_bar(false);
  }
}


// Add transection history menu 
add_action('admin_menu', 'nashvanCustom_menu_page');
function nashvanCustom_menu_page()
{
 //Paypal settings menu	
	add_menu_page( 'nashvan Paypal Settings', 'Nashvan Paypal Settings',8,'paypal-settings','mrPaypalSettings');
	
	
 //Ads Payment History Menu
  add_submenu_page('edit.php?post_type=classified','classified history','Classifieds History',8,'classifieds-history',   'classTransHistory');
 add_submenu_page('edit.php?post_type=classified','cost settings option','Cost Setting',8,'classified-cost-setting',   'classCostSetting');
 
//Events Pay History Menu 
add_submenu_page('edit.php?post_type=event','event history','Events History',8,'events-history','eventsTransHistory');
add_submenu_page('edit.php?post_type=event','cost settings option','Cost Setting',8,
'events-cost-setting','eventsCostSetting');

//Business Directory History Menu 
	add_submenu_page('edit.php?post_type=business','business history','Business History',8,'business-history','businessTranHistory');
	
	add_submenu_page('edit.php?post_type=business','cost settings option','Cost Setting',8,
	'business-cost-setting','businessCostSetting');
}

//Paypal Setting options
function mrPaypalSettings()
{
 include_once('inc/paypal-settings.php');
}

function classTransHistory()
 {
   include_once('inc/classifieds-transection-history.php');
 }

function classCostSetting()
{
  include_once('inc/classifieds-cost-settings.php');	 
}

//Event Cost setting fun 

 function eventsTransHistory()
 {
   include_once('inc/events-transection-history.php');
 }
 function eventsCostSetting()
 {
   include_once('inc/events-cost-settings.php');	
 }


//Bussiness Cost setting fun 
 function businessTranHistory()
 {
   include_once('inc/businesses-transection-history.php');
 }
 function businessCostSetting()
 {
   include_once('inc/business-cost-settings.php');	
 }


//End Transection History function 

 
//create custom table
add_action('init','rbCreateTables');
function rbCreateTables()
{
 global $wpdb;
 $payHistorytab = $wpdb->prefix."payment_history";
 $wpdb->query("CREATE TABLE IF NOT EXISTS $payHistorytab(tid bigint(20) auto_increment primary key,postId varchar(255),userId bigint(20),activeDays varchar(255),payAmt varchar(255),payStatus varchar(255),
 postType varchar(100),postStatus varchar(255),date datetime)");	
}
// End function 


// Chage post status 
function changePoststatus($postId,$Status)
{
  global $wpdb;
  $postTab = $wpdb->prefix."posts";
  $wpdb->query("UPDATE $postTab SET post_status='$Status' WHERE ID='$postId'"); 		 
}


function add_featured_galleries_to_ctp( $post_types ) {
    return array('classified', 'business', 'event');
}
add_filter('fg_post_types', 'add_featured_galleries_to_ctp' );

/**
 * Use Custom Avatar if Provided
 * @author Bill Erickson
 * @link http://www.billerickson.net/wordpress-custom-avatar/
 *
 */
function be_gravatar_filter($avatar, $id_or_email, $size, $default, $alt) {
	
	// If provided an email and it doesn't exist as WP user, return avatar since there can't be a custom avatar
	$email = is_object( $id_or_email ) ? $id_or_email->comment_author_email : $id_or_email;
	if( is_email( $email ) && ! email_exists( $email ) ) return $avatar;

	$custom_avatar = get_user_meta($id_or_email, 'profile_picture_path', true);
	if ($custom_avatar) 
		$return = '<img src="'.$custom_avatar.'" width="'.$size.'" height="'.$size.'" class="photo img-circle" alt="'.$alt.'" />';
	elseif ($avatar) 
		$return = $avatar;
	else 
		$return = '<img src="'.$default.'" width="'.$size.'" height="'.$size.'" class="photo img-circle" alt="'.$alt.'" />';
	return $return;
}
add_filter('get_avatar', 'be_gravatar_filter', 10, 5);

// Translation English to Russian time
add_filter( 'human_time_diff', function($since, $diff, $from, $to) {

    $replace = array(
        'hour'  => 'час',
        'hours' => 'часов',
        'day'   => 'день',
        'days'  => 'дня',
        'weeks'	=> 'недели',
        'week'	=> 'неделю'
    );

    return strtr($since, $replace);

}, 10, 4 );

// Limit by characters
function get_excerpt(){
	$excerpt = get_the_content();
	$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
	$excerpt = strip_shortcodes($excerpt);
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, 130);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	return $excerpt;
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_google_fonts_array' );
function wpex_mce_google_fonts_array( $initArray ) {
    $theme_advanced_fonts .= 'Arial=Arial;';
    $theme_advanced_fonts .= 'Andale Mono=andale mono,times;';
    $theme_advanced_fonts .= 'Book Antiqua=book antiqua,palatino;';
    $theme_advanced_fonts .= 'Comic Sans MS=comic sans ms,sans-serif;';
    $theme_advanced_fonts .= 'Courier New=courier new,courier;';
    $theme_advanced_fonts .= 'Georgia=georgia,palatino;';
    $theme_advanced_fonts .= 'Helvetica=helvetica;';
    $theme_advanced_fonts .= 'Impact=impact,chicago;';
    $theme_advanced_fonts .= 'Lato=Lato;';
	$theme_advanced_fonts .= 'Michroma=Michroma;';
    $theme_advanced_fonts .= 'Open Sans=Open Sans;';
    $theme_advanced_fonts .= 'Tahoma=tahoma,arial,helvetica,sans-serif;';
    $theme_advanced_fonts .= 'Terminal=terminal,monaco;';
    $theme_advanced_fonts .= 'Time New Roman=Times New Roman;';
    $theme_advanced_fonts .= 'Trebuchet MS=trebuchet ms,geneva;';
    $theme_advanced_fonts .= 'Verdana=Verdana;';
    $theme_advanced_fonts .= 'Webdings=webdings;';
    $theme_advanced_fonts .= 'Wingdings=wingdings,zapf dingbats;';
    $initArray['font_formats'] = $theme_advanced_fonts;
    return $initArray;
}
add_action( 'admin_init', 'wpex_mce_google_fonts_styles' );
function wpex_mce_google_fonts_styles() {
   $font2 = 'http://fonts.googleapis.com/css?family=Lato:300,400,700';
   add_editor_style( str_replace( ',', '%2C', $font2 ) );
   $font3 = 'http://fonts.googleapis.com/css?family=Michroma:300,400,700';
   add_editor_style( str_replace( ',', '%2C', $font3 ) );
   $font4 = 'http://fonts.googleapis.com/css?family=Open+Sans:300,400,700';
   add_editor_style( str_replace( ',', '%2C', $font4 ) );
}
add_action('admin_head-post.php', function() {
    ?>
    <style>
    @import url(http://fonts.googleapis.com/css?family=Lato);
    @import url(http://fonts.googleapis.com/css?family=Michroma);
    @import url(http://fonts.googleapis.com/css?family=Open+Sans); 
    <?php
});

/* add metabox to event post type */
add_action( 'add_meta_boxes', 'rb_event_metaboxes' );

function rb_event_metaboxes() {
  add_meta_box('rb-event-section','Расписание Option Box','rb_schedule_callback','event');
}

function rb_schedule_callback( $post ) { 

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'myplugin_meta_box', 'myplugin_meta_box_nonce' );	
	?>
	
	<style>
		.width100 {
			width:100%;
		}
		.clearboth {
			clear:both;
		}
		.width20 {
			width:20%;
		}
		.width30 {
			width:30%;
		}
		.width15 {
			width:15%;
		}
		.width5 {
			width:5%;
		}
		.text-center {
			text-align:center;
		}
		.magintb10 {
			margin:10px auto;
		}
		.meta-box-sortables select {
		  max-width: 100%;
		  width: 90%;
		}
	</style>
	
	<div class="width100">
		<div class="width100 schedule">
			<div class="width100 magintb10">
				<div class="col1 width20 alignleft">
					<span class="date control-label">Число:</span>
				</div>
				<div class="col2 width30 alignleft">
					<span class="date control-label">Месяц:</span>
				</div>
				<div class="col3 width40 alignleft">
					<span class="date control-label">Время начала:</span>
				</div>
				<div class="clearboth"></div>
			</div>
			
			<?php
			global $wpdb;
			$countrows = $wpdb->get_row("SELECT count(*) as countevent FROM $wpdb->postmeta WHERE 1 AND post_id = '".$post->ID."' AND meta_key LIKE 'event_date_%'");
			
			if( intval($countrows->countevent) > 0 ){
				
				$lastcounter = intval($countrows->countevent) - 1;
				for($counter=0; $counter < intval($countrows->countevent); $counter++) {

					$event_date               = get_post_meta($post->ID, 'event_date_'.$counter, true);
					$event_month              = get_post_meta($post->ID, 'event_month_'.$counter, true);
					$event_time_start_hours   = get_post_meta($post->ID, 'evnt_time_start_hours_'.$counter, true);
					$event_time_start_minutes = get_post_meta($post->ID, 'evnt_time_start_minutes_'.$counter, true);
					$event_start_time         = get_post_meta($post->ID, 'event_start_time_'.$counter, true);
				?>
					<?php if($lastcounter == $counter){ ?>
					<div class="width100 event_schedule schedulediv magintb10">
					<?php } else { ?>
					<div class="width100 schedulediv magintb10">
					<?php } ?>
						<div class="width20 alignleft">
							<div class="form-group">
								<select name="scDate[]" class="form-control scDate">
									<option <?php if($event_date == 1) { echo 'selected="selected"'; } ?> value="1">1</option>
									<option <?php if($event_date == 2) { echo 'selected="selected"'; } ?> value="2">2</option>
									<option <?php if($event_date == 3) { echo 'selected="selected"'; } ?> value="3">3</option>
									<option <?php if($event_date == 4) { echo 'selected="selected"'; } ?> value="4">4</option>
									<option <?php if($event_date == 5) { echo 'selected="selected"'; } ?> value="5">5</option>
									<option <?php if($event_date == 6) { echo 'selected="selected"'; } ?> value="6">6</option>
									<option <?php if($event_date == 7) { echo 'selected="selected"'; } ?> value="7">7</option>
									<option <?php if($event_date == 8) { echo 'selected="selected"'; } ?> value="8">8</option>
									<option <?php if($event_date == 9) { echo 'selected="selected"'; } ?> value="9">9</option>
									<option <?php if($event_date == 10) { echo 'selected="selected"'; } ?> value="10">10</option>
									<option <?php if($event_date == 11) { echo 'selected="selected"'; } ?> value="11">11</option>
									<option <?php if($event_date == 12) { echo 'selected="selected"'; } ?> value="12">12</option>
									<option <?php if($event_date == 13) { echo 'selected="selected"'; } ?> value="13">13</option>
									<option <?php if($event_date == 14) { echo 'selected="selected"'; } ?> value="14">14</option>
									<option <?php if($event_date == 15) { echo 'selected="selected"'; } ?> value="15">15</option>
									<option <?php if($event_date == 16) { echo 'selected="selected"'; } ?> value="16">16</option>
									<option <?php if($event_date == 17) { echo 'selected="selected"'; } ?> value="17">17</option>
									<option <?php if($event_date == 18) { echo 'selected="selected"'; } ?> value="18">18</option>
									<option <?php if($event_date == 19) { echo 'selected="selected"'; } ?> value="19">19</option>
									<option <?php if($event_date == 20) { echo 'selected="selected"'; } ?> value="20">20</option>
									<option <?php if($event_date == 21) { echo 'selected="selected"'; } ?> value="21">21</option>
									<option <?php if($event_date == 22) { echo 'selected="selected"'; } ?> value="22">22</option>
									<option <?php if($event_date == 23) { echo 'selected="selected"'; } ?> value="23">23</option>
									<option <?php if($event_date == 24) { echo 'selected="selected"'; } ?> value="24">24</option>
									<option <?php if($event_date == 25) { echo 'selected="selected"'; } ?> value="25">25</option>
									<option <?php if($event_date == 26) { echo 'selected="selected"'; } ?> value="26">26</option>
									<option <?php if($event_date == 27) { echo 'selected="selected"'; } ?> value="27">27</option>
									<option <?php if($event_date == 28) { echo 'selected="selected"'; } ?> value="28">28</option>
									<option <?php if($event_date == 29) { echo 'selected="selected"'; } ?> value="29">29</option>
									<option <?php if($event_date == 30) { echo 'selected="selected"'; } ?> value="30">30</option>
									<option <?php if($event_date == 31) { echo 'selected="selected"'; } ?> value="31">31</option>
								</select>
							</div>
						</div>
						<div class="width30 alignleft">
							<div class="form-group">
								<select name="scMonth[]" class="form-control scMonth">
									<option <?php if($event_month == 01) { echo 'selected="selected"'; } ?> value="01">Января</option>
									<option <?php if($event_month == 02) { echo 'selected="selected"'; } ?> value="02">Февраля</option>
									<option <?php if($event_month == 03) { echo 'selected="selected"'; } ?> value="03">Марта</option>
									<option <?php if($event_month == 04) { echo 'selected="selected"'; } ?> value="04">Апреля</option>
									<option <?php if($event_month == 05) { echo 'selected="selected"'; } ?> value="05">Мая</option>
									<option <?php if($event_month == 06) { echo 'selected="selected"'; } ?> value="06">Июня</option>
									<option <?php if($event_month == 07) { echo 'selected="selected"'; } ?> value="07">Июля</option>
									<option <?php if($event_month == 08) { echo 'selected="selected"'; } ?> value="08">Августа</option>
									<option <?php if($event_month == 09) { echo 'selected="selected"'; } ?> value="09">Сентября</option>
									<option <?php if($event_month == 10) { echo 'selected="selected"'; } ?> value="10">Октября</option>
									<option <?php if($event_month == 11) { echo 'selected="selected"'; } ?> value="11">Ноября</option>
									<option <?php if($event_month == 12) { echo 'selected="selected"'; } ?> value="12">Декабря</option>
								</select>
							</div>
						</div>
						<div class="width15 alignleft">
							<div class="form-group">
								<select name="evnt_time_start_hours[]" class="form-control time-start-hours">
									<option <?php if($event_time_start_hours == 1) { echo 'selected="selected"'; } ?> value="1">1</option>
									<option <?php if($event_time_start_hours == 2) { echo 'selected="selected"'; } ?> value="2">2</option>
									<option <?php if($event_time_start_hours == 3) { echo 'selected="selected"'; } ?> value="3">3</option>
									<option <?php if($event_time_start_hours == 4) { echo 'selected="selected"'; } ?> value="4">4</option>
									<option <?php if($event_time_start_hours == 5) { echo 'selected="selected"'; } ?> value="5">5</option>
									<option <?php if($event_time_start_hours == 6) { echo 'selected="selected"'; } ?> value="6">6</option>
									<option <?php if($event_time_start_hours == 7) { echo 'selected="selected"'; } ?> value="7">7</option>
									<option <?php if($event_time_start_hours == 8) { echo 'selected="selected"'; } ?> value="8">8</option>
									<option <?php if($event_time_start_hours == 9) { echo 'selected="selected"'; } ?> value="9">9</option>
									<option <?php if($event_time_start_hours == 10) { echo 'selected="selected"'; } ?> value="10">10</option>
									<option <?php if($event_time_start_hours == 11) { echo 'selected="selected"'; } ?> value="11">11</option>
									<option <?php if($event_time_start_hours == 12) { echo 'selected="selected"'; } ?> value="12">12</option>
								</select>
							</div>
						</div>
						<div class="width15 alignleft">
							<div class="form-group">
								<select name="evnt_time_start_minutes[]" class="form-control time-start-minutes">
									<option <?php if($event_time_start_minutes == 00) { echo 'selected="selected"'; } ?> value="00">0</option>
									<option <?php if($event_time_start_minutes == 01) { echo 'selected="selected"'; } ?> value="01">1</option>
									<option <?php if($event_time_start_minutes == 02) { echo 'selected="selected"'; } ?> value="02">2</option>
									<option <?php if($event_time_start_minutes == 03) { echo 'selected="selected"'; } ?> value="03">3</option>
									<option <?php if($event_time_start_minutes == 04) { echo 'selected="selected"'; } ?> value="04">4</option>
									<option <?php if($event_time_start_minutes == 05) { echo 'selected="selected"'; } ?> value="05">5</option>
									<option <?php if($event_time_start_minutes == 06) { echo 'selected="selected"'; } ?> value="06">6</option>
									<option <?php if($event_time_start_minutes == 07) { echo 'selected="selected"'; } ?> value="07">7</option>
									<option <?php if($event_time_start_minutes == 08) { echo 'selected="selected"'; } ?> value="08">8</option>
									<option <?php if($event_time_start_minutes == 09) { echo 'selected="selected"'; } ?> value="09">9</option>
									<option <?php if($event_time_start_minutes == 10) { echo 'selected="selected"'; } ?> value="10">10</option>
									<option <?php if($event_time_start_minutes == 11) { echo 'selected="selected"'; } ?> value="11">11</option>
									<option <?php if($event_time_start_minutes == 12) { echo 'selected="selected"'; } ?> value="12">12</option>
									<option <?php if($event_time_start_minutes == 13) { echo 'selected="selected"'; } ?> value="13">13</option>
									<option <?php if($event_time_start_minutes == 14) { echo 'selected="selected"'; } ?> value="14">14</option>
									<option <?php if($event_time_start_minutes == 15) { echo 'selected="selected"'; } ?> value="15">15</option>
									<option <?php if($event_time_start_minutes == 16) { echo 'selected="selected"'; } ?> value="16">16</option>
									<option <?php if($event_time_start_minutes == 17) { echo 'selected="selected"'; } ?> value="17">17</option>
									<option <?php if($event_time_start_minutes == 18) { echo 'selected="selected"'; } ?> value="18">18</option>
									<option <?php if($event_time_start_minutes == 19) { echo 'selected="selected"'; } ?> value="19">19</option>
									<option <?php if($event_time_start_minutes == 20) { echo 'selected="selected"'; } ?> value="20">20</option>
									<option <?php if($event_time_start_minutes == 21) { echo 'selected="selected"'; } ?> value="21">21</option>
									<option <?php if($event_time_start_minutes == 22) { echo 'selected="selected"'; } ?> value="22">22</option>
									<option <?php if($event_time_start_minutes == 23) { echo 'selected="selected"'; } ?> value="23">23</option>
									<option <?php if($event_time_start_minutes == 24) { echo 'selected="selected"'; } ?> value="24">24</option>
									<option <?php if($event_time_start_minutes == 25) { echo 'selected="selected"'; } ?> value="25">25</option>
									<option <?php if($event_time_start_minutes == 26) { echo 'selected="selected"'; } ?> value="26">26</option>
									<option <?php if($event_time_start_minutes == 27) { echo 'selected="selected"'; } ?> value="27">27</option>
									<option <?php if($event_time_start_minutes == 28) { echo 'selected="selected"'; } ?> value="28">28</option>
									<option <?php if($event_time_start_minutes == 29) { echo 'selected="selected"'; } ?> value="29">29</option>
									<option <?php if($event_time_start_minutes == 30) { echo 'selected="selected"'; } ?> value="30">30</option>
									<option <?php if($event_time_start_minutes == 31) { echo 'selected="selected"'; } ?> value="31">31</option>
									<option <?php if($event_time_start_minutes == 32) { echo 'selected="selected"'; } ?> value="32">32</option>
									<option <?php if($event_time_start_minutes == 33) { echo 'selected="selected"'; } ?> value="33">33</option>
									<option <?php if($event_time_start_minutes == 34) { echo 'selected="selected"'; } ?> value="34">34</option>
									<option <?php if($event_time_start_minutes == 35) { echo 'selected="selected"'; } ?> value="35">35</option>
									<option <?php if($event_time_start_minutes == 36) { echo 'selected="selected"'; } ?> value="36">36</option>
									<option <?php if($event_time_start_minutes == 37) { echo 'selected="selected"'; } ?> value="37">37</option>
									<option <?php if($event_time_start_minutes == 38) { echo 'selected="selected"'; } ?> value="38">38</option>
									<option <?php if($event_time_start_minutes == 39) { echo 'selected="selected"'; } ?> value="39">39</option>
									<option <?php if($event_time_start_minutes == 40) { echo 'selected="selected"'; } ?> value="40">40</option>
									<option <?php if($event_time_start_minutes == 41) { echo 'selected="selected"'; } ?> value="41">41</option>
									<option <?php if($event_time_start_minutes == 42) { echo 'selected="selected"'; } ?> value="42">42</option>
									<option <?php if($event_time_start_minutes == 43) { echo 'selected="selected"'; } ?> value="43">43</option>
									<option <?php if($event_time_start_minutes == 44) { echo 'selected="selected"'; } ?> value="44">44</option>
									<option <?php if($event_time_start_minutes == 45) { echo 'selected="selected"'; } ?> value="45">45</option>
									<option <?php if($event_time_start_minutes == 46) { echo 'selected="selected"'; } ?> value="46">46</option>
									<option <?php if($event_time_start_minutes == 47) { echo 'selected="selected"'; } ?> value="47">47</option>
									<option <?php if($event_time_start_minutes == 48) { echo 'selected="selected"'; } ?> value="48">48</option>
									<option <?php if($event_time_start_minutes == 49) { echo 'selected="selected"'; } ?> value="49">49</option>
									<option <?php if($event_time_start_minutes == 50) { echo 'selected="selected"'; } ?> value="50">50</option>
									<option <?php if($event_time_start_minutes == 51) { echo 'selected="selected"'; } ?> value="51">51</option>
									<option <?php if($event_time_start_minutes == 52) { echo 'selected="selected"'; } ?> value="52">52</option>
									<option <?php if($event_time_start_minutes == 53) { echo 'selected="selected"'; } ?> value="53">53</option>
									<option <?php if($event_time_start_minutes == 54) { echo 'selected="selected"'; } ?> value="54">54</option>
									<option <?php if($event_time_start_minutes == 55) { echo 'selected="selected"'; } ?> value="55">55</option>
									<option <?php if($event_time_start_minutes == 56) { echo 'selected="selected"'; } ?> value="56">56</option>
									<option <?php if($event_time_start_minutes == 57) { echo 'selected="selected"'; } ?> value="57">57</option>
									<option <?php if($event_time_start_minutes == 58) { echo 'selected="selected"'; } ?> value="58">58</option>
									<option <?php if($event_time_start_minutes == 59) { echo 'selected="selected"'; } ?> value="59">59</option>
								</select>
							</div>
						</div>
						<div class="width15 alignleft">
							<div class="form-group">
							<select name="event_start_time[]" class="form-control event_start_time">
								<option <?php if($event_start_time == "am") { echo 'selected="selected"'; } ?> value="am">AM</option>
								<option <?php if($event_start_time == "pm") { echo 'selected="selected"'; } ?> value="pm">PM</option>
							</select>
							</div>
						</div>

						<?php if($counter != 0){ ?>
						<div class="parentremovecurrentschedule width5 alignleft">
							<button type="button" class="close remove-current-schedule">×</button>
							<div class="clearfix"></div>
						</div>
						<?php } ?>
						<div class="clearboth"></div>
					</div>
					<?php
				}
			}
		   ?>
			<div class="width100 text-center magintb10">
				<a href="javascript:void(0);" id="create_event_schedule" class="add_schedule">+ Добавить еще расписание</a>
			</div>
			<div class="clearboth"></div>
		</div>
		<div class="clearboth"></div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function(e) {
		
			jQuery("#create_event_schedule").click(function(){
				
				jQuery.ajax({
					type:'post',
					url:'<?php echo get_bloginfo('stylesheet_directory'); ?>/schedule.php',
					success:function(result) {

						jQuery(result).insertAfter(".event_schedule");
						jQuery( "div.event_schedule" ).first().removeClass( "event_schedule" );
					}
				});
			});

			jQuery(".schedule").on('click', '.remove-current-schedule', function(){
				jQuery(this).parent().parent().remove();
				jQuery( "div.schedulediv" ).last().addClass( "event_schedule" );
			});
		});
	</script>
	<?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function myplugin_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'myplugin_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'event' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	
	global $wpdb;
	$wp_postmeta = $wpdb->prefix."postmeta";
	$wpdb->query("DELETE FROM $wp_postmeta WHERE post_id = $post_id AND meta_key LIKE 'event_date_%'");
	$wpdb->query("DELETE FROM $wp_postmeta WHERE post_id = $post_id AND meta_key LIKE 'event_month_%'");
	$wpdb->query("DELETE FROM $wp_postmeta WHERE post_id = $post_id AND meta_key LIKE 'evnt_time_start_hours_%'");
	$wpdb->query("DELETE FROM $wp_postmeta WHERE post_id = $post_id AND meta_key LIKE 'evnt_time_start_minutes_%'");
	$wpdb->query("DELETE FROM $wp_postmeta WHERE post_id = $post_id AND meta_key LIKE 'event_start_time_%'");

	if( count($_POST['scDate']) > 0 && count($_POST['scMonth']) > 0 ){

		for($counter=0; $counter < count($_POST['scDate']); $counter++){
			update_post_meta($post_id, 'event_date_'.$counter, $_POST['scDate'][$counter]);
			update_post_meta($post_id, 'event_month_'.$counter, $_POST['scMonth'][$counter]);
			update_post_meta($post_id, 'evnt_time_start_hours_'.$counter, $_POST['evnt_time_start_hours'][$counter]);
			update_post_meta($post_id, 'evnt_time_start_minutes_'.$counter, $_POST['evnt_time_start_minutes'][$counter]);
			update_post_meta($post_id, 'event_start_time_'.$counter, $_POST['event_start_time'][$counter]);
		}
	}
}
add_action( 'save_post_event', 'myplugin_save_meta_box_data' );

/* check post expiration */
function check_post_expiration( $postId, $postType ) {
	
	global $wpdb;
	$payHistorytab = $wpdb->prefix."payment_history";
	$checkPayment = $wpdb->get_row("select * from $payHistorytab where postId = '$postId' and postType='$postType' ORDER BY tid DESC limit 0,1");
	$numDays = 0;
	
	if($checkPayment) {
		
		if(!$checkPayment->activeDays) {
			$addactiveDays = 0;
		} else {
			$addactiveDays = $checkPayment->activeDays;
		}
		
		$activeDaysSec = $addactiveDays * 24 * 60 * 60;
		$activateTime = strtotime($checkPayment->date);
		$expirationTime = $activeDaysSec + $activateTime;
		$numDays = ($expirationTime - time())/60/60/24;
	}
	
	return $numDays;
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}