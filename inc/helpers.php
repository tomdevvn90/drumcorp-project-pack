<?php 
/**
 * Helpers 
 */

/**
 * @param String $selector
 * @param Mixed $post_id
 * @param Bool $format_value
 */
function pp_get_field( $selector, $post_id = 0, $format_value = true ) {
  if( ! function_exists( 'get_field' ) ) return null;

  return get_field( $selector, $post_id, $format_value );
}

/**
 * 
 * @return Void
 */
function pp_send_email( $to, $subject, $message, $attachments = [] ) {
  $headers = apply_filters( 'pp_hook_email_header', [
    'Content-Type: text/html; charset=UTF-8'
  ] );

  wp_mail( $to, $subject, $message, $headers, $attachments );
}