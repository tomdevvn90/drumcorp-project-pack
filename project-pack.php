<?php
/*
Plugin Name: Project Pack
Plugin URI: #
Description: Plugin make only for this website
Version: 1.0.0
Author: Beplus
Author URI: #
Text Domain: project-pack
Domain Path: /lang
*/

require( __DIR__ . '/vendor/autoload.php' );


// use Twilio\Rest\Client;

{
  /**
   * Define
   */
  define( 'PP_VER', '1.0.0' );
  define( 'PP_DIR', plugin_dir_path( __FILE__ ) );
  define( 'PP_URI', plugin_dir_url( __FILE__ ) );
}

{
  /**
   * Inc
   */
  require_once( PP_DIR . '/inc/static.php' );
  require_once( PP_DIR . '/inc/helpers.php' );
  require_once( PP_DIR . '/inc/hooks.php' );
  require_once( PP_DIR . '/inc/ajax.php' );
  require_once( PP_DIR . '/inc/options.php' );

  require_once( PP_DIR . '/inc/tasks/schedule-email-system.php' );
  require_once( PP_DIR . '/inc/tasks/schedule-sms-system.php' );

  require_once( PP_DIR . '/inc/tasks/cron.php' );
}
