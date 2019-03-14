<?php
/*
*	Plugin Name: Import YouTube by Northwoods Digital
*	Author: Mathew Moore
* Plugin URI: https://northwoodsdigital.com/plugins/import-youtube
* Author URI: https://northwoodsdigital.com
* Description: Import YouTube videos as posts with ease! An advanced plugin to create posts by importing YouTube videos as posts or video post type via the YouTube API v3.
*	Version: 1.1.0
* Text Domain: import-youtube-nwdigital
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', 'yih4_nwd_add_admin_menu' );
add_action( 'admin_init', 'yih4_nwd_settings_init' );
add_action( 'admin_init', 'yih4_nwd_settings_channel_init' );
add_action( 'init', 'yih4_nwd_post_type_register' );

function yih4_nwd_activation_hooks(){
  yih4_nwd_options_add();
}
register_activation_hook( __FILE__, 'yih4_nwd_activation_hooks' );

function yih4_nwd_deactivation_hooks(){
  yih4_nwd_options_remove();
}
register_deactivation_hook( __FILE__, 'yih4_nwd_deactivation_hooks' );

define( 'YIH4_NWD_INC_DIR', plugin_dir_path( __FILE__ ) . '/' );

function yih4_nwd_plugin_files($files) {
  foreach ($files as $file){
    $file = include_once (YIH4_NWD_INC_DIR . 'includes' . '/' . $file);
  }
}

  yih4_nwd_plugin_files(array(
    'yih4-settings.php',
    'yih4-activation.php',
    'yih4-bulk-actions.php',
    'yih4-meta-boxes.php',
    'yih4-shortcodes.php',
    'yih4-searchpage.php',
    'yih4-widgets.php',
    'youtube-create-from-list.php',
    'public/yih4-comments.php',
    'public/yih4-frontend.php',
  ));

function yih4_nwd_register_dashicon(){
  wp_register_style( 'yih4_dashglance_style', plugin_dir_url( __FILE__ ) . 'css/dashglance.css', '1.0.0' );
  wp_enqueue_style( 'yih4_dashglance_style' );
}
add_action( 'admin_enqueue_scripts', 'yih4_nwd_register_dashicon' );

function yih4_nwd_post_type_register() {
    $labels = array(
        'name'          => _x( 'YouTube Posts', 'import-youtube-nwdigital' ),
        'singular_name' => _x( 'YouTube Post', 'import-youtube-nwdigital' ),
        'all_items'     => _x( 'All Videos', 'import-youtube-nwdigital' ),
    		'menu_name'             => __( 'YouTube Videos', 'import-youtube-nwdigital' ),
    		'name_admin_bar'        => __( 'YouTube Video', 'import-youtube-nwdigital' ),
    		'archives'              => __( 'Video Archives', 'import-youtube-nwdigital' ),
    		'attributes'            => __( 'Video Attributes', 'import-youtube-nwdigital' ),
    		'parent_item_colon'     => __( 'Parent Video:', 'import-youtube-nwdigital' ),
    		'all_items'             => __( 'All Videos', 'import-youtube-nwdigital' ),
    		'add_new_item'          => __( 'Add New Video', 'import-youtube-nwdigital' ),
    		'add_new'               => __( 'Add New', 'import-youtube-nwdigital' ),
    		'new_item'              => __( 'New Video', 'import-youtube-nwdigital' ),
    		'edit_item'             => __( 'Edit Video', 'import-youtube-nwdigital' ),
    		'update_item'           => __( 'Update Video', 'import-youtube-nwdigital' ),
    		'view_item'             => __( 'View Video', 'import-youtube-nwdigital' ),
    		'view_items'            => __( 'View Videos', 'import-youtube-nwdigital' ),
    		'search_items'          => __( 'Search Video', 'import-youtube-nwdigital' ),
    		'not_found'             => __( 'Not found', 'import-youtube-nwdigital' ),
    		'not_found_in_trash'    => __( 'Not found in Trash', 'import-youtube-nwdigital' ),
    		'featured_image'        => __( 'Featured Image', 'import-youtube-nwdigital' ),
    		'set_featured_image'    => __( 'Set featured image', 'import-youtube-nwdigital' ),
    		'remove_featured_image' => __( 'Remove featured image', 'import-youtube-nwdigital' ),
    		'use_featured_image'    => __( 'Use as featured image', 'import-youtube-nwdigital' ),
    		'insert_into_item'      => __( 'Insert into video', 'import-youtube-nwdigital' ),
    		'uploaded_to_this_item' => __( 'Uploaded to this video', 'import-youtube-nwdigital' ),
    		'items_list'            => __( 'Videos list', 'import-youtube-nwdigital' ),
    		'items_list_navigation' => __( 'Videos list navigation', 'import-youtube-nwdigital' ),
    		'filter_items_list'     => __( 'Filter videos list', 'import-youtube-nwdigital' ),
    );

    // Setup Post Type & Category Slugs
    $options3 = get_option( 'yih4_nwd_tab_settings_3' );
      if(!empty($options3['yih4_tab_option_18'])){
        $video_slug = $options3['yih4_tab_option_18'];
      } else { $video_slug = 'video'; };
      $options3 = get_option( 'yih4_nwd_tab_settings_3' );
      if(!empty($options3['yih4_tab_option_19'])){
        $category_slug = $options3['yih4_tab_option_19'];
      } else { $category_slug = 'videos'; };

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'rest_base'          => 'videos',
  		  'rest_controller_class' => 'WP_REST_Posts_Controller',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => $video_slug ),
        'capability_type'    => 'post',
        'has_archive'        => $video_slug.'s',
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-video-alt3',
        'menu_position'      => null,
        'supports'           => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'comments', 'post-formats', ),
        'taxonomies'          => array( 'yih4-category'. 'yih4-tag' )
    );

    register_post_type( 'yih4-video', $args );

    register_taxonomy('yih4-category', 'yih4-video', array(
    		'labels' => array(
          'name'          => 'Categories',
          'singular_name' => 'Category',
          'search_items'  => 'Search Categories',
          'edit_item'     => 'Edit Category',
          'add_new_item'  => 'Add New Category',
          'show_in_nav_menus' => true,
        ),
        'hierarchical' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => $category_slug )
      )); // Category taxonomy

    register_taxonomy('yih4-tag', 'yih4-video', array(
    		'labels' => array(
          'name'              => 'Tags',
          'singular_name'     => 'Tag',
          'search_items'      => 'Search Tags',
          'edit_item'         => 'Edit Tag',
          'add_new_item'      => 'Add New Tag',
          'show_in_nav_menus' => true
    		),
        'hierarchical'       => true,
        'show_in_quick_edit' => true,
        'show_admin_column'  => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'video-tag')
      )); // Tag taxonomy
    }

/**
 * Add Custom Columns to the custom post type screen
 **/
add_filter( 'manage_edit-yih4-video_columns', 'edit_yih4_video_columns' ) ;
function edit_yih4_video_columns( $columns ) {
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Video' ),
    'author' => __( 'Author' ),
    'yih4-category' => __( 'Categories' ),
    'yih4-tag' => __( 'Tags' ),
    'comments' => '<span class="vers comment-grey-bubble" title="' . esc_attr__( 'Comments' ) . '"><span class="screen-reader-text">' . __( 'Comments' ) . '</span></span>',
    'updated' => __( 'Last Update' ),
    'date' => __( 'Date' )
  );
  return $columns;
}

add_action( 'manage_yih4-video_posts_custom_column', 'yih4_video_column_content', 10, 2 );
function yih4_video_column_content( $column_name, $post_id ) {
    switch ( $column_name ) {

        case 'yih4-category' :
            $terms = get_the_term_list( $post_id , 'yih4-category' , '' , ',' , '' );
            if ( is_string( $terms ) )
                echo $terms;
            else
                _e( 'Unable to get category(s)', 'nwdigital' );
            break;

        case 'yih4-tag' :
            $terms = get_the_term_list( $post_id , 'yih4-tag' , '' , ',' , '' );
            if ( is_string( $terms ) )
                echo $terms;
            else
                _e( '___', 'nwdigital' );
            break;

        case 'updated' :
            $yih4_updated_date = get_post_meta( $post_id, '_yih4_updated_date', true );
            $yih4_updated_time = get_post_meta( $post_id, '_yih4_updated_time', true );
            echo esc_html($yih4_updated_date) . '<br>' . esc_html($yih4_updated_time);
            break;
    }
}

add_filter( 'manage_edit-yih4-video_sortable_columns', 'sortable_yih4_video_column' );
function sortable_yih4_video_column( $columns ) {
    $columns['updated'] = 'updated';

    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);

    return $columns;
}

/**
 * Add Featured Image URL to Rest API Json Response
 **/

$options3 = get_option( 'yih4_nwd_tab_settings_3' );
if(!empty($options3['yih4_tab_option_2'])){
add_action( 'rest_api_init', 'yih4_nwd_insert_thumbnail_url' );
function yih4_nwd_insert_thumbnail_url() {
    register_rest_field( 'yih4-video',
        'swp_thumbnail',
        array(
            'get_callback'    => 'yih4_nwd_get_thumbnail_url',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function yih4_nwd_get_thumbnail_url($post) {
  if(has_post_thumbnail($post['id'])) {
    $imgArray = wp_get_attachment_image_src( get_post_thumbnail_id( $post['id'] ), 'full' );
    $imgURL = $imgArray[0];
    return $imgURL;
  } else {return false;}
  }
}

// Add this post type into the normal post loop
$options3 = get_option( 'yih4_nwd_tab_settings_3' );
if(!empty($options3['yih4_tab_option_1'])){
  function yih4_nwd_add_cpt_to_query( $query ) {
    if ( ( is_home() && $query->is_main_query() ) || is_feed() ) {
        $query->set('post_type', array('post', 'yih4-video'));
        return $query;
    }
  }
  add_filter( 'pre_get_posts', 'yih4_nwd_add_cpt_to_query' );
}

// Add this post type to "At a Glance" widget
add_filter( 'dashboard_glance_items', 'yih4_nwd_glance_items', 10, 1 );
function yih4_nwd_glance_items( $items = array() ) {
    $post_types = array( 'yih4-video' );

    foreach( $post_types as $type ) {
        if( ! post_type_exists( $type ) ) continue;
        $num_posts = wp_count_posts( $type );

        if( $num_posts ) {

            $published = intval( $num_posts->publish );
            $post_type = get_post_type_object( $type );

            $text = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'your_textdomain' );
            $text = sprintf( $text, number_format_i18n( $published ) );

            if ( current_user_can( $post_type->cap->edit_posts ) ) {
                $items[] = sprintf( '<a class="%1$s-count" href="edit.php?post_type=%1$s">%2$s</a>', $type, $text ) . "\n";
            } else {
                $items[] = sprintf( '<span class="%1$s-count">%2$s</span>', $type, $text ) . "\n";
            }
        }
    }
    return $items;
}

function yih4_nwd_add_admin_menu(  ) {
  global $yih4_nwd_settings_page;
	$yih4_nwd_settings_page = add_submenu_page( 'edit.php?post_type=yih4-video', __('Import Videos', 'import-youtube-nwdigital'), __('Import Videos', 'import-youtube-nwdigital'), 'manage_options', 'youtube_import_nwdigital', 'yih4_nwd_options_page');
  $yih4_nwd_options_page = add_submenu_page( 'edit.php?post_type=yih4-video', __('Settings', 'youtube-import-settings-nwdigital'), __('Settings', 'youtube-import-settings-nwdigital'), 'manage_options', 'youtube_import_nwdigital&tab=display_options', 'yih4_settings_page' );
  $yih4_nwd_api_page = add_submenu_page( 'edit.php?post_type=yih4-video', __('API Key', 'youtube-import-api-settings-nwdigital'), __('API Key', 'youtube-import-api-settings-nwdigital'), 'manage_options', 'youtube_import_nwdigital&tab=display_api_key', 'yih4_api_settings_page' );
}

function yih4_nwd_custom_admin_notice() { // Admin Notice for empty YouTube API Key
  $options = get_option( 'yih4_nwd_settings' );
  $yih4_nwd_yt_api_key = $options['yih4_nwd_yt_api_key'];
  if( function_exists( 'yih4_nwd_ajax_create' ) AND empty($yih4_nwd_yt_api_key) ) { ?>
    <div class="notice error yih4-api-admin-notice is-dismissible" >
      <p><?php _e( 'You must enter a valid YouTube API Key in order to import videos!', 'youtube_import_nwdigital' ); ?>
        &nbsp;&nbsp;<a href="<?php echo esc_url(admin_url('edit.php?post_type=yih4-video&page=youtube_import_nwdigital&tab=display_api_key'));?>">Enter your API key</a></p>
      </div>
      <?php }
    }
    add_action( 'admin_notices', 'yih4_nwd_custom_admin_notice' );

function yih4_nwd_register_public_css(){
  wp_register_style( 'yih4_public_css_style', plugin_dir_url( __FILE__ ) . 'css/public.css', '1.0.0' );
  wp_enqueue_style( 'yih4_public_css_style' );
}
add_action( 'wp_enqueue_scripts', 'yih4_nwd_register_public_css' );

function yih4_nwd_enqueue_admin_style($hook) {
  global $yih4_nwd_settings_page;

	if( $hook != $yih4_nwd_settings_page )
		return;
    settings_errors();

  // Check for fontawesome and register if not found
  if (!wp_style_is( 'font-awesome', 'enqueued' )) {
      wp_register_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.6.1' );
      wp_enqueue_style( 'font-awesome' );
  }

  // YouTube Admin CSS
  wp_register_style( 'yih4_modal_style', plugin_dir_url( __FILE__ ) . 'css/admin.css', false );
  wp_enqueue_style( 'yih4_modal_style' );

  wp_register_script( 'yih4_yt_list_scripts', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), '1.0.1', true );
  wp_enqueue_script( 'yih4_yt_list_scripts' );

  // Register PostBoxes JS
  wp_enqueue_script( 'yih4_yt_postbox_edit', plugin_dir_url( __FILE__ ) . 'js/postbox-edit.js', array('jquery', 'postbox'), '1.0.0', true );
  wp_register_script( 'postbox', 'yih4_yt_postbox_edit' );

  // Register YouTube Video Search Autocomplete Script
  wp_register_script( 'yih4_jquery_ui_script', 'https://code.jquery.com/ui/1.12.0/jquery-ui.min.js', array( 'jquery' ), '1.0.0', true );
  wp_enqueue_script( 'yih4_jquery_ui_script' );

  $options = get_option( 'yih4_nwd_settings' );

  $scriptData = array(
      'ajaxurl'       =>  admin_url('admin-ajax.php'),
      'apikey'        => $options['yih4_nwd_yt_api_key'],
      'yih4admurl'    => admin_url(),
      'referrer'      => get_site_url(),
  );
  wp_localize_script('yih4_yt_list_scripts', 'mypc_options', $scriptData);
}
add_action( 'admin_enqueue_scripts', 'yih4_nwd_enqueue_admin_style' );
// creating Ajax call for WordPress
add_action( 'wp_ajax_nopriv_youtube_list_post_create', 'youtube_list_post_create' );
add_action( 'wp_ajax_youtube_list_post_create', 'youtube_list_post_create' );

function yih4_nwd_admin_scripts($hook) {
  global $yih4_nwd_settings_page;

  if( $hook != $yih4_nwd_settings_page )
    return;
    wp_register_script( 'yih4-js', plugin_dir_url( __FILE__ ) . 'js/ajax_post.js', array( 'jquery' ), '', true );

  $scriptData = array(
      'yih4_ajax_url' => admin_url( 'admin-ajax.php' ),
      'yih4_admurl' => get_site_url(),
  );
	wp_localize_script( 'yih4-js', 'yih4_params', $scriptData );
	wp_enqueue_script( 'yih4-js' );
};
add_action('admin_enqueue_scripts', 'yih4_nwd_admin_scripts');
// creating Ajax call for WordPress
add_action( 'wp_ajax_nopriv_yih4_nwd_ajax_create', 'yih4_nwd_ajax_create' );
add_action( 'wp_ajax_yih4_nwd_ajax_create', 'yih4_nwd_ajax_create' );

/*
* Sanitize Text Box Fields Function START
*/
function yih4_nwd_validate_text_input( $input ) {
    // Create our array for storing the validated options
    $output = array();
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
        } // end if
    } // end foreach
    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'yih4_nwd_validate_text_input', $output, $input );
}
/*
* Sanitize Text Box Fields Function END
*/

/*
* Sanitize Slug Input Fields Function START
*/
function yih4_nwd_validate_slug_input($options3){
	$options3['yih4_tab_option_18'] = sanitize_title_with_dashes($options3['yih4_tab_option_18']);
	$options3['yih4_tab_option_19'] = sanitize_title_with_dashes($options3['yih4_tab_option_19']);
	return $options3;
}
/*
* Sanitize Slug Input Fields Function END
*/

function yih4_nwd_settings_init(  ) {
	register_setting( 'yih4_nwd_pluginPage', 'yih4_nwd_settings', 'yih4_nwd_validate_text_input' );

	add_settings_section(
		'yih4_nwd_pluginPage_section',
		__( '', 'nwdigital' ),
		'yih4_nwd_settings_section_callback',
		'yih4_nwd_pluginPage'
	);

	add_settings_field(
		'yih4_nwd_yt_api_key',
		__( 'YouTube API Key', 'nwdigital' ),
		'yih4_nwd_api_key_render',
		'yih4_nwd_pluginPage',
		'yih4_nwd_pluginPage_section'
	);
}

function yih4_nwd_settings_channel_init(  ) {
	register_setting( 'yih4_nwd_pluginPage_channel', 'yih4_nwd_channel_settings', 'yih4_nwd_validate_text_input' );

  	add_settings_section(
  		'yih4_nwd_pluginPage_channel_section',
  		__( '', 'nwdigital' ),
  		'yih4_nwd_settings_section_callback_3',
  		'yih4_nwd_pluginPage_channel'
  	);

    add_settings_field(
  		'',
  		__( 'Search Videos', 'nwdigital' ),
  		'yih4_nwd_search_videos_render',
  		'yih4_nwd_pluginPage_channel',
  		'yih4_nwd_pluginPage_channel_section'
  	);
}

function yih4_nwd_api_key_render(  ) {

	$options = get_option( 'yih4_nwd_settings' );
	?>
	<input type='text' class="regular-text code" id="yih4_nwd_yt_api_key" name='yih4_nwd_settings[yih4_nwd_yt_api_key]' value='<?php echo sanitize_text_field($options['yih4_nwd_yt_api_key']); ?>'>
  <?php submit_button(__( 'Save API Key', 'import-youtube-nwdigital' ), 'primary', 'submit-button-api-key', false) ; ?>
  <span id="api_key_validate_resonse"></span>
  <ol style="list-style-type: decimal;">
    <li>Get your YouTube API key, visit this address: <a href="https://code.google.com/apis/console" target="_blank">https://code.google.com/apis/console</a>.</li>
    <li>After signing in, visit Create a new project and enable YouTube Data API.</li>
    <li>To get your API key, visit <a href="https://console.developers.google.com/apis/credentials" target="_blank">APIs & Services > Credentials</a> for your new porject.</li>
    <li>Click <b>Create credentials > API key</b> and type in name for the key.</li>
    <li>In the API key creation dialog box, you may choose either <b>Close</b> or <b>Restrict Key</b>.</li><br>
    <li style="margin-left:-15px;list-style-type: none;"><b>Note:</b> If you choose the option to Restrict Key (recommended), don't forget to add your website to the HTTP referrers(web sites) section</b>.</li>
  </ol>
<?php
}

add_action('wp_ajax_yih4_nwd_validate_api_key', 'yih4_nwd_validate_api_key');
function yih4_nwd_validate_api_key() {
  $api_key = esc_html($_POST['apikey']);
  echo $api_key;

  // Defaults for Import Page
  $options = array(
      'yih4_nwd_yt_api_key' => $api_key,
  );  update_option( 'yih4_nwd_settings', $options );

  ?>
  <?php
}

function yih4_nwd_search_videos_render(  ) {
	?>
  <select id="yih4_nwd_yt_channel_num_vids" name='yih4_nwd_yt_channel_num_vids'>
		<option value='5'>5</option>
		<option value='10'>10</option>
    <option value='15'>15</option>
    <option value='20'>20</option>
    <option value='25'>25</option>
	</select>
  <select id="yih4_search_type" name='yih4_search_type'>
    <option value='everything'>Everything</option>
		<option value='username'>Username</option>
		<option value='channel'>Channel ID</option>
	</select><br/><br/>
  <div id="yih4_username_search">
    <form>
      <input type='hidden' id="uname_to_channelid_ajax" name="uname_to_channelid_ajax" >
      <input type='text' class="regular-text" id="yih4_search_username" name='yih4_search_username' placeholder="Enter Username..."value=''>
      <button class="icon button button-secondary" name="submit-button-search-username" id="submit-button-search-username">Search</button>
    </form>
  </div>
  <div id="yih4_channelid_search">
    <form>
      <input type='text' class="regular-text" id="yih4_search_channelid" name='yih4_search_channelid' placeholder="Enter Channel ID..." value=''>
      <button class="icon button button-secondary" name="submit-button-search-channelid" id="submit-button-search-channelid">Search</button>
    </form>
  </div>
  <div id="yih4_everything_search">
    <form>
      <input type="search" name="hyv-search" id="hyv-search" placeholder="Enter Search Terms..." class="regular-text" autocomplete="on">
      <button class="icon button button-secondary" name="yt-insert" id="searchBtn">Search</button>
    </form>
  </div>

  <?php
}

add_action('wp_ajax_yih4_nwd_username_to_channelid','yih4_nwd_username_to_channelid');
function yih4_nwd_username_to_channelid(){
  $username = esc_html($_POST['username']);
  // function to convert YouTube username to channel ID when 'Search by' type 'username' is selected
  $options = get_option( 'yih4_nwd_settings' );
  $yih4_nwd_yt_api_key = $options['yih4_nwd_yt_api_key'];
  // add site url to header for json request
  $referer = get_site_url();
  $opts = array(
    'http'=>array(
      'method'=>  "GET",
      'header'=>  "Origin: $referer"
    )
  );
  if (!empty($username)){
    $context = stream_context_create($opts);
    $json_url = "https://www.googleapis.com/youtube/v3/channels?key=".$yih4_nwd_yt_api_key."&forUsername=".$username."&part=id";
    $json_response = file_get_contents($json_url, false, $context);
    $json_decode = json_decode($json_response);
    $channelID =  $json_decode->items[0]->id;
    echo $channelID;
  } else {};
  exit;
}

function yih4_nwd_get_video_id() {
  $options_id = get_option( 'mypc_id_settings' );
  $data_vidurl = $options_id['mypc_yt_video_id'];
  $clean_url_vid_ID = substr($data_vidurl, strpos($data_vidurl, "watch?v=") + 8);
  return $clean_url_vid_ID;
}

// Ajax Function to Create the post
function yih4_nwd_ajax_create(){
  $data = json_encode($_POST['videoID']);
  $data = json_decode($data);
  foreach ($data as $video){
    // Create post object
    yih4_nwd_create_update_posts($video);
  }
}

function yih4_nwd_settings_section_callback(  ) {

	echo __( '<p class="description">In order to create a post from a YouTube video, we need to get access to the YouTube API.</p>', 'nwdigital' );
  echo __( '<p><a href="https://developers.google.com/youtube/registering_an_application#create_project" target="_blank">Learn how to obtain an API key</a></p>', 'nwdigital' );
}

function yih4_nwd_settings_section_callback_3(  ) {

  echo __( '', 'nwdigital' );
}

function yih4_nwd_options_page(  ) {

	// General check for user permissions.
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient priveleges to access this page.')    );
	} ?>

<div class="wrap">
  <h1>Import YouTube by Northwoods Digital</h1>

  <?php
  if( isset( $_GET[ 'tab' ] ) ) {
    $active_tab = $_GET[ 'tab' ];
  } // end if

  $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_import_videos';
  ?>

  <h2 class="nav-tab-wrapper">
    <a href="edit.php?post_type=yih4-video&page=youtube_import_nwdigital&tab=display_import_videos" class="nav-tab <?php echo $active_tab == 'display_import_videos' ? 'nav-tab-active' : ''; ?>">Import Videos</a>
    <a href="edit.php?post_type=yih4-video&page=youtube_import_nwdigital&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">Settings</a>
    <?php if(function_exists('yih4_scheduler_hook')) { ?>
      <a href="edit.php?post_type=yih4-video&page=youtube_import_nwdigital&tab=display_scheduler" class="nav-tab <?php echo $active_tab == 'display_scheduler' ? 'nav-tab-active' : ''; ?>">Scheduler</a>
    <?php } ?>
    <a href="edit.php?post_type=yih4-video&page=youtube_import_nwdigital&tab=display_api_key" class="nav-tab <?php echo $active_tab == 'display_api_key' ? 'nav-tab-active' : ''; ?>">API Key</a>
  </h2>

  <div id="poststuff">
    <div id="postbox-container" class="postbox-container">
      <div class="meta-box-sortables ui-sortable" id="normal-sortables">

        <?php if( $active_tab == 'display_options' ) {  ?>
          <div class="postbox " id="yih4_option2">
            <button type="button" title="Click to toggle" class="handlediv"><span class="toggle-indicator" aria-hidden="true"></span></button><h3 class="hndle ui-sortable-handle"><span>Playback Options</span></h3>
            <div class="inside">
            	<form class="yih4-section" action='options.php' method='post'>
            		<?php
                  settings_fields( 'yih4_nwd_tab_options_2' );
              		do_settings_sections( 'yih4_nwd_tab_options_2' );
                  submit_button(__( 'Save Changes', 'import-youtube-nwdigital' ), 'primary', 'submit-button-2', false) ;
            		?>
            	</form>
            </div>
          </div>
        <?php } else {}; ?>

        <?php if( $active_tab == 'display_options' ) {  ?>
          <div class="postbox " id="yih4_option3">
            <button type="button" title="Click to toggle" class="handlediv"><span class="toggle-indicator" aria-hidden="true"></span></button><h3 class="hndle ui-sortable-handle"><span>Global Options</span></h3>
            <div class="inside">
              <form class="yih4-section" action='options.php' method='post'>
                <?php
                  settings_fields( 'yih4_nwd_tab_options_3' );
              		do_settings_sections( 'yih4_nwd_tab_options_3' );
                  submit_button(__( 'Save Changes', 'import-youtube-nwdigital' ), 'primary', 'submit-button-3', false);
                  flush_rewrite_rules();
                ?>
              </form>
            </div>
          </div>
        <?php } else {}; ?>

      <?php // END 'Options' Tab Items ?>

      <?php // START 'Options' Scheduler Tab Items ?>
        <?php if( $active_tab == 'display_scheduler' ) {  ?>
            <?php if(function_exists('yih4_scheduler_hook')) {
              // Show the scheduler settings if the plugin is active
              yih4_scheduler_hook();
            } ?>
        <?php } else {}; ?>
      <?php // END 'Options' Scheduler Tab Items ?>

      <?php // START 'API Key' Tab Items ?>
        <?php if( $active_tab == 'display_api_key' ) {  ?>
          <div class="postbox " id="apikey">
            <button type="button" title="Click to toggle" class="handlediv"><span class="toggle-indicator" aria-hidden="true"></span></button><h3 class="hndle"><span>API Key</span></h3>
            <div class="inside">
              <form class="yih4-section" action='options.php' method='post'>
                <?php
                settings_fields( 'yih4_nwd_pluginPage' );
                do_settings_sections( 'yih4_nwd_pluginPage' );
                ?>
              </form>
            </div>
          </div>
        <?php } else {}; ?>
      <?php // END 'API Key' Tab Items ?>

      <?php // START 'Import Videos' Tab Items ?>
        <?php if( $active_tab == 'display_import_videos' ) {  ?>
          <?php // Don't show these forms unless a API key is present
          $options = get_option( 'yih4_nwd_settings' );
        	$yih4_nwd_yt_api_key = $options['yih4_nwd_yt_api_key'];
          if(!empty($yih4_nwd_yt_api_key)) {
          ?>
          <div class="postbox " id="results">
            <button type="button" title="Click to toggle" class="handlediv"><span class="toggle-indicator" aria-hidden="true"></span></button><h3 class="hndle"><span>Results</span></h3>
            <div class="inside">
              <form class="yih4-section" action='options.php' method='post'>
                <?php // Form for YouTube Channel Function
                settings_fields( 'yih4_nwd_pluginPage_channel' );
                do_settings_sections( 'yih4_nwd_pluginPage_channel' );
                // submit_button( 'Parse Channel', 'primary' );
                ?>
              </form>
            </div>
          </div>
        <?php } else {
          ?>
          <div class="postbox " id="results">
            <button type="button" title="Click to toggle" class="handlediv"><span class="toggle-indicator" aria-hidden="true"></span></button><h3 class="hndle"><span>Missing YouTube API Key</span></h3>
            <div class="inside">
              <table class="form-table">
                <tr>
                  <td>
                  <p>You need to enter your YouTube API key to import videos.
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=yih4-video&page=youtube_import_nwdigital&tab=display_api_key'));?>">Enter YouTube API Key.</a>
                  </p>
                </td>
                </tr>
              </table>
            </div>
          </div>
          <?php
        }; ?>
        <?php // END 'Import Videos' Tab Items ?>

        <?php if( $active_tab == 'display_import_videos' ) {  ?>
          <form id="ytposts" action='' method='post'>
            <?php // Form for YouTube Channel List Submission
            $options = get_option( 'yih4_nwd_settings' );
            $data_api_key = $options['yih4_nwd_yt_api_key'];
              if (!empty($data_api_key)){
                // Get YouTube Results via Jquery
                yih4_nwd_search_results();
              }
            ?>
          </form>
        <?php } else {}; ?>
      </div>
    </div>
  </div>
</div>
	<?php }
}
