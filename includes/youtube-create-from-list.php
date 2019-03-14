<?php
/**
	* This is the file that will generate posts from selected videos in the "Create Posts from Channel" section.
	*/
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

function yih4_nwd_create_update_posts($videoID){
	// Get the Youtube Stuff
	$options 			= get_option( 'yih4_nwd_settings' );
	$options3 		= get_option( 'yih4_nwd_tab_settings_3' );
	$yih4_api_key = $options['yih4_nwd_yt_api_key'];

	// Get the video contents from YouTube via JSON response
	// add site url to header for json request
  $referer = get_site_url();
  $opts = array(
    'http'=>array(
      'method'=>  "GET",
      'header'=>  "Origin: $referer"
    )
  );

  $context 						= stream_context_create($opts);
	$yih4_json_url 			= "https://www.googleapis.com/youtube/v3/videos?key=".$yih4_api_key."&part=snippet,statistics&id=".$videoID."";
	$yih4_data 					= file_get_contents($yih4_json_url, false, $context);
	$yih4_json_response = json_decode($yih4_data, true);
	$yih4_items 				= $yih4_json_response['items'][0];

	// Setup the Post Data
	$title				= $yih4_items['snippet']['title'];
	$post 				= get_page_by_title( wp_strip_all_tags($title), 'OBJECT', 'yih4-video' );
	$description 	= $yih4_items['snippet']['description'];
	$description 	= wp_strip_all_tags($description);
	$jsondate 		= $yih4_items['snippet']['publishedAt'];

	if(isset($options3['yih4_tab_option_17'])){
		$publishedAt = date("Y-m-d H:i:s",strtotime($jsondate) - 6 * 3600);
	} else {
		$publishedAt = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
	};

	$category_id 			= $yih4_items['snippet']['categoryId'];
	$yih4_tags_array	= $yih4_items['snippet']['tags'];

	// Setup Statistics for the Post Meta
	$viewCount			= $yih4_items['statistics']['viewCount'];
	$likeCount			= $yih4_items['statistics']['likeCount'];
	$dislikeCount		= $yih4_items['statistics']['dislikeCount'];
	$favoriteCount 	= $yih4_items['statistics']['favoriteCount'];
	$commentCount 	= $yih4_items['statistics']['commentCount'];

	// Check for yotube image thumbnails from max resolution downwards
	$yih4_thumbs = $yih4_items['snippet']['thumbnails'];

	if (!empty($yih4_thumbs['maxres']['url'])) {
		$thumbnail = $yih4_thumbs['maxres']['url'];
	}
	elseif (!empty($yih4_thumbs['standard']['url'])) {
		$thumbnail = $yih4_thumbs['standard']['url'];
	}
	elseif (!empty($yih4_thumbs['high']['url'])) {
		$thumbnail = $yih4_thumbs['high']['url'];
	}
	elseif (!empty($yih4_thumbs['medium']['url'])) {
		$thumbnail = $yih4_thumbs['medium']['url'];
	}
	elseif (!empty($yih4_thumbs['default']['url'])) {
		$thumbnail = $yih4_thumbs['default']['url'];
	}

	$yih4_category_array = array(
		1 => 'Film & Animation',
		2 => 'Autos & Vehicles',
		10 => 'Music',
		15 => 'Pets & Animals',
		17 => 'Sports',
		18 => 'Short Movies',
		19 => 'Travel & Events',
		20 => 'Gaming',
		21 => 'Videoblogging',
		22 => 'People & Blogs',
		23 => 'Comedy',
		24 => 'Entertainment',
		25 => 'News & Politics',
		26 => 'Howto & Style',
		27 => 'Education',
		28 => 'Science & Technology',
		29 => 'Nonprofits & Activism',
		30 => 'Movies',
		31 => 'Anime/Animation',
		32 => 'Action/Adventure',
		33 => 'Classics',
		34 => 'Comedy',
		35 => 'Documentary',
		36 => 'Drama',
		37 => 'Family',
		38 => 'Foreign',
		39 => 'Horror',
		40 => 'Sci-Fi/Fantasy',
		41 => 'Thriller',
		42 => 'Shorts',
		43 => 'Shows',
		44 => 'Trailers',
	);

	// Get the Category ID Name from the YouTube Category#
	$yih4_category = $yih4_category_array[$category_id];
	// Gets the ID number of the custom term name for the custom taxonomy 'yih4-category'
	$tax_query = get_term_by('slug', $yih4_category, 'yih4-category');

	if( !$tax_query == NULL ) {
	$cat_ID = $tax_query->term_id;
	}
	// If not create new yih4-category
	if( $tax_query == NULL ) {
			$arg = array( 'description' => "", 'parent' => 0 );
			$cat_ID = wp_insert_term($yih4_category, "yih4-category", $arg);
	}

	// Check if the post already exists
	if(NULL===$post) {	// Create post object
		$options3 = get_option( 'yih4_nwd_tab_settings_3' );
		$post_status = $options3['yih4_tab_option_22'];

		$my_post = array(
			'post_type'			=> 'yih4-video',
			'post_date'			=> $publishedAt,
			'post_title'    => wp_strip_all_tags( $title ),
			'post_content'  => '',
			'tax_input'	=>	array( 'yih4-category' => $cat_ID ),
			'post_status'   => $post_status,
			'post_author'   => 1,
			'meta_input'		=> array(
				'_yih4_video_id'				=>	$videoID,
				'_yih4_video_category'	=>	$yih4_category,
				'_yih4_description'		=>	$description,
				'_yih4_viewCount'			=>	$viewCount,
				'_yih4_likeCount'			=>	$likeCount,
				'_yih4_dislikeCount'		=>	$dislikeCount,
				'_yih4_favoriteCount'	=>	$favoriteCount,
				'_yih4_commentCount'		=>	$commentCount,
			),
		);
		$post_id = wp_insert_post( $my_post );

		// Insert the post into the database
		// Add Featured Image to Post
		$image_url        = $thumbnail; // Define the image URL here
		$image_name       = $title.'.jpg';
		$upload_dir       = wp_upload_dir(); // Set upload folder
		$image_data       = file_get_contents($image_url); // Get image data
		$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
		$filename         = basename( $unique_file_name ); // Create image file name

		// Check folder permission and define file location
		if( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		// Create the image  file on the server
		file_put_contents( $file, $image_data );
		// Check image file type
		$wp_filetype = wp_check_filetype( $filename, null );

		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
		// Include image.php
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );
		// And finally assign featured image to post
		set_post_thumbnail( $post_id, $attach_id );
		// Set post format to video
		set_post_format($post_id, 'video' );

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );

		if(!empty($options3['yih4_tab_option_16'])){
			wp_set_object_terms( $post_id, $yih4_tags_array, 'yih4-tag');
		} else {
			'';
		};
	}	else /* update post if it already exists	*/	{

		date_default_timezone_set(get_option('timezone_string'));
	  $yih4_updated_date = date('Y/m/d');
	  $yih4_updated_time = date('h:i:s A');

		$update_post = array (
			'ID' 						=>	$post->ID,
			'post_title' 		=>	$title,
			'post_date'			=>	$publishedAt,
			'post_content' 	=>	'',
			'post_type' 		=>	"yih4-video",
			'tax_input'			=>	array( 'yih4-category' => $cat_ID ),
			'post_status' 	=>	"publish",
			'meta_input'		=>	array(
				'_yih4_video_id'				=>	$videoID,
				'_yih4_video_category'	=>	$yih4_category,
				'_yih4_description'		=>	$description,
				'_yih4_viewCount'			=>	$viewCount,
				'_yih4_likeCount'			=>	$likeCount,
				'_yih4_dislikeCount'		=>	$dislikeCount,
				'_yih4_favoriteCount'	=>	$favoriteCount,
				'_yih4_commentCount'		=>	$commentCount,
				'_yih4_updated_date'		=> $yih4_updated_date,
				'_yih4_updated_time'		=> $yih4_updated_time,
			),
		);
		$yih4_update_post = wp_update_post( $update_post );

		// Add Featured Image to Post
		$image_url        = $thumbnail; // Define the image URL here
		$image_name       = $title.'.jpg';
		$upload_dir       = wp_upload_dir(); // Set upload folder
		$image_data       = file_get_contents($image_url); // Get image data
		$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
		$filename         = basename( $unique_file_name ); // Create image file name

		// Check folder permission and define file location
		if( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		if ( has_post_thumbnail($post->ID) ) {
			'';
		}
		else {
			// Create the image  file on the server
			file_put_contents( $file, $image_data );
			// Check image file type
			$wp_filetype = wp_check_filetype( $filename, null );
			// Set attachment data
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name( $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			// Create the attachment
			$attach_id = wp_insert_attachment( $attachment, $file, $yih4_update_post );
			// Include image.php
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			// Define attachment metadata
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			// Assign metadata to attachment
			wp_update_attachment_metadata( $attach_id, $attach_data );
			// And finally assign featured image to post
			set_post_thumbnail( $yih4_update_post, $attach_id );
		}
		// Set post format to video
		set_post_format($yih4_update_post, 'video' );

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );
		if(!empty($options3['yih4_tab_option_16'])){
			wp_set_object_terms( $yih4_update_post, $yih4_tags_array, 'yih4-tag');
		} else {
			wp_remove_object_terms( $yih4_update_post, $yih4_tags_array, 'yih4-tag');
		};
	}
}
