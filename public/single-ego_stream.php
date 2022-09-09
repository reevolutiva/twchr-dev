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
		if($cpt->have_posts()){
			while ( $cpt->have_posts() ) :
				$cpt->the_post();
			?>
					<h1><?php the_title(); ?></h1>
					<section>
						<?php the_content(); ?>
					</section>
			<?php
				$token = get_twicth_api('80i53du4hlrjvnp6yag1lzirzk2kpd','oc3y4236g7hh43o6z3y3pd2mzlt3pn');

				$list_videos = get_twicth_video($token)->data;
				
				//crearPostParaTwitch($list_videos);
				
				
				
			endwhile; // End of the loop.
		}else{
			echo "<h3>Aun no se ha publicado contenido</h3>";
		}
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
