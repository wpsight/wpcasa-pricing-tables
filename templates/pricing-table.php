<?php
/**
 * Pricing Table
 */
if ( ! defined( 'ABSPATH' ) )
	exit;

$pricing_table			= WPSight_Pricing_Tables_General::get_pricing_table( $args['id'] );
$pricing_table_title	= $pricing_table->post_title;
$pricing_table_subtitle = get_post_meta( $pricing_table->ID, 'pricing_table_subtitle', true );
$pricing_table_note		= get_post_meta( $pricing_table->ID, 'pricing_table_note', true );

$pricing_plans = get_post_meta( $pricing_table->ID, 'pricing_plans', true ); ?>

<section class="wpsight-pricing-table">

	<?php if( $args['show_title'] || $args['show_subtitle'] ) : ?>
	<div class="pricing-table-title">
		<?php if( $args['show_title'] ) : ?>
		<h2><?php echo strip_tags( $pricing_table_title ); ?></h2>
		<?php endif; ?>
		<?php if( $args['show_subtitle'] ) : ?>
		<p><?php echo wp_kses_post( $pricing_table_subtitle ); ?></p>
		<?php endif; ?>
	</div><!-- .pricing-table-title -->
	<?php endif; ?>
	
	<?php if( ! empty( $pricing_plans ) ) : ?>
	
		<div class="pricing-plans pricing-plans-<?php echo count( $pricing_plans ); ?>-items clearfix">
		
			<?php foreach( (array) $pricing_plans as $pricing_plan ) : ?>
			
				<div id="pricing-plan-wrap-<?php echo sanitize_title( $pricing_plan['pricing_plan_title'] ); ?>" class="pricing-plan-wrap">
				
					<div id="pricing-plan-<?php echo sanitize_title( $pricing_plan['pricing_plan_title'] ); ?>" class="pricing-plan">
						<div class="pricing-plan-inner">
					
						<?php if( ! empty( $pricing_plan['pricing_plan_title'] ) ) : ?>
						<div class="pricing-plan-name">
							<h2><?php echo strip_tags( $pricing_plan['pricing_plan_title'] ); ?></h2>
							<?php if( ! empty( $pricing_plan['pricing_plan_subtitle'] ) ) : ?>
							<p class="text-muted"><?php echo wp_kses_post( $pricing_plan['pricing_plan_subtitle'] ); ?></p>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						
						<?php if( ! empty( $pricing_plan['pricing_plan_price'] ) ) : ?>
						<div class="pricing-plan-price">
							<strong><?php echo WPSight_Pricing_Tables_General::format_price( $pricing_plan['pricing_plan_price'] ); ?></strong>
							<?php if( ! empty( $pricing_plan['pricing_plan_duration'] ) ) : ?>
							<?php echo strip_tags( $pricing_plan['pricing_plan_duration'] ); ?>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						
						<?php if( ! empty( $pricing_plan['pricing_plan_details'] ) ) : ?>
						
							<?php $plan_details = explode( PHP_EOL, $pricing_plan['pricing_plan_details'] ); ?>
							
							<div class="pricing-plan-details">
							
								<?php foreach( $plan_details as $plan_detail ) : ?>
									<div class="plan-detail"><?php echo strip_tags( $plan_detail, '<span><b><strong><i><em><small>' ); ?></div>
								<?php endforeach; ?>
					
							</div>
							
						<?php endif; ?>
						
						<?php $package_payment_id = wpsight_get_option( 'dashboard_payment', true ); ?>
						<?php $button_text = ! empty( $pricing_plan['pricing_plan_button_text'] ) ? strip_tags( $pricing_plan['pricing_plan_button_text'] ) : __( 'Choose Plan', 'wpcasa-pricing-tables' ); ?>
						
						<div class="pricing-plan-action">
						
							<?php if( ! empty( $pricing_plan['pricing_plan_package'] ) && $package_payment_id && class_exists( 'WPSight_Dashboard_Packages' ) ) : ?>
							
								<form method="post" action="<?php echo get_permalink( $package_payment_id ); ?>">
								    <input type="hidden" name="payment_type" value="package">
								    <input type="hidden" name="object_id" value="<?php echo esc_attr( $pricing_plan['pricing_plan_package'] ); ?>">
								
								    <button type="submit" class="btn btn-primary btn-block btn-lg"><?php echo strip_tags( $button_text ); ?></button>
								</form>
							
							<?php else : ?>
							
								<?php if( ! empty( $pricing_plan['pricing_plan_button_url'] ) ) : ?>
									<a href="<?php echo esc_url( $pricing_plan['pricing_plan_button_url'] ); ?>" class="btn btn-primary btn-block btn-lg" role="button"><?php echo strip_tags( $button_text ); ?></a>
								<?php endif; ?>
							
							<?php endif; ?>
						
						</div><!-- .pricing-plan-action -->
						
						<?php if( ! empty( $pricing_plan['pricing_plan_ribbon'] ) && $args['show_ribbon'] ) : ?>
						<div class="corner-ribbon top-right blue shadow"><?php echo strip_tags( $pricing_plan['pricing_plan_ribbon'] ); ?></div>
						<?php endif; ?>
					
						</div>
					</div><!-- .pricing-plan -->
				
				</div><!-- .pricing-plan-wrap -->
			
			<?php endforeach; ?>
		
		</div><!-- .pricing-plans -->
	
	<?php else : ?>
	
		<div class="wpsight-alert alert alert-error">
			<?php _e( 'This pricing table does not have any plans yet.', 'wpcasa-pricing-tables' ); ?>
		</div>
	
	<?php endif; ?>
	
	<?php if( ! empty( $pricing_table_note ) && $args['show_note'] ) : ?>
	<div class="pricing-table-note">
		<p class="text-muted"><?php echo wp_kses_post( $pricing_table_note ); ?></p>
	</div><!-- .pricing-table-note -->
	<?php endif; ?>

</section><!-- .wpsight-pricing-table -->
