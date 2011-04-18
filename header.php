<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage East End Architect
 * @since East End Architect 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<?php if ( is_home() || is_front_page() || is_page('projects') || is_page('contact') ): ?>
			<meta name="description" content="Darren J. Helgesen offers architectural, interior, and landscape design, including site analysis, preliminary design, working drawings, and construction follow up.">
		<?php elseif( is_page('press') ): ?>
			      <meta name="description" content="Darren Helgesen is included in numerous publications, including Architectural Digest, House Beautiful, Country Living, This Old House, W, Elle, Hampton Style.">	
	 	<?php else: ?> 
		<?php $post = get_post( $postID );
		    $dw_dh_excerpt = strip_tags($post->post_content);
			$dw_dh_excerpt = str_replace( array( "\r\n", "\n\n"),' ',$dw_dh_excerpt); 
			$dw_dh_excerpt = preg_replace('/\s+?(\S+)?$/', '', substr($dw_dh_excerpt, 0, 155));
			?>
			 <meta name="description" content="<?php echo $dw_dh_excerpt; ?>">
		<?php endif; ?> 
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" /> 
<?php if( is_front_page() ): ?>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/tabs_slideshow.css" />
<?php endif; ?>
<?php

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */

	wp_head();
?>

<script type="text/javascript" src="http://use.typekit.com/tps2mqd.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php if ( is_front_page() ): ?>
<script type="text/javascript" src="<?php bloginfo("stylesheet_directory"); ?>/js/jquery.tools.min.js"></script>
<?php endif; ?> 
<?php if (is_page('press')): ?>
	<script type="text/javascript">
	// http://web.enavu.com/tutorials/making-image-captions-using-jquery/  
	jQuery(document).ready(function($){
		//for each description div...
		$('div.dw_description').each(function(){
			//...set the opacity to 0...
			$(this).css('opacity', 0);
			//..set width same as the image...
			$(this).css('width', $(this).siblings('img').width());
			//...get the parent (the wrapper) and set it's width same as the image width... '
			$(this).parent().css('width', $(this).siblings('img').width());
			//...set the display to block
			$(this).css('display', 'block');
		});
	
		$('div.dw_wrapper').hover(function(){
			//when mouse hover over the wrapper div
			//get it's children elements with class descriptio
			//and show it using fadeTo
			$(this).children('.dw_description').stop().fadeTo(500, 0.7);
		},function(){
			//when mouse out of the wrapper div
			//use fadeTo to hide the div
			$(this).children('.dw_description').stop().fadeTo(500, 0);
		});
	
	});  
	</script>        
<?php endif; ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
	<div id="header">
		<div id="masthead">
			<div id="branding" role="banner">
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> id="site-title">
					
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><span><?php bloginfo( 'name' ); ?></span></a></<?php echo $heading_tag; ?>>

			</div><!-- #branding -->

			<div id="access" role="navigation">
			  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			</div><!-- #access -->
		</div><!-- #masthead -->
	</div><!-- #header -->

	<div id="main"> 