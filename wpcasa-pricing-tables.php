<?php
/**
 * WPCasa Pricing Tables
 *
 * @package           WPCasaPricingTables
 * @author            WPSight
 * @copyright         2024 Kybernetik Services GmbH
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WPCasa Pricing Tables
 * Plugin URI:        https://wpcasa.com/downloads/wpcasa-pricing-tables/
 * Description:       Add pricing tables to WPCasa using a shortcode.
 * Version:           1.0.3
 * Requires at least: 6.2
 * Requires PHP:      7.2
 * Requires Plugins:  wpcasa
 * Author:            WPSight
 * Author URI:        https://wpcasa.com
 * Text Domain:       wpcasa-pricing-tables
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 *	WPSight_Pricing_Tables class
 */
class WPSight_Pricing_Tables {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );
		
		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );

		define( 'WPSIGHT_PRICING_TABLES_NAME', 'WPCasa Pricing Tables' );
		define( 'WPSIGHT_PRICING_TABLES_DOMAIN', 'wpcasa-pricing-tables' );
		define( 'WPSIGHT_PRICING_TABLES_VERSION', '1.0.3' );
		define( 'WPSIGHT_PRICING_TABLES_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_PRICING_TABLES_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		if ( is_admin() ){
			include( WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/includes/admin/class-wpsight-pricing-tables-admin.php' );
			$this->admin = new WPSight_Pricing_Tables_Admin();

            add_action( 'admin_enqueue_scripts', array( $this, 'backend_scripts' ) );
		}
		
		include( WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/includes/class-wpsight-pricing-tables-general.php' );
		include( WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/includes/class-wpsight-pricing-tables-post-type.php' );
		include( WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/includes/class-wpsight-pricing-tables-shortcode.php' );

		// Actions
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

	}

	/**
	 *	init()
	 *
	 *	Initialize the plugin when WPCasa is loaded.
	 *
	 *  @param	object	$wpsight
	 *	@uses	do_action_ref_array()
	 *  @return object	$wpsight->pricing_tables
	 *
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {
		
		if ( ! isset( $wpsight->pricing_tables ) )
			$wpsight->pricing_tables = new self();

		do_action_ref_array( 'wpsight_init_pricing_tables', array( &$wpsight ) );

		return $wpsight->pricing_tables;

	}

	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue scripts and css.
	 *	
	 *	@uses	wp_enqueue_style()
	 *
	 *	@since 1.0.0
	 */
	public function frontend_scripts(): void {
		
		// Script debugging?
		$suffix  = SCRIPT_DEBUG ? '' : '.min';
        $version = SCRIPT_DEBUG ? rand( 100,1000 ) : WPSIGHT_PRICING_TABLES_VERSION;

        if( apply_filters('wpsight_pricing_tables_css', true) )
			wp_enqueue_style( 'wpsight-pricing-tables', WPSIGHT_PRICING_TABLES_PLUGIN_URL . '/assets/css/wpsight-pricing-tables' . $suffix . '.css', NULL, $version );
		
	}


    /**
     *	backend_scripts()
     *
     *	Register and enqueue scripts in the WordPress admin
     *
     *	@uses	wp_enqueue_style()
     *
     *	@since 1.0.2
     */
    public function backend_scripts( $hook ): void {

        // Get current screen object
        $screen = get_current_screen();

        if ( 'post.php' != $hook || 'pricing_table' != $screen->id ) {
            return;
        }

        // Script debugging?
        $suffix  = SCRIPT_DEBUG ? '' : '.min';
        $version = SCRIPT_DEBUG ? rand( 100,1000 ) : WPSIGHT_PRICING_TABLES_VERSION;

        wp_enqueue_script( 'wpsight-pricing-tables-admin', WPSIGHT_PRICING_TABLES_PLUGIN_URL . '/assets/js/admin/wpsight-pricing-tables-admin' . $suffix . '.js', array( 'jquery-core' ), $version );
        wp_add_inline_script( 'wpsight-pricing-tables-admin', 'var limit = ' . apply_filters( 'wpsight_pricing_table_limit', 4 ), 'before' );

    }

}

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Pricing_Tables', 'init' ) );
