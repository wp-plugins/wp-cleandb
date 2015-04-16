=== WP-CleanDB ===
Contributors: gerrygooner
Tags: clean, cleanup, comments, database, optimize
Requires at least: 3.0
Tested up to: 4.1.1
Stable tag: 1.1
License: GPLv2 or later

Clean your WordPress database.

== Description ==

This WordPress plugin will cleanup your WordPress database, performing the following actions:

* Remove all post revisions.

* Remove all spam comments.

* Remove all unapproved comments.

* Remove all unused tags.

* Remove all unused post meta.

* Optimize MySQL tables by removing all unused table space.

Based on WP-Cleanup by JortK, this plugin replaces usage of mysql_query() that was deprecated in PHP 5.5 with WP_Query functions .

== Installation ==

1. Download WP-CleanDB.
2. Unzip the ZIP archive.
3. Upload the folder to /wp-content/plugins/.
4. Login into your WordPress Admin Panel.
5. Go to [Plugins/Installed].
6. Activate the WP-CleanDB plugin.
7. Under Settings there is a new option called WP-CleanDB.

== Frequently Asked Questions ==

Have any questions? Leave them in the comments!

== Screenshots ==

1. WP-CleanDB settings screen.

== Changelog ==


= 1.1 =
* Added display of each WPDB table size having overhead.

= 1.0 =
* Initial release.