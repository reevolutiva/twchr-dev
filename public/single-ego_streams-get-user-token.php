<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package egosapiens
 */



$twch_data_prime = json_decode(db_to_front('twitcher_keys')['last_result'][0]->option_value);

get_header();
?>

	<main id="primary" class="site-main">

		<?php
			while ( have_posts() ) :
				the_post();
			?>
				
					<h1><?php the_title(); ?></h1>
					<section>
						<?php the_content(); ?>
					</section>
					<a href="?autentication=true">Auntenticar</a>
			<?php
				
		
			if ($_GET['autentication'] == true) {
				if(!empty($twch_data_prime->{'client-secret'}) && 
				!empty($twch_data_prime->{'client-id'})
				):
					$client_id = $twch_data_prime->{'client-id'};
					$secret_key = $twch_data_prime->{'client-secret'};
					$return = site_url('/ego_stream/get-user-token');
					$scope = array(
						"channel:manage:schedule"
					);
		
					autenticate($client_id, $secret_key, $return,$scope);  
				endif;
			}
			if (isset($_GET['code'])) {
				$client_id = $twch_data_prime->{'client-id'};
				$apikey = $twch_data_prime->{'client-secret'};

				$array_keys = array(
					'client-secret' => $apikey,
					'client-id' => $client_id,
					'code' => $_GET['code'],
					'scope' => $_GET['scope']
				);
				$json_array = json_encode($array_keys);
				$sql = "UPDATE wp_options SET option_value='$json_array' WHERE option_name='twitcher_keys'";

				
				$wpdb->query($sql);
				var_dump($array_keys);
				echo "<h3>User Token actualizado actualizado correctamente</h3>";
			}
			
			
			endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
