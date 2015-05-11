<?php
	/*
		Template Name: Redirect
	*/
?>
<?php
if (have_posts()) : while (have_posts()) : the_post(); ?>

	<!DOCTYPE html>
	<html dir="ltr" lang="en-US">
	<head>
	<title><?php bloginfo('name'); ?></title>
	<meta charset="UTF-8" />
	<meta http-equiv="REFRESH" content="0;url=<?php the_title(); ?>">
	</head>
	<body>
	<?php _e('Redirecting...', 'advanced'); ?>
	</body>
	</html>

<?php endwhile; endif; ?>