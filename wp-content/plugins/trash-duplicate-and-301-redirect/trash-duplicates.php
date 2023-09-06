<?php
/**
 * Plugin Name: Trash Duplicate And 301 Redirect
 * Plugin URI: https://wordpress.org/plugins/trash-duplicate-and-301-redirect/
 * Description: Find and delete duplicates posts, custom posts and pages specifying which one to keep (newest or oldest) and 301 redirection to the post you are keeping.
 * Version: 1.4.8
 * Author: Solwin Infotech
 * Author URI: https://www.solwininfotech.com/
 * Copyright: Solwin Infotech
 * Requires at least: 5.4
 * Tested up to: 6.2.2
 *
 * Text Domain: trash-duplicate-and-301-redirect
 * Domain Path: /languages/
 *
 * @package Trash Duplicate and 301 Redirect
 */

/*
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TDRD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TDRD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once TDRD_PLUGIN_DIR . 'includes/promo_notice.php';

if ( ! function_exists( 'tdrd_init' ) ) {
	/**
	 * This function is call when initializing this plugin.
	 */
	function tdrd_init() {
		// set the current plugin version.
		$trash_duplicates_version = '1.4.8';
		$trash_duplicates_options = get_option( 'trash_duplicates_options' );
		// if it's not the latest version.
		if ( isset( $trash_duplicates_options['version'] ) ) {
			if ( version_compare( $trash_duplicates_version, $trash_duplicates_options['version'], '>' ) ) {
				$trash_duplicates_options['version'] = $trash_duplicates_version;
				update_option( 'trash_duplicates_options', $trash_duplicates_options );
			}
		}
		// Load admin scripts.
		if ( is_admin() ) {
			require_once 'trash-duplicates-admin.php';
			require_once 'redirect_admin.php';
		} else {
			require_once 'redirect_client.php';
		}
	}
}
add_action( 'init', 'tdrd_init', 0 );

add_action( 'plugins_loaded', 'tdrd_load_text_domain' );
if ( ! function_exists( 'tdrd_load_text_domain' ) ) {
	/**
	 * Plugin text domain.
	 */
	function tdrd_load_text_domain() {
		load_plugin_textdomain( 'trash-duplicate-and-301-redirect', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

if ( ! function_exists( 'tdrd_admin_scripts' ) ) {
	/**
	 * Admin scripts.
	 *
	 * @param string $hook_suffix Hook Suffix.
	 */
	function tdrd_admin_scripts( $hook_suffix ) {
		$screen          = get_current_screen();
		$plugin_data     = get_plugin_data( WP_PLUGIN_DIR . '/trash-duplicate-and-301-redirect/trash-duplicates.php', $markup = true, $translate = true );
		$current_version = $plugin_data['Version'];
		$old_version     = get_option( 'tdr_version' );
		if ( $old_version != $current_version ) {
			update_option( 'tdr_version', $current_version );
		}
		if ( ( isset( $_GET['page'] ) && ( 'trash_duplicates_welcome' == $_GET['page'] ) ) || 'plugins.php' == $hook_suffix ) {
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );
			wp_register_script( 'custom_wp_admin_js', plugins_url( 'js/admin_script.js', __FILE__ ), array(), '1.4.5', true );
			wp_enqueue_script( 'custom_wp_admin_js' );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'tdrd_admin_scripts' );
add_action( 'plugins_loaded', 'tdrd_latest_news_solwin_feed' );
add_action( 'current_screen', 'tdrd_footer' );
add_filter( 'set-screen-option', 'tdrd_set_screen_option', 10, 3 );

if ( ! function_exists( 'tdrd_latest_news_solwin_feed' ) ) {
	/**
	 * Add solwin news dashboard.
	 */
	function tdrd_latest_news_solwin_feed() {
		// Register the new dashboard widget with the 'wp_dashboard_setup' action.
		add_action( 'wp_dashboard_setup', 'tdrd_solwin_latest_news_with_product_details' );
		if ( ! function_exists( 'tdrd_solwin_latest_news_with_product_details' ) ) {
			/**
			 * Latest News With Product Details.
			 */
			function tdrd_solwin_latest_news_with_product_details() {
				add_screen_option(
					'layout_columns',
					array(
						'max'     => 3,
						'default' => 2,
					)
				);
				add_meta_box( 'trash-duplicates_dashboard_widget', __( 'News From Solwin Infotech', 'trash-duplicate-and-301-redirect' ), 'tdrd_solwin_dashboard_widget_news', 'dashboard', 'normal', 'high' );
			}
		}
		if ( ! function_exists( 'tdrd_solwin_dashboard_widget_news' ) ) {
			/**
			 * News Widget for Solwin Dashboard.
			 */
			function tdrd_solwin_dashboard_widget_news() {
				echo '<div class="rss-widget">'
				. '<div class="solwin-news"><p><strong>Solwin Infotech News</strong></p>';
				wp_widget_rss_output(
					array(
						'url'          => 'https://www.solwininfotech.com/feed/',
						'title'        => __( 'News From Solwin Infotech', 'trash-duplicate-and-301-redirect' ),
						'items'        => 5,
						'show_summary' => 0,
						'show_author'  => 0,
						'show_date'    => 1,
					)
				);
				echo '</div>';
				$title     = '';
				$link      = '';
				$thumbnail = '';
				// get Latest product detail from xml file.
				$file = 'https://www.solwininfotech.com/documents/assets/latest_product.xml';
				echo '<div class="display-product">'
				. '<div class="product-detail"><p><strong>' . esc_html__( 'Latest Product', 'trash-duplicate-and-301-redirect' ) . '</strong></p>';
				$response = wp_remote_get( $file );
				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					echo '<p>' . esc_html__( 'Something went wrong', 'trash-duplicate-and-301-redirect' ) . ' : ' . esc_html( $error_message ) . '</p>';
				} else {
					$body                = wp_remote_retrieve_body( $response );
					$xml                 = simplexml_load_string( $body );
					$title               = $xml->item->name;
					$thumbnail           = $xml->item->img;
					$link                = $xml->item->link;
					$allproducttext      = $xml->item->viewalltext;
					$allproductlink      = $xml->item->viewalllink;
					$moretext            = $xml->item->moretext;
					$needsupporttext     = $xml->item->needsupporttext;
					$needsupportlink     = $xml->item->needsupportlink;
					$customservicetext   = $xml->item->customservicetext;
					$customservicelink   = $xml->item->customservicelink;
					$joinproductclubtext = $xml->item->joinproductclubtext;
					$joinproductclublink = $xml->item->joinproductclublink;
					echo '<div class="product-name"><a href="' . esc_url( $link ) . '" target="_blank">'
					. '<img alt="' . esc_attr( $title ) . '" src="' . esc_url( $thumbnail ) . '"> </a>'
					. '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_html( $title ) . '</a>'
					. '<p><a href="' . esc_url( $allproductlink ) . '" target="_blank" class="button button-default">' . esc_html( $allproducttext ) . ' &RightArrow;</a></p>'
					. '<hr>'
					. '<p><strong>' . esc_html( $moretext ) . '</strong></p>'
					. '<ul>'
					. '<li><a href="' . esc_url( $needsupportlink ) . '" target="_blank">' . esc_html( $needsupporttext ) . '</a></li>'
					. '<li><a href="' . esc_url( $customservicelink ) . '" target="_blank">' . esc_html( $customservicetext ) . '</a></li>'
					. '<li><a href="' . esc_url( $joinproductclublink ) . '" target="_blank">' . esc_html( $joinproductclubtext ) . '</a></li>'
					. '</ul>'
					. '</div>';
				}
				echo '</div></div><div class="clear"></div>'
				. '</div>';
			}
		}
	}
}

if ( ! function_exists( 'tdrd_footer' ) ) {
	/**
	 * Add Footer link.
	 */
	function tdrd_footer() {
		$screen = get_current_screen();
		if ( ( isset( $_GET['page'] ) && ( 'trash_duplicates' == $_GET['page'] || '301_redirects' == $_GET['page'] || 'about_trash_duplicates' == $_GET['page'] ) ) ) {
			add_filter( 'admin_footer_text', 'tdrd_remove_footer_admin' ); // change admin footer text.
		}
	}
}

if ( ! function_exists( 'tdrd_remove_footer_admin' ) ) {
	/**
	 * Add rating html at footer of admin.
	 *
	 * @return html rating
	 */
	function tdrd_remove_footer_admin() {
		ob_start();
		?>
		<p id="footer-left" class="alignleft">
			<?php esc_html_e( 'If you like ', 'trash-duplicate-and-301-redirect' ); ?>
			<a href="https://www.solwininfotech.com/product/wordpress-plugins/trash-duplicate-and-301-redirect/" target="_blank"><strong><?php esc_html_e( 'Trash Duplicate And 301 Redirection Plugin', 'trash-duplicate-and-301-redirect' ); ?></strong></a>
			<?php esc_html_e( 'please leave us a', 'trash-duplicate-and-301-redirect' ); ?>
			<a class="bdp-rating-link" data-rated="Thanks :)" target="_blank" href="https://wordpress.org/support/plugin/trash-duplicate-and-301-redirect/reviews?filter=5#new-post">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</a>
			<?php esc_html_e( 'rating. A huge thank you from Solwin Infotech in advance!', 'trash-duplicate-and-301-redirect' ); ?>
		</p>
		<?php
		return ob_get_clean();
	}
}

add_action( 'init', 'tdrd_session_start' );
if ( ! function_exists( 'tdrd_session_start' ) ) {
	/**
	 * Start session if not.
	 */
	function tdrd_session_start() {
		if ( version_compare( phpversion(), '7.0.0' ) != -1 ) {
			if ( session_status() == PHP_SESSION_NONE ) {
				session_start( array( 'read_and_close' => true ) );
			}
		} elseif ( version_compare( phpversion(), '5.4.0' ) != -1 ) {
			if ( session_status() == PHP_SESSION_NONE ) {
				session_start();
			}
		} else {
			if ( session_id() == '' ) {
				session_start();
			}
		}
	}
}


add_action( 'admin_head', 'tdrd_subscribe_mail', 10 );
if ( ! function_exists( 'tdrd_subscribe_mail' ) ) {
	/**
	 * Subscribe email form.
	 */
	function tdrd_subscribe_mail() {
		?>
		<div id="sol_deactivation_widget_cover_tdrd" style="display:none;">
			<div class="sol_deactivation_widget">
				<h3><?php esc_html_e( 'If you have a moment, please let us know why you are deactivating. We would like to help you in fixing the issue.', 'trash-duplicate-and-301-redirect' ); ?></h3>
				<form id="frmDeactivationtdrd" name="frmDeactivation" method="post" action="">
					<ul class="sol_deactivation_reasons_ul">
						<?php $i = 1; ?>
						<li>
							<input class="sol_deactivation_reasons" checked name="sol_deactivation_reasons_tdrd" type="radio" value="<?php echo esc_attr( $i ); ?>" id="tdrd_reason_<?php echo esc_attr( $i ); ?>">
							<label for="tdrd_reason_<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'I am going to upgrade to PRO version', 'trash-duplicate-and-301-redirect' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_tdrd" type="radio" value="<?php echo esc_attr( $i ); ?>" id="tdrd_reason_<?php echo esc_attr( $i ); ?>">
							<label for="tdrd_reason_<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'The plugin suddenly stopped working', 'trash-duplicate-and-301-redirect' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_tdrd" type="radio" value="<?php echo esc_attr( $i ); ?>" id="tdrd_reason_<?php echo esc_attr( $i ); ?>">
							<label for="tdrd_reason_<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'The plugin was not working', 'trash-duplicate-and-301-redirect' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_tdrd" type="radio" value="<?php echo esc_attr( $i ); ?>" id="tdrd_reason_<?php echo esc_attr( $i ); ?>">
							<label for="tdrd_reason_<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Found other better plugin than this plugin', 'trash-duplicate-and-301-redirect' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_tdrd" type="radio" value="<?php echo esc_attr( $i ); ?>" id="tdrd_reason_<?php echo esc_attr( $i ); ?>">
							<label for="tdrd_reason_<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'The plugin broke my site completely', 'trash-duplicate-and-301-redirect' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_tdrd" type="radio" value="<?php echo esc_attr( $i ); ?>" id="tdrd_reason_<?php echo esc_attr( $i ); ?>">
							<label for="tdrd_reason_<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'No any reason', 'trash-duplicate-and-301-redirect' ); ?></label>
						</li>
						<?php $i++; ?>
						<li>
							<input class="sol_deactivation_reasons" name="sol_deactivation_reasons_tdrd" type="radio" value="<?php echo esc_attr( $i ); ?>" id="tdrd_reason_<?php echo esc_attr( $i ); ?>">
							<label for="tdrd_reason_<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Other', 'trash-duplicate-and-301-redirect' ); ?></label><br/>
							<input style="display:none;width: 90%" value="" type="text" name="sol_deactivation_reason_other_tdrd" class="sol_deactivation_reason_other_tdrd" />
						</li>
					</ul>
					<p>
						<input type='checkbox' class='tdrd_agree' id='tdrd_agree_gdpr_deactivate' value='1' />
						<label for='tdrd_agree_gdpr_deactivate' class='tdrd_agree_gdpr_lbl'><?php echo esc_attr( __( 'By clicking this button, you agree with the storage and handling of your data as mentioned above by this website. (GDPR Compliance)', 'trash-duplicate-and-301-redirect' ) ); ?></label>
					</p>
					<a onclick='tdrd_submit_optin("deactivate")' class="button button-secondary"><?php esc_html_e( 'Submit &amp; Deactivate', 'trash-duplicate-and-301-redirect' ); ?></a>
					<input type="submit" name="sbtDeactivationFormClose" id="sbtDeactivationFormClosetdrd" class="button button-primary" value="<?php esc_html_e( 'Cancel', 'trash-duplicate-and-301-redirect' ); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="javascript:void(0)" class="tdrd-deactivation" aria-label="<?php esc_html_e( 'Deactivate Trash Duplicates and 301 redirects', 'trash-duplicate-and-301-redirect' ); ?>"><?php esc_html_e( 'Skip &amp; Deactivate', 'trash-duplicate-and-301-redirect' ); ?></a>
				</form>
				<div class="support-ticket-section">
					<h3><?php esc_html_e( 'Would you like to give us a chance to help you?', 'trash-duplicate-and-301-redirect' ); ?></h3>
					<img src="<?php echo esc_url( TDRD_PLUGIN_URL ) . '/images/support-ticket.png'; ?>">
					<a target='_blank' href="<?php echo esc_url( 'http://support.solwininfotech.com/' ); ?>"><?php esc_html_e( 'Create a support ticket', 'trash-duplicate-and-301-redirect' ); ?></a>
				</div>
			</div>
		</div>
		<a style="display:none" href="#TB_inline?height=500&inlineId=sol_deactivation_widget_cover_tdrd" class="thickbox" id="deactivation_thickbox_tdrd"></a>
		<?php
	}
}

add_action( 'wp_ajax_tdrd_submit_optin', 'tdrd_submit_optin' );
if ( ! function_exists( 'tdrd_submit_optin' ) ) {
	/**
	 * Submit optin data.
	 */
	function tdrd_submit_optin() {
		global $wpdb, $wp_version;
		$tdrd_submit_type = '';
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['email'] ) ) {
				$tdrd_email = sanitize_email( wp_unslash( $_POST['email'] ) );
			} else {
				$tdrd_email = get_option( 'admin_url' );
			}
			if ( isset( $_POST['type'] ) ) {
				$tdrd_submit_type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
			}
			if ( 'submit' == $tdrd_submit_type ) {
				$status_type   = get_option( 'tdrd_is_optin' );
				$theme_details = array();
				if ( $wp_version >= 3.4 ) {
					$active_theme                   = wp_get_theme();
					$theme_details['theme_name']    = wp_strip_all_tags( $active_theme->name );
					$theme_details['theme_version'] = wp_strip_all_tags( $active_theme->version );
					$theme_details['author_url']    = wp_strip_all_tags( $active_theme->{'Author URI'} );
				}
				$active_plugins = (array) get_option( 'active_plugins', array() );
				if ( is_multisite() ) {
					$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
				}
				$plugins = array();
				if ( count( $active_plugins ) > 0 ) {
					$get_plugins = array();
					foreach ( $active_plugins as $plugin ) {
						$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

						$get_plugins['plugin_name']    = wp_strip_all_tags( $plugin_data['Name'] );
						$get_plugins['plugin_author']  = wp_strip_all_tags( $plugin_data['Author'] );
						$get_plugins['plugin_version'] = wp_strip_all_tags( $plugin_data['Version'] );
						array_push( $plugins, $get_plugins );
					}
				}

				$plugin_data     = get_plugin_data( WP_PLUGIN_DIR . '/trash-duplicate-and-301-redirect/trash-duplicates.php', $markup = true, $translate = true );
				$current_version = $plugin_data['Version'];

				$plugin_data                           = array();
				$plugin_data['plugin_name']            = 'Trash Duplicate And 301 Redirection';
				$plugin_data['plugin_slug']            = 'trash-duplicate-and-301-redirect';
				$plugin_data['plugin_version']         = $current_version;
				$plugin_data['plugin_status']          = $status_type;
				$plugin_data['site_url']               = home_url();
				$plugin_data['site_language']          = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
				$current_user                          = wp_get_current_user();
				$f_name                                = $current_user->user_firstname;
				$l_name                                = $current_user->user_lastname;
				$plugin_data['site_user_name']         = esc_attr( $f_name ) . ' ' . esc_attr( $l_name );
				$plugin_data['site_email']             = false !== $tdrd_email ? $tdrd_email : get_option( 'admin_email' );
				$plugin_data['site_wordpress_version'] = $wp_version;
				$plugin_data['site_php_version']       = esc_attr( phpversion() );
				$plugin_data['site_mysql_version']     = $wpdb->db_version();
				$plugin_data['site_max_input_vars']    = ini_get( 'max_input_vars' );
				$plugin_data['site_php_memory_limit']  = ini_get( 'max_input_vars' );
				$plugin_data['site_operating_system']  = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
				$plugin_data['site_extensions']        = get_loaded_extensions();
				$plugin_data['site_activated_plugins'] = $plugins;
				$plugin_data['site_activated_theme']   = $theme_details;
				$url                                   = 'https://www.solwininfotech.com/analytics/';
				$response                              = wp_safe_remote_post(
					$url,
					array(
						'method'      => 'POST',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => array(),
						'body'        => array(
							'data'   => maybe_serialize( $plugin_data ),
							'action' => 'plugin_analysis_data',
						),
					)
				);
				update_option( 'tdrd_is_optin', 'yes' );
			} elseif ( 'cancel' == $tdrd_submit_type ) {
				update_option( 'tdrd_is_optin', 'no' );
			} elseif ( 'deactivate' == $tdrd_submit_type ) {
				$status_type   = get_option( 'tdrd_is_optin' );
				$theme_details = array();
				if ( $wp_version >= 3.4 ) {
					$active_theme                   = wp_get_theme();
					$theme_details['theme_name']    = wp_strip_all_tags( $active_theme->name );
					$theme_details['theme_version'] = wp_strip_all_tags( $active_theme->version );
					$theme_details['author_url']    = wp_strip_all_tags( $active_theme->{'Author URI'} );
				}
				$active_plugins = (array) get_option( 'active_plugins', array() );
				if ( is_multisite() ) {
					$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
				}
				$plugins = array();
				if ( count( $active_plugins ) > 0 ) {
					$get_plugins = array();
					foreach ( $active_plugins as $plugin ) {
						$plugin_data                   = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$get_plugins['plugin_name']    = wp_strip_all_tags( $plugin_data['Name'] );
						$get_plugins['plugin_author']  = wp_strip_all_tags( $plugin_data['Author'] );
						$get_plugins['plugin_version'] = wp_strip_all_tags( $plugin_data['Version'] );
						array_push( $plugins, $get_plugins );
					}
				}

				$plugin_data     = get_plugin_data( WP_PLUGIN_DIR . '/trash-duplicate-and-301-redirect/trash-duplicates.php', $markup = true, $translate = true );
				$current_version = $plugin_data['Version'];

				$plugin_data                             = array();
				$plugin_data['plugin_name']              = 'Trash Duplicate And 301 Redirection';
				$plugin_data['plugin_slug']              = 'trash-duplicate-and-301-redirect';
				$reason_id                               = isset( $_POST['selected_option_de'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_option_de'] ) ) : '';
				$plugin_data['deactivation_option']      = $reason_id;
				$plugin_data['deactivation_option_text'] = isset( $_POST['selected_option_de_text'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_option_de_text'] ) ) : '';
				if ( 7 == $reason_id && isset( $_POST['selected_option_de_other'] ) ) {
					$plugin_data['deactivation_option_text'] = sanitize_text_field( wp_unslash( $_POST['selected_option_de_other'] ) );
				}
				$plugin_data['plugin_version']         = $current_version;
				$plugin_data['plugin_status']          = $status_type;
				$plugin_data['site_url']               = home_url();
				$plugin_data['site_language']          = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
				$current_user                          = wp_get_current_user();
				$f_name                                = $current_user->user_firstname;
				$l_name                                = $current_user->user_lastname;
				$plugin_data['site_user_name']         = esc_attr( $f_name ) . ' ' . esc_attr( $l_name );
				$plugin_data['site_email']             = false !== $tdrd_email ? esc_attr( $tdrd_email ) : get_option( 'admin_email' );
				$plugin_data['site_wordpress_version'] = $wp_version;
				$plugin_data['site_php_version']       = esc_attr( phpversion() );
				$plugin_data['site_mysql_version']     = $wpdb->db_version();
				$plugin_data['site_max_input_vars']    = ini_get( 'max_input_vars' );
				$plugin_data['site_php_memory_limit']  = ini_get( 'max_input_vars' );
				$plugin_data['site_operating_system']  = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
				$plugin_data['site_extensions']        = get_loaded_extensions();
				$plugin_data['site_activated_plugins'] = $plugins;
				$plugin_data['site_activated_theme']   = $theme_details;
				$url                                   = 'https://www.solwininfotech.com/analytics/';
				$response                              = wp_safe_remote_post(
					$url,
					array(
						'method'      => 'POST',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => array(),
						'body'        => array(
							'data'   => maybe_serialize( $plugin_data ),
							'action' => 'plugin_analysis_data_deactivate',
						),
					)
				);
				update_option( 'tdrd_is_optin', '' );
			}
			exit();
		}
	}
}

if ( ! function_exists( 'tdrd_advertisement_sidebar' ) ) {
	/**
	 * Display Advertisement Sidebar.
	 */
	function tdrd_advertisement_sidebar() {
		?>
		<div class="td-admin-sidebar">
			<div class="td-help">
				<h2><?php esc_html_e( 'Help to improve this plugin!', 'trash-duplicate-and-301-redirect' ); ?></h2>
				<div class="help-wrapper">
					<span><?php esc_html_e( 'Enjoyed this plugin?', 'trash-duplicate-and-301-redirect' ); ?></span>
					<span><?php esc_html_e( ' You can help by', 'trash-duplicate-and-301-redirect' ); ?>
						<a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/trash-duplicate-and-301-redirect/reviews?filter=5#new-post' ); ?>" target="_blank">
							<?php esc_html_e( ' rating this plugin on wordpress.org', 'trash-duplicate-and-301-redirect' ); ?>
						</a>
					</span>
					<div class="td-total-download">
						<?php esc_html_e( 'Downloads:', 'trash-duplicate-and-301-redirect' ); ?><?php tdrd_get_total_downloads(); ?>
						<?php
						$wp_version = get_bloginfo( 'version' );
						if ( $wp_version > 3.8 ) {
							tdrd_wp_trash_duplicates_star_rating();
						}
						?>
					</div>
				</div>
			</div>
			<div class="useful_plugins">
				<h2><?php esc_html_e( 'Trash Duplicate And 301 Redirection PRO', 'trash-duplicate-and-301-redirect' ); ?></h2>
				<div class="help-wrapper">
					<div class="pro-content">
						<ul class="advertisementContent">
							<li><?php esc_html_e( 'Enable/disable redirection', 'trash-duplicate-and-301-redirect' ); ?></li>
							<li><?php esc_html_e( 'Trash without 301 redirection', 'trash-duplicate-and-301-redirect' ); ?></li>
							<li><?php esc_html_e( '301 redirection without Trash', 'trash-duplicate-and-301-redirect' ); ?></li>
							<li><?php esc_html_e( 'Wildcard redirection feature', 'trash-duplicate-and-301-redirect' ); ?></li>
							<li><?php esc_html_e( 'Delete Post Meta Permanently', 'trash-duplicate-and-301-redirect' ); ?></li>
							<li><?php esc_html_e( 'Import/Export Redirections', 'trash-duplicate-and-301-redirect' ); ?></li>
						</ul>
						<p class="pricing_change"><?php esc_html_e( 'Now only at', 'trash-duplicate-and-301-redirect' ); ?> <ins>$29</ins></p>
					</div>
					<div class="pre-book-pro">
						<a href="https://codecanyon.net/item/trash-duplicate-and-301-redirect-pro-for-wordpress/20885697?ref=solwin" target="_blank">
							<?php esc_html_e( 'Buy Now on Codecanyon', 'trash-duplicate-and-301-redirect' ); ?>
						</a>
					</div>
				</div>
			</div>
			<div class="td-support">
				<h3><?php esc_html_e( 'Need Support?', 'trash-duplicate-and-301-redirect' ); ?></h3>
				<div class="help-wrapper">
					<span><?php esc_html_e( 'Check out the', 'trash-duplicate-and-301-redirect' ); ?>
						<a href="https://wordpress.org/plugins/trash-duplicate-and-301-redirect/faq/" target="_blank"><?php esc_html_e( 'FAQs', 'trash-duplicate-and-301-redirect' ); ?></a>
						<?php esc_html_e( 'and', 'trash-duplicate-and-301-redirect' ); ?>
						<a href="https://wordpress.org/support/plugin/trash-duplicate-and-301-redirect" target="_blank"><?php esc_html_e( 'Support Forums', 'trash-duplicate-and-301-redirect' ); ?></a>
					</span>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'tdrd_plugin_links' ) ) {
	/**
	 * Display links.
	 *
	 * @param string $links Links.
	 */
	function tdrd_plugin_links( $links ) {
		$links[] = '<a class="documentation_tdrd_plugin" target="_blank" href="' . esc_url( 'https://www.solwininfotech.com/documents/wordpress/trash-duplicate-and-301-redirect/' ) . '">' . __( 'Documentation', 'trash-duplicate-and-301-redirect' ) . '</a>';
		$links[] = '<a class="success_msg tdrd_upgrade_link" target="_blank" href="' . esc_url( 'https://codecanyon.net/item/trash-duplicate-and-301-redirect-pro-for-wordpress/20885697?ref=solwin' ) . '">' . __( 'Upgrade', 'trash-duplicate-and-301-redirect' ) . '</a>';
		return $links;
	}
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tdrd_plugin_links' );

// Deactivate trash duplicate pro plugin when trash duplicate lite is activate.
register_activation_hook( __FILE__, 'tdrd_plugin_deactivate' );

if ( ! function_exists( 'tdrd_plugin_deactivate' ) ) {
	/**
	 * Deactivation of plugin.
	 */
	function tdrd_plugin_deactivate() {
		if ( is_plugin_active( 'trash-duplicate-and-301-redirect-pro/trash-duplicates.php' ) ) {
			deactivate_plugins( 'trash-duplicate-and-301-redirect-pro/trash-duplicates.php' );
		}
	}
}

// Remove optin in decativation of plugin.
register_deactivation_hook( __FILE__, 'tdrd_update_optin' );

if ( ! function_exists( 'tdrd_update_optin' ) ) {
	/**
	 * Delete optin on deactivation of plugin.
	 */
	function tdrd_update_optin() {
		update_option( 'tdrd_is_optin', '' );
	}
}

if ( ! function_exists( 'tdrd_upgrade_link_style' ) ) {
	/**
	 * Add css for upgrade link.
	 */
	function tdrd_upgrade_link_style() {
		echo '<style>.row-actions a.tdrd_upgrade_link { color: #4caf50; }</style>';
	}
}
add_action( 'admin_head', 'tdrd_upgrade_link_style' );

add_action( 'activated_plugin', 'tdrd_activated_plugin' );
if ( ! function_exists( 'tdrd_activated_plugin' ) ) {
	/**
	 * Redirection on welcome page.
	 *
	 * @param string $plugin Plugin.
	 */
	function tdrd_activated_plugin( $plugin ) {
		if ( plugin_basename( __FILE__ ) == $plugin ) {
			$tdrd_is_optin = get_option( 'tdrd_is_optin' );
			if ( 'yes' == $tdrd_is_optin || 'no' == $tdrd_is_optin ) {
				exit( wp_redirect( admin_url( 'admin.php?page=trash_duplicates' ) ) );
			} else {
				exit( wp_redirect( admin_url( 'admin.php?page=trash_duplicates_welcome' ) ) );
			}
		}
	}
}
