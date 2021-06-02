<?php
if ( is_active_sidebar( 'page-title-sidebar' ) ) { ?>
  <div class="container">
    <?php if ( !function_exists('dynamic_sidebar')
    || !dynamic_sidebar('page-title-sidebar') ) : ?>
    <?php endif; ?>
  </div>
<?php
} else {
?>

  <div class="page-title container">
    <?php include('func_title.php'); ?>
  </div>

<?php }	?>
