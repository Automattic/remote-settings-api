<?php

/*
 * Plugin Name: Remote Settings API
 */

require_once('src/Plugin.php');

$remoteSettingsApiPlugin = new RemoteSettingsApiPlugin();

add_action( 'init', [$remoteSettingsApiPlugin, 'init']);;

add_action('init', function() {
	add_filter( 'pre_get_posts', function($query) {
		var_dump(is_admin());
		return $query;
	} );
});
