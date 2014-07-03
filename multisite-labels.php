<?php
/**
 * Plugin Name:     Multisite labels
 * Plugin URI:      https://github.com/trendwerk/multisite-labels/
 * Description:     Assign labels to WP Multisite websites. Useful for keeping them apart in multilingual environments.
 * Version:         1.0
 * Author:          Trendwerk
 * Author URI:      https://github.com/trendwerk/
 */

if( ! is_multisite() )
	return;

class Multisite_Labels {
	function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ) );
	}

	/**
	 * Show the right label in the admin bar
	 */
	function admin_bar( $menu ) {
		if( 0 >= count( $menu->user->blogs ) )
			return;

		foreach( $menu->user->blogs as &$blog ) {
			$hash = md5( time() );

			if( $hash === get_blog_option( $blog->userblog_id, 'admin_label', $hash ) ) {
				/**
				 * Allow superadmins to setup an admin label
				 */
				switch_to_blog( $blog->userblog_id );
				update_option( 'admin_label', '' );
				restore_current_blog();
			} else if( $admin_label = get_blog_option( $blog->userblog_id, 'admin_label' ) ) {
				/**
				 * Set the admin label
				 */
				$blog->blogname = $admin_label;
			}
		}
	}

} new Multisite_Labels;	
