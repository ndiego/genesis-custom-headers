<?php
/*
Plugin Name: Genesis Custom Headers
Plugin URI: http://www.outermostdesign.com
Description: Adds custom headers to pages, posts, and custom post types in Genesis themes. The Genesis Framework 2.0+ is required.
Version: 1.0.0
Author: Nick Diego
Author URI: http://www.outermostdesign.com
Text Domain: genesis-custom-headers
License: GPLv2
*/

/*
Copyright 2014 Nick Diego

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

defined( 'WPINC' ) or die;


register_activation_hook( __FILE__, 'gch_activation_check' );
/**
 * This function runs on plugin activation. It checks to make sure the required
 * minimum Genesis version is installed. If not, it deactivates itself.
 */
function gch_activation_check() {
	$latest = '2.0';
	$theme_info = wp_get_theme( 'genesis' );

	if ( 'genesis' != basename( TEMPLATEPATH ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
		wp_die( sprintf( __( 'Sorry, you can\'t activate %1$sGenesis Custom Headers%2$s unless you have installed the %3$sGenesis Framework%4$s. Go back to the %5$sPlugins Page%4$s.', 'genesis-custom-header' ), '<em>', '</em>', '<a href="http://www.studiopress.com/themes/genesis" target="_blank">', '</a>', '<a href="javascript:history.back()">' ) );
	}

	if ( version_compare( $theme_info['Version'], $latest, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
		wp_die( sprintf( __( 'Sorry, you can\'t activate %1$sGenesis Custom Headers%2$s unless you have installed the %3$sGenesis %4$s%5$s. Go back to the %6$sPlugins Page%5$s.', 'genesis-custom-header' ), '<em>', '</em>', '<a href="http://www.studiopress.com/themes/genesis" target="_blank">', $latest, '</a>', '<a href="javascript:history.back()">' ) );
	}
}


include( dirname( __FILE__ ) . '/inc/gch-frontend-views.php');
include( dirname( __FILE__ ) . '/inc/gch-settings.php');
include( dirname( __FILE__ ) . '/inc/gch-metabox.php');
include( dirname( __FILE__ ) . '/inc/gch-supported-sliders.php');



add_filter( 'plugin_action_links', 'gch_plugin_action_links', 10, 2);
/**
 * Add custom Settings link on the plugin activation page
 */
function gch_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/genesis-custom-header.php' ) ) {
		$links[] = '<a href="' . admin_url( 'themes.php?page=genesis-custom-header' ) . '">'.__( 'Settings' ).'</a>';
	}
	return $links;
}


add_action( 'admin_enqueue_scripts', 'gch_admin_scripts_enqueue' );
/**
 * Enqueue admin scripts for image uploader and show/hide js, and admin css
 */
function gch_admin_scripts_enqueue() {
	//Enqueues all media scripts for we can use the media uploader
    wp_enqueue_media(); 
    
    wp_register_script( 'gch-admin-scripts', plugin_dir_url( __FILE__ ) . 'js/gch-admin-scripts.js', array( 'jquery' ) );
    wp_enqueue_script( 'gch-admin-scripts' );  
    
    wp_enqueue_style( 'gch-admin-styles', plugin_dir_url( __FILE__ ) . 'css/gch-admin-styles.css' );
}


add_action( 'wp_enqueue_scripts', 'gch_frontend_scripts_enqueue' );
/**
 * Loads scripts to the frontend
 */
function gch_frontend_scripts_enqueue() {
    wp_enqueue_style( 'gch-frontend-styles',  plugin_dir_url( __FILE__ ) . 'css/gch-frontend-styles.css' );
}


?>