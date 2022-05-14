=== Donation For WooCommerce ===
Contributors: wpexpertsio
Tags: WooCommerce, Donation, Product
Requires at least: 4.9
Tested up to: 5.2
Requires PHP: 5.6
Stable tag: 2.0.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==
A powerful WooCommerce Donation Extension which lets you collect donations easily without any transaction fee. User have an option to Generate multiple campaigns and raise funds for multiple causes. Provide the option to your donors at the cart page to donate instantly along with another product purchase.

== Installation ==
1- Download Donation For WooCommerce from WooCommerce.com
2- Go to: WordPress Admin > Plugins > Add New and Upload Plugin with the file you downloaded with Choose File.
3- Install Now and Activate the extension.


== wc donation filters hooks ==
* wc_donation_before_product_update, array $prod 
* wc_donation_before_product_create, array $prod 
* wc_donation_get_campaign_id_by_product_id, array $args 
* wc_donation_get_campaign, array $campaigs, $campaign_id (opt) 
* wc_donation_total_donation_count, $total_donations_count, $product_id (opt) 
* wc_donation_total_donation_amount, $total_donation_amount, $product_id (opt) 
* wc_donation_total_donation_count_on_renewal, $total_donations_count, $product_id (opt) 
* wc_donation_total_donation_amount_on_renewal, $total_donation_amount, $product_id (opt) 
* wc_donation_alter_donate_response, array $response 
* wc_donation_localize_script, array $parameters 
* wc_donation_order_status_changed_to, array( 'cancelled' ) 
* wc_donation_email_attachments, array( 'new_order', 'customer_processing_order', 'customer_completed_renewal_order', 'customer_completed_order' ) 
* wc_donation_before_display_meta_key_on_order, $key, $meta (opt), $item (opt) 
* wc_donation_before_display_meta_value_on_order, $value, $meta (opt), $item (opt) 
* wc_donation_hidden_order_itemmeta, $item_meta 
* wc_donation_hidden_order_frontend_itemmeta, $temp_metas, $formatted_meta (opt) 
* wc_donation_before_display_meta_on_cart, $item_data, $cart_item (opt) 
* wc_donation_pdf_paper_orientation, 'landscape' 
* wc_donation_pdf_enable_html5_parser, true 
* wc_donation_pdf_enable_remote_parser, true 
* wc_donation_pdf_default_pdf_font, 'dejavu sans' 
* wc_donation_change_pdf_name, get_bloginfo( 'name' ) . '_wc_donation_' 
* wc_donation_coupon_valid_for_campaign, false 
* wc_donation_on_checkout, true, $campaign_id (opt) 
* wc_donation_change_csv_name, get_bloginfo( 'name' ) . '_wc_donation_' 
* wc_donation_change_admin_email_subject, __( 'WC Donation Report for Order #' . sanitize_text_field( $_REQUEST['order_id'] ), 'wc-donation' ) (opt) 
* wc_donation_change_pdf_message, esc_html__('Thank you for your donations:', 'wc-donation') 
* wc_donation_pdf_head_bg_color, '#6D3DAF' 
* wc_donation_pdf_head_txt_color, '#6D3DAF' 
* wc_donation_change_admin_email_message, __( 'Thank you for your donation. Your Kindness is appreciated.', 'wc-donation' ) 
* wc_donation_change_pdf_message, esc_html__('Thank you for your donations:', 'wc-donation') 
* wc_donation_other_amount_placeholder, esc_html__('Enter amount between ', 'wc-donation') . $donation_min_value . ' - ' . $donation_max_value, $donation_min_value (opt), $donation_max_value (opt) 
* wc_donation_is_recurring_checkbox, '' 
* wc_donation_recurring_default_period, $period 



== wc donation action hooks ==
* wc_donation_before_save_product_meta, $campaign_id, $product_id 
* wc_donation_after_save_product_meta, $campaign_id, $product_id 
* wc_donation_before_archive_add_donation_button 
* wc_donation_after_archive_add_donation_button 
* wc_donation_before_single_add_donation, $campaign_id 
* wc_donation_after_single_add_donation, $campaign_id 
* wc_donation_before_shortcode_add_donation, $campaign_id 
* wc_donation_after_shortcode_add_donation, $campaign_id 
* wc_donation_before_save_campaign_meta, $campaign_id 
* wc_donation_after_save_campaign_meta, $campaign_id 
* wc_donation_before_donate 
* wc_donation_after_donate 
* add_popup_before_order 
* wc_donation_before_checkout_add_donation, $campaign_id 
* wc_donation_after_checkout_add_donation, $campaign_id 
* wc_donation_before_cart_add_donation, $campaign_id 
* wc_donation_after_cart_add_donation, $campaign_id 
* wc_donation_before_widget_add_donation, $campaign_id 
* wc_donation_after_widget_add_donation, $campaign_id 
* wc_donation_summary_before, $campaign_id, $product_id 
* wc_donation_summary_after, $campaign_id, $product_id 
* wc_donation_before_pdf_details 
* wc_donation_after_pdf_details 
* wc_donation_after_report_email_details 
* wc_donation_before_subscription_interval 
* wc_donation_after_subscription_interval
* wc_donation_before_subscription_period
* wc_donation_after_subscription_period
* wc_donation_before_subscription_length
* wc_donation_after_subscription_length