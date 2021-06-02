<?php get_header() ?>

	<div id="container" class="single_post" >
		<?php include('inc/title.php'); ?>
		<div id="content" class="<?php echo get_option( 'layout_opt' ); ?>">

		<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">

				<div class="entry-content">
					<div class="image" style="display: none;">
						<img src="<?php the_post_thumbnail_url( 'full' ); ?>" alt="">
				  </div>

					<?php the_content() ?>
				</div>

			</div><!-- .post -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>
