<?php
/**
 * Plugin Name:       WPP Post Series
 * Plugin URI:        https://pyksid.com/wpp-post-series/
 * Description:       Group WordPress Posts into Series.
 * Version:           2.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.1
 * Author:            PYKSID
 * Author URI:        https://pyksid.com
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wpp-post-series
 * Domain Path:       /languages
 */

namespace WPPPostSeries;

// ** Constants ** //
const PLUGIN_FILE = __FILE__;
const PLUGIN_SLUG = 'wpp-post-series';
const PLUGIN_SETTINGS_PAGE = PLUGIN_SLUG;
const PLUGIN_SETTINGS_OPTION_NAME = PLUGIN_SLUG . '_options';

// ** Includes ** //
require __DIR__ . '/inc/functions.php';
require __DIR__ . '/inc/filters.php';
require __DIR__ . '/inc/actions.php';
