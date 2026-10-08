<?php
/**
 * WP Statuses loader.
 *
 * Lets any number of plugins bundle this library at once: every bundled copy
 * registers its version here, and only the newest one is loaded.
 *
 * Require this file, not wp-statuses.php, from each plugin that bundles the
 * library, when the plugin's main file is parsed (before plugins_loaded):
 *
 *     require_once __DIR__ . '/vendor/lhero-org/wp-statuses/loader.php';
 *
 * Do not load it through Composer's autoload "files": Composer includes a
 * given package's files only once per request, so the other bundled copies
 * would never register their versions.
 *
 * The highest registered version is loaded on plugins_loaded at priority 1,
 * before the library boots at priority 5. A copy registered after that point
 * is loaded straight away if no copy has been loaded yet.
 *
 * Copies older than 2.2.0 have no loader. If one is included directly before
 * plugins_loaded, it defines WP_Statuses first and wins, and this loader
 * does nothing.
 *
 * @package WP Statuses
 *
 * @since 2.2.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wp_statuses_register_copy' ) ) :

	/**
	 * Registers one bundled copy of the library.
	 *
	 * @since 2.2.0
	 *
	 * @param string $version The copy's version, as in wp-statuses.php.
	 * @param string $file    Absolute path to the copy's wp-statuses.php.
	 */
	function wp_statuses_register_copy( $version, $file ) {
		if ( ! isset( $GLOBALS['wp_statuses_copies'] ) || ! is_array( $GLOBALS['wp_statuses_copies'] ) ) {
			$GLOBALS['wp_statuses_copies'] = array();
		}

		if ( ! isset( $GLOBALS['wp_statuses_copies'][ $version ] ) ) {
			$GLOBALS['wp_statuses_copies'][ $version ] = $file;
		}
	}

	/**
	 * Loads the newest registered copy, unless a copy is already loaded.
	 *
	 * @since 2.2.0
	 */
	function wp_statuses_load_newest_copy() {
		if ( class_exists( 'WP_Statuses', false ) || empty( $GLOBALS['wp_statuses_copies'] ) ) {
			return;
		}

		$copies = $GLOBALS['wp_statuses_copies'];
		uksort( $copies, 'version_compare' );

		require_once end( $copies );
	}
	add_action( 'plugins_loaded', 'wp_statuses_load_newest_copy', 1 );

endif;

// Keep this version in step with wp-statuses.php.
wp_statuses_register_copy( '2.2.0', __DIR__ . '/wp-statuses.php' );

if ( did_action( 'plugins_loaded' ) ) {
	wp_statuses_load_newest_copy();
}
