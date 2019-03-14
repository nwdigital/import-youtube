<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

// Add the bulk action
add_filter( 'bulk_actions-edit-yih4-video', 'yih4_nwd_register_actions' );

function yih4_nwd_register_actions($bulk_actions) {
  $bulk_actions['update_videos'] = __( 'Update Videos', 'update_videos');
  return $bulk_actions;
}

// Handle the form submission
add_filter( 'handle_bulk_actions-edit-yih4-video', 'yih4_nwd_action_handler', 10, 3 );

function yih4_nwd_action_handler( $redirect_to, $doaction, $post_ids ) {
  if ( $doaction !== 'update_videos' ) {
    return $redirect_to;
  }

  foreach ( $post_ids as $post_id ) {
    // Perform action for each video post.
    $update_videoID = get_post_meta( $post_id, '_yih4_video_id', true );
    yih4_nwd_create_update_posts($update_videoID);
  }
  $redirect_to = add_query_arg( 'bulk_updated_videos', count( $post_ids ), $redirect_to );
  return $redirect_to;
}

// Showing Admin Notices

add_action( 'admin_notices', 'yih4_nwd_action_admin_notice' );

function yih4_nwd_action_admin_notice() {
  if ( ! empty( $_REQUEST['bulk_updated_videos'] ) ) {
    $updated_count = intval( $_REQUEST['bulk_updated_videos'] );
    printf( '<div id="message" class="updated notice is-dismissible">' .
      _n( '<p>Updated %s video.</p>',
        '<p>Updated %s videos.</p>',
        $updated_count,
        'update_videos'
      ) . '</div>', $updated_count );
  }
}

// Remove the previous admin notice when pagination is clicked
add_action('admin_notices' , 'yih4_nwd_remove_bulk_notices');

function yih4_nwd_remove_bulk_notices(){
  ?>
  <script type="text/javascript">
      jQuery(document).ready(function($){
        $('a' + '.next-page').each(function(){
            this.href = this.href.replace('&bulk_updated_videos=<?php echo intval( !empty($_REQUEST['bulk_updated_videos']) ? $_REQUEST['bulk_updated_videos'] : '' ); ?>', '');
        });
        $('a' + '.prev-page').each(function(){
            this.href = this.href.replace('&bulk_updated_videos=<?php echo intval( !empty($_REQUEST['bulk_updated_videos']) ? $_REQUEST['bulk_updated_videos'] : '' ); ?>', '');
        });
    });
  </script>
  <?php
}
