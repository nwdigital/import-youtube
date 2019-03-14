<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
  * Adds a metabox to the post edit screen
  */
function yih4_nwd_add_meta_boxes() {
    add_meta_box(
        'yih4_video_stats',
        __( 'Video Details', 'youtube-import-nwdigital' ),
        'yih4_nwd_video_stats_callback',
        'yih4-video',
        'normal',
        'high'
    );
}

add_action( 'add_meta_boxes', 'yih4_nwd_add_meta_boxes' );

/**
  * The Meta Box Callbacks
  */
function yih4_nwd_video_stats_callback( $post ) {
    // make sure the form request comes from WordPress
    wp_nonce_field( basename( __FILE__ ), 'yih4_meta_boxes_nonce' );

    $yih4_description = esc_textarea(get_post_meta( $post->ID, '_yih4_description', true ));
    $yih4_description = (!empty($yih4_description) ? $yih4_description : 'no description found');

    $yih4_commentCount = intval(get_post_meta( $post->ID, '_yih4_commentCount', true));
    $yih4_commentCount = (!empty($yih4_commentCount) ? $yih4_commentCount : '0');

    $yih4_dislikeCount = intval(get_post_meta( $post->ID, '_yih4_dislikeCount', true));
    $yih4_dislikeCount = (!empty($yih4_dislikeCount) ? $yih4_dislikeCount : '0');

    $yih4_favoriteCount = intval(get_post_meta( $post->ID, '_yih4_favoriteCount', true));
    $yih4_favoriteCount = (!empty($yih4_favoriteCount) ? $yih4_favoriteCount : '0');

    $yih4_likeCount = intval(get_post_meta( $post->ID, '_yih4_likeCount', true));
    $yih4_likeCount = (!empty($yih4_likeCount) ? $yih4_likeCount : '0');

    $yih4_viewCount = intval(get_post_meta( $post->ID, '_yih4_viewCount', true));
    $yih4_viewCount = (!empty($yih4_viewCount) ? $yih4_viewCount : '0');

    $yih4_videocategory = esc_html(get_post_meta( $post->ID, '_yih4_video_category', true));
    $yih4_videoid = esc_html(get_post_meta( $post->ID, '_yih4_video_id', true));
    $yih4_showvideo = do_shortcode('[video src="https://www.youtube.com/watch?v='.esc_html($yih4_videoid).'"]'); ?>

    <div class="yih4-meta-col" style='-webkit-flex: 1;flex: 1;'><?php echo $yih4_showvideo; ?></div><br>
    <div style='display: -webkit-flex;display:flex;'>
      <div style='-webkit-flex: 1;flex: 1;'><b>Views:</b> <?php echo $yih4_viewCount; ?></div>
      <div style='-webkit-flex: 1;flex: 1;'><b>Likes:</b> <?php echo $yih4_likeCount; ?></div>
      <div style='-webkit-flex: 1;flex: 1;'><b>Favorites:</b> <?php echo $yih4_favoriteCount; ?></div>
    </div>
    <div style='display: -webkit-flex;display:flex;'>
      <div style='-webkit-flex: 1;flex: 1;'><b>Comments:</b> <?php echo $yih4_commentCount; ?></div>
      <div style='-webkit-flex: 1;flex: 1;'><b>Dislikes:</b> <?php echo $yih4_dislikeCount; ?></div>
      <div style='-webkit-flex: 1;flex: 1;'><b>Category:</b> <?php echo $yih4_videocategory; ?></div>
    </div>
    <div class="yih4-meta-col" style='-webkit-flex: 1;flex: 1;'>
      <h3>Description</h3><p><?php echo $yih4_description; ?></p>
    </div>
  <?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id
 */

// add_action( 'save_post', 'yih4_nwd_save_meta_box_data' );
// function below is not enabled currently, placeholder for future use if needed.
function yih4_nwd_save_meta_box_data( $post_id ) {
    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['yih4_meta_boxes_nonce'], 'yih4_meta_boxes_nonce' ) ) {
        return;
    }

    // Check if our nonce is set.
    if ( ! isset( $_POST['yih4_meta_boxes_nonce'] ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    }
    else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */
    // Make sure that it is set.
    if ( ! isset( $_POST['yih4_description'] ) ) {
        return;
    }

    // Sanitize user input.
    $yih4_description = sanitize_texarea_field($_POST['yih4_description']);
    $yih4_commentCount = intval($_POST['yih4_commentCount']);
    $yih4_dislikeCount = intval($_POST['yih4_dislikeCount']);
    $yih4_favoriteCount = intval($_POST['yih4_favoriteCount']);
    $yih4_likeCount = intval($_POST['yih4_likeCount']);
    $yih4_viewCount = intval($_POST['yih4_viewCount']);
    $yih4_videocategory = sanitize_text_field($_POST['yih4_videocategory']);
    $yih4_videoid = sanitize_text_field($_POST['yih4_videoid']);

    // Update the meta field in the database.
    update_post_meta( $post_id, '_yih4_description', $yih4_description );
    update_post_meta( $post_id, '_yih4_commentCount', $yih4_commentCount );
    update_post_meta( $post_id, '_yih4_dislikeCount', $yih4_dislikeCount);
    update_post_meta( $post_id, '_yih4_favoriteCount', $yih4_favoriteCount);
    update_post_meta( $post_id, '_yih4_likeCount', $yih4_likeCount);
    update_post_meta( $post_id, '_yih4_viewCount', $yih4_viewCount);
    update_post_meta( $post_id, '_yih4_video_category', $yih4_videocategory);
    update_post_meta( $post_id, '_yih4_video_id', $yih4_videoid);
}

// Display the entered data after the post content
function yih4_nwd_description_before_post(  ) {
    global $post;
    // retrieve the yih4 description for the current post
    if ( is_singular( 'yih4-video' ) ) {
      $yih4_description = get_post_meta( $post->ID, '_yih4_description', true );
      $description = "<div class='sp_yih4_description'>$yih4_description</div>";
      return $description;
  }
}
