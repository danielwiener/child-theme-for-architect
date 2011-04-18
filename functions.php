<?php 
 /**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 700;

//http://digwp.com/2010/03/wordpress-functions-php-template-custom-functions/ 
// add a favicon to your 
function blog_favicon() {
	echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('wpurl').'/favicon.ico" />';
}
add_action('wp_head', 'blog_favicon');

// remove junk from head  
//http://digwp.com/2010/03/wordpress-functions-php-template-custom-functions/
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
//http://www.google-adsense-templates.co.uk/removing-wordpress-generator-version-and-other-code-from-the-head.html
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
//http://pixelpunk.co.uk/2010/01/disable-wordpress-built-in-canonical-url/
remove_action('wp_head', 'rel_canonical');  

// smart jquery inclusion 
//http://digwp.com/2010/03/wordpress-functions-php-template-custom-functions/ 
function dw_add_js_scripts() {
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false);
		wp_enqueue_script('jquery'); 
	
		   wp_register_script('dw_slideshow',
		       get_bloginfo('stylesheet_directory') . '/js/dw_slideshow.js',
		       array('jquery'),
		       '1.0' );
		   // enqueue the script
		   wp_enqueue_script('dw_slideshow');
		   // register your script location, dependencies and version
		   //then if press page add the text overlay js
		   // can't get the is_page('press') to work. don't know why. try again later
		   wp_register_script('dw_text_overlay',
		       get_bloginfo('stylesheet_directory') . '/js/dw_text_overlay.js',
		       array('jquery'),
		       '1.0' ); 
		wp_enqueue_script('dw_text_overlay');
	  }       
} 
//also need to figure out how do this with less repitition, more elegantly
add_action('init', 'dw_add_js_scripts');

// enable threaded comments
function enable_threaded_comments(){
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
		}
}
add_action('get_header', 'enable_threaded_comments');

/**
* kill the admin nag 
* http://digwp.com/2010/03/wordpress-functions-php-template-custom-functions/ 
*/
if (!current_user_can('edit_users')) {
	add_action('init', create_function('$a', "remove_action('init', 'wp_version_check');"), 2);
	add_filter('pre_option_update_core', create_function('$a', "return null;"));
} 

/**
* This code will add the same "excerpt" box which you are familiar with on the "Add/Edit Post" admin area
* duplicate that functionality on the "Add/Edit Page" section.
* http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file 
* This is added to use excerpts for meta-tags
*/
if ( function_exists('add_post_type_support') ) {
    add_action('init', 'add_page_excerpts');
    function add_page_excerpts()
    {
        add_post_type_support( 'page', 'excerpt' );
    }
}

/**
* 	Add categories to Pages. An easy way to have 2 table of content pages for Projects, without having to change project code
*   http://shibashake.com/wordpress-theme/add-tags-and-categories-to-your-wordpress-page
*/ 

	function add_custom_tags_box() {
		add_meta_box(	'categorydiv', __('Categories'), 'post_categories_meta_box', 
				'page', 'side', 'low'); 
	   register_taxonomy_for_object_type('category', 'page');
	} 
	
add_action('admin_menu', 'add_custom_tags_box');  

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and post formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since East End Architect 0.5
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150, true ); // default thumbnail size
	add_image_size('pinky', 40, 40, true); // for pinky previews
	add_image_size('tn-200', 200, 200, true); // just in case
	add_image_size('tn-250', 250, 250, true); // just in case   
   	add_image_size('tn-300', 300, 300, true); // just in case

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();   

	// Deleted changeable header stuff   
}
endif;

if ( ! function_exists( 'twentyten_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *  not using this, so empty, so parent theme does not call it.
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since East End Architect 0.5
 */
	function twentyten_admin_header_style() {
	}
endif;

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *  not using this, so empty, so parent theme does not call it.
 * @since East End Architect 0.5
 */
	function twentyten_posted_on() {
	}
endif;

	  
	
function remove_dashboard_widgets() {
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
 	global $wp_meta_boxes;

	// Remove the incomming links widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);	

	// Remove right now
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
}

// Hook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

function nerdy_get_images($size = 'thumbnail', $limit = '0', $offset = '0') {
	global $post;

	$images = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );

	if ($images) {

		$num_of_images = count($images);

		if ($offset > 0) : $start = $offset--; else : $start = 0; endif;
		if ($limit > 0) : $stop = $limit+$start; else : $stop = $num_of_images; endif;

		$i = 0;
		foreach ($images as $image) {
			if ($start <= $i and $i < $stop) {
			$img_title = $image->post_title;   // title.
			$img_description = $image->post_content; // description.
			$img_caption = $image->post_excerpt; // caption.
			$img_url = wp_get_attachment_url($image->ID); // url of the full size image.
			$preview_array = image_downsize( $image->ID, $size );
 			$img_preview = $preview_array[0]; // thumbnail or medium image to use for preview.

 			///////////////////////////////////////////////////////////
			// This is where you'd create your custom image/link/whatever tag using the variables above		   
			?>
			<a href="<?php echo $img_url; ?>"><img src="<?php echo $img_preview; ?>" alt='<?php echo $img_title; ?>' title='<?php echo $img_title; ?>' height="40" width="40"></a>
			<?php
			// End custom image tag. Do not edit below here.
			///////////////////////////////////////////////////////////
			}
			$i++;
		}
	}
}
/* Numeric Pagination *******************************************
April 18, 2011 deleted this, because I was not using it. Find it in many of my function files, if needed
This if from the Gravy template by Darren Hoyt. http://www.darrenhoyt.com 
*/

//add google analytics to footer
function add_google_analytics() {
echo '<script type="text/javascript">';
echo "\n";
echo '  var _gaq = _gaq || [];';
echo '  _gaq.push(["_setAccount", "UA-21463862-1"]);';
echo '  _gaq.push(["_trackPageview"]);';
echo "\n";
echo '  (function() {';
echo '    var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;';
echo '    ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";';
echo '    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);';
echo '  })();';
echo "\n";
echo '</script>';
}
add_action('wp_footer', 'add_google_analytics');  
?>