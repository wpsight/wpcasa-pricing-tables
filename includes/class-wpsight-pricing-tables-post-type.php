<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class WPSight_Pricing_Tables_Post_Type
 */
class WPSight_Pricing_Tables_Post_Type {

    /**
	 *	Initialize class
	 */
	public static function init() {
        add_action( 'init', array( __CLASS__, 'definition' ) );
        add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_box_pricing_table' ) );
        add_filter( 'wpsight_meta_boxes', array( __CLASS__, 'meta_box_pricing_table_shortcode' ) );
        add_action( 'cmb2_after_post_form_pricing_table_general', array( __CLASS__, 'js_limit_group_repeat' ), 10, 2 );
        add_filter( 'wpsight_meta_box_pricing_table_fields', array( __CLASS__, 'meta_box_pricing_table_packages' ) );        
        add_filter( 'manage_edit-pricing_table_columns', array( __CLASS__, 'columns' ) );
		add_action( 'manage_pricing_table_posts_custom_column', array( __CLASS__, 'custom_columns' ), 2 );
    }

    /**
	 *	definition()
	 *
     *	Define the custom post type.
     *	
     *	@access	public
	 *
	 *	@since	1.1.0
     */
    public static function definition() {

        $labels = array(
            'name'                  => __( 'Pricing Tables', 'wpcasa-pricing-tables' ),
            'singular_name'         => __( 'Pricing Table', 'wpcasa-pricing-tables' ),
            'add_new'               => __( 'Add New', 'wpcasa-pricing-tables' ),
            'add_new_item'          => __( 'Add New Pricing Table', 'wpcasa-pricing-tables' ),
            'edit_item'             => __( 'Edit Pricing Table', 'wpcasa-pricing-tables' ),
            'new_item'              => __( 'New Pricing Table', 'wpcasa-pricing-tables' ),
            'all_items'             => __( 'Pricing Tables', 'wpcasa-pricing-tables' ),
            'view_item'             => __( 'View Pricing Table', 'wpcasa-pricing-tables' ),
            'search_items'          => __( 'Search Pricing Table', 'wpcasa-pricing-tables' ),
            'not_found'             => __( 'No Pricing Tables found', 'wpcasa-pricing-tables' ),
            'not_found_in_trash'    => __( 'No Items Found in Trash', 'wpcasa-pricing-tables' ),
            'parent_item_colon'     => '',
            'menu_name'             => __( 'Pricing Tables', 'wpcasa-pricing-tables' ),
        );

        register_post_type( 'pricing_table',
            array(
                'labels'            => $labels,
                'show_in_menu'      => true,
				'menu_position'		=> 51,
				'menu_icon'			=> 'dashicons-arrow-right-alt2',
                'supports'          => array( 'title' ),
                'public'            => false,
                'has_archive'       => false,
                'show_ui'           => true,
                'categories'        => array(),
            )
        );

    }
	
	/**
	 *	meta_box_pricing_table()
	 *	
	 *	Create pricing table meta box.
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@uses	wpsight_post_type()
	 *	@return	array
	 *	@see wpsight_meta_boxes()
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_pricing_table( $meta_boxes ) {

		// Set meta box fields

		// Set meta box fields

		$fields = array(
			'subtitle' => array(
				'name'      => __( 'Subtitle', 'wpcasa-pricing-tables' ),
				'id'        => 'pricing_table_subtitle',
				'type'      => 'text',
				'desc'		=> __( 'Enter a subtitle that is displayed before the pricing table', 'wpcasa-pricing-tables' ),
				'priority'  => 10
			),
			'pricing_plans' => array(
				'name'      	=> __( 'Pricing Plans', 'wpcasa-pricing-tables' ),
				'id'        	=> 'pricing_plans',
				'type'      	=> 'group',
				'group_fields'	=> array(
					'plan_title' => array(
						'name'		=> __( 'Title', 'wpcasa-pricing-tables' ),
						'id'		=> 'pricing_plan_title',
						'type'		=> 'text',
						'desc'		=> __( 'Enter the name of the pricing plan here', 'wpcasa-pricing-tables' )
					),
					'plan_subtitle' => array(
						'name'		=> __( 'Subtitle', 'wpcasa-pricing-tables' ),
						'id'		=> 'pricing_plan_subtitle',
						'type'		=> 'text',
						'desc'		=> __( 'Enter the subtitle of the pricing plan here', 'wpcasa-pricing-tables' )
					),
					'plan_price' => array(
						'name'              => __( 'Price', 'wpcasa-pricing-tables' ),
						'id'                => 'pricing_plan_price',
						'type'              => 'text_money',
						'before_field'      => wpsight_get_currency(),
						'description'       => sprintf( __( 'In %s.', 'wpcasa-pricing-tables' ), wpsight_get_currency_abbr() ),
						'sanitization_cb'   => false,
						'attributes'		=> array(
						    'type'				=>	'number',
						    'step'				=> 	'any',
						    'min'				=> 	0,
						    'pattern'			=> 	'\d*(\.\d*)?',
						)
					),
					'plan_duration' => array(
						'name'		=> __( 'Duration', 'wpcasa-pricing-tables' ),
						'id'		=> 'pricing_plan_duration',
						'type'		=> 'text_small',
						'desc'		=> __( 'Enter the pricing plan duration (e.g. <code>/ month</code>)', 'wpcasa-pricing-tables' )
					),
					'plan_details' => array(
						'name'		=> __( 'Details', 'wpcasa-pricing-tables' ),
						'id'		=> 'pricing_plan_details',
						'type'		=> 'textarea',
						'desc'		=> __( 'Enter the pricing plan details here (one per line)', 'wpcasa-pricing-tables' )
					),
					'plan_ribbon' => array(
						'name'		=> __( 'Ribbon', 'wpcasa-pricing-tables' ),
						'id'		=> 'pricing_plan_ribbon',
						'type'		=> 'text_small',
						'desc'		=> __( 'Enter some text (e.g. Best Value) to display a ribbon', 'wpcasa-pricing-tables' )
					),
					'plan_button_text' => array(
						'name'		=> __( 'Button Text', 'wpcasa-pricing-tables' ),
						'id'		=> 'pricing_plan_button_text',
						'type'		=> 'text',
						'desc'		=> __( 'Enter the text for the button', 'wpcasa-pricing-tables' )
					),
					'plan_button_url' => array(
						'name'		=> __( 'Button URL', 'wpcasa-pricing-tables' ),
						'id'		=> 'pricing_plan_button_url',
						'type'		=> 'text_url',
						'desc'		=> __( 'Enter the URL for the button', 'wpcasa-pricing-tables' )
					),
				),
				'description' 	=> __( 'Create the pricing plans for this table', 'wpcasa-pricing-tables' ),
				'repeatable'  	=> true,
				'options'     	=> array(
				    'group_title'   => __( 'Pricing Plan {#}', 'wpcasa-pricing-tables' ),
				    'add_button'    => __( 'Add Pricing Plan', 'wpcasa-pricing-tables' ),
				    'remove_button' => __( 'Remove', 'wpcasa-pricing-tables' ),
				    'sortable'      => true,
				    'closed'		=> true
				),
				'priority'	=> 20
			),
			'note' => array(
				'name'      => __( 'Note', 'wpcasa-pricing-tables' ),
				'id'        => 'pricing_table_note',
				'type'      => 'text',
				'desc'		=> __( 'Enter a note that is displayed after the pricing table', 'wpcasa-pricing-tables' ),
				'priority'  => 30
			),
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'wpsight_meta_box_pricing_table_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'			=> 'pricing_table_general',
            'title'			=> __( 'General', 'wpcasa-pricing-tables' ),
            'object_types'	=> array( 'pricing_table' ),
            'context'		=> 'normal',
            'priority'		=> 'high',
            'show_names'	=> true,
			'fields'		=> $fields,
			'group_limit'	=> apply_filters( 'wpsight_pricing_table_limit', 4 )
		);
		
		// Add meta box to general meta box array		
		$meta_boxes = array_merge( $meta_boxes, array( 'wpsight_pricing_table' => apply_filters( 'wpsight_meta_box_pricing_table', $meta_box ) ) );

		return $meta_boxes;

	}
	
	/**
	 *	meta_box_pricing_table_packages()
	 *	
	 *	Add packages option to pricing table using
	 *	the wpsight_meta_box_pricing_table_fields filter.
	 *	
	 *	@param	array	$fields
	 *	@uses	WPSight_Dashboard_Packages::get_packages_choices()
	 *	@return	array
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_pricing_table_packages( $fields ) {
		
		if( class_exists( 'WPSight_Dashboard_Packages' ) ) {
		
			$fields['pricing_plans']['group_fields']['plan_package'] = array(
				'name'		=> __( 'Package', 'wpcasa-pricing-tables' ),
				'id'		=> 'pricing_plan_package',
				'type'		=> 'select',
				'options'   => WPSight_Dashboard_Packages::get_packages_choices( true, true, true, false ),
				'desc'		=> __( 'Select a package this plan is linked to', 'wpcasa-pricing-tables' )
			);
			
			$fields['pricing_plans']['group_fields']['plan_button_url']['desc'] = $fields['pricing_plans']['group_fields']['plan_button_url']['desc'] . ' (' . __( 'will be ignored when a package is selected', 'wpcasa-pricing-tables' ) . ')';
		
		}
		
		return $fields;
		
	}
	
	/**
	 *	meta_box_pricing_table()
	 *	
	 *	Create pricing table meta box.
	 *	
	 *	@param	array	$meta_boxes
	 *	@uses	wpsight_sort_array_by_priority()
	 *	@uses	wpsight_post_type()
	 *	@return	array
	 *	@see wpsight_meta_boxes()
	 *	
	 *	@since 1.1.0
	 */
	public static function meta_box_pricing_table_shortcode( $meta_boxes ) {

		// Set meta box fields

		// Set meta box fields

		$fields = array(
			'shortcode' => array(
				'name'      => __( 'Shortcode', 'wpcasa-pricing-tables' ),
				'id'        => 'pricing_table_shortcode',
				'type'      => 'text',
				'attributes'  => array(
					'readonly' => 'readonly',
					'disabled' => 'disabled',
				),
				'default'	=> array( 'WPSight_Pricing_Tables_Post_Type', 'show_shortcode' ),
				'desc'		=> __( 'Use this shortcode to display this pricing table in your content', 'wpcasa-pricing-tables' ),
				'priority'  => 10
			),
		);

		// Apply filter and order fields by priority
		$fields = wpsight_sort_array_by_priority( apply_filters( 'meta_box_pricing_table_shortcode_fields', $fields ) );

		// Set meta box

		$meta_box = array(
			'id'			=> 'pricing_table_shortcode',
            'title'			=> __( 'Shortcode', 'wpcasa-pricing-tables' ),
            'object_types'	=> array( 'pricing_table' ),
            'context'		=> 'normal',
            'show_names'	=> false,
			'fields'		=> $fields
		);
		
		// Add meta box to general meta box array		
		$meta_boxes = array_merge( $meta_boxes, array( 'wpsight_pricing_table_shortcode' => apply_filters( 'wpsight_meta_box_pricing_table_shortcode', $meta_box ) ) );

		return $meta_boxes;

	}
	
	/**
	 *	show_shortcode()
	 *
	 *	Display shortcode for the corresponding pricing table.
	 *	
	 *	@access	public
	 *	@param	$field_args	array
	 *	@param	$field		object	CMB2_Field
	 *	@return	string
	 *
	 *	@since	1.1.0
	 */
	public static function show_shortcode( $field_args, $field ) {
		return sprintf( '[wpsight_pricing_table id="%s" show_title="true" show_subtitle="true" show_note="true" show_ribbon="true"]', $field->object_id );
	}
    
    /**
	 *	js_limit_group_repeat()
	 *
	 *	Limit the pricing plan group field number.
	 *	
	 *	@access	public
	 *	@param	$post_id	integer
	 *	@param	$cmb		object
	 *
	 *	@since	1.1.0
	 */
    public static function js_limit_group_repeat( $post_id, $cmb ) {
		// Grab the custom attribute to determine the limit
		$limit = absint( $cmb->prop( 'group_limit' ) );
		$limit = $limit ? $limit : 0;
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			// Only allow 3 groups
			var limit            = <?php echo $limit; ?>;
			var fieldGroupId     = 'pricing_plans';
			var $fieldGroupTable = $( document.getElementById( fieldGroupId + '_repeat' ) );
	
			var countRows = function() {
				return $fieldGroupTable.find( '> .cmb-row.cmb-repeatable-grouping' ).length;
			};
	
			var disableAdder = function() {
				$fieldGroupTable.find('.cmb-add-group-row.button').prop( 'disabled', true );
			};
	
			var enableAdder = function() {
				$fieldGroupTable.find('.cmb-add-group-row.button').prop( 'disabled', false );
			};
			
			if ( countRows() >= limit ) {
				disableAdder();
			}
	
			$fieldGroupTable
				.on( 'cmb2_add_row', function() {
					if ( countRows() >= limit ) {
						disableAdder();
					}
				})
				.on( 'cmb2_remove_row', function() {
					if ( countRows() < limit ) {
						enableAdder();
					}
				});
		});
		</script>
		<?php
	}
	
	/**
	 *	columns()
	 *	
	 *	Define columns for manage_edit-pricing_table_columns filter.
	 *	
	 *	@access	public
	 *	@param	mixed	$columns
	 *	
	 *	@since 1.0.1
	 */
	public static function columns( $columns ) {
		
		// Make sure we deal with array

		if ( ! is_array( $columns ) )
			$columns = array();
		
		// Unset some default columns
		unset( $columns['date'], $columns['author'] );

		// Define our custom column
		$columns['shortcode'] = __( 'Shortcode', 'wpcasa-pricing-tables' );

		return apply_filters( 'wpsight_admin_pricing_tables_columns', $columns );

	}

	/**
	 *	custom_columns()
	 *	
	 *	Define custom columns for
	 *	manage_pricing_table_posts_custom_column action.
	 *	
	 *	@access	public
	 *	@param	mixed	$column
	 *	@uses	wpsight_get_option()
	 *	
	 *	@since 1.0.1
	 */
	public static function custom_columns( $column ) {
		global $post;
		
		$datef = wpsight_get_option( 'date_format', get_option( 'date_format' ) );

		switch ( $column ) {

			case 'shortcode':
			
				printf( '<input style="font-size:12px" type="text" onfocus="this.select();" readonly="readonly" value="[wpsight_pricing_table id=&quot;%s&quot; show_title=&quot;true&quot; show_subtitle=&quot;true&quot; show_note=&quot;true&quot; show_ribbon=&quot;true&quot;]" class="large-text code">', $post->ID );

			break;

		}
		
	}
    
}

WPSight_Pricing_Tables_Post_Type::init();
