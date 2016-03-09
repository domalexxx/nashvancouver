<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Post_Views_Counter_Frontend class.
 */
class Post_Views_Counter_Frontend {

	public function __construct() {
		// actions
		add_action( 'after_setup_theme', array( $this, 'register_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts_styles' ) );

		// filters
		add_filter( 'the_content', array( $this, 'add_post_views_count' ) );
		add_filter( 'the_excerpt', array( $this, 'remove_post_views_count' ) );
	}

	/**
	 * Register post-views shortcode function.
	 */
	public function register_shortcode() {
		add_shortcode( 'post-views', array( $this, 'post_views_shortcode' ) );
	}

	/**
	 * Post views shortcode function.
	 * 
	 * @param array $args
	 * @return mixed
	 */
	public function post_views_shortcode( $args ) {
		$defaults = array(
			'id' => get_the_ID()
		);

		$args = shortcode_atts( $defaults, $args );

		return pvc_post_views( $args['id'], false );
	}

	/**
	 * Add post views counter to content.
	 * 
	 * @param mixed $content
	 * @return mixed
	 */
	public function add_post_views_count( $content ) {
		$display = false;
		
		if ( is_singular() && in_array( get_post_type(), Post_Views_Counter()->options['display']['post_types_display'], true ) )
			$display = true;

		// get groups to check it faster
		$groups = Post_Views_Counter()->options['display']['restrict_display']['groups'];

		// whether to display views
		if ( is_user_logged_in() ) {
			// exclude logged in users?
			if ( in_array( 'users', $groups, true ) )
				$display = false;
			// exclude specific roles?
			elseif ( in_array( 'roles', $groups, true ) && Post_Views_Counter()->counter->is_user_roles_excluded( Post_Views_Counter()->options['display']['restrict_display']['roles'] ) )
				$display = false;
		}
		// exclude guests?
		elseif ( in_array( 'guests', $groups, true ) )
			$display = false;

		
		if ( apply_filters( 'pvc_display_views_count', $display ) === true ) {
			switch ( Post_Views_Counter()->options['display']['position'] ) {
				case 'after':
					$content = $content . '[post-views]';
					break;

				case 'before':
					$content = '[post-views]' . $content;
					break;

				case 'manual':
				default:
					break;
			}
		}

		return $content;
	}

	/**
	 * Remove post views shortcode from excerpt.
	 * 
	 * @param mixed $excerpt
	 * @return mixed
	 */
	public function remove_post_views_count( $excerpt ) {
		remove_shortcode( 'post-views' );
		$excerpt = preg_replace( '/\[post-views[^\]]*\]/', '', $excerpt );
		return $excerpt;
	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public function frontend_scripts_styles() {
		$post_types = Post_Views_Counter()->options['display']['post_types_display'];

		// load dashicons
		wp_enqueue_style( 'dashicons' );

		// load style
		wp_enqueue_style( 'post-views-counter-frontend', POST_VIEWS_COUNTER_URL . '/css/frontend.css' );

		if ( Post_Views_Counter()->options['general']['counter_mode'] === 'js' ) {
			$post_types = Post_Views_Counter()->options['general']['post_types_count'];

			// whether to count this post type or not
			if ( empty( $post_types ) || ! is_singular( $post_types ) )
				return;

			wp_register_script(
				'post-views-counter-frontend', POST_VIEWS_COUNTER_URL . '/js/frontend.js', array( 'jquery' )
			);

			wp_enqueue_script( 'post-views-counter-frontend' );

			wp_localize_script(
				'post-views-counter-frontend',
				'pvcArgsFrontend',
				array(
					'ajaxURL'	 => admin_url( 'admin-ajax.php' ),
					'postID'	 => get_the_ID(),
					'nonce'		 => wp_create_nonce( 'pvc-check-post' ),
					'postType'	 => get_post_type()
				)
			);
		}
	}
}
