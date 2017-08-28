<?php

/**
 * Import styles from the parent theme
 */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style('fontawesome', get_stylesheet_directory_uri() . '/assets/stylesheet/font-awesome.min.css');
}

/**
 * Insert OpenGraph in head while a single post is fired
 */
function insert_opengraph_in_head() {
	global $post, $in_meta_header;
	$in_meta_header = true;
	if ( is_single() ) :
	$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
	echo "
<meta property='og:title' content='". get_the_title() ."'/>
<meta property='og:type' content='article' />
<meta property='og:url' content='". get_permalink() ."' />
<meta property='og:description' content='". strip_tags($post->post_content) ."' />
<meta property='og:image' content='". esc_attr( $thumbnail_src[0] ) ."' />".PHP_EOL;
	endif;

	$in_meta_header = false;


}
add_action('wp_head', 'insert_opengraph_in_head', 5);

/**
* Add the custom text to the post title within the single page
* @param string
* @return string
*/
function add_custom_title( $title ) {
	global $in_meta_header;
	if ( (is_single() && in_the_loop() ) && !$in_meta_header) {
		$sep = ": ";
		$custom_title = 'title : ';
		$custom_title .= $title;
		$title = $custom_title;
	}
	return $title;
}
add_filter( 'the_title', 'add_custom_title', 1 );

/**
* Add the custom text to the post title within the single page
* @param string
* @return string
*/
function add_custom_content( $content ) {
	global $in_meta_header;
	if ( is_single() && !$in_meta_header) {
		$custom_content = 'content : ';
		$custom_content .= $content;
		$content = wpautop( $custom_content );
	}
	return $content;
}
add_filter( 'the_content', 'add_custom_content', 1 );

/**
* Change the custom logo image when the post id equals to 1
*/
function change_logo_img($html)
{
	if( is_single('1') ) {
		$html = str_replace("cropped-logo.png", "cropped-logo-1.png", $html);
	}

	return $html;
}
add_filter('get_custom_logo','change_logo_img');
