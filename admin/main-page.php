<?php
	require_once 'aux-functions/twchr-max-of-list.php';
if ( ! twchr_is_ssl_secure() ) :
	?>
			<script>
				if(!location.href.includes('https')){
					alert('<?php _e( 'Twitch.tv requires SSL https:// secure sites. ', 'twitcher' ); ?>');
				}
				
			</script>
		<?php
		endif;
?>
<style>
	<?php include 'main_page.css'; ?>
</style>

<div class="twchr-for-back twchr-container">
	
	<article class='twchr-dashboard-card <?php if ( ! twchr_is_ssl_secure() ) { echo 'card-blur'; }?> plugin-hello'>
		<picture>
			<img src="<?php echo TWCHR_URL_ASSETS . 'Isologo_twitcher.svg'; ?>" alt="Logo Twitcher">
		</picture>
		<h2><?php twchr_esc_i18n( 'Dashboard', 'html' ); ?></h2>
	</article>
		<article>
			<h3><?php twchr_esc_i18n( 'Twitcher Data', 'html' ); ?></h3>
			<div class="twchr-dashboard-card 
			<?php
			if ( ! twchr_is_ssl_secure() ) {
				echo 'card-blur'; }
			?>
			 twchr-card-keys">
			<?php
				$data_broadcaster_raw = get_option( 'twchr_data_broadcaster', false ) == false ? false : json_decode( get_option( 'twchr_data_broadcaster' ) );

				$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
				// show_dump($twch_data_prime);
				// $twch_data_prime_lengt = count($twch_data_prime);
				$twch_data_app_token = get_option( 'twchr_app_token' );
				// show_dump($twch_data_app_token);

			if ( $data_broadcaster_raw != false ) :
				$display_name = $data_broadcaster_raw->{'data'}[0]->{'display_name'};
				$nombre = $data_broadcaster_raw->{'data'}[0]->{'login'};
				$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};
				$description = $data_broadcaster_raw->{'data'}[0]->{'description'};
				$foto = $data_broadcaster_raw->{'data'}[0]->{'profile_image_url'};
				$type = $data_broadcaster_raw->{'data'}[0]->{'type'};
				$broadcaster_type = $data_broadcaster_raw->{'data'}[0]->{'broadcaster_type'};
				$created_at = $data_broadcaster_raw->{'data'}[0]->{'created_at'};


				?>
				<div class='hello-twchr-user'> 
					<h2><?php printf( '%s', $display_name ); ?></h2>                                
					<p><?php printf( 'Description: %s', $description ); ?></p>
					<picture><img src="<?php echo esc_url( $foto ); ?>" alt="twchr-profile-picture"></picture>
				</div>
				<div class='keys-twchr twchr-data'> 
				
					<form method="GET" action="./edit.php">
						<input type="hidden" name="post_type" value="twchr_streams">
						<input type="hidden" name="page" value="twchr-dashboard">
					<?php $clientID = ! empty( $twch_data_prime->{'client-id'} ) ? $twch_data_prime->{'client-id'} : null; ?>
						<input id="client-id" type="hidden" placeholder="Client ID" name="client-id" value="<?php echo sanitize_key( $clientID ); ?>">
					<?php $clientSecret = ! empty( $twch_data_prime->{'client-secret'} ) ? $twch_data_prime->{'client-secret'} : null; ?>
						<input id="client-secret" type="hidden" placeholder="Client Secret" name="client-secret" value="<?php echo sanitize_key( $clientSecret ); ?>" disabled="true">
						<div>
							<p>Broadcaster Type</p>
							<p class="twchr-key-value"><?php echo empty( $broadcaster_type ) ? 'undefined' : esc_html( $broadcaster_type ); ?></p>
						</div>
						<div>
							<p>Type</p>
							<p class="twchr-key-value"><?php echo empty( $type ) ? 'undefined' : esc_html( $type ); ?></p>
						</div>
						<div>
							<p>Created at</p>
							<p class="twchr-key-value created_at"><?php echo esc_html( $created_at ); ?></p>
							
						</div>
						<div>
							<p>Client ID</p>
							<p class="twchr-key-value"><?php echo esc_html( $clientID ); ?></p>
						</div>
						<div>
							<p>User login</p>
							<p class="twchr-key-value"><?php echo esc_html( $nombre ); ?></p>
						</div>
						<input type="submit" value="<?php esc_attr_e( 'Reconnect', 'twitcher' ); ?>" name="renew" id='twchr_submitbutton' >
					<?php
					if ( isset( $_GET['renew'] ) ) {
						?>
									<script> 
										const wishexist = prompt('<?php twchr_esc_i18n( 'If you continue this process, all the api-keys installed in wordpress will be removed. Are you sure to do it?', 'html' ); ?> y = yes & n = no.');
										if(wishexist === 'y' || wishexist === 'yes'){
											location.href='<?php echo TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true&clearAll'; ?>';
										}else{
											location.href='<?php echo TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&page=twchr-dashboard'; ?>';
										}
									</script> 
							<?php
							die();
					}
					?>
							
						</form>
					</div>
				</div>
				<?php
			endif;
			if ( $data_broadcaster_raw == false ) :
				?>
			<div class="connect-card">
				<h2>Connecting...</h2>
			</div>
			<?php endif; ?>
		</article>
		<article>
			<h3>Your Twitch Results:</h3>
			<?php
				$data_broadcaster = '';
				$listVideo_from_api = false;
				$listVideo_from_wp = '';
			if ( $data_broadcaster_raw != false ) {
				$data_broadcaster = $data_broadcaster_raw->{'data'}[0];
				$client_id = $twch_data_prime->{'client-id'};
				$userToken = $twch_data_prime->{'user_token'};
				$broadcaster_id = $data_broadcaster_raw->{'data'}[0]->{'id'};

				$subcribers = twtchr_twitch_subscribers_get( $userToken, $client_id );

				$listVideo_from_api = false;
				if ( ! isset( twchr_twitch_video_get( $twch_data_app_token, $twch_data_prime->{'client-id'}, $broadcaster_id )->{'data'} ) ) {
					if ( twchr_twitch_video_get( $twch_data_app_token, $twch_data_prime->{'client-id'}, $broadcaster_id )->{'status'} === 401 ) {
						$listVideo_from_api = false;
					}
				} else {
					$listVideo_from_api = twchr_twitch_video_get( $twch_data_app_token, $twch_data_prime->{'client-id'}, $broadcaster_id );
				}

				$listVideo_from_wp = twchr_get_stream();
			}

			if ( $listVideo_from_api === false && get_option( 'twchr_set_instaled' ) <= 3 ) {

			} else {

				$mostViwed_from_api = twchr_max_of_list( $listVideo_from_api->{'data'}, 'view_count', 'title' );
				$mostViwed_from_wp = twchr_max_of_list( $listVideo_from_wp, 'twchr-from-api_view_count', 'post_title', true );

				//show_dump($listVideo_from_api->{'data'});
			}
				// TODO: Llamar credeciales en la misma funcion.
				$followers = twtchr_twitch_users_get_followers();
				

			?>
			<div class="twchr-dashboard-card 
			<?php
			if ( ! twchr_is_ssl_secure() ) {
				echo 'card-blur'; }
			?>
			 twitch-result" >
				<?php if ( $listVideo_from_api != false && get_option( 'twchr_set_instaled' ) >= 3 && get_option( 'twchr_data_broadcaster' ) != false ) : ?>
				<table>
					<tbody>
						<tr>
							<td><?php twchr_esc_i18n( 'Followers', 'html' ); ?></td>
							<td data-twchr-final-number="<?php echo isset( $followers ) ? 'Followers' : ''; ?>" class='twchr-results-item' ><?php echo isset( $followers->{'total'} ) ? $followers->{'total'}: ''; ?></td> 
						</tr>
						<tr>
							<td><?php twchr_esc_i18n( 'Suscribers', 'html' ); ?></td>
							<td data-twchr-final-number="<?php echo isset( $subcribers->{'total'} ) ? $subcribers->{'total'} : 0; ?>" class='twchr-results-item' ><?php echo isset( $subcribers->{'total'} ) ? esc_html( $subcribers->{'total'} ) : 0; ?></td>
						</tr>
						<tr>
							<td><?php twchr_esc_i18n( 'Videos', 'html' ); ?></td>
							<td data-twchr-final-number="<?php echo isset( $listVideo_from_api ) ? COUNT( $listVideo_from_api->{'data'} ) : 0; ?>" class='twchr-results-item'><?php echo isset( $listVideo_from_api ) ? COUNT( $listVideo_from_api->{'data'} ) : 0; ?></td>
						</tr>
						<tr>
							<td><?php twchr_esc_i18n( 'Most viewed', 'html' ); ?></td>
							<td data-twchr-final-number="<?php echo $mostViwed_from_api != false ? $mostViwed_from_api['view'] : 0; ?>" class='twchr-results-item'>0</td>
							<td class="twchr-tooltip"><?php echo $mostViwed_from_api != false ? esc_html( $mostViwed_from_api['title'] ) : 'undefined'; ?></td>
						</tr>
						<tr>
							<td><?php twchr_esc_i18n( 'Last Imported', 'html' ); ?></td>
							<td data-twchr-final-number="<?php echo $mostViwed_from_wp != false ? $mostViwed_from_wp['view'] : 0; ?>" class='twchr-results-item' >12</td>
							<td class="twchr-tooltip"><?php echo $mostViwed_from_wp != false ? esc_html( $mostViwed_from_wp['title'] ) : 'undefined'; ?></td>
						</tr>
						<tr>
							<td class="btn-renew-apiKeys">
								<a href="<?php echo TWCHR_HOME_URL . '/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard'; ?>"><?php twchr_esc_i18n( 'Refresh', 'html' ); ?></a>
								
							</td>
						</tr>
					</tbody>
				</table>
					<?php
				endif;
				if ( get_option( 'twchr_set_instaled' ) <= 3  && isset($broadcaster_id)) :
					$setInstaled = get_option( 'twchr_set_instaled' );
					if ( $setInstaled >= 3 && $listVideo_from_api === false ) {
						$error = twchr_twitch_video_get( $twch_data_app_token, $twch_data_prime->{'client-id'}, $broadcaster_id );
						?>
							<div class="error-card">
								<h3>Error: <?php echo esc_html( $error->{'status'} ); ?></h3>
								<p><?php echo esc_html( $error->{'message'} ); ?></p>
							</div>
						<?php
					} else if ( $setInstaled < 3 ) {
						?>
							<div class="connect-card">
								<h2>Connecting...</h2>
							</div>
						<?php
					}
					endif;
				?>
			</div>
		</article>
		<article>
		<h3>Your Twitch connection:</h3>
		<?php
		if ( isset( $_GET['from'] ) && $_GET['from'] == 'setUp-plugin' ) {

			if (
				isset( $_GET['client-id'] ) && ! empty( $_GET['client-id'] ) &&
				isset( $_GET['client-secret'] ) && ! empty( $_GET['client-secret'] ) &&
				isset( $_GET['twchr_share_twitch_data'] ) && ! empty( $_GET['twchr_share_twitch_data'] )

			) {
				$client_id = sanitize_text_field( $_GET['client-id'] );
				$client_secret = sanitize_text_field( $_GET['client-secret'] );
				$share_twitch_data = $_GET['twchr_share_twitch_data'] == 'on' ? true : false;
				$share_crm_data = isset( $_GET['twchr_share_crm_data'] ) && $_GET['twchr_share_crm_data'] == 'on' ? true : false;
				$share_permision = array(
					'twitch'  => $share_twitch_data,
					'crm' => $share_crm_data,
				);

				add_option( 'twchr_share_permissions', json_encode( $share_permision ) );
				add_option( 'twchr_set_clear_tw', 0 );


				fronted_to_db( $client_secret, $client_id );

				// Obtengo App Token
				$twchr_token_app = twtchr_twitch_autenticate_apptoken_get( $client_id, $client_secret );

				// Guardo AppToken
				twchr_save_app_token( $twchr_token_app->{'access_token'} );



				// Paso 2 de la instalacion
				update_option( 'twchr_set_instaled', 2, true );
				echo "<script> location.href='" . TWCHR_HOME_URL . "/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true'; </script>";
				die();

			}
		} else {


			?>
			
			<div class="twchr-dashboard-card 
			<?php
			if ( ! twchr_is_ssl_secure() ) {
				echo 'card-blur'; }
			?>
			 twitch-connect">
				<table>
					<tbody>
						<tr>
							<td><?php twchr_esc_i18n( 'Renew Client ID', 'html' ); ?></td>
							<td class='twitch-connect__status'><?php echo isset( $twch_data_prime->{'client-id'} ) != false ? "<img src='" . TWCHR_URL_ASSETS . "sync.svg'>" : "<span style='color:tomato;'>" . __( 'Error', 'twitcher' ) . '</span>'; ?></td>
						</tr>
						<tr>
							<td><?php twchr_esc_i18n( 'Renew Client Secret', 'html' ); ?></td>
							<td class='twitch-connect__status'><?php echo isset( $twch_data_prime->{'client-secret'} ) != false ? "<img src='" . TWCHR_URL_ASSETS . "sync.svg'>" : "<span style='color:tomato;'>" . __( 'Error', 'twitcher' ) . '</span>'; ?></td>
						</tr>
						<tr>
							<td><a class="btn" href="<?php echo TWCHR_HOME_URL; ?>/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true"><?php twchr_esc_i18n( 'Renew User Token', 'html' ); ?></a></td>
							<td class='twitch-connect__status'><?php echo isset( $twch_data_prime->{'user_token'} ) != false ? "<img src='" . TWCHR_URL_ASSETS . "sync.svg'>" : "<span style='color:tomato;'>" . __( 'Error', 'twitcher' ) . '</span>'; ?></td>
						</tr>
						<tr>
							<td><a href="<?php echo TWCHR_HOME_URL; ?>/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&app_token_action=update"><?php echo twchr_esc_i18n( 'Renew App Token', 'html' ); ?></a></td>
							<td class='twitch-connect__status'><?php echo $twch_data_app_token != false ? "<img src='" . TWCHR_URL_ASSETS . "sync.svg'>" : "<span style='color:tomato;'>" . __( 'Error', 'twitcher' ) . '</span>'; ?></td>
						</tr>
						<tr>
							<td class="btn-renew-apiKeys"><a href="<?php echo TWCHR_ADMIN_URL . '/edit.php?post_type=twchr_streams&page=twchr-dashboard'; ?>&app_token_action=renewAll_api_keys"><?php twchr_esc_i18n( 'Renew all', 'html' ); ?></a></td>
						</tr>
					</tbody>
				</table>
			</div>

			<?php
			if ( isset( $_GET['app_token_action'] ) ) {
				switch ( sanitize_text_field( $_GET['app_token_action'] ) ) {
					case 'update':
						$twchr_token_app = twtchr_twitch_autenticate_apptoken_get( $twch_data_prime->{'client-id'}, $twch_data_prime->{'client-secret'} );
						twchr_save_app_token( $twchr_token_app->{'access_token'} );
						// Paso 3 de instalaccion
						echo "<script>location.href='" . TWCHR_ADMIN_URL . "edit.php?post_type=twchr_streams&page=twchr-dashboard'" . '</script>';
						break;
					case 'renewAll_api_keys':
						$twchr_token_app = twtchr_twitch_autenticate_apptoken_get( $twch_data_prime->{'client-id'}, $twch_data_prime->{'client-secret'} );
						twchr_save_app_token( $twchr_token_app->{'access_token'} );
						echo "<script>location.href='" . TWCHR_ADMIN_URL . "edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true'" . '</script>';

					default:
						// code...
						break;
				}
			}
			?>
		
	</article>
			<?php
			if ( isset( $_GET ) ) {
				if ( count( $_GET ) > 1 ) {
					if ( isset( $_GET['sincronizar'] ) ) {
						if ( isset( $_GET['client-id'] ) && isset( $_GET['client-secret'] ) ) {
							$client_id = sanitize_text_field( $_GET['client-id'] );
							$client_secret = sanitize_text_field( $_GET['client-secret'] );
							$array_keys = array(
								'client-secret' => $client_secret,
								'client-id' => $client_id,
							);
							$json_array = json_encode( $array_keys );

							add_option( 'twchr_keys', $json_array );

							wp_redirect( TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams&page=twchr-dashboard' );
							exit;


						}
					}

					if ( isset( $_GET['autentication'] ) ) {
						// show_dump($twch_data_prime);
						if ( $_GET['autentication'] == true && twchr_is_ssl_secure() ) {

							if ( ! empty( $twch_data_prime->{'client-secret'} ) &&
							! empty( $twch_data_prime->{'client-id'} )
							) :
								$client_id = $twch_data_prime->{'client-id'};
								$secret_key = $twch_data_prime->{'client-secret'};
								$return = TWCHR_HOME_URL . '/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard';
								$scope = array(
									'channel:manage:schedule',
									'channel:read:subscriptions',
								);

								if ( isset( $_GET['twchr_id'] ) ) {
									$term_id = sanitize_text_field( $_GET['twchr_id'] );
									$allData = '';
									update_term_meta( $term_id, 'twchr_fromApi_allData', $allData );
								}

								if ( isset( $_GET['twcr_from_cpt'] ) ) {
									$post_id = sanitize_text_field( $_GET['twcr_from_cpt'] );
									$twitcher_respose = '';
									update_post_meta( $post_id, 'twchr_stream_all_data_from_twitch', $twitcher_respose );
								}



								twtchr_twitch_autenticate( $client_id, $secret_key, $return, $scope );

							endif;
						}
					}
					if ( isset( $_GET['code'] ) ) {
						$client_id = sanitize_text_field( $twch_data_prime->{'client-id'} );
						$client_secret = sanitize_text_field( $twch_data_prime->{'client-secret'} );
						$code = sanitize_text_field( $_GET['code'] );
						$scope = sanitize_text_field( $_GET['scope'] );
						$redirect = TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams%26page=twchr-dashboard';

						$response = twchr_validate_token( $client_id, $client_secret, $code, $redirect );
						$twchr_validate_tokenObject = json_decode( $response['body'] );
						$response_response = $response['response'];

						if ( $response_response['code'] == 200 ) {
							$userToken = $twchr_validate_tokenObject->{'access_token'};
							$userTokenRefresh = $twchr_validate_tokenObject->{'refresh_token'};

							$array_keys = array(
								'client-secret' => $client_secret,
								'client-id' => $client_id,
								'code' => $code,
								'scope' => $scope,
								'user_token' => $userToken,
								'user_token_refresh' => $userTokenRefresh,
							);

							$json_array = json_encode( $array_keys );

							update_option( 'twchr_keys', $json_array, true );
							update_option( 'twchr_set_instaled', 3, true );


							$url_redirection = TWCHR_HOME_URL . '/wp-admin/edit.php?post_type=twchr_streams&page=twchr-dashboard&autentication=true';
							echo "<script>location.href='$url_redirection'</script>";
						} else {
							?>
							<div class="twchr-modal-error">
								<h3>¡Ups! User Token no ha sido actualizado actualizado correctamente</h3>
								<p>Intente nuevamente</p>
								<p><b>Error: </b><?php echo $twchr_validate_tokenObject->{'message'}; ?></p>
								<p>
									<a href='<?php echo TWCHR_ADMIN_URL . 'edit.php?post_type=twchr_streams&page=twchr_help&setUpPage=true&error=' . $twchr_validate_tokenObject->{'message'}; ?>'>
										<?php twchr_esc_i18n( 'Back SetUp', 'html' ); ?>
									</a>
								</p>
							</div>
							<?php
							die();
						}
					}

					if ( isset( $_GET['error'] ) && isset( $_GET['error_description'] ) ) {
						echo 'Error: ' . sanitize_text_field( $_GET['error'] );
						echo '</br>';
						echo __( 'Error description: ', 'twitcher' ) . sanitize_text_field( $_GET['error_description'] );
					}

					if ( isset( $_GET['error_description'] ) ) {
						?>
							<script>
								alert("<?php echo $_GET['error_description']; ?>");
							</script>
						<?php
					}

					if ( isset( $_GET['twch_api_error'] ) ) {
						$data_raw = sanitize_text_field( $_GET['twch_api_error'] );
						$data = str_replace( '\\', '', $data_raw, );
						?>
						<h4><?php esc_html( $data ); ?></h4>
						<?php
						echo "<a href='" . TWCHR_HOME_URL . '/wp-admin/post.php?post=' . sanitize_text_field( $_GET['twch_post_id'] ) . "&action=edit'>" . __( 'try again', 'twitcher' ) . '</a>';
					}
				}
			}
			// var_dump($twch_data_prime);
		}

		if ( isset( $_GET['twchr_server_response'] ) && $_GET['twchr_server_response'] != 200 ) {
			?>
				<script>
					alert("<?php echo $_GET['twchr_server_response']; ?>");
				</script>
			<?php
		}

		?>
