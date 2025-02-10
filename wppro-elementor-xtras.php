<?php
/*
Plugin Name: Elementor and more code snippets Extras from WPProAtoZ.com
Plugin URI: https://wpproatoz.com/plugins
Description: Code Snippets and more Extras for Elementor and other sections of your website from WPProAtoZ.com
Version: 0.5.0
Requires at least: 5.2
Requires PHP:      7.2
Author: WPProAtoZ.com
Author URI: https://wpproatoz.com
Text Domain:       wpproatoz-code-snippets
Update URI:        https://wpproatoz.com/plugins
GitHub Plugin URI: /Ahkonsu/wpproatoz-code-snippets
GitHub Branch: main  // or whatever branch you're using
*/
 
// These are extra code snippets to help improve or fix issues in your Elementor site. Also included are other great functions I feel are helpful.

////***check for updates code




////////ELEMENTOR FUNCTIONS//////////

////////////////////////////
//add function to allow for sidebars
if (function_exists("register_sidebar")) {
  register_sidebar();
}


//////////////////////////
//Remove the auto display of Page/Post titles site wide.
function ele_disable_page_title( $return ) {

return false;

}

require 'plugin-update-checker-5.5/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/Ahkonsu/wpproatoz-code-snippets/',
	__FILE__,
	'unique-plugin-or-theme-slug'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
//$myUpdateChecker->setAuthentication('your-token-here');



///////////////
// In elementor the load more function sometimes breaks and there is no universal fix for it. However 95% of the time this fixes your issue though. This is a fix for load more showing the same posts over and over again. This may not completly solve it but it does work.

function pre_handle_404($preempt, $wp_query)
{
if (isset($wp_query->query['page']) && $wp_query->query['page']) {
return true;
}

return $preempt;
}

///////OTHER USEFUL SITE FUNCTIONS////////////
// these are other site options that are useful

/////////////
//This adds a check box that will allow you to hide featured images on a post by post basis

function add_hide_featured_image_meta_box() {

    add_meta_box(

        'hide_featured_image_metabox',

        'Hide Featured Image in this post',

        'hide_featured_image_metabox_callback',

        'post',

        'side',

        'default'

    );

}


function hide_featured_image_metabox_callback($post) {

    $hide_featured = get_post_meta( $post->ID, 'hide_featured_image', true );

    ?>

    <input type="checkbox" id="hide_featured_image" name="hide_featured_image" <?php checked( $hide_featured, 'on' ); ?>>

    <label for="hide_featured_image">Hide Featured Image</label>

    <?php

}
// Add custom CSS to hide the featured image when the checkbox is checked



function custom_css_hide_featured_image() {

    if ( get_post_meta( get_the_ID(), 'hide_featured_image', true ) ) {

        echo '<style> .post-thumbnail { display: none; }</style>';

    }

}



//////////////////
//Custom function for preserving the excerpt formating this function allows you to maintain all your formatting when showing an excerpt of the post instead of the whole post content. The excerpt is self generating

function custom_wp_trim_excerpt($text) {
$raw_excerpt = $text;
if ( '' == $text ) {
    //Retrieve the post content. 
    $text = get_the_content('');
 
    //Delete all shortcode tags from the content. 
    $text = strip_shortcodes( $text );
 
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
     
    $allowed_tags = '<p>,<a>,<em>,<strong>,<img>'; /*** MODIFY THIS. Add the allowed HTML tags separated by a comma.***/
    $text = strip_tags($text, $allowed_tags);
     
    $excerpt_word_count = 55; /*** MODIFY THIS. change the excerpt word count to any integer you like.***/
    $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
     
    $excerpt_end = '[...]'; /*** MODIFY THIS. change the excerpt endind to something else.***/
    $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);
     
    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
        array_pop($words);
        $text = implode(' ', $words);
        $text = $text . $excerpt_more;
    } else {
        $text = implode(' ', $words);
    }
}
return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

// Register the submenu in "Tools"
add_action('admin_menu', 'wpproatoz_add_admin_menu');

function wpproatoz_add_admin_menu() {
	add_submenu_page(
		'tools.php', // Parent menu (Tools)
		'WPPro Code Snippets', // Page title
		'WPPro Code Snippets', // Menu title
		'manage_options', // Capability
		'wppro-code-snippets-filters-actions', // Menu slug
		'wpproatoz_settings_page' // Callback function
	);
}

function wpproatoz_settings_page() {
	?>
    <div class="wrap">
        <h1><?php echo __('WpPro Code Snippets Filters & Actions', 'wpproatoz'); ?></h1>
        <form method="post" action="options.php">
			<?php
			settings_fields('wpproatoz_settings_group');
			do_settings_sections('wppro-code-snippets-filters-actions');
			submit_button();
			?>
        </form>
    </div>
	<?php
}
// Register settings
add_action('admin_init', 'wpproatoz_register_settings');

function wpproatoz_register_settings() {
	register_setting('wpproatoz_settings_group', 'wpproatoz_hooks');

	add_settings_section(
		'wpproatoz_main_section',
		'Manage Filters & Actions',
		'wpproatoz_section_callback',
		'wppro-code-snippets-filters-actions'
	);

	add_settings_field(
		'ele_disable_page_title',
		'Enable Hide elementor Page Title Filter.',
		'wpproatoz_toggle_field_callback',
		'wppro-code-snippets-filters-actions',
		'wpproatoz_main_section',
        ['id' => 'ele_disable_page_title']
	);

	add_settings_field(
		'pre_handle_404',
		'Enable Elementor Load More Display Fix Filter',
		'wpproatoz_toggle_field_callback',
		'wppro-code-snippets-filters-actions',
		'wpproatoz_main_section',
        ['id' => 'pre_handle_404']
	);

	add_settings_field(
		'add_hide_featured_image_meta_box',
		'Enable Hide featured images on a post by post basis Action.',
		'wpproatoz_toggle_field_callback',
		'wppro-code-snippets-filters-actions',
		'wpproatoz_main_section',
        ['id' => 'add_hide_featured_image_meta_box']
	);

	add_settings_field(
		'custom_wp_trim_excerpt',
		'Enable Preserve Excerpt Formatting Filter',
		'wpproatoz_toggle_field_callback',
		'wppro-code-snippets-filters-actions',
		'wpproatoz_main_section',
        ['id' => 'custom_wp_trim_excerpt']
	);

	add_settings_field(
		'custom_css_hide_featured_image',
		'Enable Add custom CSS to hide the featured image Action.',
		'wpproatoz_toggle_field_callback',
		'wppro-code-snippets-filters-actions',
		'wpproatoz_main_section',
        ['id' => 'custom_css_hide_featured_image']
	);

}

function wpproatoz_section_callback() {
?>
    <p><?php echo __('Select the filters and actions you want to enable.', 'wpproatoz');?></p>
<?php
}

function wpproatoz_toggle_field_callback($args) {
	$options = get_option('wpproatoz_hooks');
	$checked = isset($options[$args['id']]) ? 'checked' : '';
	echo "<input type='checkbox' name='wpproatoz_hooks[{$args['id']}]' value='1' {$checked} />";
}


// Apply Hooks Based on User Settings
add_action('init', 'wpproatoz_hooks');
function wpproatoz_hooks() {
	$options = get_option('wpproatoz_hooks');

    if(isset($options['ele_disable_page_title'])) {
	    add_filter( 'hello_elementor_page_title', 'ele_disable_page_title' );
    }

	if(isset($options['pre_handle_404'])) {
		add_filter( 'pre_handle_404', 'pre_handle_404', 10, 2 );
	}

	if(isset($options['add_hide_featured_image_meta_box'])) {
		add_action('add_meta_boxes', 'add_hide_featured_image_meta_box');
	}

	if(isset($options['custom_wp_trim_excerpt'])) {
		remove_filter('get_the_excerpt', 'wp_trim_excerpt');
		add_filter('get_the_excerpt', 'custom_wp_trim_excerpt');
	}

	if(isset($options['custom_css_hide_featured_image'])) {
		add_action( 'wp_head', 'custom_css_hide_featured_image' );
	}

}
