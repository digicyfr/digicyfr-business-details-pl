<?php
/** Small dependency-free tests for validation and shared rendering. */

declare( strict_types=1 );

define( 'ABSPATH', '/test/' );

$test_settings = array();
$test_errors   = array();

function __( string $value, string $domain = '' ): string { return $value; }
function esc_html( string $value ): string { return htmlspecialchars( $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' ); }
function esc_attr( string $value ): string { return esc_html( $value ); }
function esc_url( string $value, array $protocols = array() ): string {
	$scheme = parse_url( $value, PHP_URL_SCHEME );
	return in_array( $scheme, $protocols, true ) ? esc_html( $value ) : '';
}
function esc_url_raw( string $value, array $protocols = array() ): string {
	$scheme = parse_url( $value, PHP_URL_SCHEME );
	return in_array( $scheme, $protocols, true ) ? $value : '';
}
function wp_parse_url( string $value, int $part ) { return parse_url( $value, $part ); }
function wp_unslash( string $value ): string { return stripslashes( $value ); }
function sanitize_text_field( string $value ): string { return trim( strip_tags( $value ) ); }
function sanitize_textarea_field( string $value ): string { return trim( strip_tags( $value ) ); }
function sanitize_email( string $value ): string { return filter_var( $value, FILTER_SANITIZE_EMAIL ); }
function is_email( string $value ): bool { return false !== filter_var( $value, FILTER_VALIDATE_EMAIL ); }
function add_settings_error( string $setting, string $code, string $message ): void {
	global $test_errors;
	$test_errors[ $code ] = $message;
}
function get_option( string $key, $default = false ) {
	global $test_settings;
	return $test_settings ? $test_settings : $default;
}
function shortcode_atts( array $defaults, array $attributes, string $shortcode ): array {
	return array_merge( $defaults, array_intersect_key( $attributes, $defaults ) );
}

require dirname( __DIR__ ) . '/includes/class-digicyfr-business-details-pl.php';

function check( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: $message\n" );
		exit( 1 );
	}
}

check( Digicyfr_Business_Details_PL::valid_nip( '1234563218' ), 'valid synthetic NIP' );
check( Digicyfr_Business_Details_PL::valid_nip( '0123456789' ), 'leading zero preserved' );
check( ! Digicyfr_Business_Details_PL::valid_nip( '1234563219' ), 'wrong checksum rejected' );
check( ! Digicyfr_Business_Details_PL::valid_nip( '0000000000' ), 'all-zero value rejected' );
check( ! Digicyfr_Business_Details_PL::valid_nip( '1111111111' ), 'repeated-digit value rejected' );
check( ! Digicyfr_Business_Details_PL::valid_nip( '1234567890' ), 'modulo 10 result rejected' );
check( ! Digicyfr_Business_Details_PL::valid_nip( '123-456-3218' ), 'validator accepts only normalized digits' );

$test_settings = array( 'nip' => '0123456789', 'email' => 'old@example.com', 'website' => 'https://example.com' );
$clean = Digicyfr_Business_Details_PL::sanitize_settings(
	array(
		'name'    => '<script>alert(1)</script> Example C:\\Projekt Ltd',
		'address' => "Street 1\nCity",
		'nip'     => '123-456-3218',
		'email'   => 'new@example.com',
		'website' => 'https://example.org',
	)
);
check( '1234563218' === $clean['nip'], 'NIP spaces and hyphens normalize' );
check( false === strpos( $clean['name'], '<script>' ), 'markup removed on save' );
check( false !== strpos( $clean['name'], 'C:\\Projekt' ), 'literal backslash preserved' );
check( 'new@example.com' === $clean['email'], 'email saved' );

$test_errors = array();
$clean       = Digicyfr_Business_Details_PL::sanitize_settings(
	array( 'nip' => '1234563219', 'email' => 'bad name@example.com', 'website' => 'example.com' )
);
check( '0123456789' === $clean['nip'], 'invalid NIP leaves old NIP' );
check( 'old@example.com' === $clean['email'], 'invalid email leaves old email' );
check( 'https://example.com' === $clean['website'], 'invalid website leaves old website' );
check( isset( $test_errors['invalid_nip'], $test_errors['invalid_email'], $test_errors['invalid_website'] ), 'errors recorded' );

$test_settings = array(
	'name'    => '<script>alert(1)</script> Example Ltd',
	'address' => "Street 1\nCity",
	'nip'     => '0123456789',
	'regon'   => '',
	'website' => 'https://example.com/?a=1&b=2',
);
$output = Digicyfr_Business_Details_PL::render_details( array( 'name', 'address', 'regon', 'website', 'unknown' ) );
check( false === strpos( $output, '<script>' ), 'stored markup escaped' );
check( false !== strpos( $output, '&lt;script&gt;' ), 'escaped text retained' );
check( false !== strpos( $output, 'Street 1<br' ), 'address line breaks rendered' );
check( false === strpos( $output, '<dt>REGON</dt>' ), 'empty field omitted' );
check( false === strpos( $output, '<dt>NIP</dt>' ), 'unselected field omitted' );
check( false === strpos( $output, 'javascript:' ), 'no unsafe link' );
check( '' === Digicyfr_Business_Details_PL::render_details( array( 'regon' ) ), 'no wrapper for empty selection' );
check(
	Digicyfr_Business_Details_PL::shortcode( array( 'fields' => 'name,address,nip' ) )
	=== Digicyfr_Business_Details_PL::render_block( array( 'fields' => array( 'name', 'address', 'nip' ) ) ),
	'block and shortcode render identically'
);

echo "Business Details PL tests passed\n";
