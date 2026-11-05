<?php
/** Exercise the exact ZIP after installation in a disposable WordPress site. */

declare( strict_types=1 );

$port = getenv( 'DIGICYFR_TEST_PORT' );
$_SERVER['HTTP_HOST']    = '127.0.0.1:' . $port;
$_SERVER['SERVER_NAME']  = '127.0.0.1';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SERVER_PORT']  = $port;
$_SERVER['HTTPS']       = 'off';

require '/var/www/html/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/template.php';

function check( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: $message\n" );
		exit( 1 );
	}
}

$option      = 'digicyfr_business_details_pl_settings';
$plugin_file = 'digicyfr-business-details-pl/digicyfr-business-details-pl.php';

check( is_plugin_active( $plugin_file ), 'plugin active from ZIP' );
check( shortcode_exists( 'digicyfr_business_details' ), 'shortcode registered on init' );
$block_type = WP_Block_Type_Registry::get_instance()->get_registered( 'digicyfr/business-details' );
check( $block_type instanceof WP_Block_Type, 'block registered on init' );
check( in_array( 'digicyfr-business-details-pl-editor', $block_type->editor_script_handles, true ), 'block editor script attached' );
check( in_array( 'digicyfr-business-details-pl-style', $block_type->style_handles, true ), 'block style attached' );

do_action( 'admin_init' );
$registered = get_registered_settings();
check( isset( $registered[ $option ] ), 'Settings API option registered' );
check( empty( $registered[ $option ]['show_in_rest'] ), 'settings not exposed via REST' );

$admin = get_user_by( 'login', 'digicyfr_test_admin' );
check( $admin instanceof WP_User, 'synthetic administrator exists' );
wp_set_current_user( $admin->ID );
check( current_user_can( 'manage_options' ), 'administrator can manage settings' );

update_option(
	$option,
	wp_unslash( wp_slash( array(
		'name'    => 'Example Sp. z o.o. C:\\Projekt',
		'address' => "ul. Przykładowa 1\n00-001 Warszawa",
		'nip'     => '012-345-6789',
		'email'   => 'hello@example.invalid',
		'website' => 'https://example.invalid',
	) ) )
);
$stored = get_option( $option );
check( '0123456789' === $stored['nip'], 'NIP normalized on real option save' );
check( 'Example Sp. z o.o. C:\\Projekt' === $stored['name'], 'normal settings save preserves a literal backslash' );

$shortcode = do_shortcode( '[digicyfr_business_details fields="name,nip"]' );
$block     = do_blocks( '<!-- wp:digicyfr/business-details {"fields":["name","nip"]} /-->' );
check( $shortcode === $block, 'block and shortcode render identical selected fields' );
check( false !== strpos( $block, 'Example Sp. z o.o.' ), 'saved name rendered' );
check( false !== strpos( $block, '0123456789' ), 'saved NIP rendered' );
check( false === strpos( $block, 'Przykładowa' ), 'unselected address omitted' );
check( '' === do_shortcode( '[digicyfr_business_details fields="regon"]' ), 'empty selection produces no markup' );

update_option( $option, array_merge( $stored, array( 'nip' => '1111111111' ) ) );
$stored = get_option( $option );
check( '0123456789' === $stored['nip'], 'invalid NIP did not replace saved NIP' );
$errors = get_settings_errors( $option );
check( count( $errors ) > 0, 'invalid NIP creates Settings API error' );

update_option( $option, array_merge( $stored, array( 'email' => 'bad name@example.com', 'website' => 'example.com' ) ) );
$stored = get_option( $option );
check( 'hello@example.invalid' === $stored['email'], 'invalid raw email did not silently normalize' );
check( 'https://example.invalid' === $stored['website'], 'schemeless website did not silently gain a scheme' );

$raw = array( 'name' => '<img src=x onerror=alert(1)>', 'website' => 'javascript:alert(1)' );
$raw_filter = static function () use ( $raw ): array { return $raw; };
add_filter( 'pre_option_' . $option, $raw_filter );
$escaped = Digicyfr_Business_Details_PL::render_details( array( 'name', 'website' ) );
remove_filter( 'pre_option_' . $option, $raw_filter );
check( false === strpos( $escaped, '<img' ), 'hostile stored HTML escaped' );
check( false === strpos( $escaped, 'href="javascript:' ), 'unsafe stored URL not linked' );

$contributor_id = wp_create_user( 'digicyfr_test_contributor', 'test-only-password', 'contributor@example.invalid' );
check( ! is_wp_error( $contributor_id ), 'synthetic contributor created' );
( new WP_User( $contributor_id ) )->set_role( 'contributor' );
wp_set_current_user( $contributor_id );
check( ! current_user_can( 'manage_options' ), 'contributor lacks settings capability' );
add_filter(
	'wp_die_handler',
	static function (): callable {
		return static function ( $message ): void {
			throw new RuntimeException( wp_strip_all_tags( (string) $message ) );
		};
	}
);
$denied = false;
try {
	Digicyfr_Business_Details_PL::admin_page();
} catch ( RuntimeException $exception ) {
	$denied = true;
}
check( $denied, 'contributor denied settings page' );

wp_set_current_user( $admin->ID );
check( load_textdomain( 'digicyfr-business-details-pl', WP_PLUGIN_DIR . '/digicyfr-business-details-pl/languages/digicyfr-business-details-pl-pl_PL.mo' ), 'Polish catalog loads' );
check( 'Pełna nazwa firmy' === __( 'Legal name', 'digicyfr-business-details-pl' ), 'Polish translation works' );

deactivate_plugins( $plugin_file );
uninstall_plugin( $plugin_file );
global $wpdb;
$remaining = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name = %s", $option ) );
check( 0 === (int) $remaining, 'uninstall removes plugin option' );

echo "Business Details PL WordPress integration tests passed\n";
