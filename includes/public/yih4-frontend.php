<?php
/* Custom Functions & Shortcodes */
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;
// Output the YouTube Before Video Widget
function yih4_nwd_before_video_func() {
	ob_start();
	if ( is_singular( array( 'yih4-video' ) ) && is_active_sidebar( 'youtube_before_video' ) && is_main_query() ) {
		dynamic_sidebar( 'youtube_before_video' );
	}
	$yih4_before_video = ob_get_contents();
	ob_end_clean();
	return $yih4_before_video;
}

// Output the YouTube After Video Widget
function yih4_nwd_after_video_func() {
	ob_start();
	if ( is_singular( array( 'yih4-video' ) ) && is_active_sidebar( 'youtube_after_video' ) && is_main_query() ) {
		dynamic_sidebar( 'youtube_after_video' );
	}
	$yih4_after_video = ob_get_contents();
	ob_end_clean();
	return $yih4_after_video;
}
// Output the YouTube After Content Widget
function yih4_nwd_after_content_func() {
	ob_start();
	if ( is_singular( array( 'yih4-video' ) ) && is_active_sidebar( 'youtube_after_content' ) && is_main_query() ) {
		dynamic_sidebar( 'youtube_after_content' );
	}
	$yih4_after_content = ob_get_contents();
	ob_end_clean();
	return $yih4_after_content;
}

// Insert Custom Content Before and/or After 'the_content();'
function yih4_nwd_postwrap_content( $content ) {
			$options = get_option( 'yih4_nwd_settings' );
			$options2 = get_option( 'yih4_nwd_tab_settings_2' );
			$options3 = get_option( 'yih4_nwd_tab_settings_3' );
			$yih4_description = yih4_nwd_description_before_post();
			$yih4_videoID = get_post_meta( get_the_ID(), '_yih4_video_id', true );

			// Video Arguments START
			$yih4_videoargs = ((!empty($options2['yih4_tab_option_9']))	?	'?autoplay=1'	:	'?autoplay=0');
			$yih4_videoargs	.=	((!empty($options2['yih4_tab_option_10'])) ? '&rel=1' : '&rel=0');
			$yih4_videoargs .= ((!empty($options2['yih4_tab_option_11']))	?	'&showinfo=1'	:	'&showinfo=0');
			$yih4_videoargs .= ((!empty($options2['yih4_tab_option_12'])) ? '&loop=1&playlist='.$yih4_videoID : '&loop=0');
			$yih4_videoargs .= ((!empty($options2['yih4_tab_option_13'])) ? '&modestbranding=0' : '&modestbranding=1');
			$yih4_videoargs .= ((!empty($options2['yih4_tab_option_14'])) ? '&iv_load_policy=1' : '&iv_load_policy=3');
			// Video Arguments END

			// MediaElement Arguments
			$media_elem_autoplay = ((!empty($options2['yih4_tab_option_9']))	?	'autoplay="on"'	:	'');

			// YouTube Post Global Settings START
			$yih4_comments = ((!empty(	$options3['yih4_tab_option_15']	)	)	? yih4_nwd_youtube_comments() :	'');
			$yih4_showstats = ((!empty($options3['yih4_tab_option_20'])) ? do_shortcode('[allStats]') : '<br/>');
			$yih4_embed = ((!empty($options3['yih4_tab_option_20']))	?	'<div style="margin-bottom:0px;" class="embed-container">' : '<div class="embed-container">');

			// Function to display the YouTube or MediaElement Embedded Video
			if(empty($options2['yih4_tab_option_21']))	{
				$yih4_video = $yih4_embed;
				$yih4_video .= '<iframe src="https://www.youtube.com/embed/' . $yih4_videoID . $yih4_videoargs . '" frameborder="0" allowfullscreen></iframe>';
				$yih4_video .= '</div>';
				$yih4_video .= $yih4_showstats;

			} else {$yih4_video = do_shortcode('[video src="https://www.youtube.com/watch?v='.esc_html($yih4_videoID).'" '.$media_elem_autoplay.']') . $yih4_showstats;};

					// YouTube Post Output Global Settings START
            if ( is_singular( 'yih4-video' ) ) {
							return  yih4_nwd_before_video_func() . $yih4_video . yih4_nwd_after_video_func() . $content . wpautop($yih4_description) . $yih4_comments  . yih4_nwd_after_content_func();
            } else { return $content; };
    }
    add_filter( 'the_content', 'yih4_nwd_postwrap_content' );

		//Adding the Open Graph in the Language Attributes
function yih4_nwd_add_opengraph_doctype( $output ) {
  return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'yih4_nwd_add_opengraph_doctype');

function yih4_nwd_add_fb_open_graph_tags() {
  if (is_singular('yih4-video')) {
    global $post;
    if(get_the_post_thumbnail($post->ID, 'medium')) {
      $thumb_id = get_post_thumbnail_id($post->ID);
      $thumbnail_obj = get_post($thumb_id);
      $fbthumbimg = get_the_post_thumbnail_url($post->ID, 'full');
    }
    else {
      $fbthumbimg = ''; // Change this to the URL of the logo you want beside your links shown on Facebook
    }
		$yih4_excerpt = get_post_meta( get_the_ID(), '_yih4_description', true );

    if($excerpt = $yih4_excerpt) {
      $description = strip_tags($yih4_excerpt);
      $description = str_replace("", "'", $excerpt);
    } else {
      $description = get_bloginfo('description');
    }
?>
  <meta property="og:title" content="<?php the_title(); ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:image" content="<?php echo esc_url($fbthumbimg); ?>" />
  <meta property="og:url" content="<?php the_permalink(); ?>" />
  <meta property="og:description" content="<?php echo esc_html($description); ?>" />
  <meta property="og:site_name" content="<?php echo esc_html(get_bloginfo('name')); ?>" />

<?php
  }
}
add_action('wp_head', 'yih4_nwd_add_fb_open_graph_tags');

/**
  * Facebook Comments plugin
  */
function yih4_nwd_facebook_comments($content) {
  global $post;
  $options3 = get_option( 'yih4_nwd_tab_settings_3' );
  $facebook_comments = (!empty($options3['yih4_tab_option_23']));
  if (is_singular('yih4-video') && $facebook_comments ){
    $fb_JavaScriptSDK = "<div id='fb-root'></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>";

    $current_page_url = get_permalink($post->ID);
    $fb_comments_div = "<div class='fb-comments' data-href='{$current_page_url}' data-width='100%' data-numposts='5'></div>";

    return $fb_JavaScriptSDK . $content . $fb_comments_div;
  }
  else {
    return $content;
  }
}

add_filter('the_content', 'yih4_nwd_facebook_comments');
