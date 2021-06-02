<?php get_header() ?>

	<div id="container">
		<?php include('inc/title.php'); ?>
		<div id="content" class="<?php echo get_option( 'layout_opt' ); ?>">

		<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">

				<div class="entry-content">
					<p>
						The page your looking for can not be found. <br>
						You may want to go back to the <a href="<?php echo esc_url( home_url( '/' ) ); ?>">homepage and try again.</a>
					</p>
				</div>

			</div><!-- .post -->

		</div><!-- #content -->
	</div><!-- #container -->

<?php get_footer() ?>
