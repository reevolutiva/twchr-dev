<?php
/**
 * Plugin Name: Manage Twitch Account (Easy APi Integration) - Promote, Embed & Manage Streamings & VOD - Twitcher.pro
 * Plugin URI: twitcher.pro
 * Description: Manage, promote and monetise your Twitch.tv streamings integrating Twitch to Wordpress.
 * Version: 0.5
 * Author: Conjuntas.Club
 * Author URI: conjuntas.club
 * License: GPL3
 * Text Domain: twitcher
 * Domain Path: /languages
 **/

define( 'TWCHR_HOME_URL', site_url() );
define( 'TWCHR_ADMIN_URL', site_url() . '/wp-admin/' );
define( 'TWCHR_PATH', __FILE__ );
define( 'TWCHR_URL', plugin_dir_url( __FILE__ ) );
define( 'TWCHR_ASSETS_VERSION', 'beta.5.25' );

define( 'TWCHR_URL_ASSETS', plugin_dir_url( __FILE__ ) . 'includes/assets/' );
define( 'TWCHR_URL_FONTS', plugin_dir_url( __FILE__ ) . 'includes/assets/fonts' );

// Añadí la carpeta features para poner funcionalidades del plugin.
define( 'TWCHR_FEATURES', plugin_dir_path( __FILE__ ) . 'includes/features/' );

define( 'TWCHR_SETUP_ASSETS', plugin_dir_url( __FILE__ ) . '/admin/set-up-img/' );
define( 'TWCHR_WPJSON_ROUTE', site_url() . '/wp-json/' );

// setings.
require_once 'includes/dev-functions.php';
require_once 'admin/aux-functions/twchr-i18n.php';
//require_once 'admin/twtchr-menus.php';
require_once TWCHR_FEATURES . 'twitch-alerts.php';
require_once TWCHR_FEATURES . 'twtchr-easy-set-up.php';
require_once TWCHR_FEATURES . 'twtchr-activate.php';
require_once TWCHR_FEATURES . 'twtchr-deactivate.php';
require_once TWCHR_FEATURES . 'twchr-delete-schedule.php';
require_once TWCHR_FEATURES . 'twchr-flush-cache.php';
require_once TWCHR_FEATURES . 'twchr-ajax-porsess.php';
require_once TWCHR_FEATURES . 'twchr-serie-import.php';

register_deactivation_hook( __FILE__, 'twchr_desactivar' );

// Aux functions.
require_once 'admin/aux-functions/aux-functions.php';

require_once 'includes/api-connection/twicht.php';
require_once 'includes/api-connection/twichtv.php';
require_once 'includes/api-connection/api-db.php';

// admin styles.
require_once 'admin/admin-dashboard.php';

// Custom post types.
require_once 'includes/cpt/streamings.php';

// Custom fields.
require_once 'includes/cf/streamings.php';
require_once 'includes/cf/streamings.php';
require_once 'includes/cf/schedule-card.php';

// Shortcodes.
require_once 'includes/shortcode/twich-embed.php';
require_once 'includes/shortcode/shortcode-archive.php';

// Crear post stream.
require_once 'includes/create-post/crear-twchr-stream.php';

// Actualizar post.
require_once 'includes/update-post/update-twchr-stream.php';

// Traer un post.
require_once 'includes/get-post/get-twchr-stream.php';

require_once 'includes/twchr-get-videos.php';

// Taxonomias.
require_once 'includes/taxonomys/twchr-tax-serie.php';
require_once 'includes/taxonomys/cat-twcht.php';
require_once 'includes/taxonomys/twchr_status.php';

// Enqueue.
require_once 'includes/assets/plugin-enqueue.php';

/** TWITCHER API
require_once 'twitcher-api/from-db.php';
require_once 'twitcher-api/twchr-twchrapi-controller.php';
*/

require_once TWCHR_FEATURES . 'twchr-clipboard.php';

// End Points.
require_once 'includes/ext-wp-api.php';

// dinamyc js
require_once 'includes/dynamic-js.php';

// Menu consola de administracion en Dashboard WP
function twchr_main_menu() {

	add_submenu_page(
		'edit.php?post_type=twchr_streams', // $parent_slug
		'Twitcher Dashboard',  // $page_title
		'Twitcher Dashboard',        // $menu_title
		'manage_options',           // $capability
		'twchr-dashboard', // $menu_slug
		'twchr_main_page', // $function
		0
	);

	add_submenu_page(
		'edit.php?post_type=twchr_streams',
		__( 'Help', 'twitcher' ),
		__( 'Help', 'twitcher' ),
		'manage_options',
		'twchr_help',
		'twchr_menu_help',
	);

}

add_action( 'admin_menu', 'twchr_main_menu' );


// Template de menu prinicpal de plugin
function twchr_main_page() {
	require_once 'admin/main-page.php';
}

// Template de menu secudario de plugin
function twchr_menu_help() {
	require_once 'admin/submenu_menu_help.php';
}
