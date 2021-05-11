<?php
/**
 * Free trail mail sys
 *
 */

/**
 * Create hook do some thing after payment success
 *
 * @param Number $order
 * @return Void
 */
function pp_do_something_after_payment_success( $order_id ) {
  $order = new WC_Order( $order_id );
  $order_status = $order->get_status();
  $user = $order->get_user();
  $items = $order->get_items();
  $products_in_order = [];

  foreach( $items as $index => $item ) {
    array_push( $products_in_order, $item->get_product_id() );
  }

  $replace_variables = apply_filters( 'pp_hook_replace_variable_mail_body', [
    '{username}' => $user->display_name,
  ] );

  /**
   * pp_after_payment_success hook.
   *
   * @see pp_freetrail_send_mail_welcome_after_payment_success - 20
   */
  do_action( 'pp_after_payment_success', $products_in_order, $order_status, $user, $replace_variables );
}

add_action( 'woocommerce_payment_complete', 'pp_do_something_after_payment_success' );

/**
 * Send mail welcome free trail
 *
 * @param Array $products_in_order
 * @param String $order_status
 * @param Object $user
 *
 * @return Void
 */
function pp_freetrail_send_mail_welcome_after_payment_success( $products_in_order, $order_status, $user, $replace_variables ) {
  $pid = pp_get_field( 'ft_product', 'option' );
  $email_welcome = pp_get_field( 'ft_welcome_email_template', 'option' );
  $pid_sms = pp_get_field( 'sms_ft_product', 'option' );
  $sms_welcome = pp_get_field('ft_welcome_sms_template','option');
  $userID = $user->ID;
  $phoneBilling = get_user_meta($userID,'billing_phone',true);
  if( in_array( $pid, $products_in_order ) ) {
    $subject = $email_welcome[ 'email_subject' ];
    $body = str_replace( array_keys( $replace_variables ), array_values( $replace_variables ), $email_welcome[ 'email_template' ] );
    pp_send_email( $user->user_email, $subject, $body );
  }
  if(in_array($pid_sms,$products_in_order)) {
      $body_sms = str_replace( array_keys( $replace_variables ), array_values( $replace_variables ), $sms_welcome[ 'sms_template' ] );
      pp_send_sms($phoneBilling,$body_sms);
  }

}

add_action( 'pp_after_payment_success', 'pp_freetrail_send_mail_welcome_after_payment_success', 20, 4 );

/**
 *
 * @param Array $products_in_order
 * @param String $order_status
 * @param Object $user
 *
 * @return Void
 */
function pp_product_send_mail_welcome_after_payment_success( $products_in_order, $order_status, $user, $replace_variables ) {
  $pid = pp_get_field( 'p_product', 'option' );
  $email_welcome = pp_get_field( 'p_welcome_email_template', 'option' );

  $pid_sms = pp_get_field( 'sms_p_product', 'option' );
  $sms_welcome = pp_get_field( 'p_welcome_sms_template', 'option' );

    if( in_array( $pid, $products_in_order ) ) {
        $subject = $email_welcome[ 'email_subject' ];
        $body = str_replace(
            array_keys( $replace_variables ),
            array_values( $replace_variables ),
            $email_welcome[ 'email_template' ]
        );

        pp_send_email( $user->user_email, $subject, $body );

    }
    if( in_array( $pid_sms, $products_in_order ) ) {
        $body_sms = str_replace(
            array_keys( $replace_variables ),
            array_values( $replace_variables ),
            $sms_welcome[ 'sms_template' ]
        );
        $userID = $user->ID;
        $phoneBilling = get_user_meta($userID,'billing_phone',true);
        pp_send_sms($phoneBilling,$body_sms);
    }
}

add_action( 'pp_after_payment_success', 'pp_product_send_mail_welcome_after_payment_success', 20, 4 );

/**
 *
 */
function pp_get_all_order_complete() {
  $today = date( 'Y-m-d' );
  $sevent_mounth_ago = date( "Y-m-d", strtotime( $today . " - 7 months" ) );

  $result = wc_get_orders( [
    'limit'=> -1,
    'type'=> 'shop_order',
    'status'=> [ 'wc-completed' ],
    'date_created'=> $sevent_mounth_ago .'...'. $today
  ] );

  return count( $result ) ? array_map( function( $order ) {
    $products = $order->get_items();
    $user = $order->get_user();

    return [
      'order_id' => $order->get_id(),
      'products' => count( $products ) ? array_map( function( $p ) { return $p->get_product_id(); }, $products ) : [],
      'status' => $order->get_status(),
      'date_completed' => $order->get_date_completed()->date( 'Y-m-d' ),
      'user' => [
        'id'=> $user->ID,
        'email' => $user->user_email,
        'display_name' => $user->display_name,
      ],
    ];
  }, $result ) : [];
}

/**
 *
 */
function pp_send_email_schedule() {
    $orders = pp_get_all_order_complete();

    # for trail
    pp_send_email_schedule_action(
        $orders,
        pp_get_field( 'ft_product', 'option' ),
        pp_get_field( 'ft_email_schedule', 'option' )
    );

    pp_send_sms_schedule_action(
        $orders,
        pp_get_field( 'sms_ft_product', 'option' ),
        pp_get_field( 'ft_sms_schedule', 'option' )
    );

    # for product
    pp_send_email_schedule_action(
        $orders,
        pp_get_field( 'p_product', 'option' ),
        pp_get_field( 'p_email_schedule', 'option' )
    );

    pp_send_sms_schedule_action(
        $orders,
        pp_get_field( 'sms_p_product', 'option' ),
        pp_get_field( 'p_sms_schedule', 'option' )
    );
}

add_action( 'pp_hook_cron_daily_action', 'pp_send_email_schedule', 20 );

/**
 *
 */

function pp_send_sms_schedule_action ($orders, $product, $schedule = [] ) {

    if( ! $schedule || count( $schedule ) == 0 ) return;

    $today = date( 'Y-m-d' );

    foreach( $schedule as $s_index => $s ) {
      $after_payment_day = 0; // $s[ 'send_after_payment_day' ];

      foreach( $orders as $o_index => $o ) {
        if( in_array( $product, $o[ 'products' ] ) ) {
          $order_day = $o[ 'date_completed' ];
          $date_send_email = date( "Y-m-d", strtotime( $order_day . " + $after_payment_day day" ) );
          if( $date_send_email == $today ) {
            $replace_variables = [
              '{username}' => $o[ 'user' ][ 'display_name' ],
            ];
            $userID = $o['user']['id'];
            $phoneBilling = get_user_meta($userID,'billing_phone',true);
            pp_send_sms($phoneBilling,str_replace(
              array_keys( $replace_variables ),
              array_values( $replace_variables ),
              $s[ 'sms_template' ] ) );
          }
        }
      }
    }
}

function pp_send_email_schedule_action( $orders, $product, $schedule = [] ) {

  if( ! $schedule || count( $schedule ) == 0 ) return;

  $today = date( 'Y-m-d' );

  foreach( $schedule as $s_index => $s ) {
    $after_payment_day = (int) $s[ 'send_after_payment_day' ];

    foreach( $orders as $o_index => $o ) {

      if( in_array( $product, $o[ 'products' ] ) ) {

        $order_day = $o[ 'date_completed' ];
        $date_send_email = date( "Y-m-d", strtotime( $order_day . " + $after_payment_day day" ) );

        if( $date_send_email == $today ) {
          $replace_variables = [
            '{username}' => $o[ 'user' ][ 'display_name' ],
          ];

          pp_send_email(
            $o[ 'user' ][ 'email' ],
            $s[ 'email_subject' ],
            str_replace(
              array_keys( $replace_variables ),
              array_values( $replace_variables ),
              $s[ 'email_template' ] )
          );
        }
      }
    }
  }
}

/**
 *
 */
function pp_send_mail_trigger_completed_lession( $args ) {
  $activity_status = $args[ 'activity_status' ];
  $post_id = $args[ 'post_id' ];
  $user = get_userdata( $args[ 'user_id' ] );
  $userID = $args[ 'user_id' ];
  $phoneBilling = get_user_meta($userID,'billing_phone',true);

  # Check is lesson & status = 1
  if( $activity_status != 1 ) return;

    $trigger_completed_lession = [
        pp_get_field( 'ft_after_completing_level', 'option' ),
        pp_get_field( 'p_after_completing_level', 'option' )
    ];

    $trigger_completed_lession_sms = [
        pp_get_field( 'ft_sms_after_completing_level', 'option' ),
        pp_get_field( 'p_sms_after_completing_level', 'option' )
    ];
    foreach( $trigger_completed_lession as $index => $item ) {
        $select_lesson = $item[ 'select_lesson' ];
        if( (int) $select_lesson == $post_id ) {
            pp_send_email( $user->user_email, $item[ 'email_subject' ], $item[ 'email_template' ] );
        }
    }

    foreach( $trigger_completed_lession_sms as $index => $item ) {
        $select_lesson = $item[ 'select_lesson' ];
        if( (int) $select_lesson == $post_id ) {
            pp_send_sms($phoneBilling,$item[ 'sms_template' ]);
        }
    }
}

add_action( 'learndash_update_user_activity', 'pp_send_mail_trigger_completed_lession' );

/**
 * Test
 */
add_action( 'init', function() {
  if( $_GET[ 'pp' ] ) {
    // pp_send_email_schedule_action(
    //     pp_get_all_order_complete(),
    //     pp_get_field( 'ft_product', 'option' ),
    //     pp_get_field( 'ft_email_schedule', 'option' )
    // );
    // pp_send_sms_schedule_action(
    //     pp_get_all_order_complete(),
    //     pp_get_field( 'sms_ft_product', 'option' ),
    //     pp_get_field( 'ft_sms_schedule', 'option' )
    // );
    // pp_send_mail_trigger_completed_lession( [
    //     'activity_status' => 1,
    //     'user_id' => 815,
    //     'post_id' => 621,
    // ] );
  }
} );
