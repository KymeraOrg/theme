<?php get_header() ?>

	<div id="container" class="single_page" >
		<?php include('inc/title.php'); ?>
		<div id="content" class="container">

		<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">

				<div class="entry-content">
					<?php the_content() ?>
				</div>

			</div><!-- .post -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>
