<?php
/* Content here is displayed on the "Options" Tab
*/

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_init', 'yih4_nwd_settings_options_init' );

// Register Settings & Fields
function yih4_nwd_settings_options_init(  ) {
	register_setting( 'yih4_nwd_tab_options_2', 'yih4_nwd_tab_settings_2' );
	register_setting( 'yih4_nwd_tab_options_3', 'yih4_nwd_tab_settings_3', 'yih4_nwd_validate_slug_input' );

	// Section 2 - Settings - Playback Options
	add_settings_section( 'yih4_tab_options_section_2', __( '', 'nwdigital' ), 'yih4_nwd_tab_settings_2_callback', 'yih4_nwd_tab_options_2' );
	// Section 2 - Settings - Playback Options Fields
	add_settings_field( 'yih4_tab_option_9', __( 'Autoplay Videos', 'nwdigital' ), 'yih4_tab_option_9_render', 'yih4_nwd_tab_options_2', 'yih4_tab_options_section_2' );
	add_settings_field( 'yih4_tab_option_10', __( 'Show Related Videos', 'nwdigital' ), 'yih4_tab_option_10_render', 'yih4_nwd_tab_options_2', 'yih4_tab_options_section_2' );
	add_settings_field( 'yih4_tab_option_11', __( 'Show Video Info', 'nwdigital' ), 'yih4_tab_option_11_render', 'yih4_nwd_tab_options_2', 'yih4_tab_options_section_2' );
	add_settings_field( 'yih4_tab_option_12', __( 'Loop Videos', 'nwdigital' ), 'yih4_tab_option_12_render', 'yih4_nwd_tab_options_2', 'yih4_tab_options_section_2' );
	add_settings_field( 'yih4_tab_option_13', __( 'Show Youtube Branding', 'nwdigital' ), 'yih4_tab_option_13_render', 'yih4_nwd_tab_options_2', 'yih4_tab_options_section_2' );
	add_settings_field( 'yih4_tab_option_14', __( 'Show Annotations', 'nwdigital' ), 'yih4_tab_option_14_render', 'yih4_nwd_tab_options_2', 'yih4_tab_options_section_2' );
	add_settings_field( 'yih4_tab_option_21', __( 'Use MediaElement Player', 'nwdigital' ), 'yih4_tab_option_21_render', 'yih4_nwd_tab_options_2', 'yih4_tab_options_section_2' );

	// Section 3 - Settings - Global Options
	add_settings_section( 'yih4_tab_options_section_3', __( '', 'nwdigital' ), 'yih4_nwd_tab_settings_3_callback', 'yih4_nwd_tab_options_3' );
	// Section 3 - Settings - Global Options Fields
	add_settings_field( 'yih4_tab_option_1', __( 'Include YouTube Posts in normal post loop', 'nwdigital' ), 'yih4_nwd_tab_option_1_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_2', __( 'Include Featured Image in REST API Response', 'nwdigital' ), 'yih4_nwd_tab_option_2_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_15', __( 'Show YouTube Comments', 'nwdigital' ), 'yih4_tab_option_15_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_16', __( 'Import Tags<br/>(not recommended)', 'nwdigital' ), 'yih4_tab_option_16_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_17', __( 'Import Date', 'nwdigital' ), 'yih4_tab_option_17_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_20', __( 'Show Video Stats', 'nwdigital' ), 'yih4_tab_option_20_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_18', __( 'Post slug', 'nwdigital' ), 'yih4_tab_option_18_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_19', __( 'Category slug', 'nwdigital' ), 'yih4_tab_option_19_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_22', __( 'Publish Status', 'nwdigital' ), 'yih4_tab_option_22_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );
	add_settings_field( 'yih4_tab_option_23', __( 'Facebook Comments', 'nwdigital' ), 'yih4_tab_option_23_render', 'yih4_nwd_tab_options_3', 'yih4_tab_options_section_3' );

}

// Render the fields
function yih4_nwd_tab_option_1_render(  ) {

	$options3 = get_option( 'yih4_nwd_tab_settings_3' );

	?>
	<input type='checkbox' name='yih4_nwd_tab_settings_3[yih4_tab_option_1]' <?php checked( !empty($options3['yih4_tab_option_1']), 1 ); ?> value='1'>
	Check this box if you want YouTube posts to be included in the normal WordPress post loop / blog page.
	<?php

}

function yih4_nwd_tab_option_2_render(  ) {

	$options3 = get_option( 'yih4_nwd_tab_settings_3' );

	?>
	<input type='checkbox' name='yih4_nwd_tab_settings_3[yih4_tab_option_2]' <?php checked( !empty($options3['yih4_tab_option_2']), 1 ); ?> value='1'>
	Check this box if you want to add the YouTube Post's featured image to the REST API response.
	<?php

}

function yih4_tab_option_9_render(  ) {

		$options2 = get_option( 'yih4_nwd_tab_settings_2' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_2[yih4_tab_option_9]' <?php checked( !empty($options2['yih4_tab_option_9']), 1 ); ?> value='1'>
				Checking this setting will cause the video to automatically start to play when the player loads.
			<?php

}

function yih4_tab_option_10_render(  ) {

		$options2 = get_option( 'yih4_nwd_tab_settings_2' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_2[yih4_tab_option_10]' <?php checked( !empty($options2['yih4_tab_option_10']), 1 ); ?> value='1'>
				Checking this setting causes the player to show related videos when playback of the initial video ends. <strong>(Default Player Only)</strong>
			<?php

}

function yih4_tab_option_11_render(  ) {

		$options2 = get_option( 'yih4_nwd_tab_settings_2' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_2[yih4_tab_option_11]' <?php checked( !empty($options2['yih4_tab_option_11']), 1 ); ?> value='1'>
				Checking this setting causes the player to display information like video title and uploader before the video starts playing. <strong>(Default Player Only)</strong>
			<?php

}

function yih4_tab_option_12_render(  ) {

		$options2 = get_option( 'yih4_nwd_tab_settings_2' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_2[yih4_tab_option_12]' <?php checked( !empty($options2['yih4_tab_option_12']), 1 ); ?> value='1'>
				Checking this setting causes the player to play the initial video again and again. <strong>(Default Player Only)</strong>
			<?php

}

function yih4_tab_option_13_render(  ) {

		$options2 = get_option( 'yih4_nwd_tab_settings_2' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_2[yih4_tab_option_13]' <?php checked( !empty($options2['yih4_tab_option_13']), 1 ); ?> value='1'>
				Checking this setting lets you use a YouTube player that does not show a YouTube logo in the control bar. <strong>(Default Player Only)</strong>
			<?php

}

function yih4_tab_option_14_render(  ) {

		$options2 = get_option( 'yih4_nwd_tab_settings_2' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_2[yih4_tab_option_14]' <?php checked( !empty($options2['yih4_tab_option_14']), 1 ); ?> value='1'>
				Checking this setting causes video annotations to be shown. <strong>(Default Player Only)</strong>
			<?php

}

function yih4_tab_option_15_render(  ) {

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_3[yih4_tab_option_15]' <?php checked( !empty($options3['yih4_tab_option_15']), 1 ); ?> value='1'>
				Check this box to show the 25 most recent YouTube comments for each video.<br/><br/>

				<p><strong>NOTE:</strong> This will count against your daily YouTube API available units each time a YouTube post is loaded on the frontend of your website.</p>
			<?php

}

function yih4_tab_option_16_render(  ) {

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_3[yih4_tab_option_16]' <?php checked( !empty($options3['yih4_tab_option_16']), 1 ); ?> value='1'>
				For videos previously imported without tags, use <a href="<?php echo admin_url('edit.php?post_type=yih4-video'); ?>">Bulks Actions</a> to "Update Videos" and import the tags.<br/><br/>

				<p><strong>NOTE:</strong> Due to the large number of tags videos may have, this can cause server timeouts. You should be able to use this function successfully if you import or update only a small number of posts at a time (eg. 10 or less).</p>
			<?php

}

function yih4_tab_option_17_render(  ) {

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );

			?>
				<input type='checkbox' name='yih4_nwd_tab_settings_3[yih4_tab_option_17]' <?php checked( !empty($options3['yih4_tab_option_17']), 1 ); ?> value='1'>
				Imports will have YouTube video's publishing date.
			<?php

}

function yih4_tab_option_18_render(  ) {

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );

			?>
			<input type='text' class="regular-text" name='yih4_nwd_tab_settings_3[yih4_tab_option_18]' value='<?php echo $options3['yih4_tab_option_18']; ?>'>
			<?php

}

function yih4_tab_option_19_render(  ) {

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );

			?>
			<input type='text' class="regular-text" name='yih4_nwd_tab_settings_3[yih4_tab_option_19]' value='<?php echo $options3['yih4_tab_option_19']; ?>'>
			<?php

}

function yih4_tab_option_20_render(  ) {

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );

			?>
			<input type='checkbox' name='yih4_nwd_tab_settings_3[yih4_tab_option_20]' <?php checked( !empty($options3['yih4_tab_option_20']), 1 ); ?> value='1'>
			Check this to show the video stats below all videos on the frontend of the website.
			<?php

}

// Facebook Comments Enable
function yih4_tab_option_23_render(  ) {

	$options3 = get_option( 'yih4_nwd_tab_settings_3' );

	?>
	<input type='checkbox' name='yih4_nwd_tab_settings_3[yih4_tab_option_23]' <?php checked( !empty($options3['yih4_tab_option_23']), 1 ); ?> value='1'>
	Check this box if you want to enable Facebook Comments for YouTube Posts.
	<?php

}

// MediaElement Player
function yih4_tab_option_21_render(  ) {

		$options2 = get_option( 'yih4_nwd_tab_settings_2' );
		?>
		<input type='checkbox' name='yih4_nwd_tab_settings_2[yih4_tab_option_21]' <?php checked( !empty($options2['yih4_tab_option_21']), 1 ); ?> value='1'>
		Choose this to use the MediaElement Player instead of the native YouTube player on the front-end.<br/><br/>

		<strong>NOTE:</strong> "Default Player" is defined as the default video player provided by YouTube.com. Anything stating "Default Player Only" does not apply to use of the "Media Element Player" provided in WordPress.
		<?php
}

function yih4_tab_option_22_render(  ) {

		$options3 = get_option( 'yih4_nwd_tab_settings_3' );

		$yih4_publish_options = array(
			'Publish'	=>	'publish',
			'Draft'		=>	'draft',
			'Pending'	=>	'pending'
		)
		?>

		<select name='yih4_nwd_tab_settings_3[yih4_tab_option_22]'>
			<?php
			foreach ( $yih4_publish_options as $yih4_option_key => $yih4_publish_option ) {
				?>
				<option value="<?php echo esc_attr($yih4_publish_option); ?>" <?php echo selected( esc_attr($yih4_publish_option), esc_attr($options3['yih4_tab_option_22']) ); ?>><?php echo $yih4_option_key; ?></option>
				<?php
			}
			?>
		</select>
		<?php
}

function yih4_nwd_tab_settings_2_callback (){
	// Playback Options Callback
  echo __( '', 'nwdigital' );
}
function yih4_nwd_tab_settings_3_callback (){
	// Global Options Callback
  echo __( '', 'nwdigital' );
}
