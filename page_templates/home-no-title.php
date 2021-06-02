<?php
/*
Template Name: No Title
Template Post Type: post, page, product
*/
?>

<?php get_header() ?>

	<div>

		<div class="container">

		<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">

				<div class="entry-content">
					<?php the_content() ?>
				</div>

			</div><!-- .post -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>
