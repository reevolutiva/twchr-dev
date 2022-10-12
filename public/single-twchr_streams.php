<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package egosapiens
 */


get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			?>
				<h1><?php the_title();?></h1>
			<?php
			the_post();

			the_content();

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
