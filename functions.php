<?php
if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '%2$s <span class="meta-sep">by</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'twentyten' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

	set_post_thumbnail_size( 150, 150, true ); // default thumbnail size
	add_image_size('pinky', 40, 40, true); // for pinky previews
	add_image_size('tn-200', 200, 200, true); // just in case
	add_image_size('tn-250', 250, 250, true); // just in case
	
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
			// This is where you'd create your custom image/link/whatever tag using the variables above.
			// This is an example of a basic image tag using this method.
			
			//DW trying to get rid of _still in Horizon
			
			$pattern = '/_still/';
			?>
			<?php if (!preg_match($pattern, $img_url)): ?>
				
		

			<a href="<?php echo $img_url; ?>"><img src="<?php echo $img_preview; ?>" alt='<?php echo $img_title; ?>' title='<?php echo $img_title; ?>' height="40" width="40"></a>
				<?php endif ?>
			<?php
			// End custom image tag. Do not edit below here.
			///////////////////////////////////////////////////////////

			}
			$i++;
		}

	}
}
/* Strip Extensions *******************************************

To get the extension of an image. http://www.lost-in-code.com/programming/php-code/php-strip-or-find-a-file-extension/
*/
	function stripExtension($filename = ”) {
	    if (!empty($filename)) {
	        $filename = strtolower($filename);
	        $extArray = split("[/\\.]", $filename);
	        $p = count($extArray) - 1;
	        $extension = $extArray[$p];
	        return $extension;
	    } else {
	        return false;
	    }
	}

/* Numeric Pagination *******************************************

This if from the Gravy template by Darren Hoyt. http://www.darrenhoyt.com 
*/

function numeric_pagination ($pageCount = 5, $query = null) {

	if ($query == null) {
		global $wp_query;
		$query = $wp_query;
	}
	
	if ($query->max_num_pages <= 1) {
		return;
	}

	$pageStart = 1;
	$paged = $query->query_vars['paged'];
	
	// set current page if on the first page
	if ($paged == null) {
		$paged = 1;
	}
	
	// work out if page start is halfway through the current visible pages and if so move it accordingly
	if ($paged > floor($pageCount / 2)) {
		$pageStart = $paged	- floor($pageCount / 2);
	}

	if ($pageStart < 1) {
		$pageStart = 1;
	}

	// make sure page start is 
	if ($pageStart + $pageCount > $query->max_num_pages) {
		$pageCount = $query->max_num_pages - $pageStart;
	}
	
?>
	<div id="archive_pagination">
<?php
	if ($paged != 1) {
?>
	<a href="<?php echo get_pagenum_link(1); ?>" class="numbered page-number-first"><span>&lsaquo; <?php _e('Newest', 'twentyten'); ?></span></a>
<?php
	}
	// first page is not visible...
	if ($pageStart > 1) {
		//echo 'previous';
	}
	for ($p = $pageStart; $p <= $pageStart + $pageCount; $p ++) {
		if ($p == $paged) {
?>
		<span class="numbered page-number-<?php echo $p; ?> current-numeric-page"><?php echo $p; ?></span>
<?php } else { ?>
		<a href="<?php echo get_pagenum_link($p); ?>" class="numbered page-number-<?php echo $p; ?>"><span><?php echo $p; ?></span></a>
<?php
		}
	}
	// last page is not visible
	if ($pageStart + $pageCount < $query->max_num_pages) {
		//echo "last";
	}
	if ($paged != $query->max_num_pages) {
?>
		<a href="<?php echo get_pagenum_link($query->max_num_pages); ?>" class="numbered page-number-last"><span><?php _e('Oldest', 'gravy'); ?> &rsaquo;</span></a>
<?php } ?>
	</div>
<?php } /* end of pagination */	
// add google analytics to footer
function add_google_analytics() {
echo '<script type="text/javascript">';
echo "\n";
echo '  var _gaq = _gaq || [];';
echo '  _gaq.push(["_setAccount", "UA-4209431-1"]);';
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

function get_archives_link_mod ( $link_html ) {
   preg_match ("/href='(.+?)'/", $link_html, $url);
   $requested = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";
      if ($requested == $url[1]) {
         $link_html = str_replace("<li>", "<li class='current-menu-item'>", $link_html);
      }
      return $link_html;
   }
add_filter("get_archives_link", "get_archives_link_mod");


?>