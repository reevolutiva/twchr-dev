<?php

add_action('init', 'twchr_taxonomy_cat_twcht');
function twchr_taxonomy_cat_twcht() {
    $labels = array(
        'name'              => _x( 'Category Twitch', 'taxonomy general name' , 'twitcher'),
        'singular_name'     => _x( 'Category Twitch', 'taxonomy singular name' , 'twitcher'),
        'search_items'      => __( 'Search Categorys Twitch' , 'twitcher'),
        'all_items'         => __( 'All Categorys Twitch' , 'twitcher'),
        'parent_item'       => __( 'Parent Category Twitch' , 'twitcher'),
        'parent_item_colon' => __( 'Parent Category Twitch:' , 'twitcher'),
        'edit_item'         => __( 'Edit Category Twitch' , 'twitcher'),
        'update_item'       => __( 'Update Category Twitch' , 'twitcher'),
        'add_new_item'      => __( 'Add New Category Twitch' , 'twitcher'),
        'new_item_name'     => __( 'New Category Twitch Name' , 'twitcher'),
        'menu_name'         => __( 'Category Twitch' , 'twitcher'),
    );
    $args = array( 
        'hierarchical'      => false, 
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => [ 'slug' => 'cat_twcht' ],
    );
    register_taxonomy( 'cat_twcht', array( 'post', 'twchr_streams' ), $args );
}

add_action( 'cat_twcht_add_form_fields', 'twchr_cat_twcht_create_field');

function twchr_cat_twcht_create_field(){
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

add_action( 'cat_twcht_edit_form_fields', 'twchr_cat_twcht_edit_field',10, 2 );

function twchr_cat_twcht_edit_field($term,$taxonomy) {
    $term_id = $term->term_id;
         
    $twchr_cat_id = get_term_meta($term_id,'twchr_stream_category_id',true);
    $twchr_cat_name = get_term_meta($term_id,'twchr_stream_category_name',true);
    $twchr_cat_thumbail = get_term_meta($term_id,'twchr_stream_category_thumbail',true);
    ?>
    <div clasS="form-field">
        <label>
            <p>Twitcht Catergory ID</p>
            <input type="number" name="twchr_stream_category_id" value="<?php echo $twchr_cat_id?>" placeholder="999"/>
        </label>
        <label>
            <p>Twitch Category Name</p>
            <input type="text" name="twchr_stream_category_name" value="<?php echo $twchr_cat_name?>" placeholder="just-chatting"/>
        </label>
        <label>
            <p>Twitch Category Thubnail</p>
            <div style="display: grid;grid-template-columns:1fr 75px;">
                <input style="height: 1cm;align-self: center;" type="text" name="twchr_stream_category_thumbail" value="<?php echo $twchr_cat_thumbail?>" placeholder="https://static-cdn.jtvnw.net/ttv-boxart/33214-52x72.jpg" />
                <img  src="<?php echo $twchr_cat_thumbail?>" alt="Twitcher Stream Category Thumbnail">
            </div>
        </label>
    </div>
    <?php
}

function twchr_cat_twitch_save( $term_id, $tt_id ) {
        
    $twchr_cat_id_old = get_term_meta($term_id,'twchr_stream_category_id',true);
    $twchr_cat_name_old = get_term_meta($term_id,'twchr_stream_category_name',true);
    $twchr_cat_thumbail_old = get_term_meta($term_id,'twchr_stream_category_thumbail',true);
        
    // Saneamos lo introducido por el usuario.            
    $twchr_cat_id = $_POST['twchr_stream_category_id'];
    $twchr_cat_name = $_POST['twchr_stream_category_name'];
    $twchr_cat_thumbail = $_POST['twchr_stream_category_thumbail'];
    
        
    // Actualizamos el campo meta en la base de datos.
    update_term_meta($term_id,'twchr_stream_category_id',$twchr_cat_id,$twchr_cat_id_old);
    update_term_meta($term_id,'twchr_stream_category_name',$twchr_cat_name, $twchr_cat_name_old);
    update_term_meta($term_id,'twchr_stream_category_thumbail',$twchr_cat_thumbail, $twchr_cat_thumbail_old);
    
  }
  add_action( 'edit_cat_twcht', 'twchr_cat_twitch_save', 10,5);
  add_action( 'create_cat_twcht', 'twchr_cat_twitch_save', 10,5);


  function twchr_cat_twcht_endpoint() {
    register_rest_route( 'twchr/', 'twchr_get_cat_twcht', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'twchr_api_get_cat_twcht',
    ) );
}

add_action( 'rest_api_init', 'twchr_cat_twcht_endpoint' );

function twchr_api_get_cat_twcht( $request ) {
    $args = array(
        'taxonomy' => 'cat_twcht',
        'hide_empty' => false
    );
    $request = get_terms($args);
    $response = array();
    foreach($request as $term){
        $term_id = $term->{'term_id'};
        $array_rest = array(
            "term_id" => $term_id,
            "name" => $term->{'name'},
            "taxonomy" => $term->{'taxonomy'},
            "stream_category_id" => get_term_meta($term_id,'twchr_stream_category_id',true),
            "stream_category_name" => get_term_meta($term_id,'twchr_stream_category_name',true),
            "stream_category_thumbail" => get_term_meta($term_id,'twchr_stream_category_thumbail',true)
        );

        array_push($response, $array_rest);
    }

    COUNT($response) === 0 ? $response = false : $response = $response;

    return $response;
}
