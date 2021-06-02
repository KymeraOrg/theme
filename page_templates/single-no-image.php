<?php
/*
 * Template Name: No Image, Full Width
 * Template Post Type: post, page, product
 */

 get_header();  ?>

 	<div class="single_post" >
 		<?php include('inc/title.php'); ?>
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
