<?php

add_action( 'init', 'twchr_taxonomy_cat_twcht' );
function twchr_taxonomy_cat_twcht() {
	$labels = array(
		'name'              => _x( 'Twitch Category', 'taxonomy general name', 'twitcher' ),
		'singular_name'     => _x( 'Twitch Category', 'taxonomy singular name', 'twitcher' ),
		'search_items'      => __( 'Search Categorys Twitch', 'twitcher' ),
		'all_items'         => __( 'All Categorys Twitch', 'twitcher' ),
		'parent_item'       => __( 'Parent Twitch Category', 'twitcher' ),
		'parent_item_colon' => __( 'Parent Twitch Category:', 'twitcher' ),
		'edit_item'         => __( 'Edit Twitch Category', 'twitcher' ),
		'update_item'       => __( 'Update Twitch Category', 'twitcher' ),
		'add_new_item'      => __( 'Add New Twitch Category', 'twitcher' ),
		'new_item_name'     => __( 'New Twitch Category Name', 'twitcher' ),
		'menu_name'         => __( 'Twitch Category', 'twitcher' ),
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'cat_twcht' ),
	);
	register_taxonomy( 'cat_twcht', array( 'twchr_streams' ), $args );
}

add_action( 'cat_twcht_add_form_fields', 'twchr_cat_twcht_create_field' );

function twchr_cat_twcht_create_field() {
	?>
	<div clasS="form-field">
		<label>
			<p>Twitcht Catergory ID</p>
			<input type="number" name="twchr_stream_category_id" value="" placeholder="999"/>
		</label>
		<label>
			<p>Twitch Category Name</p>
			<input type="text" name="twchr_stream_category_name" value="" placeholder="just-chatting"/>
		</label>
		<label>
			<p>Twitch Category Thubnail</p>
			<input type="text" name="twchr_stream_category_thumbail" placeholder="https://static-cdn.jtvnw.net/ttv-boxart/33214-52x72.jpg" />
		</label>
	</div>
	<?php
}

add_action( 'cat_twcht_edit_form_fields', 'twchr_cat_twcht_edit_field', 10, 2 );

function twchr_cat_twcht_edit_field( $term, $taxonomy ) {
	$term_id = $term->term_id;

	$twchr_cat_id = get_term_meta( $term_id, 'twchr_stream_category_id', true );
	$twchr_cat_name = get_term_meta( $term_id, 'twchr_stream_category_name', true );
	$twchr_cat_thumbail = get_term_meta( $term_id, 'twchr_stream_category_thumbail', true );

	?>
	<div clasS="form-field">
		<label>
			<p>Twitcht Catergory ID</p>
			<input type="number" name="twchr_stream_category_id" value="<?php twchr_esc_i18n( $twchr_cat_id, 'html' ); ?>" placeholder="999"/>
		</label>
		<label>
			<p>Twitch Category Name</p>
			<input type="text" name="twchr_stream_category_name" value="<?php twchr_esc_i18n( $twchr_cat_name, 'html' ); ?>" placeholder="just-chatting"/>
		</label>
		<label>
			<p>Twitch Category Thubnail</p>
			<div style="display: grid;grid-template-columns:1fr 75px;">
				<input style="height: 1cm;align-self: center;" type="text" name="twchr_stream_category_thumbail" value="<?php twchr_esc_i18n( $twchr_cat_thumbail, 'html' ); ?>" placeholder="https://static-cdn.jtvnw.net/ttv-boxart/33214-52x72.jpg" />
				<?php if ( ! empty( $twchr_cat_thumbail ) ) : ?>
					<img  src="<?php twchr_esc_i18n( $twchr_cat_thumbail, 'html' ); ?>" alt="Twitcher Stream Category Thumbnail">
				<?php endif; ?>        
			</div>
		</label>
	</div>
	<?php
}

function twchr_cat_twitch_save( $term_id, $tt_id ) {
	

	$twchr_cat_id_old = get_term_meta( $term_id, 'twchr_stream_category_id', true );
	$twchr_cat_name_old = get_term_meta( $term_id, 'twchr_stream_category_name', true );
	$twchr_cat_thumbail_old = get_term_meta( $term_id, 'twchr_stream_category_thumbail', true );

	// Saneamos lo introducido por el usuario.
	$twchr_cat_id = isset($_POST['twchr_stream_category_id']) ?  sanitize_text_field( $_POST['twchr_stream_category_id'] ) : false;
	$twchr_cat_name = isset($_POST['twchr_stream_category_name']) ? sanitize_text_field( $_POST['twchr_stream_category_name'] ) : false;
	$twchr_cat_thumbail = isset($_POST['twchr_stream_category_thumbail']) ? sanitize_text_field( $_POST['twchr_stream_category_thumbail'] ) : false;

	if ( ! empty( $twchr_cat_id ) && ! empty( $twchr_cat_name ) && ! empty( $twchr_cat_thumbail ) ) {
		// Actualizamos el campo meta en la base de datos.
		update_term_meta( $term_id, 'twchr_stream_category_id', $twchr_cat_id, $twchr_cat_id_old );
		update_term_meta( $term_id, 'twchr_stream_category_name', $twchr_cat_name, $twchr_cat_name_old );
		update_term_meta( $term_id, 'twchr_stream_category_thumbail', $twchr_cat_thumbail, $twchr_cat_thumbail_old );
	}

}
  add_action( 'edit_cat_twcht', 'twchr_cat_twitch_save', 10, 5 );
  add_action( 'create_cat_twcht', 'twchr_cat_twitch_save', 10, 5 );



/**
 * Esta función PHP se utiliza para recuperar términos de una taxonomía específica. La taxonomía en cuestión es "cat_twcht". La función también recupera metadatos específicos para cada término, como el ID de la categoría de transmisión, el nombre de la categoría de transmisión y la miniatura de la categoría de transmisión. La función devuelve los términos recuperados y sus metadatos en una matriz. Si no se encuentran términos, la función devuelve false.
 *
 * @param [type] $request
 * @return void
 */
function twchr_api_get_cat_twcht( $request ) {
	$args = array(
		'taxonomy' => 'cat_twcht',
		'hide_empty' => false,
	);
	$request = get_terms( $args );
	$response = array();
	foreach ( $request as $term ) {
		$term_id = $term->{'term_id'};
		$array_rest = array(
			'term_id' => $term_id,
			'name' => $term->{'name'},
			'taxonomy' => $term->{'taxonomy'},
			'stream_category_id' => get_term_meta( $term_id, 'twchr_stream_category_id', true ),
			'stream_category_name' => get_term_meta( $term_id, 'twchr_stream_category_name', true ),
			'stream_category_thumbail' => get_term_meta( $term_id, 'twchr_stream_category_thumbail', true ),
		);

		array_push( $response, $array_rest );
	}

	COUNT( $response ) === 0 ? $response = false : $response = $response;

	return $response;
}

// Traigo todas las taxonomias de tipo cat_twcht
function twchr_set_terms() {
	// Aqui estaba tax_input
	$args = array(
		'taxonomy' => 'cat_twcht',
		'hide_empty' => false,
	);

	$request = get_terms( $args );
	$list_categories = array();
	foreach ( $request as $term ) {
		$term_id = $term->{'term_id'};
		$array_rest = array(
			'term_id' => $term_id,
			'name' => $term->{'name'},
			'taxonomy' => $term->{'taxonomy'},
			'stream_category_id' => get_term_meta( $term_id, 'twchr_stream_category_id', true ),
			'stream_category_name' => get_term_meta( $term_id, 'twchr_stream_category_name', true ),
			'stream_category_thumbail' => get_term_meta( $term_id, 'twchr_stream_category_thumbail', true ),
		);

		array_push( $list_categories, $array_rest );
	}

	foreach ( $list_categories as $list ) {
		$term_id = $list['term_id'];
		$twchr_cat_id = $list['stream_category_id'];
		$name_wp = $list['name'];
		$twchr_cat_name = $list['stream_category_name'];
		$twchr_cat_thumbail = $list['stream_category_name'];

		$twch_data_prime = get_option( 'twchr_keys' ) == false ? false : json_decode( get_option( 'twchr_keys' ) );
		$client_id = $twch_data_prime->{'client-id'};
		$app_token = get_option( 'twchr_app_token' );

		// $response = twtchr_twitch_categories_get($app_token, $client_id, $name_wp);

		if ( empty( $twchr_cat_id ) ) {
			$response = twtchr_twitch_categories_get( $app_token, $client_id, $name_wp );
			$data = $response->{'data'};
			foreach ( $data as $item ) {
				$name_twcht = $item->{'name'};
				if ( $name_twcht === $name_wp ) {
					update_term_meta( $term_id, 'twchr_stream_category_id', $item->id );
				}
			}
		}
		if ( empty( $twchr_cat_name ) ) {
			$response = twtchr_twitch_categories_get( $app_token, $client_id, $name_wp );
			$data = $response->{'data'};
			foreach ( $data as $item ) {
				$name_twcht = $item->{'name'};
				if ( $name_twcht === $name_wp ) {
					update_term_meta( $term_id, 'twchr_stream_category_name', $item->name );
				}
			}
		}
		if ( empty( $twchr_cat_thumbail ) ) {
			$response = twtchr_twitch_categories_get( $app_token, $client_id, $name_wp );
			$data = $response->{'data'};
			foreach ( $data as $item ) {
				$name_twcht = $item->{'name'};
				if ( $name_twcht === $name_wp ) {
					update_term_meta( $term_id, 'twchr_stream_category_thumbail', $item->{'box_art_url'} );
				}
			}
		}

		// show_dump($list);
	}
	// die();
}

add_action( 'set_object_terms', 'twchr_set_terms' );

if ( str_contains( $_SERVER['REQUEST_URI'], 'edit-tags.php?taxonomy=cat_twcht' ) ) {
	add_action( 'create_term', 'twchr_set_terms' );
}
