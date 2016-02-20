<?php
/**
 * Uninstall Genesis Custom Headers
 *
 * Deletes all the plugin data i.e.
 * 		1. Post metadata (Local Blocks)
 * 		2. Plugin settings.
 *
 * @since 	1.1.2
 *
 * @package	Genesis Custom Headers
 * @author 	Nick Diego
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Load main Blox file.
include_once( 'genesis-custom-headers.php' );

global $wpdb;

$gch_uninstall_on_delete = genesis_get_option( 'uninstall_on_delete', 'genesis-custom-header' );;

if ( $gch_uninstall_on_delete == 1 ) {
	
	// Delete all page/post meta data
	delete_metadata( 'post', 0, '_gch_enable_header', '', true );
	delete_metadata( 'post', 0, '_gch_enable_image', '', true );
	delete_metadata( 'post', 0, '_gch_image_type', '', true );
	delete_metadata( 'post', 0, '_gch_custom_image', '', true );
	delete_metadata( 'post', 0, '_gch_custom_image_alt', '', true );
  	delete_metadata( 'post', 0, '_gch_image_caption', '', true );
	delete_metadata( 'post', 0, '_gch_background_image', '', true );
  	delete_metadata( 'post', 0, '_gch_enable_slideshow', '', true );
	delete_metadata( 'post', 0, '_gch_slider_shortcode', '', true );
	delete_metadata( 'post', 0, '_gch_soliloquy_slider', '', true );
	delete_metadata( 'post', 0, '_gch_revolution_slider', '', true );
	delete_metadata( 'post', 0, '_gch_meta_slider', '', true );
	delete_metadata( 'post', 0, '_gch_sliderpro_slider', '', true );
  	delete_metadata( 'post', 0, '_gch_enable_custom_content', '', true );
	delete_metadata( 'post', 0, '_gch_custom_content', '', true );
	delete_metadata( 'post', 0, '_gch_enable_header_raw', '', true );
	delete_metadata( 'post', 0, '_gch_header_raw', '', true );

	
	// Delete all Blox settings
	delete_option( 'genesis-custom-header' );
}
