<?php
/**
 * Business details settings and presentation.
 *
 * @package Digicyfr_Business_Details_PL
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Isolated plugin implementation.
 */
final class Digicyfr_Business_Details_PL {
	/** Option key. */
	private const OPTION = 'digicyfr_business_details_pl_settings';

	/** Text domain. */
	private const DOMAIN = 'digicyfr-business-details-pl';

	/** Available public fields in their output order. */
	private const FIELD_KEYS = array( 'name', 'address', 'nip', 'regon', 'krs', 'email', 'phone', 'website' );

	/** Register WordPress hooks. */
	public static function boot(): void {
		add_action( 'init', array( __CLASS__, 'on_init' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_page' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_style' ) );
	}

	/** Load translations and register display surfaces. */
	public static function on_init(): void {
		load_plugin_textdomain( self::DOMAIN, false, dirname( plugin_basename( DIGICYFR_BUSINESS_DETAILS_PL_PATH . 'digicyfr-business-details-pl.php' ) ) . '/languages' );
		add_shortcode( 'digicyfr_business_details', array( __CLASS__, 'shortcode' ) );
		self::register_block();
	}

	/** Labels are shared by settings, the block editor and public output. */
	public static function field_labels(): array {
		return array(
			'name'    => __( 'Legal name', 'digicyfr-business-details-pl' ),
			'address' => __( 'Address', 'digicyfr-business-details-pl' ),
			'nip'     => __( 'NIP', 'digicyfr-business-details-pl' ),
			'regon'   => __( 'REGON', 'digicyfr-business-details-pl' ),
			'krs'     => __( 'KRS', 'digicyfr-business-details-pl' ),
			'email'   => __( 'Email', 'digicyfr-business-details-pl' ),
			'phone'   => __( 'Phone', 'digicyfr-business-details-pl' ),
			'website' => __( 'Website', 'digicyfr-business-details-pl' ),
		);
	}

	/** Register a single per-site option using the Settings API. */
	public static function register_settings(): void {
		register_setting(
			'digicyfr_business_details_pl',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ),
				'default'           => array(),
			)
		);
		add_settings_section(
			'digicyfr_business_details_pl_identity',
			__( 'Business identity', 'digicyfr-business-details-pl' ),
			array( __CLASS__, 'settings_intro' ),
			'digicyfr_business_details_pl'
		);
		foreach ( self::field_labels() as $key => $label ) {
			add_settings_field(
				'digicyfr_business_details_pl_' . $key,
				$label,
				array( __CLASS__, 'settings_field' ),
				'digicyfr_business_details_pl',
				'digicyfr_business_details_pl_identity',
				array(
					'key'       => $key,
					'label_for' => 'digicyfr-business-details-pl-' . $key,
				)
			);
		}
	}

	/** Explain the limits of locally entered details. */
	public static function settings_intro(): void {
		echo '<p>' . esc_html__( 'Enter the details you want to make available. Each block or shortcode chooses which populated fields to display. NIP validation checks its format and checksum only; it does not verify registration or VAT status.', 'digicyfr-business-details-pl' ) . '</p>';
	}

	/**
	 * Render one labeled Settings API input.
	 *
	 * @param array $args Field registration arguments.
	 */
	public static function settings_field( array $args ): void {
		$key     = $args['key'];
		$id      = 'digicyfr-business-details-pl-' . $key;
		$values  = self::get_settings();
		$value   = $values[ $key ] ?? '';
		$name    = self::OPTION . '[' . $key . ']';
		$classes = 'regular-text';

		if ( 'address' === $key ) {
			printf(
				'<textarea id="%1$s" name="%2$s" rows="3" cols="50" class="%3$s">%4$s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $classes ),
				esc_textarea( $value )
			);
		} else {
			$type = 'email' === $key ? 'email' : ( 'website' === $key ? 'url' : 'text' );
			printf(
				'<input id="%1$s" name="%2$s" type="%3$s" value="%4$s" class="%5$s" maxlength="255" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $type ),
				esc_attr( $value ),
				esc_attr( $classes )
			);
		}
		if ( 'nip' === $key ) {
			echo '<p class="description">' . esc_html__( 'Enter ten digits; spaces and hyphens are allowed. This is a local checksum check only.', 'digicyfr-business-details-pl' ) . '</p>';
		}
	}

	/**
	 * Sanitize all submitted fields, retaining the previous NIP on an invalid entry.
	 *
	 * @param mixed $input Submitted settings value.
	 * @return array Sanitized settings.
	 */
	public static function sanitize_settings( $input ): array {
		$old   = self::get_settings();
		$input = is_array( $input ) ? $input : array();
		$clean = array();

		foreach ( self::FIELD_KEYS as $key ) {
			$raw = isset( $input[ $key ] ) && is_scalar( $input[ $key ] ) ? (string) $input[ $key ] : '';
			if ( 'address' === $key ) {
				$clean[ $key ] = sanitize_textarea_field( $raw );
			} elseif ( 'email' === $key ) {
				$raw   = trim( $raw );
				$value = sanitize_email( $raw );
				if ( '' !== $raw && ( ! is_email( $raw ) || '' === $value || ! is_email( $value ) ) ) {
					add_settings_error( self::OPTION, 'invalid_email', __( 'Enter a valid email address. The previous email was kept.', 'digicyfr-business-details-pl' ) );
					$clean[ $key ] = $old[ $key ] ?? '';
				} else {
					$clean[ $key ] = $value;
				}
			} elseif ( 'website' === $key ) {
				$raw             = trim( $raw );
				$original_scheme = wp_parse_url( $raw, PHP_URL_SCHEME );
				$value           = esc_url_raw( $raw, array( 'http', 'https' ) );
				$scheme          = wp_parse_url( $value, PHP_URL_SCHEME );
				$host            = wp_parse_url( $value, PHP_URL_HOST );
				if ( '' !== $raw && ( ! in_array( $original_scheme, array( 'http', 'https' ), true ) || ! in_array( $scheme, array( 'http', 'https' ), true ) || ! $host ) ) {
					add_settings_error( self::OPTION, 'invalid_website', __( 'Enter a full http or https website URL. The previous website was kept.', 'digicyfr-business-details-pl' ) );
					$clean[ $key ] = $old[ $key ] ?? '';
				} else {
					$clean[ $key ] = $value;
				}
			} elseif ( 'nip' === $key ) {
				$value = preg_replace( '/[ \t\r\n-]+/', '', $raw );
				if ( '' !== $value && ! self::valid_nip( $value ) ) {
					add_settings_error( self::OPTION, 'invalid_nip', __( 'NIP must contain ten digits with a valid checksum. The previous NIP was kept. This does not verify registration or VAT status.', 'digicyfr-business-details-pl' ) );
					$clean[ $key ] = $old[ $key ] ?? '';
				} else {
					$clean[ $key ] = $value;
				}
			} else {
				$clean[ $key ] = sanitize_text_field( $raw );
			}
		}
		return $clean;
	}

	/**
	 * Check the mathematical NIP checksum without any external lookup.
	 *
	 * @param string $nip Ten-digit NIP.
	 * @return bool Whether the format and checksum are valid.
	 */
	public static function valid_nip( string $nip ): bool {
		if ( ! preg_match( '/^[0-9]{10}$/D', $nip ) ) {
			return false;
		}
		if ( preg_match( '/^([0-9])\1{9}$/D', $nip ) ) {
			return false;
		}
		$weights = array( 6, 5, 7, 2, 3, 4, 5, 6, 7 );
		$sum     = 0;
		foreach ( $weights as $index => $weight ) {
			$sum += (int) $nip[ $index ] * $weight;
		}
		$check = $sum % 11;
		return $check < 10 && $check === (int) $nip[9];
	}

	/** Register the settings page under Settings. */
	public static function register_admin_page(): void {
		add_options_page(
			__( 'Business Details PL', 'digicyfr-business-details-pl' ),
			__( 'Business Details PL', 'digicyfr-business-details-pl' ),
			'manage_options',
			'digicyfr-business-details-pl',
			array( __CLASS__, 'admin_page' )
		);
	}

	/** Render the owner-only settings page. */
	public static function admin_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to manage business details.', 'digicyfr-business-details-pl' ) );
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Business Details PL', 'digicyfr-business-details-pl' ); ?></h1>
			<?php settings_errors( self::OPTION ); ?>
			<form action="options.php" method="post">
				<?php settings_fields( 'digicyfr_business_details_pl' ); ?>
				<?php do_settings_sections( 'digicyfr_business_details_pl' ); ?>
				<?php submit_button( __( 'Save business details', 'digicyfr-business-details-pl' ) ); ?>
			</form>
			<p><?php echo esc_html__( 'Shortcode example:', 'digicyfr-business-details-pl' ); ?> <code>[digicyfr_business_details fields="name,address,nip"]</code></p>
		</div>
		<?php
	}

	/** Register the native server-rendered block. */
	private static function register_block(): void {
		wp_register_script(
			'digicyfr-business-details-pl-editor',
			DIGICYFR_BUSINESS_DETAILS_PL_URL . 'blocks/business-details/editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor' ),
			DIGICYFR_BUSINESS_DETAILS_PL_VERSION,
			true
		);
		wp_localize_script(
			'digicyfr-business-details-pl-editor',
			'digicyfrBusinessDetailsLabels',
			array(
				'title'       => __( 'Business Details PL', 'digicyfr-business-details-pl' ),
				'description' => __( 'Display selected business details entered in Settings.', 'digicyfr-business-details-pl' ),
				'fields'      => __( 'Fields to display', 'digicyfr-business-details-pl' ),
				'preview'     => __( 'Populated selected fields appear on the published page.', 'digicyfr-business-details-pl' ),
				'none'        => __( 'Select at least one field.', 'digicyfr-business-details-pl' ),
				'labels'      => self::field_labels(),
			)
		);
		wp_register_style(
			'digicyfr-business-details-pl-style',
			DIGICYFR_BUSINESS_DETAILS_PL_URL . 'assets/business-details.css',
			array(),
			DIGICYFR_BUSINESS_DETAILS_PL_VERSION
		);
		register_block_type(
			DIGICYFR_BUSINESS_DETAILS_PL_PATH . 'blocks/business-details',
			array(
				'render_callback' => array( __CLASS__, 'render_block' ),
			)
		);
	}

	/** Style both block and shortcode output. */
	public static function enqueue_frontend_style(): void {
		wp_enqueue_style( 'digicyfr-business-details-pl-style' );
	}

	/**
	 * Server-rendered block callback.
	 *
	 * @param array $attributes Block attributes.
	 * @return string Public HTML.
	 */
	public static function render_block( array $attributes ): string {
		$fields = isset( $attributes['fields'] ) && is_array( $attributes['fields'] ) ? $attributes['fields'] : self::FIELD_KEYS;
		return self::render_details( $fields );
	}

	/**
	 * Shortcode callback using the same renderer as the block.
	 *
	 * @param mixed $attributes Shortcode attributes.
	 * @return string Public HTML.
	 */
	public static function shortcode( $attributes ): string {
		$attributes = shortcode_atts(
			array( 'fields' => implode( ',', self::FIELD_KEYS ) ),
			is_array( $attributes ) ? $attributes : array(),
			'digicyfr_business_details'
		);
		$fields     = array_map( 'trim', explode( ',', (string) $attributes['fields'] ) );
		return self::render_details( $fields );
	}

	/**
	 * Render only requested, populated and recognized fields.
	 *
	 * @param array $requested Field keys selected by the author.
	 * @return string Public HTML.
	 */
	public static function render_details( array $requested ): string {
		$settings = self::get_settings();
		$labels   = self::field_labels();
		$selected = array();
		foreach ( self::FIELD_KEYS as $key ) {
			if ( in_array( $key, $requested, true ) && isset( $settings[ $key ] ) && is_string( $settings[ $key ] ) && '' !== trim( $settings[ $key ] ) ) {
				$selected[] = $key;
			}
		}
		if ( array() === $selected ) {
			return '';
		}

		$html = '<dl class="digicyfr-business-details-pl">';
		foreach ( $selected as $key ) {
			$value = $settings[ $key ];
			if ( 'address' === $key ) {
				$display = nl2br( esc_html( $value ) );
			} elseif ( 'website' === $key ) {
				$url = esc_url( $value, array( 'http', 'https' ) );
				if ( '' === $url ) {
					continue;
				}
				$display = '<a href="' . esc_url( $url ) . '">' . esc_html( $value ) . '</a>';
			} elseif ( 'email' === $key && is_email( $value ) ) {
				$display = '<a href="' . esc_url( 'mailto:' . $value, array( 'mailto' ) ) . '">' . esc_html( $value ) . '</a>';
			} else {
				$display = esc_html( $value );
			}
			$html .= '<div class="digicyfr-business-details-pl__item digicyfr-business-details-pl__item--' . esc_attr( $key ) . '">';
			$html .= '<dt>' . esc_html( $labels[ $key ] ) . '</dt><dd>' . $display . '</dd></div>';
		}
		return $html . '</dl>';
	}

	/** Read the single settings option safely. */
	private static function get_settings(): array {
		$value = get_option( self::OPTION, array() );
		return is_array( $value ) ? $value : array();
	}
}
