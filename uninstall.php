<?php
/**
 * Remove this plugin's one settings option on uninstall.
 *
 * @package Digicyfr_Business_Details_PL
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$digicyfr_business_details_pl_option = 'digicyfr_business_details_pl_settings';

if ( is_multisite() ) {
	$digicyfr_business_details_pl_offset = 0;
	do {
		$digicyfr_business_details_pl_ids = get_sites(
			array(
				'fields' => 'ids',
				'number' => 100,
				'offset' => $digicyfr_business_details_pl_offset,
			)
		);
		foreach ( $digicyfr_business_details_pl_ids as $digicyfr_business_details_pl_site_id ) {
			switch_to_blog( $digicyfr_business_details_pl_site_id );
			delete_option( $digicyfr_business_details_pl_option );
			restore_current_blog();
		}
		$digicyfr_business_details_pl_site_count = count( $digicyfr_business_details_pl_ids );
		$digicyfr_business_details_pl_offset    += $digicyfr_business_details_pl_site_count;
	} while ( 100 === $digicyfr_business_details_pl_site_count );
} else {
	delete_option( $digicyfr_business_details_pl_option );
}
