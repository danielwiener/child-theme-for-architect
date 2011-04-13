<?php
/*
Template Name: Recent Project Image Grid
*/
/**
 * The template for displaying the Thumbnails of the 10 most recent Project Pages in a Grid.
 *
 *
 * @package WordPress
 * @subpackage East End Architect
 * @since East End Architect 1.0  
 */

get_header(); ?>

		<div id="container" class="single-attachment">
			<div id="content" role="main">
				
	
		<div id="image_grid">
			<ul>		
<?php 
	$recent_args = array(
		'posts_per_page' => 15,
		'post_type' => 'page',
		'post_status' => 'publish',
		'caller_get_posts' => 1,
		'post_parent' => 69
		);
	query_posts($recent_args);
	if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			
				<li><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?><br />
				<?php the_title(); ?></a></li>
			
<?php endwhile; ?>
</ul>
</div> <!-- #image_grid -->
			</div><!-- #content -->
		</div><!-- #container -->


<?php get_footer(); ?>
