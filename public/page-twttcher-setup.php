<?php 


get_header();
?>
	<style>
		.twchr_settUp_overlay{
			
			width: 100%;
			height: 100%;
			position:absolute;
			z-index: 99999;
			top:0;
		}
		.twchr_settUp_overlay #twchr-setUp{
			background-color: #fff;
		}
		.twchr_settUp_overlay #twchr-setUp section{
			display: grid;
			place-items:center;
			background-color: #fff;
			padding:2.5% 15%;

		}
		.twchr-button-setUp{
			margin-top:10px;
		}

		.twchr_settUp_overlay #twchr-setUp section form{
			margin-top: 50px;
		}

		.twchr_settUp_overlay #twchr-setUp section form input{
			margin-bottom: 10px;
		}
	</style>
	<main id="primary" class="twchr_settUp_overlay">

		<?php
		while ( have_posts() ) : the_post(); ?>

		<div class="contenido ">
			<?php the_content(); ?>
		</div>
		<?php
		endwhile; // End of the loop.
		?>
	</main><!-- #main -->

<?php
get_footer();


?>