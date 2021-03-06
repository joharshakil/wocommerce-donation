<?php
/**
 * Frontend order  html .
 *
 * @package  donation
 */

if ( get_woocommerce_currency_symbol() ) {
	$currency_symbol =  get_woocommerce_currency_symbol();
}
$wp_rand = wp_rand( 1, 999 );
$donation_product = !empty( $object->product['product_id'] ) ? $object->product['product_id'] : '';
$donation_values = !empty( $object->campaign['predAmount'] ) ? $object->campaign['predAmount'] : array();
$donation_value_labels = !empty( $object->campaign['predLabel'] ) ? $object->campaign['predLabel'] : array();
$donation_min_value = !empty( $object->campaign['freeMinAmount'] ) ? $object->campaign['freeMinAmount'] : 0;
$donation_max_value = !empty( $object->campaign['freeMaxAmount'] ) ? $object->campaign['freeMaxAmount'] : 1000;
$display_donation = !empty($object->campaign['amount_display']) ? $object->campaign['amount_display'] : 'both';
$where_currency_symbole = !empty($object->campaign['currencyPos']) ? $object->campaign['currencyPos'] : 'before';
$donation_label  = !empty( $object->campaign['donationTitle'] ) ? $object->campaign['donationTitle'] : '';
$donation_button_text  = !empty( $object->campaign['donationBtnTxt'] ) ? $object->campaign['donationBtnTxt'] : esc_attr__('Donate', 'wc-donation');
$donation_button_color  = !empty( $object->campaign['donationBtnBgColor'] ) ? $object->campaign['donationBtnBgColor'] : '333333';
$donation_button_text_color  = !empty( $object->campaign['donationBtnTxtColor'] ) ? $object->campaign['donationBtnTxtColor'] : 'FFFFFF';
$display_donation_type = !empty( $object->campaign['DonationDispType'] ) ? $object->campaign['DonationDispType'] : 'select';

$RecurringDisp = !empty( $object->campaign['RecurringDisp'] ) ? $object->campaign['RecurringDisp'] : 'disabled';
$recurring_text = !empty( $object->campaign['recurringText'] ) ? $object->campaign['recurringText'] : 'Make it recurring for';
$causeDisp = !empty( $object->campaign['causeDisp'] ) ? $object->campaign['causeDisp'] : 'hide';
$causeNames = !empty( $object->campaign['causeNames'] ) ? $object->campaign['causeNames'] : array();
$causeDesc = !empty( $object->campaign['causeDesc'] ) ? $object->campaign['causeDesc'] : array();
$causeImg = !empty( $object->campaign['causeImg'] ) ? $object->campaign['causeImg'] : array();
/**
 * Donation Goal Settings
 */
$goalDisp = !empty( $object->goal['display'] ) ? $object->goal['display'] : '';
$goalType = !empty( $object->goal['type'] ) ? $object->goal['type'] : '';

$get_donations = WcdonationSetting::has_bought_items( $donation_product );

$progressBarColor = !empty( $object->goal['progress_bar_color'] ) ? $object->goal['progress_bar_color'] : '';
$dispDonorCount = !empty( $object->goal['display_donor_count'] ) ? $object->goal['display_donor_count'] : '';
$closeForm = !empty( $object->goal['form_close'] ) ? $object->goal['form_close'] : '';
$message = !empty( $object->goal['message'] ) ? $object->goal['message'] : '';

$progressOnWidget = !empty( $object->goal['show_on_widget'] ) ? $object->goal['show_on_widget'] : '';

$donation_tributes = get_option( 'wc-donation-tributes' );
$all_tributes = !empty( $object->campaign['tributes'] ) ? $object->campaign['tributes'] : array();
$donation_gift_aid = get_option( 'wc-donation-gift-aid' );
$donation_gift_aid_area = get_option( 'wc-donation-gift-aid-area' );
$donation_gift_aid_title = get_option( 'wc-donation-gift-aid-title' );
$donation_gift_aid_checkbox_title = ! empty( get_option( 'wc-donation-gift-aid-checkbox-title' ) ) ? get_option( 'wc-donation-gift-aid-checkbox-title' ) : __('Yes, I would like to claim Gift Aid', 'wc-donation');
$donation_gift_aid_explanation = get_option( 'wc-donation-gift-aid-explanation' );
$donation_gift_aid_declaration = get_option( 'wc-donation-gift-aid-declaration' );
$is_cart = is_cart();
$is_checkout = is_checkout();


/**
 * Donation product.
 *
 * @var type
 */

$post_exist = !empty( $object->campaign['campaign_id'] ) ? get_post( $object->campaign['campaign_id'] ) : '';
if ( empty( $donation_product ) || empty($post_exist) || ( isset($post_exist->post_status) && 'trash' == $post_exist->post_status ) ) { 
	$message = __('You have enabled donation on this page but didn\'t select campaign for it.', 'wc-donation');
	$notice_type = 'error';
	wc_clear_notices(); //<--- check this line.
	$result = wc_add_notice($message, $notice_type); 
	return $result;
}

if ( 'enabled' === $goalDisp && 'enabled' === $closeForm ) {
	$progress = 0;

	if ( 'fixed_amount' === $goalType || 'percentage_amount' === $goalType  ) { 
		$fixedAmount = !empty( $object->goal['fixed_amount'] ) ? $object->goal['fixed_amount'] : 0;		
		if ( $fixedAmount > 0 ) {
			$progress = ( $get_donations['total_donation_amount']/$fixedAmount ) * 100;
		}
	}

	if ( 'no_of_donation' === $goalType  ) { 
		$no_of_donation = !empty( $object->goal['no_of_donation'] ) ? $object->goal['no_of_donation'] : 0;
		if ( $no_of_donation > 0 ) {
			$progress = ( $get_donations['total_donations']/$no_of_donation ) * 100;
		}
	}

	if ( $progress >= 100 ) {
		?>
		<p class="donation-goal-completed">
			<?php echo esc_html__($message, 'wc-donation'); ?>
		</p>
		<?php

		return;
	}

	if ( 'no_of_days' === $goalType  ) {
		$no_of_days = !empty( $object->goal['no_of_days'] ) ? $object->goal['no_of_days'] : 0;
		$end_date = gmdate('Y-m-d', strtotime($no_of_days));
		$current_date = gmdate('Y-m-d');
		
		if ( $current_date >= $end_date  ) {
			?>
			<p class="donation-goal-completed">
				<?php echo esc_html__($message, 'wc-donation'); ?>
			</p>
			<?php

			return;
		}
	
	}
}
?>
<style>
	:root {
		--wc-bg-color: #<?php esc_html_e( $donation_button_color ); ?>;
		--wc-txt-color: #<?php esc_html_e( $donation_button_text_color ); ?>;
	}

	<?php
	if ( 'before' === $where_currency_symbole ) {
		if ( 'checkout' == $type ) {
			?>
			#wc_donation_on_checkout .price-wrapper::before {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}
			<?php
		}

		if ( 'cart' == $type ) {
			?>
			#wc_donation_on_cart .price-wrapper::before {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}
			<?php
		}

		if ( 'widget' == $type ) {
			?>
			#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> .price-wrapper::before {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}
			
			<?php
		}

		if ( 'shortcode' == $type ) {
			?>
			#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> .price-wrapper::before {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}

			<?php
		}

		if ( 'single' == $type ) {
			?>
			#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> .price-wrapper::before {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}

			<?php
		}
	} else {
		if ( 'checkout' == $type ) {
			?>
			#wc_donation_on_checkout .price-wrapper::after {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}
			<?php
		}

		if ( 'cart' == $type ) {
			?>
			#wc_donation_on_cart .price-wrapper::after {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}
			<?php
		}

		if ( 'widget' == $type ) {
			?>
			#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> .price-wrapper::after {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}
			
			<?php
		}

		if ( 'shortcode' == $type ) {
			?>
			#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> .price-wrapper::after {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}
			<?php
		}
		
		if ( 'single' == $type ) {
			?>
			#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> .price-wrapper::after {
				background: #<?php esc_html_e( $donation_button_color ); ?>;
				color: #<?php esc_html_e( $donation_button_text_color ); ?>;
			}
			<?php
		}
	} 

	if ( 'checkout' == $type ) {
		?>
		#wc_donation_on_checkout .wc-input-text {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}

		#wc_donation_on_checkout .checkmark {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_checkout .wc-label-radio input:checked ~ .checkmark {
			background-color: #<?php esc_html_e( $donation_button_color); ?>;
		}
		#wc_donation_on_checkout .wc-label-radio .checkmark:after {
			border-color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_checkout .wc-label-button {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_checkout label.wc-label-button.wc-active {
			background-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_checkout .wc_progressBarContainer > ul > li.wc_progress div.progressbar {
			background: #<?php esc_html_e( $progressBarColor ); ?>;
		}
		<?php
	}

	if ( 'cart' == $type ) {
		?>
		#wc_donation_on_cart .wc-input-text {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}

		#wc_donation_on_cart .checkmark {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_cart .wc-label-radio input:checked ~ .checkmark {
			background-color: #<?php esc_html_e( $donation_button_color); ?>;
		}
		#wc_donation_on_cart .wc-label-radio .checkmark:after {
			border-color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_cart .wc-label-button {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_cart label.wc-label-button.wc-active {
			background-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_cart .wc_progressBarContainer > ul > li.wc_progress div.progressbar {
			background: #<?php esc_html_e( $progressBarColor ); ?>;
		}
		<?php
	}

	if ( 'widget' == $type ) {
		?>
		#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> .wc-input-text {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}

		#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> .checkmark {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> .wc-label-radio input:checked ~ .checkmark {
			background-color: #<?php esc_html_e( $donation_button_color); ?>;
		}
		#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> .wc-label-radio .checkmark:after {
			border-color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> .wc-label-button {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> label.wc-label-button.wc-active {
			background-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_widget_<?php echo esc_attr($campaign_id); ?> .wc_progressBarContainer > ul > li.wc_progress div.progressbar {
			background: #<?php esc_html_e( $progressBarColor ); ?>;
		}
		<?php
	}

	if ( 'shortcode' == $type ) {
		?>
		#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> .wc-input-text {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}

		#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> .checkmark {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> .wc-label-radio input:checked ~ .checkmark {
			background-color: #<?php esc_html_e( $donation_button_color); ?>;
		}
		#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> .wc-label-radio .checkmark:after {
			border-color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> .wc-label-button {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> label.wc-label-button.wc-active {
			background-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_shortcode_<?php echo esc_attr($campaign_id); ?> .wc_progressBarContainer > ul > li.wc_progress div.progressbar {
			background: #<?php esc_html_e( $progressBarColor ); ?>;
		}
		<?php
	}
	
	if ( 'single' == $type ) {
		?>
		#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> .wc-input-text {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}

		#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> .checkmark {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> .wc-label-radio input:checked ~ .checkmark {
			background-color: #<?php esc_html_e( $donation_button_color); ?>;
		}
		#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> .wc-label-radio .checkmark:after {
			border-color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> .wc-label-button {
			border-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_color ); ?>!important;
		}
		#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> label.wc-label-button.wc-active {
			background-color: #<?php esc_html_e( $donation_button_color ); ?>!important;
			color: #<?php esc_html_e( $donation_button_text_color); ?>!important;
		}
		#wc_donation_on_single_<?php echo esc_attr($campaign_id); ?> .wc_progressBarContainer > ul > li.wc_progress div.progressbar {
			background: #<?php esc_html_e( $progressBarColor ); ?>;
		}
		<?php
	}
	
	?>
</style>
<div class="wc-donation-in-action" data-donation-type="<?php echo esc_attr($display_donation); ?>">
	<label for="donation-price"><?php echo esc_html( __( $donation_label, 'wc-donation' ) ); ?></label>
	<div class="in-action-elements">
		<div class="row1">
			<?php 
			if ( ( 'predefined' === $display_donation || 'both' === $display_donation )  && ( count( $donation_values[0] ) > 0 ) ) { 
				if ( ! empty( $donation_values[0] ) ) {
					
					if ( 'select' === $display_donation_type ) {
						?>
						<div class="price-wrapper <?php echo esc_attr($where_currency_symbole); ?>" currency="<?php echo esc_attr($currency_symbol); ?>">							
							<select name="wc_select_price" data-id="<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" class='wc-label-select select wc-input-text <?php echo esc_attr($where_currency_symbole); ?>' id='wc-donation-f-donation-value-<?php echo esc_attr($wp_rand); ?>'>
							<option value=""><?php echo esc_html__('--Please select--', 'wc-donation'); ?></option>
							<?php
							foreach ( $donation_values[0] as $key => $value ) {
								?>
								<option value='<?php echo esc_attr( $value ); ?>'><?php echo !empty( $donation_value_labels[0][$key] ) ? esc_attr( $donation_value_labels[0][$key] ) : esc_attr( $value ); ?></option>
								<?php
							}

							if ( 'both' === $display_donation ) {
								?>
								<option value="wc-donation-other-amount"><?php echo esc_html__('Other', 'wc-donation'); ?></option>
								<?php
							}
							?>
							</select>
							</div>
						<?php
					}

					if ( 'radio' === $display_donation_type ) { 
						?>
						<div class="row1">
						<?php
						foreach ( $donation_values[0] as $key => $value ) {
							?>
							<label for="<?php echo esc_attr($campaign_id) . '_' . esc_attr($key) . '_' . esc_attr($wp_rand); ?>" class="wc-label-radio">
								<?php /* echo esc_attr( $donation_value_labels[0][$key] ); */ ?>
								<?php echo !empty( $donation_value_labels[0][$key] ) ? esc_attr( $donation_value_labels[0][$key] ) : esc_attr( $value ); ?>
								<input type="radio" data-id="<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" name="wc_radio_price" id="<?php echo esc_attr($campaign_id) . '_' . esc_attr( $key ) . '_' . esc_attr($wp_rand); ?>" value="<?php echo esc_attr( $value ); ?>">                                
								<div class="checkmark"></div>
							</label>
							<?php
						}

						if ( 'both' === $display_donation ) {
							?>
							<label for="wc-donation-other-amount-<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" class="wc-label-radio">
								<?php echo esc_html__( 'Other', 'wc-donation' ); ?>
								<input type="radio" data-id="<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" name="wc_radio_price" id="wc-donation-other-amount-<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" value="wc-donation-other-amount">                                
								<div class="checkmark"></div>
							</label>
							<?php
						}
						?>
						</div>
						<?php
					}
					if ( 'label' === $display_donation_type ) {
						?>
						<div class="row1">
						<?php
						foreach ( $donation_values[0] as $key => $value ) {
							?>
							<label class="wc-label-button" for="<?php echo esc_attr($campaign_id) . '_' . esc_attr( $key ) . '_' . esc_attr($wp_rand); ?>">
								<input type="radio" data-id="<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" name="wc_label_price" id="<?php echo esc_attr($campaign_id) . '_' . esc_attr( $key ) . '_' . esc_attr($wp_rand); ?>" value="<?php echo esc_attr( $value ); ?>">
								<?php /* echo esc_attr( $donation_value_labels[0][$key] ); */ ?>
								<?php echo !empty( $donation_value_labels[0][$key] ) ? esc_attr( $donation_value_labels[0][$key] ) : esc_attr( $value ); ?>
							</label>
							<?php
						}

						if ( 'both' === $display_donation ) { 
							?>
							<label class="wc-label-button" for="wc-donation-other-amount-<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>">
								<input type="radio" data-id="<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" name="wc_label_price" id="wc-donation-other-amount-<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" value="wc-donation-other-amount">
								<?php echo esc_html__( 'Other', 'wc-donation' ); ?>
							</label>
							<?php
						}
						?>
						</div>
						<?php 
					}
				}

				if ( 'both' === $display_donation ) { 
					$placeholder_other_val = apply_filters( 'wc_donation_other_amount_placeholder', esc_html__('Enter amount between ', 'wc-donation') . $donation_min_value . ' - ' . $donation_max_value, $donation_min_value, $donation_max_value ); 
					?>
					<input type="text" data-min="<?php echo esc_attr($donation_min_value); ?>" data-max="<?php echo esc_attr( $donation_max_value ); ?>" data-campaign_id="<?php echo esc_attr( $campaign_id ); ?>" data-rand_id="<?php echo esc_attr( $wp_rand ); ?>" style="display:none" Placeholder="<?php echo wp_kses_post($placeholder_other_val) ; ?>" class="grab-donation wc-input-text wc-donation-f-donation-other-value" id="wc-donation-f-donation-other-value-<?php echo esc_attr($campaign_id) . '_' . esc_attr( $wp_rand ); ?>">
					<?php
				}
				echo '<input type="hidden" id="wc-donation-price-' . esc_attr($campaign_id) . '_' . esc_attr($wp_rand) . '" class="donate_' . esc_attr($campaign_id) . '_' . esc_attr($wp_rand) . '" name="wc-donation-price" value="">';
				echo '<input type="hidden" id="wc-donation-cause-' . esc_attr($campaign_id) . '_' . esc_attr($wp_rand) . '" class="donate_cause_' . esc_attr($campaign_id) . '_' . esc_attr($wp_rand) . '" name="wc-donation-cause" value="">';
 
			} else { 
				$placeholder_other_val = apply_filters( 'wc_donation_other_amount_placeholder', esc_html__('Enter amount between ', 'wc-donation') . $donation_min_value . ' - ' . $donation_max_value, $donation_min_value, $donation_max_value );
				?>
				<div class="price-wrapper <?php echo esc_attr($where_currency_symbole); ?>" currency="<?php echo esc_attr($currency_symbol); ?>">
					<input type="text" data-min="<?php echo esc_attr($donation_min_value); ?>" data-max="<?php echo esc_attr($donation_max_value); ?>" data-campaign_id="<?php echo esc_attr($campaign_id); ?>" data-rand_id="<?php echo esc_attr($wp_rand); ?>" onKeyup="return NumbersOnly(this, event, true);" class="grab-donation wc-input-text donate_<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?> <?php echo esc_attr($where_currency_symbole); ?>" Placeholder="<?php echo wp_kses_post($placeholder_other_val); ?>" name="wc-donation-price" >
				</div>				
				<?php 

				echo '<input type="hidden" id="wc-donation-price-' . esc_attr($campaign_id) . '_' . esc_attr($wp_rand) . '" class="donate_' . esc_attr($campaign_id) . '_' . esc_attr($wp_rand) . '" name="wc-donation-price" value="">';

			} 
			if ( 'show' === $causeDisp && !empty( $causeNames[0] ) ) : 
				?>
				<div class="row2">
					<h4><?php echo esc_html__('Select Cause', 'wc-donation' ); ?></h4>
					<div class="cause-wrapper after">
						<ul class="causes-dropdown">
							<li class="init"><?php echo esc_html__('Select Cause', 'wc-donation'); ?></li>
							<?php 
							foreach ( $causeNames[0] as $key => $value ) : 
								$cause_img = !empty( $causeImg[0][$key] ) ? $causeImg[0][$key] : WC_DONATION_URL . 'assets/images/no-image-cause.jpg';
								$cause_desc = !empty( $causeDesc[0][$key] ) ? $causeDesc[0][$key] : ''; 
								?>
								<li class="dropdown-item" data-id="cause-<?php echo esc_attr( $campaign_id ) . '_' . esc_attr( $wp_rand ); ?>" data-name="<?php echo esc_attr( $value ); ?>">
									<div class="cause-drop-content"><div class="cause-img-wrap"><img src="<?php echo esc_attr( $cause_img ); ?>" class="img-cause-drop" width="32px"/></div><div class="cause-text-wrap"><div class="cause-drop-title"><?php echo esc_attr( $value ); ?></div><div class="cause-drop-desc"><?php echo esc_attr( $cause_desc ); ?></div></div></div></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php 
			endif;
			?>
		</div>

		<?php 
		if ( 'yes' === $donation_tributes && is_array( $all_tributes ) && count( $all_tributes ) > 0 && ! empty( $all_tributes[0] ) ) {
			?>
			<div class="wc-donation-tribute-wrapper">
				<h3 class="wc-donation-title"><?php echo esc_html__( 'Tributes', 'wc-donation' ); ?></h3>
				<div class="all_tributes">
					<?php
					foreach ( $all_tributes as $k => $v ) {
						if ( '' != $v ) {
							?>
							<label class="wc-label-radio" for="wc_donation_tribute_checkbox_<?php echo esc_attr($campaign_id) . '_' . esc_attr( $k ) . '_' . esc_attr($wp_rand); ?>">
									<input type="radio" id="wc_donation_tribute_checkbox_<?php echo esc_attr($campaign_id) . '_' . esc_attr( $k ) . '_' . esc_attr($wp_rand); ?>" name="wc_donation_tribute_checkbox" value="<?php echo esc_html( $v ); ?>" > <?php echo esc_html( $v ); ?><div class="checkmark"></div>
								</label>
							<?php
						}
					}
					?>
					<div style="clear:both;"></div>
					<input type="hidden" id="wc_donation_trubte_name_<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" class="wc_donation_trubte_name" Placeholder="<?php echo esc_html__( 'Enter Name', 'wc-donation' ); ?>" value="">
					<input type="hidden" id="_hidden_tribute_<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" name="tribute" value="">
				</div>
			</div>
			<?php
		}
		?>
		
		<?php 
		// Gift Aid by Developer53 
		if ( 'yes' === $donation_gift_aid ) {	
			if ( ( 'cart' === $donation_gift_aid_area && $is_cart && 'cart' === $type ) || ( 'checkout' === $donation_gift_aid_area && $is_checkout && 'checkout' === $type ) ) {
				?>
				<div class="wc-donation-gift-aid-wrapper">		
					<?php 
					if ( ! empty( trim( $donation_gift_aid_title ) ) ) {
						?>
						<h3 class="wc-donation-title"><?php echo esc_html( $donation_gift_aid_title ); ?></h3>
						<?php
					}
					?>

					<?php 
					if ( ! empty( trim( $donation_gift_aid_explanation ) ) ) {
						?>
						<p class="wc-donation-gift-aid-explanation" ><?php echo esc_html( $donation_gift_aid_explanation ); ?></p>
						<?php
					}
					?>

					<label class="wc-label-radio" for="wc_donation_gift_aid_checkbox_<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>">
						<input type="checkbox" id="wc_donation_gift_aid_checkbox_<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" name="wc_donation_gift_aid_checkbox" value="yes" > <?php echo esc_html( $donation_gift_aid_checkbox_title ); ?><div class="checkmark"></div>
					</label>

					<?php 
					if ( ! empty( trim( $donation_gift_aid_declaration ) ) ) {
						?>
						<div style="clear:both;"></div>
						<p class="wc-donation-gift-aid-declaration" ><?php echo esc_html( $donation_gift_aid_declaration ); ?></p>
						<?php
					}
					?>
				</div>
				<?php
			}
		}
		?>

		<?php 
		$get_fee_campaign = get_option('wc-donation-fees-product');
		$check_fee_option = get_option('wc-donation-card-fee');
		if ( !is_array( $get_fee_campaign ) ) {
			$get_fee_campaign = array();
		}
		if ( 'yes' === $check_fee_option && in_array( $campaign_id, $get_fee_campaign ) ) : 
			?>
			<div class="row1">
				<div class="row1"><label class="wc-label-radio"><input class="donation-processing-fees" id="processing-fees-<?php echo esc_attr( $campaign_id ) . '_' . esc_attr( $wp_rand ); ?>" type="checkbox" data-camp="<?php echo esc_attr( $campaign_id ) . '_' . esc_attr( $wp_rand ); ?>" data-id="fees-<?php echo esc_attr( $campaign_id ) . '_' . esc_attr( $wp_rand ); ?>" name="wc_check_fees" value="<?php echo esc_attr( get_option( 'wc-donation-fees-percent' ) ); ?>"><?php echo esc_attr( get_option('wc-donation-fees-field-message') ); ?><div class="checkmark"></div></label></div>
			</div>
			<?php echo '<input type="hidden" id="wc-donation-fees-' . esc_attr($campaign_id) . '_' . esc_attr($wp_rand) . '" class="donate_fees_' . esc_attr($campaign_id) . '_' . esc_attr($wp_rand) . '" name="wc-donation-fees" value="">'; ?>
		<?php endif; ?>
		<?php if ( 'user' == $RecurringDisp && class_exists('WC_Subscriptions') ) : ?>

			<?php

			$interval = !empty( $object->campaign['interval'] ) ? $object->campaign['interval'] : '1';
			$period = !empty( $object->campaign['period'] ) ? $object->campaign['period'] : 'day';
			$length = !empty( $object->campaign['length'] ) ? $object->campaign['length'] : '1';			
			$period_arr = '<select name="_subscription_period" class="_subscription_period">'; 
			foreach ( wcs_get_available_time_periods() as $key => $value ) {
				if ( !empty(wcs_get_subscription_ranges($key)[$object->campaign['length']]) ) {
					$period_arr	.= '<option value="' . esc_attr( $key ) . '" ' . selected( $period, $key, false ) . ' >' . esc_attr( $value ) . '</option>';
				}
			}
			$period_arr	.= '</select>';			
			$is_checked = apply_filters('wc_donation_is_recurring_checkbox', '');
			?>
			<div class="row3">
				<label class="wc-label-radio recurring-label"><input class="donation-is-recurring" id="is-recurring-<?php echo esc_attr( $campaign_id ) . '_' . esc_attr( $wp_rand ); ?>" type="checkbox" data-id="is-recurring-<?php echo esc_attr( $campaign_id ) . '_' . esc_attr( $wp_rand ); ?>" name="wc_is_recurring" value="yes" <?php echo esc_attr($is_checked); ?> ><?php echo esc_attr( $recurring_text, 'wc-donation' ); ?>  <div class="checkmark"></div>
					<?php do_action( 'wc_donation_before_subscription_interval' ); ?>					
					<select name='_subscription_period_interval' id="_subscription_period_interval">
					<?php
					foreach ( wcs_get_subscription_period_interval_strings() as $key => $value ) {
						?>
						<option value="<?php echo esc_attr($key); ?>" <?php selected( $interval, $key ); ?>><?php echo esc_attr( $value ); ?></option>
						<?php
					}
					?>
					</select>
					<?php do_action( 'wc_donation_after_subscription_interval' ); ?>
					<?php do_action( 'wc_donation_before_subscription_period' ); ?>
					<?php echo nl2br($period_arr); ?> 
					<?php do_action( 'wc_donation_after_subscription_period' ); ?>
					<?php do_action( 'wc_donation_before_subscription_length' ); ?>
					<select name='_subscription_length' id="_subscription_length">
						<?php
						foreach ( wcs_get_subscription_ranges( apply_filters( 'wc_donation_recurring_default_period', $period) ) as $key => $value ) {
							?>
							<option value="<?php echo esc_attr($key); ?>" <?php selected( $length, $key ); ?>><?php echo esc_attr( $value ); ?></option>
							<?php
						}
						?>
					</select>
					<?php do_action( 'wc_donation_after_subscription_length' ); ?>
				</label>
			</div>		
		<?php endif; ?>
		<?php 
		$flag_for_widget_progress = false;
		if ( 'widget' === $type && 'enabled' === $progressOnWidget ) {
			$flag_for_widget_progress = true;
		} 

		if ( 'widget' !== $type ) {
			$flag_for_widget_progress = true;
		}
		?>
		<?php if ( 'enabled' === $goalDisp && $flag_for_widget_progress ) : ?>
			<div class="row3">
				<?php require( WC_DONATION_PATH . 'includes/views/frontend/frontend-donation-goal-disp.php' ); ?>
			</div>
		<?php endif; ?>
		<div class="row2">
			<input type="hidden" name="wc_donation_camp" id="wc_donation_camp_<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>" class="wc_donation_camp" value="<?php echo esc_attr($campaign_id); ?>">
			<input type="hidden" name="wc_rand_id" class="wp_rand" value="<?php echo esc_attr($wp_rand); ?>">
			<button class="button wc-donation-f-submit-donation" data-min-value="<?php echo esc_attr($donation_min_value); ?>" data-max-value="<?php echo esc_attr($donation_max_value); ?>" data-type="<?php esc_attr_e( $type ); ?>" style="background-color:#<?php esc_attr_e( $donation_button_color ); ?>;border-color:#<?php esc_attr_e( $donation_button_color ); ?>;color:#<?php esc_attr_e( $donation_button_text_color ); ?>;" id='wc-donation-f-submit-donation' value='Donate'><?php echo esc_attr( __( $donation_button_text, 'wc-donation' ) ); ?></button>
		</div>
		<?php if ( 'yes' === $check_fee_option && in_array( $campaign_id, $get_fee_campaign ) ) : ?>
		<div class="row3 wc-donation-summary" id="wc-donation-summary-<?php echo esc_attr($campaign_id) . '_' . esc_attr($wp_rand); ?>">
			<table cellspacing="0" class="wc-donation-summary-table">
				<thead>
					<tr>
						<th><?php echo esc_html__( 'Item', 'wc-donation' ); ?></th>
						<td><?php echo esc_html__( 'Charge', 'wc-donation' ); ?></td>
					</tr>
				</thead>
				<tbody>
					<tr class="wc-donation-charge">
						<th><?php echo esc_html__( 'Donation', 'wc-donation' ); ?></th>
						<td><span class="wc-donation-currency-symbol"><?php echo esc_attr($currency_symbol); ?></span><span class="wc-donation-amt"><?php echo esc_html__('NONE', 'wc-donation'); ?></span></td>
					</tr>
					<tr class="wc-donation-fee-summary"> 
						<th><?php echo esc_html__( 'Fees', 'wc-donation' ) . ' ( ' . esc_attr( get_option( 'wc-donation-fees-percent' ) ) . '% )'; ?></th>
						<td><span class="wc-donation-currency-symbol"><?php echo esc_attr($currency_symbol); ?></span><span class="wc-donation-amt"><?php echo esc_html__('NONE', 'wc-donation'); ?></span></td>
					</tr>
				</tbody>
				<tfoot>
					<tr class="wc-donation-summary-total">
						<th><?php echo esc_html__( 'Total', 'wc-donation' ); ?></th>
						<td><span class="wc-donation-currency-symbol"><?php echo esc_attr($currency_symbol); ?></span><span class="wc-donation-amt"><?php echo esc_html__('No Charge', 'wc-donation'); ?></span></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>
<div style="clear:both;height:1px;">&nbsp;</div>
