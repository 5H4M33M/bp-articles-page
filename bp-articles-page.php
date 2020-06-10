<?php
/**
 * Plugin Name: BP Add Articles Page
 * Plugin URI:  https://github.com/5H4M33M/bp-articles-page.git
 * Description: Adding a page to BuddyPress profiles which show's articles published by BP user
 * Author:      Shameem
 * Author URI:  https://shameem.in
 * Version:     1.0.0
 * License:     GPLv2
 */


/**
 * adds the profile user nav link
 */
function bp_custom_user_nav_item() {
    global $bp;

    $args = array(
            'name' => __('Articles', 'buddypress'),
            'slug' => 'user-articles',
            'default_subnav_slug' => 'user-articles',
            'position' => 50,
            'screen_function' => 'bp_custom_user_nav_item_screen',
            'item_css_id' => 'user-articles'
    );

    bp_core_new_nav_item( $args );
}
add_action( 'bp_setup_nav', 'bp_custom_user_nav_item', 99 );

/**
 * the calback function from our nav item arguments
 */
function bp_custom_user_nav_item_screen() {
    add_action( 'bp_template_content', 'bp_custom_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

/**
 * the function hooked to bp_template_content, this hook is in plugns.php
 */
/* BP user profile - user articles page */
function bp_custom_screen_content() {
	global $bp;
	$memberId = $bp->displayed_user->id;
	$query = new WP_Query( 'author=' . $memberId );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
			$post = $query->the_post();
			// echo get_the_ID($post);
			?>
            <div class="col-md-12 col-sm-12 col-xs-12 user-article-item">
            	<div class="col-md-3 col-sm-3 col-xs-12">
            		<?php $url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>
            		<img src="<?php echo $url ?>" />
            	</div>
            	<div class="col-md-9 col-sm-9 col-xs-12 content-wrap">
					<a href="<?php the_permalink(); ?>"><h4 class="title"><?php  the_title(); ?></h4></a>
					
					<p><?php echo get_the_excerpt($post); ?></p>
					<div class="meta-wrap">
						<span class="meta-date"><?php echo get_the_modified_date($post); ?></span>
						<span class="meta-read-time"><?php echo do_shortcode('[rt_reading_time label="" postfix="min read" postfix_singular="min read" post_id='. get_the_ID($post) .']'); ?></span>
					</div>
            		<?php //echo get_the_date();  
		           // $content = get_the_content(); ?>
		            <?php // echo mb_strimwidth(get_the_content(), 0, 250, '...'); ?>
            	</div>
            </div>
            
       <?php
   		}
    }
}