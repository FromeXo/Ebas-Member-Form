<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @author Robin "FromeXo" Johansson
 * @since 1.0.0
 * @package Emf
 */

// If uninstall was not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('ef_plugin_options');