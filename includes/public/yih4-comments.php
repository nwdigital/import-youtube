<?php
/* Function to display YouTube Comments section for single video posts.
*/

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

function yih4_nwd_youtube_comments(){
  $yih4_comments_videoid      = get_post_meta( get_the_ID(), '_yih4_video_id', true ); // Youtube video ID
  $options = get_option( 'yih4_nwd_settings' );
  $yih4_comments_api_key = $options['yih4_nwd_yt_api_key'];
  $nextPage = ""; // Next Page Token for get comments of next Page.
  // add site url to header for json request
  $yih4_referer = get_site_url();
  $yih4_opts = array(
    'http'=>array(
      'method'=>  "GET",
      'header'=>  "Origin: $yih4_referer"
    )
  );
  $yih4_context = stream_context_create($yih4_opts);

  ob_start();

  $yih4_json_url = "https://www.googleapis.com/youtube/v3/commentThreads?key=" . esc_html($yih4_comments_api_key) . "&textFormat=plainText&part=snippet&videoId=" . esc_html($yih4_comments_videoid) . "&maxResults=15";
  $yih4_json_response = file_get_contents($yih4_json_url, false, $yih4_context);
  $yih4_json_data = json_decode($yih4_json_response, true); // decode the JSON into an associative array
  $yih4_commentCount = intval(count($yih4_json_data['items']));

  echo '<div class="yih4-comments"><h3>Comments  • '. esc_html($yih4_commentCount).'</h3>';

  foreach ($yih4_json_data['items'] as $yih4_json_val) { // Loop for list comments...
    $yih4_comment_author  = sanitize_text_field($yih4_json_val['snippet']['topLevelComment']['snippet']['authorDisplayName']); //Get Comment Author Name.
    $yih4_comment = sanitize_text_field($yih4_json_val['snippet']['topLevelComment']['snippet']['textDisplay']); //Get Comment Content.
    echo "<span style='color:#4c99ba;font-weight:600';>" . esc_html($yih4_comment_author) . "</span> --> " . esc_html($yih4_comment); // Author and comment
    echo "<hr>"; // Divider
  }
  echo '</div>';
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
}
