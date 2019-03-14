<?php
/* YIH4 Shortcodes
*/

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


add_action('wp_enqueue_scripts', 'yih4_check_font_awesome', 999);

function yih4_check_font_awesome() {
  if (!wp_style_is( 'font-awesome', 'enqueued' )) {
      wp_register_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.6.1' );
      wp_enqueue_style( 'font-awesome' );
  }
}

function yih4_allStats(){
  $allStats = '<div class="section group">';
  $allStats .= '<div class="col span_1_of_5">';
  $allStats .= '<span class="yih4-viewCount"><i class="fa fa-eye" aria-hidden="true"></i> ';
  $allStats .= !empty(get_post_meta( get_the_ID(), '_yih4_viewCount', true)) ? number_format(get_post_meta( get_the_ID(), '_yih4_viewCount', true)) . '</span>' : ' 0 </span>';
  $allStats .= '</div>';
  $allStats .= '<div class="col span_1_of_5">';
  $allStats .= '<span class="yih4-likeCount"><i class="fa fa-thumbs-up" aria-hidden="true"></i> ';
  $allStats .= !empty(get_post_meta( get_the_ID(), '_yih4_likeCount', true)) ? number_format(get_post_meta( get_the_ID(), '_yih4_likeCount', true)) . '</span>' : ' 0 </span>';
  $allStats .= '</div>';
  $allStats .= '<div class="col span_1_of_5">';
  $allStats .= '<span class="yih4-dislikeCount"><i class="fa fa-thumbs-down" aria-hidden="true"></i> ';
  $allStats .= !empty(get_post_meta( get_the_ID(), '_yih4_dislikeCount', true)) ? number_format(get_post_meta( get_the_ID(), '_yih4_dislikeCount', true)) . '</span>' : ' 0 </span>';
  $allStats .= '</div>';
  $allStats .= '<div class="col span_1_of_5">';
  $allStats .= '<span class="yih4-favoriteCount"><i class="fa fa-heart" aria-hidden="true"></i> ';
  $allStats .= !empty(get_post_meta( get_the_ID(), '_yih4_favoriteCount', true)) ? number_format(get_post_meta( get_the_ID(), '_yih4_favoriteCount', true)) . '</span>' : ' 0 </span>';
  $allStats .= '</div>';
  $allStats .= '<div class="col span_1_of_5">';
  $allStats .= '<span class="yih4-commentCount"><i class="fa fa-comments" aria-hidden="true"></i> ';
  $allStats .= !empty(get_post_meta( get_the_ID(), '_yih4_commentCount', true)) ? number_format(get_post_meta( get_the_ID(), '_yih4_commentCount', true)) . '</span>' : ' 0 </span>';
  $allStats .= '</div>';
  $allStats .= '</div>';
  return $allStats;
}
add_shortcode('allStats', 'yih4_allStats');

function yih4_commentCount(){
  $commentCount = '<span class="yih4-commentCount"><i class="fa fa-comments" aria-hidden="true"></i> ';
  $commentCount .= number_format(get_post_meta( get_the_ID(), '_yih4_commentCount', true)) . '</span>';
  return $commentCount;
}
add_shortcode('commentCount', 'yih4_commentCount');

function yih4_dislikeCount(){
  $dislikeCount = '<span class="yih4-dislikeCount"><i class="fa fa-thumbs-down" aria-hidden="true"></i> ';
  $dislikeCount .= number_format(get_post_meta( get_the_ID(), '_yih4_dislikeCount', true)) . '</span>';
  return $dislikeCount;
}
add_shortcode('dislikeCount', 'yih4_dislikeCount');

function yih4_likeCount(){
  $likeCount = '<span class="yih4-likeCount"><i class="fa fa-thumbs-up" aria-hidden="true"></i> ';
  $likeCount .= number_format(get_post_meta( get_the_ID(), '_yih4_likeCount', true)) . '</span>';
  return $likeCount;
}
add_shortcode('likeCount', 'yih4_likeCount');

function yih4_favoriteCount(){
  $favoriteCount = '<span class="yih4-favoriteCount"><i class="fa fa-heart" aria-hidden="true"></i> ';
  $favoriteCount .= number_format(get_post_meta( get_the_ID(), '_yih4_favoriteCount', true)) . '</span>';
  return $favoriteCount;
}
add_shortcode('favoriteCount', 'yih4_favoriteCount');

function yih4_viewCount(){
  $viewCount = '<span class="yih4-viewCount"><i class="fa fa-eye" aria-hidden="true"></i> ';
  $viewCount .= number_format(get_post_meta( get_the_ID(), '_yih4_viewCount', true)) . '</span>';
  return $viewCount;
}
add_shortcode('viewCount', 'yih4_viewCount');
