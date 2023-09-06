<?php
/**
 * Admin Redirection.
 *
 * @package Trash Duplicate and 301 Redirect
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'tdrd_301_redirect' ) ) {
	/**
	 * 301 Redirection.
	 */
	function tdrd_301_redirect() {
		?>
		<div class="trash-duplicates-wrap wrap">
			<h1><?php esc_html_e( '301 Redirection List', 'trash-duplicate-and-301-redirect' ); ?></h1>
			<?php
			global $wpdb;
			$tabel_name = $wpdb->prefix . 'tdrd_redirection';
			if ( ( isset( $_REQUEST['301_redirect_from_new'] ) || isset( $_REQUEST['301_redirect_to_new'] ) ) && isset( $_REQUEST['btnAdd'] ) ) {

				if ( ! isset( $_POST['301_redirection_nonce'] ) ) {
					return;
				}

				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['301_redirection_nonce'] ) ), plugin_basename( __FILE__ ) ) ) {
					return;
				}

				$old_url = sanitize_text_field( wp_unslash( $_REQUEST['301_redirect_from_new'] ) );
				$new_url = sanitize_text_field( wp_unslash( $_REQUEST['301_redirect_to_new'] ) );
				if ( '' != $old_url ) {
					if ( $wpdb->insert(
						$tabel_name,
						array(
							'old_url'   => $old_url,
							'new_url'   => $new_url,
							'date_time' => current_time(
								'mysql',
								1
							),
						)
					) === false ) {
						?>
						<div class="error notice is-dismissible below-h1" id="message">
							<p>
								<strong><?php esc_html_e( 'Error:', 'trash-duplicate-and-301-redirect' ); ?></strong>
								<?php esc_html_e( 'Fail to insert new record.', 'trash-duplicate-and-301-redirect' ); ?>
								<button class="notice-dismiss" type="button">
									<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'trash-duplicate-and-301-redirect' ); ?></span>
								</button>
							</p>
						</div>
						<?php
					} else {
						?>
						<div class="updated notice is-dismissible below-h1" id="message">
							<p>
								<strong><?php esc_html_e( 'Success:', 'trash-duplicate-and-301-redirect' ); ?></strong>
						<?php esc_html_e( ' Record inserted successfully.', 'trash-duplicate-and-301-redirect' ); ?>
								<button class="notice-dismiss" type="button">
									<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'trash-duplicate-and-301-redirect' ); ?></span>
								</button>
							</p>
						</div>
						<?php
					}
				}
			}

			if ( isset( $_REQUEST['chk_delete_sel'] ) && ( isset( $_REQUEST['btnDelete'] ) || isset( $_REQUEST['btnDelete1'] ) ) ) {

				if ( ! isset( $_POST['301_redirection_nonce'] ) ) {
					return;
				}

				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['301_redirection_nonce'] ) ), plugin_basename( __FILE__ ) ) ) {
					return;
				}
				tdrd_delete_selected( array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['chk_delete_sel'] ) ) );
			}
			if ( isset( $_REQUEST['btnSubmit'] ) ) {

				if ( ! isset( $_POST['301_redirection_nonce'] ) ) {
					return;
				}

				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['301_redirection_nonce'] ) ), plugin_basename( __FILE__ ) ) ) {
					return;
				}

				$update_from = isset( $_REQUEST['301_redirect_from'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['301_redirect_from'] ) ) : '';
				$update_to   = isset( $_REQUEST['301_redirect_to'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['301_redirect_to'] ) ) : '';
				$update_id   = isset( $_REQUEST['editid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['editid'] ) ) : '';
				$count       = is_array( $update_id ) || $update_id instanceof Countable ? count( $update_id ) : 0;
				for ( $i = 0; $i < $count; $i++ ) :
					$wpdb->update(
						$tabel_name,
						array(
							'old_url' => sanitize_text_field( wp_unslash( $update_from[ $i ] ) ),
							'new_url' => sanitize_text_field( wp_unslash( $update_to[ $i ] ) ),
						),
						array( 'ID' => absint( $update_id[ $i ] ) )
					);
				endfor;
				?>
				<div class="updated notice is-dismissible below-h1" id="message">
					<p>
						<?php esc_html_e( 'Saved successfully.', 'trash-duplicate-and-301-redirect' ); ?>
						<button class="notice-dismiss" type="button">
							<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'trash-duplicate-and-301-redirect' ); ?></span>
						</button>
					</p>
				</div>
				<?php
			}
				$stored_resultset  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}tdrd_redirection" ), ARRAY_A );
				$total_redirection = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}tdrd_redirection" ) );
			?>
			<form id="frm_301_redirect" method="post" name="frm_301_redirect">
			<?php
				wp_nonce_field( plugin_basename( __FILE__ ), '301_redirection_nonce' );
			if ( $stored_resultset ) :
				?>
					<input type="submit" value="<?php esc_attr_e( 'Delete', 'trash-duplicate-and-301-redirect' ); ?>" class="button-secondary margin_bottom_15" name="btnDelete1" onclick="return confirm(<?php esc_attr_e( 'Are you sure to remove it?', 'trash-duplicate-and-301-redirect' ); ?>)">
					<div class="td_redirection_count">
					<?php echo esc_html( $total_redirection ) . ' ' . esc_html__( 'Items', 'trash-duplicate-and-301-redirect' ); ?>
					</div>
				<?php
				endif;
			?>
				<table class="widefat striped">
					<thead>
						<tr>
							<th class="manage-column text-center padding_left_6">
								<?php if ( $stored_resultset ) : ?>
									<input type="checkbox" name="chk_del_all" id="chk_del_all">
									<?php
									endif;
								?>
							</th>
							<th colspan="2" class="manage-column">
								<strong>
									<?php esc_html_e( 'Request', 'trash-duplicate-and-301-redirect' ); ?>
									<small>(&nbsp;<?php esc_html_e( 'Example:', 'trash-duplicate-and-301-redirect' ); ?> /<?php esc_html_e( 'contact', 'trash-duplicate-and-301-redirect' ); ?> )</small>
								</strong>
							</th>
							<th colspan="2" class="manage-column">
								<strong>
									<?php esc_html_e( 'Destination', 'trash-duplicate-and-301-redirect' ); ?>
									<small>(&nbsp;<?php esc_html_e( 'Example:', 'trash-duplicate-and-301-redirect' ); ?> http://loremipsum.com/xyz/contact/ )</small>
								</strong>
							</th>
						</tr>
					</thead>
					<tbody><?php foreach ( $stored_resultset as $row ) : ?>
							<tr>
								<td class="text-center">
									<input type="checkbox" class="chkbox" name="chk_delete_sel[]" value="<?php echo esc_attr( $row['ID'] ); ?>">
								</td>
								<td>
									<input type="text" class="full_width" value="<?php echo esc_attr( $row['old_url'] ); ?>" name="301_redirect_from[]">
									<input type="hidden" name="editid[]" value="<?php echo absint( $row['ID'] ); ?>" />
								</td>
								<td class="icon_cover">»</td>
								<td>
									<input type="text" class="full_width" value="<?php echo esc_attr( $row['new_url'] ); ?>" name="301_redirect_to[]">
								</td>
								<td class="padding_left_0">
									<a href="
										<?php
										echo wp_nonce_url(
											add_query_arg(
												array(
													'action'    => 'delete',
													'delete-id' => absint( $row['ID'] ),
												)
											),
											'delete-action' . absint( $row['ID'] ),
											'delete_nonce'
										);
										?>
										" onclick="return confirm(<?php esc_attr_e( 'Are you sure to remove it?', 'trash-duplicate-and-301-redirect' ); ?>);" >
										<?php esc_html_e( 'Delete', 'trash-duplicate-and-301-redirect' ); ?>
									</a>
								</td>
							</tr><?php endforeach; ?>
						<tr>
							<td></td>
							<td><input type="text" class="full_width" name="301_redirect_from_new"></td>
							<td class="icon_cover">»</td>
							<td><input type="text" class="full_width" name="301_redirect_to_new"></td>
							<td class="padding_left_0"><input type="submit" class="button button-primary" name="btnAdd" value="<?php esc_attr_e( 'Add', 'trash-duplicate-and-301-redirect' ); ?>"></td>
						</tr>
					</tbody>
				</table>
				<?php if ( $stored_resultset ) : ?>
					<input type="submit" value="<?php esc_attr_e( 'Delete', 'trash-duplicate-and-301-redirect' ); ?>" class="margin_top_15 margin_right_15 button button-secondary" name="btnDelete" onclick="return confirm(<?php esc_attr_e( 'Are you sure to remove it?', 'trash-duplicate-and-301-redirect' ); ?>);">
					<div class="td_redirection_count">
						<?php echo esc_html( $total_redirection ) . ' ' . esc_html__( 'Items', 'trash-duplicate-and-301-redirect' ); ?>
					</div>
					<?php
				endif;
				?>
				<!-- <input type="submit" value="<?php //esc_attr_e( 'Save Changes', 'trash-duplicate-and-301-redirect' ); ?>" class="button-primary margin_top_15" name="btnSubmit"> -->
			</form>
		</div>
		<?php
		tdrd_advertisement_sidebar();
	}
}

if ( ! function_exists( 'tdrd_delete_selected' ) ) {
	/**
	 * Deleted Selected IDs.
	 *
	 * @param string $delete_sel_id Delete ID.
	 */
	function tdrd_delete_selected( $delete_sel_id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'tdrd_redirection';
		if ( $delete_sel_id ) {
			$count         = count( $delete_sel_id );
			$removed_items = array();
			for ( $i = 0; $i < $count; $i++ ) :
				$wpdb->delete( $table, array( 'ID' => absint( $delete_sel_id[ $i ] ) ) );
				$removed_items[] = $delete_sel_id[ $i ];
			endfor;
			$count_del = count( $removed_items );
			if ( $count_del ) {
				?>
				<div class="updated notice is-dismissible below-h1" id="message">
					<p>
						<?php echo esc_html( $count_del ); ?><?php esc_html_e( ' Records removed successfully.', 'trash-duplicate-and-301-redirect' ); ?>
						<button class="notice-dismiss" type="button">
							<span class="screen-reader-text">
								<?php esc_html_e( 'Dismiss this notice.', 'trash-duplicate-and-301-redirect' ); ?>
							</span>
						</button>
					</p>
				</div>
				<?php
			} else {
				?>
				<div class="error notice is-dismissible below-h1" id="message">
					<p>
					<?php esc_html_e( 'Failed to remove.', 'trash-duplicate-and-301-redirect' ); ?>
						<button class="notice-dismiss" type="button">
							<span class="screen-reader-text">
							<?php esc_html_e( 'Dismiss this notice.', 'trash-duplicate-and-301-redirect' ); ?>
							</span>
						</button>
					</p>
				</div>
				<?php
			}
		}
	}
}
add_action( 'admin_init', 'tdrd_delete_redirection_links' );
if ( ! function_exists( 'tdrd_delete_redirection_links' ) ) {
	/**
	 * Delete Redirection Links.
	 */
	function tdrd_delete_redirection_links() {
		global $wpdb;
		$tabel_name = $wpdb->prefix . 'tdrd_redirection';
		if ( isset( $_REQUEST['delete-id'] ) && isset( $_REQUEST['action'] ) && esc_html( sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) == 'delete' ) {
			$delete_id = absint( $_REQUEST['delete-id'] );
			if ( isset( $_GET['delete_nonce'] ) || wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['delete_nonce'] ) ), 'delete-action' . $delete_id ) ) {
				$counter = $wpdb->delete( $tabel_name, array( 'ID' => $delete_id ) );
				if ( $counter ) {
					?>
					<div class="updated notice is-dismissible below-h1" id="message">
						<p>
							<strong><?php esc_html_e( 'Success:', 'trash-duplicate-and-301-redirect' ); ?></strong>
							<?php esc_html_e( ' Removed successfully.', 'trash-duplicate-and-301-redirect' ); ?>
							<button class="notice-dismiss" type="button">
								<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'trash-duplicate-and-301-redirect' ); ?></span>
							</button>
						</p>
					</div>
					<?php
				} else {
					?>
					<div class="error notice is-dismissible below-h1" id="message">
						<p><strong><?php esc_html_e( 'Error:', 'trash-duplicate-and-301-redirect' ); ?></strong>
						<?php esc_html_e( ' Failed to remove.', 'trash-duplicate-and-301-redirect' ); ?>
							<button class="notice-dismiss" type="button">
								<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'trash-duplicate-and-301-redirect' ); ?></span>
							</button>
						</p>
					</div>
					<?php
				}
			} else {
				return false;
			}
			$url = admin_url() . 'admin.php?page=301_redirects';
			wp_redirect( $url );
			exit;
		}

		if ( isset( $_POST['filter-post'] ) ) {
			$search_arg = '';
			if ( isset( $_GET['search'] ) && '' != $_GET['search'] ) {
				$search_arg = '&search=' . esc_html( sanitize_text_field( wp_unslash( $_GET['search'] ) ) );
			}
			$post_type = isset( $_POST['trash-post-types'] ) ? esc_html( sanitize_text_field( wp_unslash( $_POST['trash-post-types'] ) ) ) : '';
			if ( isset( $post_type ) && '' != $post_type ) {
				$location = admin_url() . 'admin.php?page=trash_duplicates&trash_post_type=' . $post_type . $search_arg;
				wp_redirect( $location );
			}
		}

		if ( isset( $_POST['tra-search-submit'] ) ) {
			$post_type_arg = '';
			if ( isset( $_GET['trash_post_type'] ) && '' != $_GET['trash_post_type'] ) {
				$post_type_arg = '&trash_post_type=' . esc_html( sanitize_text_field( wp_unslash( $_GET['trash_post_type'] ) ) );
			}
			$search_text = isset( $_POST['tra-search-input'] ) ? esc_html( sanitize_text_field( wp_unslash( $_POST['tra-search-input'] ) ) ) : '';
			if ( isset( $search_text ) ) {
				$location = admin_url() . 'admin.php?page=trash_duplicates&search=' . $search_text . $post_type_arg;
				wp_redirect( $location );
			}
		}
	}
}
