<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

// Creating the widget
class yih4_widget_categories extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
        // Base ID of your widget
            'yih4_widget_categories',
        // Widget name will appear in UI
            __('YouTube Categories', 'youtube-import-nwdigital'),
        // Widget description
            array(
            'description' => __('Sample widget based on WPBeginner Tutorial', 'youtube-import-nwdigital')
        ));
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        // This is where you run the code and display the output

				// Output a named taxonomy
				$yih4_args = array( 'hide_empty=0' );

					$terms = get_terms( 'yih4-category', $yih4_args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					    $term_list = '<ul class="yih4_term-archive">';
					    foreach ( $terms as $term ) {
                  $term_list .=  '<li>';
					        $term_list .= '<a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( sprintf( __( 'View all post filed under %s', 'youtube-import-nwdigital' ), $term->name ) ) . '">' . $term->name . '</a>&nbsp;(' . $term->count . ')</li>';
					    }
					    echo $term_list. '</ul>';
					}

					echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'youtube-import-nwdigital');
        }
        // Widget admin form
?>
<p>
<label for="<?php
        echo $this->get_field_id('title');
?>"><?php
        _e('Title:');
?></label>
<input class="widefat" id="<?php
        echo $this->get_field_id('title');
?>" name="<?php
        echo $this->get_field_name('title');
?>" type="text" value="<?php
        echo esc_attr($title);
?>" />
</p>
<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance          = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
} // Class yih4_widget_categories ends here

// Register and load the widget
function wpb_load_widget()
{
    register_widget('yih4_widget_categories');
}
add_action('widgets_init', 'wpb_load_widget');

/**
 * Register our sidebars and widgetized areas.
 *
 */

function yih4_widget_video_init() {

  if ( function_exists('register_sidebar') )
  	register_sidebar( array(
  		'name'          => 'YouTube Import Before Video',
  		'id'            => 'youtube_before_video',
  		'before_widget' => '<div class="yih4_widget_content">',
  		'after_widget'  => '</div>',
  		'before_title'  => '<h2>',
  		'after_title'   => '</h2>',
  	)
  );
    register_sidebar( array(
      'name'          => 'YouTube Import After Video',
      'id'            => 'youtube_after_video',
      'before_widget' => '<div class="yih4_widget_content">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2>',
      'after_title'   => '</h2>',
    )
  );
    register_sidebar( array(
      'name'          => 'YouTube Import After Content',
      'id'            => 'youtube_after_content',
      'before_widget' => '<div class="yih4_widget_content">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2>',
      'after_title'   => '</h2>',
    )
  );
}

add_action( 'widgets_init', 'yih4_widget_video_init' );
