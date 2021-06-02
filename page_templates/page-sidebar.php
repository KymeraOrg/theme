<?php
/*
Template Name: Page sidebar
Template Post Type: post, page, product
*/
?>

<?php get_header() ?>

	<div class="page_sidebar">
		<?php include('inc/title.php'); ?>
		<div class="container">
			<?php the_post() ?>
			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">

				<div class="entry-content main-side">
					<?php the_content() ?>
				</div>

				<div class="main-sidebar">
					<?php if ( !function_exists('dynamic_sidebar')
					|| !dynamic_sidebar('main-sidebar') ) :
					endif;?>
				</div>

				<div class="clear"></div>
			</div><!-- .post -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>
