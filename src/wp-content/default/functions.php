<?php

function wpt_kill_theme($themes) {
  $new = [];
  foreach ($themes as $key => $value) {
    if (strpos($key, "_child") !== false)
      $new[$key] = $value;
  }
  return $new;
}
add_filter('wp_prepare_themes_for_js','wpt_kill_theme');


function wpt_head () {
  ?>
    <meta name="abc" content="xyz" />
  <?php
}
add_action( 'wp_head', 'wpt_head' );
?>
