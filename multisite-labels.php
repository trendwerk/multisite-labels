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
		add_action( 'get_blogs_of_user', array( $this, 'change' ) );
	}

	/**
	 * Change the label of the blog
	 */
	function change( $blogs ) {
		if( 0 == count( $blogs ) )
			return $blogs;

		foreach( $blogs as &$blog ) {
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

		return $blogs;
	}

} new Multisite_Labels;	
