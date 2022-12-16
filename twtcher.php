<?php
/*
* Plugin Name: Manage Twitch Account: Easy API Integration
* Plugin URI: twitcher.pro
* Description: Manage, promote and monetise your Twitch.tv streamings integrating Twitch to Wordpress.
* Version: 0.2
* Author: Conjuntas.Club
* Author URI: conjuntas.club
* License: GPL3
* Text Domain: twitcher
* Domain Path: /languages
*/

define('TWCHR_HOME_URL', site_url());
define('TWCHR_ADMIN_URL', site_url() . '/wp-admin/');
define('TWCHR_PATH', __FILE__);
define('TWCHR_URL', plugin_dir_url(__FILE__));

define('TWCHR_URL_ASSETS', plugin_dir_url(__FILE__) . 'includes/assets/');
define('TWCHR_URL_FONTS', plugin_dir_url(__FILE__) . 'includes/assets/fonts');
//Añadí la carpeta features para poner funcionalidades del plugin.
define('TWCHR_FEATURES', plugin_dir_path(__FILE__) . 'includes/features/');

define('TWCHR_SETUP_ASSETS', plugin_dir_url(__FILE__) . '/admin/setUp-img/');
define('TWCHR_WPJSON_ROUTE', site_url() . '/wp-json/');

//setings
require_once 'includes/dev_functions.php';
require_once 'admin/aux_functions/twchr_i18n.php';
require_once 'admin/twtchr_menus.php';
require_once TWCHR_FEATURES . 'twitch_alerts.php';
require_once TWCHR_FEATURES . 'twtchr_easy_setUp.php';
require_once TWCHR_FEATURES . 'twtchr_activate.php';
require_once TWCHR_FEATURES . 'twtchr_deactivate.php';
require_once TWCHR_FEATURES . 'twchr_delete_schedule.php';



// Aux functions
require_once 'admin/aux_functions/aux_functions.php';

require_once 'includes/api-connection/twicht.php';
require_once 'includes/api-connection/twichtv.php';
require_once 'includes/api-connection/api_db.php';

// admin styles
require_once 'admin/admin_dashboard.php';

// Custom post types
require_once 'includes/cpt/streamings.php';

// Custom fields
require_once 'includes/cf/streamings.php';
require_once 'includes/cf/twchr_stream_data.php';
require_once 'includes/cf/streamings.php';
require_once 'includes/cf/schedule_card.php';

// Shortcodes
require_once 'includes/shortcode/twich_embed.php';

//Crear post stream
require_once 'includes/create-post/crear_twchr_stream.php';

//Actualizar post
require_once 'includes/update-post/update_twchr_stream.php';

// Traer un post
require_once 'includes/get-post/get_twchr_stream.php';

require_once 'includes/twchr_get_videos.php';

//Taxonomias
require_once 'includes/taxonomys/twchr_tax_serie.php';
require_once 'includes/taxonomys/cat_twcht.php';

// Enqueue
require_once 'includes/assets/plugin_enqueue.php';

require_once 'twitcher-api/from_db.php';
require_once 'twitcher-api/twchr_twchrapi_controller.php';

// End Points
require_once 'includes/ext-wp-api.php';
