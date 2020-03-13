<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Pricing_Tables_Admin class
 */
class WPSight_Pricing_Tables_Admin {

	/**
	 *	Constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ), 20 );
	}
	
	/**
	 *	admin_enqueue_scripts()
	 *	
	 *	Enqueue scripts and styles used
	 *	on WordPress admin pages.
	 *	
	 *	@access	public
	 *	@uses	get_current_screen()
	 *	@uses	wp_enqueue_style()
	 *	@uses	wp_register_script()
	 *	@uses	wp_enqueue_script()
	 *	
	 *	@since 1.0.0
	 */
	public static function admin_enqueue_scripts() {
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		$screen		= get_current_screen();		
		$post_type	= 'pricing_table';

		if ( in_array( $screen->id, array( 'edit-' . $post_type, $post_type ) ) )
			wp_enqueue_style( 'wpsight-pricing-tables-meta-boxes', WPSIGHT_PRICING_TABLES_PLUGIN_URL . '/assets/css/wpsight-pricing-tables-meta-boxes' . $suffix . '.css' );

	}

}
