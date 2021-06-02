<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php wp_title( '-', true, 'right' ); echo get_bloginfo('name'); ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<?php wp_head() // For plugins ?>
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
	<meta name="viewport" content="width=device-width, minimumscale=1.0, maximum-scale=1.0" />
</head>

<body <?php body_class(); ?>>
	<div id="full-header">
		<div id="header" class="container">
			<?php if ( !function_exists('dynamic_sidebar')
			|| !dynamic_sidebar('menu-sidebar') ) : ?>
			<?php endif; ?>
		</div>
	</div>
	<!--  #header -->

	<div id="wrapper" class="container">
