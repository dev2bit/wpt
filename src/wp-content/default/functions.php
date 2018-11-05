<?php

function kill_theme_wpse_188906($themes) {
  $new = [];
  foreach ($themes as $key => $value) {
    if (strpos($key, "_child") !== false)
      $new[$key] = $value;
  }
  return $new;
}
add_filter('wp_prepare_themes_for_js','kill_theme_wpse_188906');

?>
