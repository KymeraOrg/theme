<?php get_header() ?>

	<div class="category_page">
		<?php include('inc/title.php'); ?>
		<div class="">

			<div class="main-side">
				<?php while ( have_posts() ) : the_post() ?>
						<?php include('inc/loop.php'); ?>
				<?php endwhile; ?>
			</div>

			<div class="main-sidebar">
				<?php if ( !function_exists('dynamic_sidebar')
				|| !dynamic_sidebar('main-sidebar') ) :
				endif;?>
			</div>

			<div class="clear"></div>


		</div><!-- #content -->
	</div><!-- #container -->


<?php get_footer() ?>
