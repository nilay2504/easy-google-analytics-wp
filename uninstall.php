<?php
/**
 * Fired when the plugin is uninstalled.
 * @link              http://nilaypatel.info
 * @since             1.0.0
 * @package           Easy_Google_Analytics_WP
 *
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('egawp_activated_on');
delete_option('egawp_deactivated_on');
delete_option('egawp_select_type');
delete_option('egawp_value');