<?php 
/**
 * Cron 
 */

//Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'pp_cron_hook' ) ) {
  wp_schedule_event( time(), 'daily', 'pp_cron_hook' );
}

// Hook into that action that'll fire every daily
add_action( 'pp_cron_hook', 'pp_cron_function' );

// create your function, that runs on cron
function pp_cron_function() {
  /**
   * pp_hook_cron_daily_action hook.
   * 
   * @see pp_send_email_schedule - 20 
   */
  do_action( 'pp_hook_cron_daily_action' );
}