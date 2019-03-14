<?php
/* Setup default options and settings
*/

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


// Activation Hook Function
function yih4_nwd_options_add(){
  // Defaults for Import Page
  update_option( 'yih4_nwd_channel_settings', '' );

  // Defaults for Settings Section 2 'yih4_nwd_tab_options_2' => Playback Options
  $playback_options_defaults = array(
      'yih4_tab_option_9' => true, //  Autoplay Videos
      'yih4_tab_option_10' => false, //  Show Related Videos
      'yih4_tab_option_11' => false, //  Show Video Info
      'yih4_tab_option_12' => false, //  Loop Videos
      'yih4_tab_option_13' => true, //  Show Youtube Branding
      'yih4_tab_option_14' => false, //  Show Annotations
  );  $playback_options = update_option( 'yih4_nwd_tab_settings_2', $playback_options_defaults );

  // Defaults for Settings Section 3 'yih4_nwd_tab_options_3' => Global Options
  $global_options_defaults = array(
      'yih4_tab_option_1' =>  true, //  Include YouTube Posts in normal post loop
      'yih4_tab_option_2' => true, //  Include Featured Image in REST API ResponseInclude YouTube Posts in normal post loop
      'yih4_tab_option_15' => true, //  Show YouTube Comments
      'yih4_tab_option_16' => false, //  Import Tags
      'yih4_tab_option_17' => true, //  Import Date
      'yih4_tab_option_18' => 'video', // Post Slug
      'yih4_tab_option_19' => 'videos', // Category Slug
      'yih4_tab_option_20' => true, // Show Stats
      'yih4_tab_option_22' => 'publish', // post status
  );  $global_options = update_option( 'yih4_nwd_tab_settings_3', $global_options_defaults );

}

// Deactivation Hook Function
function yih4_nwd_options_remove(){
  $options = delete_option( 'yih4_nwd_settings' );
  $options .= delete_option( 'yih4_nwd_channel_settings' );
  $options .= delete_option( 'yih4_nwd_tab_settings_2' );
  $options .= delete_option( 'yih4_nwd_tab_settings_3' );

  return $options;
}

function myt_video_flush_rewrite_rules() {
    yih4_post_type_register();

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'myt_video_flush_rewrite_rules' );
