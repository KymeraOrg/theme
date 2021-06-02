<?php get_header() ?>

	<div>
		<div>

			<?php while ( have_posts() ) : the_post() ?>
					<?php include('inc/loop.php'); ?>
			<?php endwhile; ?>

			<div class="clear"></div>

		</div> <!-- #content -->
	</div> <!-- #container -->


<?php get_footer() ?>
