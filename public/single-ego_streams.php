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

			the_content();

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
