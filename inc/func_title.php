<?php
echo '<h1>';
  if (is_category()){
    single_cat_title();
  }
  //if (is_woocommerce()){
  //  woocommerce_page_title();
  //}
  if (is_search()){
    echo 'Showing results for: ' . get_search_query();
  }
  if(is_404()){
    echo "404, Page not found";
  }
  if(is_home() || is_page() || is_single()) {
    the_title();
  }
echo "</h1>";

 ?>
