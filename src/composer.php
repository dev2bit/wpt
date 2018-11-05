<?php


use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class composer
{
    public static function postPackageInstall(PackageEvent $event)
    {
      if (getenv("AUTO_ACTIVATE_PLUGINS") == "true" || getenv("AUTO_ACTIVATE_PLUGINS") == "" ) {
        $package = $event->getOperation()->getPackage();
        $installationManager = $event->getComposer()->getInstallationManager();
        $originDir = $installationManager->getInstallPath($package);
        if ($package->getName() == "johnpbloch/wordpress"){
          exec("wp core install --url=/ --title='".getenv("TITLE")."' --admin_user='".getenv("DEFAULT_USERNAME")."' --admin_password='".getenv("DEFAULT_PASSWORD")."' --admin_email='".getenv("MAIL_ADDR")."' 2> /dev/null");
          include (__DIR__.'/wp/wp-load.php');
          update_option( 'mailserver_url', getenv("MAIL_HOST") );
          update_option( 'mailserver_port', getenv("MAIL_PORT") );
          update_option( 'mailserver_login', getenv("MAIL_USERNAME") );
          update_option( 'mailserver_pass', getenv("MAIL_PASSWORD") );
          update_option( 'blogdescription', getenv("DESCRIPTION") );
          update_option( 'template', "" );
          update_option( 'stylesheet', "" );
          $options = json_decode(file_get_contents(__DIR__."/options.json"), true);
          foreach ($options as $key => $value) {
              update_option($key, $value);
          }
        }
        else if (strpos($originDir, "wp-content/plugins/") !== false){
          include (__DIR__.'/wp/wp-load.php');
          $paths = explode("/", $originDir);
          $path = $paths[count($paths) - 2];
          $active_plugins = get_option( 'active_plugins' );
          array_push($active_plugins, $path."/".$path.".php");
          update_option( 'active_plugins', $active_plugins );
        }
        else if (strpos($originDir, "wp-content/themes/") !== false){
          include (__DIR__.'/wp/wp-load.php');
          $names = explode ("/",$package->getName());
          $name = $names[count($names)-1];
          $before = get_option( 'template');
          update_option( 'template', $name );
          update_option( 'stylesheet', $name.'_child' );
          update_option( 'current_theme', 'Child theme of '.$name );
          update_option( 'template_before', $before );
          if (!file_exists(__DIR__."/wp-content/themes/".$name."_child")){
            $template = file_get_contents(__DIR__."/wp-content/default/child.template.css");
            $template = str_replace("{PARENT}", $name, $template);
            $template = str_replace("{DESCRIPTION}", getenv("DESCRIPTION"), $template);
            $template = str_replace("{AUTHOR}", getenv("AUTHOR"), $template);
            mkdir(__DIR__."/wp-content/themes/".$name."_child");
            file_put_contents(__DIR__."/wp-content/themes/".$name."_child/style.css", $template);
            copy(__DIR__."/wp-content/default/child.functions.php", __DIR__."/wp-content/themes/".$name."_child/functions.php");
            copy(__DIR__."/wp-content/themes/".$name."/screenshot.png", __DIR__."/wp-content/themes/".$name."_child/screenshot.png");
          }
        }
        else if (strpos($originDir, "koodimonni-language/") !== false){

        }
      }
  }

  public static function postPackageUninstall(PackageEvent $event)
  {
      if (getenv("AUTO_ACTIVATE_PLUGINS") == "true" || getenv("AUTO_ACTIVATE_PLUGINS") == "" ) {
        $package = $event->getOperation()->getPackage();
        $installationManager = $event->getComposer()->getInstallationManager();
        $originDir = $installationManager->getInstallPath($package);
        if (strpos($originDir, "wp-content/plugins/") !== false){
          include (__DIR__.'/wp/wp-load.php');
          $paths = explode("/", $originDir);
          $path = $paths[count($paths) - 2];
          $active_plugins = get_option( 'active_plugins' );
          $new_array = [];
          for ($i = count($active_plugins); $i--;) {
            if ($active_plugins[$i] != $path."/".$path.".php") {
              array_push($new_array, $active_plugins[$i]);
            }
          }
          update_option( 'active_plugins', $new_array );
        }
        else if (strpos($originDir, "wp-content/themes/") !== false){
          include (__DIR__.'/wp/wp-load.php');
          $names = explode ("/",$package->getName());
          $name = $names[count($names)-1];
          $before = get_option( 'template_before');
          update_option( 'template', $before );
          update_option( 'stylesheet', $before.'_child' );
          update_option( 'current_theme', 'Child theme of '.$before );
          update_option( 'template_before', 'default' );
        }
      }
  }

}
