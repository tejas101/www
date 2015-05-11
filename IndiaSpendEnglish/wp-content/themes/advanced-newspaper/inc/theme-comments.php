<?php 
if ( ! function_exists( 'gab_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own gab_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function gab_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

	
		<div class="comment-wrapper" id="comment-<?php comment_ID(); ?>">
			<div class="comment-inner">
			
				<div class="comment-avatar">
					<?php echo get_avatar( $comment, 40 ); ?>
				</div> 
				
				<div class="commentmeta">
					<p class="comment-meta-1">
						<?php printf( __( '%s '), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
						<span class="reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
					</p>
					<p class="comment-meta-2">
						<?php echo get_comment_date(); ?> <?php _e( 'at', 'advanced'); ?> <?php echo get_comment_time(); ?>
						<?php edit_comment_link( __( 'Edit', 'advanced'), '(' , ')'); ?>
					</p>
					
				</div>					
				
				<div class="text">			
				
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<p class="waiting_approval"><?php _e( 'Your comment is awaiting moderation.', 'advanced' ); ?></p>
					<?php endif; ?>
					
					<div class="c">
						<?php comment_text(); ?>
					</div>
					
				</div><!-- .text  -->
				<div class="clear"></div>
			</div><!-- comment-inner  -->
		</div><!-- .comment-wrapper  -->
	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'advanced' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'advanced' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;