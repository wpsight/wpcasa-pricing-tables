<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Pricing_Tables_Shortcode
 */
class WPSight_Pricing_Tables_Shortcode {

	/**
	 *	Initialize class
	 */
	public static function init() {		
		add_shortcode( 'wpsight_pricing_table', array( __CLASS__, 'pricing_table' ) );
	}

	/**
	 *	pricing_table()
	 *
	 *	Get the template for the pricing_table shortcode.
	 *	
	 *	@access	public
	 *	@param	array	$atts
	 *	@uses	wpsight_get_template()
	 *	@return	mixed
	 *
	 *	@since	1.1.0
	 */
	public static function pricing_table( $atts = array() ) {
		
		// Define defaults
        
        $defaults = array(
	        'id'			=> false,
	        'show_title'	=> true,
	        'show_subtitle'	=> true,
	        'show_note'		=> true,
	        'show_ribbon'	=> true,
        );
        
        // Merge shortcodes atts with defaults
        $args = shortcode_atts( $defaults, $atts );
        
        $args['show_title'] 	= 'true' === $args['show_title'] || true === $args['show_title'] ? true : false;
        $args['show_subtitle'] 	= 'true' === $args['show_subtitle'] || true === $args['show_subtitle'] ? true : false;
        $args['show_note'] 		= 'true' === $args['show_note'] || true === $args['show_note'] ? true : false;
        $args['show_ribbon'] 	= 'true' === $args['show_ribbon'] || true === $args['show_ribbon'] ? true : false;
        
        if( false === $args['id'] ) {
	        $pricing_tables = WPSight_Pricing_Tables_General::get_pricing_tables( array( 'posts_per_page' => 1 ) );
	        $args['id'] = isset( $pricing_tables[0]->ID ) ? $pricing_tables[0]->ID : false;
        }
		
		ob_start();

		if( false !== $args['id'] && WPSight_Pricing_Tables_General::pricing_table_exists( $args['id'] ) ) {
		    wpsight_get_template( 'pricing-table.php', array( 'args' => $args ), WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/templates' );
		    return ob_get_clean();
		}
		
		wpsight_get_template( 'pricing-table-no.php', null, WPSIGHT_PRICING_TABLES_PLUGIN_DIR . '/templates' );

		return ob_get_clean();

	}

}

WPSight_Pricing_Tables_Shortcode::init();
