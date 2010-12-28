<?php
/**
 * The Sidebar containing the primary and secondary for the About Pages.
 *
 * @package WordPress
 * @subpackage Margeaux
 * @since Margeaux 1.0
 */
?>

		<div id="primary" class="widget-area" role="complementary">
			

<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
?>
	
			<?php $args = array(
					 'menu'            => 'Projects Menu',
					'container_class' => 'side_menu', 
					'menu_id' => 'menu_for_projects',
					'menu_class' => 'projects_menu'
					  // 'container'       => 'div'
					);
			wp_nav_menu($args); ?>
			</div><!-- #primary .widget-area -->

<?php
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

		<div id="secondary" class="widget-area" role="complementary">
			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
		</div><!-- #secondary .widget-area -->

<?php endif; ?>
