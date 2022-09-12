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

$data_to_api = array(
	'title' => get_post_meta( get_the_ID(), 'to_apititle', true ),
	'start_date' => get_post_meta( get_the_ID(), 'to_apistart-date', true ),
	'start_time' => get_post_meta( get_the_ID(), 'to_apistart-time', true ),
	'is_serie' => get_post_meta( get_the_ID(), 'to_apiis_serie', true ),
	'duration' => get_post_meta( get_the_ID(), 'to_apiduration', true ),
	'categoria' => get_post_meta( get_the_ID(), 'to_apicategoria', true )
);


$cpt = new WP_Query($args);
get_header();
?>

	<main id="primary" class="site-main">

		<?php
			while ( $cpt->have_posts() ) :
				$cpt->the_post();
			?>
				
					<a href='<?php the_permalink();?>'><h1><?php the_title(); ?></h1></a>
					<?php if(
						!($data_to_api['title'] == 'Name Program')
						) : 
					?>
					<table>
						<tr>
							<td>title</td>
							<td><?php echo $data_to_api['title']; ?></td>
						</tr>
						<tr>
							<td>start date</td>
							<td><?php echo $data_to_api['start_date'];?></td>
						</tr>
						<tr>
							<td>start time</td>
							<td><?php echo $data_to_api['start_time']?></td>
						</tr>
						<tr>
							<td>is serie</td>
							<td><?php echo $data_to_api['is_serie'];?></td>
						</tr>
						<tr>
							<td>duration</td>
							<td><?php echo $data_to_api['duration'];?></td>
						</tr>
						<tr>
							<td>categoria</td>
							<td><?php echo $data_to_api['categoria']; ?></td>
						</tr>
					</table>
					<?php endif; ?>
					<section>
						<?php the_content(); ?>
					</section>
			<?php
				//$token = get_twicth_api('80i53du4hlrjvnp6yag1lzirzk2kpd','oc3y4236g7hh43o6z3y3pd2mzlt3pn');

				//$list_videos = get_twicth_video($token)->data;
				
				//crearPostParaTwitch($list_videos);
				
				
				
			endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
