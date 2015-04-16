<?php
/*
Plugin Name: WP-CleanDB
Plugin URI: http://gerrytucker.co.uk/wp-plugins/wpclean-db.zip
Description: Cleanup your Wordpress database in one click!
Version: 1.1
Author: Gerry Tucker
Author URI: http://gerrytucker.co.uk/
License: GPLv2 or later
*/

function cleandb_admin() {
	include('wp-cleandb-admin.php');
}

function cleandb_admin_actions() {
	add_options_page('WP-CleanDB', 'WP-CleanDB', 'administrator', 'WP-CleanDB', 'cleandb_admin');
}

add_action('admin_menu', 'cleandb_admin_actions');
