<?php
/**
 * @package WordPress
 * @subpackage Theme_Compat
 * @deprecated 3.0
 *
 * This file is here for Backwards compatibility with old themes and will be removed in a future version
 *
 */
_deprecated_file(
	/* translators: %s: template name */
	sprintf( __( 'Theme without %s' ), basename( __FILE__ ) ),
	'3.0',
	null,
	/* translators: %s: template name */
	sprintf( __( 'Please include a %s template in your theme.' ), basename( __FILE__ ) )
);

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.'); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h3 id="comments">
		<?php
			// if ( 1 == get_comments_number() ) {
			// 	/* translators: %s: post title */
			// 	printf( __( 'One response to %s' ),  '&#8220;' . get_the_title() . '&#8221;' );
			// } else {
				/* translators: 1: number of comments, 2: post title */
				printf( _n( '%1$s response to %2$s', 'Комментарии: (%1$s)', get_comments_number() ),
					number_format_i18n( get_comments_number() ) );
			// }
		?>
	</h3>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<ol class="commentlist">
	<?php wp_list_comments();?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.'); ?></p>

	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

<div id="respond">

<div id="cancel-comment-reply">
	<small><?php cancel_comment_reply_link() ?></small>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php printf(__('Вы должны <a href="javascript:void(0);" data-toggle="modal" data-target="#login">Войти</a> или <a href="javascript:void(0);" data-toggle="modal" data-target="#registration">Зарегистрироваться</a> для того, чтобы оставить комментарий.')); ?></p>
<?php else : ?>

<form action="<?php echo site_url(); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( is_user_logged_in() ) : ?>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="author"><small><?php _e('Name'); ?> <?php if ($req) _e('(required)'); ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="email"><small><?php _e('Mail (will not be published)'); ?> <?php if ($req) _e('(required)'); ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo  esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website'); ?></small></label></p>

<?php endif; ?>
<div class="row">
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
		<?php echo get_avatar(get_current_user_id(),75); ?>
	</div>
	<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
		<?php /* translators: %s: user profile link  */
printf( __( '%s' ), sprintf( '<p class="pull-left"><a href="%1$s" class="fn">%2$s</a>
<div class="pull-right"><label class="answers-subscribe">Подписаться на ответы<input type="checkbox" name="answers" /></label></div>
</p>', get_edit_user_link(), $user_identity )); ?>
		<textarea name="comment" id="comment" cols="82" rows="5" tabindex="4" placeholder="Введите текст вашего комментария"></textarea>
	<div class="text-right">
		<input name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e('Оставить комментарий'); ?>" />
	</div>
	</div>
</div>

<p>
<?php comment_id_fields(); ?>
</p>
<?php
/** This filter is documented in wp-includes/comment-template.php */
do_action( 'comment_form', $post->ID );
?>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
