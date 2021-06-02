<?php get_header() ?>

	<div class="container">
		<?php include('inc/title.php'); ?>
		<div>

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post() ?>

				<?php include('inc/loop.php'); ?>

			<?php endwhile; ?>

		<?php else : ?>

			<?php include('inc/loop.php'); ?>

		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>
