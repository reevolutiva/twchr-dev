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
					<a href="?autentication=true">auth</a>
			<?php
				//$token = get_twicth_api('80i53du4hlrjvnp6yag1lzirzk2kpd','oc3y4236g7hh43o6z3y3pd2mzlt3pn');

				//$list_videos = get_twicth_video($token)->data;
				
				//crearPostParaTwitch($list_videos);
				
				if($_GET['autentication']==true){
					$api_key = 'lvlu0kmiervxate3yqfhppsh4d2kol';
  					$client_id = 'mtxa43qjzhqij6793d1l095a5hwwcd';
					autenticate($client_id, $secret_key,'https://egosapiens.local/wp-admin/admin.php?page=egosapiens_wp_menu');
				}
				if(isset($_GET['code'])){
					var_dump($_GET['code']);
				}
				
				
			endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
