<?php
/** 
* Plugin Name: Manage Twitch Account: Easy API Integration
* Plugin URI: twitcher.pro
* Description: Manage, promote and monetise your Twitch.tv streamings integrating Twitch to Wordpress.
* Version: 0.4
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

define( 'TWCHR_URL_ASSETS', plugin_dir_url( __FILE__ ) . 'includes/assets/' );
define( 'TWCHR_URL_FONTS', plugin_dir_url( __FILE__ ) . 'includes/assets/fonts' );
// Añadí la carpeta features para poner funcionalidades del plugin.
define( 'TWCHR_FEATURES', plugin_dir_path( __FILE__ ) . 'includes/features/' );

define( 'TWCHR_SETUP_ASSETS', plugin_dir_url( __FILE__ ) . '/admin/set-up-img/' );
define( 'TWCHR_WPJSON_ROUTE', site_url() . '/wp-json/' );

// setings.
require_once 'includes/dev-functions.php';
require_once 'admin/aux-functions/twchr-i18n.php';
require_once 'admin/twtchr-menus.php';
require_once TWCHR_FEATURES . 'twitch-alerts.php';
require_once TWCHR_FEATURES . 'twtchr-easy-set-up.php';
require_once TWCHR_FEATURES . 'twtchr-activate.php';
require_once TWCHR_FEATURES . 'twtchr-deactivate.php';
require_once TWCHR_FEATURES . 'twchr-delete-schedule.php';
require_once TWCHR_FEATURES . 'twchr_flush_cache.php';
require_once TWCHR_FEATURES . 'twchr-ajax-porsess.php';



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

// Enqueue.
require_once 'includes/assets/plugin-enqueue.php';

/** TWITCHER API
require_once 'twitcher-api/from-db.php';
require_once 'twitcher-api/twchr-twchrapi-controller.php';
**/

require_once TWCHR_FEATURES . 'twchr_clipboard.php';

// End Points.
require_once 'includes/ext-wp-api.php';
