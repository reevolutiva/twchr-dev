<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package egosapiens
 */
$args = array(
	"post_type" => "ego_stream"
);

$cpt = new WP_Query($args);

$twch_data_prime = json_decode(db_to_front('twitcher_keys')['last_result'][0]->option_value);

get_header();
?>

	<main id="primary" class="site-main">

		<?php
			while ( $cpt->have_posts() ) :
				$cpt->the_post();
			?>
				
					<h1><?php the_title(); ?></h1>
					<section>
						<?php the_content(); ?>
					</section>
					<a href="?autentication=true">Auntenticar</a>
			<?php
				//$token = get_twicth_api('80i53du4hlrjvnp6yag1lzirzk2kpd','oc3y4236g7hh43o6z3y3pd2mzlt3pn');

				//$list_videos = get_twicth_video($token)->data;
				
				//crearPostParaTwitch($list_videos);
		
			if ($_GET['autentication'] == true) {
				if(!empty($twch_data_prime->{'api-key'}) && 
				!empty($twch_data_prime->{'client-id'})
				):
					$client_id = $twch_data_prime->{'client-id'};
					$secret_key = $twch_data_prime->{'api-key'};
					$return = site_url('/ego_stream/get-user-token/');
		
					autenticate($client_id, $secret_key, $return);  
				endif;
			}
			if (isset($_GET['code'])) {
				$client_id = $twch_data_prime->{'client-id'};
				$apikey = $twch_data_prime->{'api-key'};

				$array_keys = array(
					'api-key' => $apikey,
					'client-id' => $client_id,
					'user_token' => $_GET['code']
				);
				$json_array = json_encode($array_keys);
				$sql = "UPDATE wp_options SET option_value='$json_array' WHERE option_name='twitcher_keys'";
				$wpdb->query($sql);
				echo "<code>".$array_keys['user_token']."</code>";
				echo "<h3>User Token actualizado actualizado correctamente</h3>";
			}
			
			
			endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
