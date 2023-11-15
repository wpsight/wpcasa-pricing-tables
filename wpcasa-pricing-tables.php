<?php
/*
Plugin Name: WPCasa Pricing Tables
Plugin URI: https://wpcasa.com/
Description: Add pricing tables to WPCasa using a shortcode.
Version: 1.0.1
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.6
Text Domain: wpcasa-pricing-tables
Domain Path: /languages

	Copyright: 2015 Simon Rimkus
	License: GNU General Public License v2.0 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
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
		define( 'WPSIGHT_PRICING_TABLES_VERSION', '1.0.1' );
		define( 'WPSIGHT_PRICING_TABLES_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_PRICING_TABLES_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		if ( is_admin() ){
			include( WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/includes/admin/class-wpsight-pricing-tables-admin.php' );
			$this->admin = new WPSight_Pricing_Tables_Admin();
		}
		
		include( WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/includes/class-wpsight-pricing-tables-general.php' );
		include( WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/includes/class-wpsight-pricing-tables-post-type.php' );
		include( WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/includes/class-wpsight-pricing-tables-shortcode.php' );

		// Actions
		
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
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
	 *	load_plugin_textdomain()
	 *	
	 *	Set up localization for this plugin
	 *	loading the text domain.
	 *	
	 *	@uses	load_plugin_textdomain()
	 *	@uses	plugin_basename()
	 *
	 *	@since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpcasa-pricing-tables', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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
	public function frontend_scripts() {
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		
		if( true == apply_filters( 'wpsight_pricing_tables_css', true ) )
			wp_enqueue_style( 'wpsight-pricing-tables', WPSIGHT_PRICING_TABLES_PLUGIN_URL . '/assets/css/wpsight-pricing-tables' . $suffix . '.css' );
		
	}
	
}

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Pricing_Tables', 'init' ) );
