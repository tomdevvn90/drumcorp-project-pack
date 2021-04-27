<?php 
/**
 * Options (use ACF PRO)
 */

if( function_exists( 'acf_add_options_page' ) ) {
	
	acf_add_options_page( [
    'page_title' => __( 'Project Pack', 'project-pack' ),
		'menu_title' => __( 'Project Pack', 'project-pack' ),
		'menu_slug' => 'project-pack-settings',
		'capability' => 'edit_posts',
		'redirect' => false,
    'icon_url' => 'dashicons-sos'
  ] );
}