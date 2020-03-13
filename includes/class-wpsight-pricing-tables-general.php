<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Pricing_Tables_General
 */
class WPSight_Pricing_Tables_General {

    /**
	 *	Initialize class
	 */
	public static function init() {
    }
    
    /**
	 *	get_pricing_table()
	 *
     *	Get a specific (active) pricing_table by ID.
     *	
     *	@access	public
     *	@param	interger	$pricing_table_id
     *	@uses	get_post()
     *	@return	object
     *
     *	@since	1.1.0
     */
    public static function get_pricing_table( $pricing_table_id ) {

        $post = get_post( $pricing_table_id );

        if( $post->post_type != 'pricing_table' || $post->post_status != 'publish' )
            return false;

        return $post;
    }

    /**
	 *	pricing_table_exists()
	 *		    
     *	Check if a specific pricing_table exists by ID.
     *	
     *	@access	public
     *	@param	integer	$pricing_table_id
     *	@return bool
     */
    public static function pricing_table_exists( $pricing_table_id = false ) {

        $pricing_table = self::get_pricing_table( $pricing_table_id );

        return is_object( $pricing_table );
    }

    /**
	 *	get_pricing_tables()
	 *
     *	Return a list of pricing_tables.
     *	
     *	@access	public
     *	@return array
     *
     *	@since	1.1.0
     */
    public static function get_pricing_tables( $args = array() ) {
	    
	    $defaults = array(
		    'post_type'         => 'pricing_table',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
	    );
	    
	    $args = wp_parse_args( $args, $defaults );

        $pricing_tables_query = new WP_Query( $args );

        return $pricing_tables_query->posts;

    }

    /**
	 *	format_price()
	 *
     *	Get a specific (active) pricing_table by ID.
     *	
     *	@access	public
     *	@param	interger	$pricing_table_id
     *	@uses	get_post()
     *	@return	object
     *
     *	@since	1.1.0
     */
    public static function format_price( $price ) {

        if ( empty( $price ) )
        	return;

		$price_arr = false;

		// Remove white spaces
		$price = preg_replace( '/\s+/', '', $price );

		if ( strpos( $price, ',' ) )
			$price_arr = explode( ',', $price );

		if ( strpos( $price, '.' ) )
			$price_arr = explode( '.', $price );

		if ( is_array( $price_arr ) )
			$price = $price_arr[0];

		// remove dots and commas

		$price = str_replace( '.', '', $price );
		$price = str_replace( ',', '', $price );

		if ( is_numeric( $price ) ) {

			// Get thousands separator
			$price_format = wpsight_get_option( 'currency_separator', true );

			// Add thousands separators

			if ( $price_format == 'dot' ) {
				$price = number_format( $price, 0, ',', '.' );
				if ( is_array( $price_arr ) )
					$price .= ',' . $price_arr[1];
			} else {
				$price = number_format( $price, 0, '.', ',' );
				if ( is_array( $price_arr ) )
					$price .= '.' . $price_arr[1];
			}

		}
			
		// Get currency symbol and place before or after value
		$currency_symbol = wpsight_get_option( 'currency_symbol', true );

		// Create price markup and place currency before or after value

		if ( $currency_symbol == 'after' ) {
			$price_symbol  = '<span class="plan-price-value" itemprop="price" content="'. esc_attr( $price ) .'">' . $price . '</span><!-- .plan-price-value -->';
			$price_symbol .= '<span class="plan-price-symbol">' . wpsight_get_currency() . '</span><!-- .plan-price-symbol -->';
		} else {
			$price_symbol  = '<span class="plan-price-symbol">' . wpsight_get_currency() . '</span><!-- .plan-price-symbol -->';
			$price_symbol .= '<span class="plan-price-value" itemprop="price" content="'. esc_attr( $price ) .'">' . $price . '</span><!-- .plan-price-value -->';
		}

		// Merge price with markup and currency
		$price = $price_symbol;

		return $price;

    }
    
}

WPSight_Pricing_Tables_General::init();
