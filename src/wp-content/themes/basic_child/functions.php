<?php

include (__DIR__."/../../default/functions.php");

add_filter('gettext',  'translate_text');
add_filter('ngettext',  'translate_text');

function translate_text($translated) {
     $translated = str_ireplace('example1',  'ejemplo1',  $translated);
     $translated = str_ireplace('example2',  'ejemplo2',  $translated);
     //....
     return $translated;
}

?>
