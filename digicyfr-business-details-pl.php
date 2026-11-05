<?php
/**
 * Plugin Name: Digicyfr Business Details PL
 * Description: Display owner-entered Polish business details with a block or shortcode.
 * Version: 0.1.0
 * Requires at least: 7.1
 * Requires PHP: 8.3
 * Author: Digicyfr
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: digicyfr-business-details-pl
 * Domain Path: /languages
 * Update URI: https://digicyfr.com/plugins/digicyfr-business-details-pl/
 *
 * @package Digicyfr_Business_Details_PL
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DIGICYFR_BUSINESS_DETAILS_PL_VERSION', '0.1.0' );
define( 'DIGICYFR_BUSINESS_DETAILS_PL_PATH', plugin_dir_path( __FILE__ ) );
define( 'DIGICYFR_BUSINESS_DETAILS_PL_URL', plugin_dir_url( __FILE__ ) );

require_once DIGICYFR_BUSINESS_DETAILS_PL_PATH . 'includes/class-digicyfr-business-details-pl.php';

Digicyfr_Business_Details_PL::boot();
