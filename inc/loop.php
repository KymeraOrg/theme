<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">

  <h2 class="entry-title">
    <a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>">
      <?php the_title() ?>
    </a>
  </h2>

  <div class="image" style="display: none;">
    <img src="<?php the_post_thumbnail_url( 'full' ); ?>" alt="">
  </div>

  <div class="entry-content">
    <?php the_excerpt(__( 'Read More <span class="meta-nav">&raquo;</span>', 'sandbox' )) ?>
  </div>
</div><!-- .post -->
