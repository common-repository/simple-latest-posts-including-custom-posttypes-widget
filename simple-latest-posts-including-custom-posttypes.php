<?php
/*
Plugin Name: Simple Latest Posts Including Custom Posttypes Widget
Plugin URI: http://infobak.nl/latest-posts-widget/
Version: 1.00
Description: Widget which displays latest posts including custom posttypes
Author: Jan Meeuwesen
Author URI: http://infobak.nl/latest-posts-widget/
License: GPLv2
Copyright 2012  Jan Meeuwesen

*/

define("DefNoOfPosts", "5"); // default number of latest posts to display

class SimpleLatestPostsWidget extends WP_Widget {

	function SimpleLatestPostsWidget()
	{
		parent::WP_Widget( false, 'Simple Latest Posts (custom)',  array('description' => 'Latest posts widget') );
	}

	function widget($args, $instance)
	{
		global $NewSimpleLatestPosts;
		$title = empty( $instance['title'] ) ? 'Simple Latest Posts (custom)' : $instance['title'];
		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		echo $NewSimpleLatestPosts->GetSimpleLatestPosts(  empty( $instance['ShowPosts'] ) ? DefNoOfPosts : $instance['ShowPosts'] );
		echo $args['after_widget'];
	}

	function update($new_instance)
	{
		return $new_instance;
	}

	function form($instance)
	{
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('ShowPosts'); ?>"><?php echo 'Number of entries:'; ?></label>
			<input type="text" name="<?php echo $this->get_field_name('ShowPosts'); ?>" id="<?php echo $this->get_field_id('ShowPosts'); ?>" value="<?php if ( empty( $instance['ShowPosts'] ) ) { echo esc_attr(DefNoOfPosts); } else { echo esc_attr($instance['ShowPosts']); } ?>" size="3" />
		</p>

		<?php
	}

}



class SimpleLatestPosts {

	function GetSimpleLatestPosts($noofposts)
	{
		$post_types=get_post_types();
		$args = array( 'numberposts' => $noofposts , 'post_type' => $post_types, 'orderby' => 'post_date', 'order' => 'DESC');
		$recent_posts = wp_get_recent_posts( $args );
		echo '<ul>';
		foreach( $recent_posts as $recent ){
			echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> </li> ';
		}
		echo '</ul>';
	}

}



$NewSimpleLatestPosts = new SimpleLatestPosts();

function SimpleLatestPosts_widgets_init()
{
	register_widget('SimpleLatestPostsWidget');
}

add_action('widgets_init', 'SimpleLatestPosts_widgets_init');


?>