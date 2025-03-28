<?php
/*
Plugin Name: == WPProAtoZ Extras for Elementor and more code snippets. 
Plugin URI: https://wpproatoz.com/plugins
Description: Code Snippets and more Extras for Elementor and other sections of your website from WPProAtoZ.com. As time permits we will add more code snippets you can turn on and off. Find our more at our GitHub Repo https://github.com/Ahkonsu/wpproatoz-code-snippets
Version: 1.0
Requires at least: 6.0
Requires PHP:      8.0
Author: WPProAtoZ.com
Author URI: https://wpproatoz.com
Text Domain:       wpproatoz-code-snippets
Update URI:        https://github.com/Ahkonsu/wpproatoz-code-snippets/releases
GitHub Plugin URI: https://github.com/Ahkonsu/wpproatoz-code-snippets/releases
GitHub Branch: main  // 
*/

// These are extra code snippets to help improve or fix issues in your Elementor site. Also included are other great functions I feel are helpful.

// Define plugin version
define('WPPROATOZ_VERSION', '1.0');

// These are extra code snippets to help improve or fix issues in your Elementor site. Also included are other great functions I feel are helpful.

////***check for updates code

require 'plugin-update-checker-5.5/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

try {
    $myUpdateChecker = PucFactory::buildUpdateChecker(
        'https://github.com/Ahkonsu/wpproatoz-code-snippets/',
        __FILE__,
        'wpproatoz-code-snippets'
    );

    //Set the branch that contains the stable release.
    $myUpdateChecker->setBranch('main');

    //$myUpdateChecker->getVcsApi()->enableReleaseAssets();
    
    //Optional: If you're using a private repository, specify the access token like this:
    //$myUpdateChecker->setAuthentication('your-token-here');
} catch (Exception $e) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('WPProAtoZ Update Checker Error: ' . $e->getMessage());
    }
}

/////support access manager class
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Load the class file if it hasn't been loaded yet
if ( ! class_exists( 'Support_Access_Manager' ) ) {
    require_once __DIR__ . '/supportaccess/class-support-access-manager.php';
}

// Get or create the instance with default settings
Support_Access_Manager::instance();

////////ELEMENTOR FUNCTIONS//////////
//////////////////////////

//////////////
//Remove the auto display of Page/Post titles site wide.
/**
 * Hides Elementor page titles site-wide
 * @param bool $return Current title display status
 * @return bool Always returns false to hide titles
 */
function ele_disable_page_title( $return ) {
    return false;
}

///////////////
// In elementor the load more function sometimes breaks and there is no universal fix for it. However 95% of the time this fixes your issue though. This is a fix for load more showing the same posts over and over again. This may not completly solve it but it does work.
/**
 * Fixes Elementor load more pagination issues
 * @param bool $preempt Current 404 status
 * @param WP_Query $wp_query Current query object
 * @return bool Returns true if on paginated page
 */
function pre_handle_404($preempt, $wp_query) {
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
        __('Hide Featured Image in this post', 'wpproatoz-code-snippets'),
        'hide_featured_image_metabox_callback',
        'post',
        'side',
        'default'
    );
}

function hide_featured_image_metabox_callback($post) {
    wp_nonce_field('hide_featured_image_save', 'hide_featured_image_nonce');
    $hide_featured = get_post_meta($post->ID, 'hide_featured_image', true);
    ?>
    <input type="checkbox" id="hide_featured_image" name="hide_featured_image" <?php checked($hide_featured, 'on'); ?>>
    <label for="hide_featured_image"><?php _e('Hide Featured Image', 'wpproatoz-code-snippets'); ?></label>
    <?php
}

// Save meta box data with security
function save_hide_featured_image($post_id) {
    if (!isset($_POST['hide_featured_image_nonce']) || 
        !wp_verify_nonce($_POST['hide_featured_image_nonce'], 'hide_featured_image_save')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $hide = isset($_POST['hide_featured_image']) ? 'on' : '';
    update_post_meta($post_id, 'hide_featured_image', $hide);
}
add_action('save_post', 'save_hide_featured_image');

/////////////
// Add custom CSS to hide the featured image when the checkbox is checked
function custom_css_hide_featured_image() {
    if (is_single() && get_post_meta(get_the_ID(), 'hide_featured_image', true)) {
        echo '<style> .post-thumbnail { display: none; }</style>';
    }
}

//////////////////
//Custom function for preserving the excerpt formating this function allows you to maintain all your formatting when showing an excerpt of the post instead of the whole post content. The excerpt is self generating
function custom_wp_trim_excerpt($text) {
    $raw_excerpt = $text;
    if ('' == $text) {
        //Retrieve the post content. 
        $text = get_the_content('');
        
        //Delete all shortcode tags from the content. 
        $text = strip_shortcodes($text);
        
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
        
        $allowed_tags = '<p>,<a>,<em>,<strong>,<img>'; /*** MODIFY THIS. Add the allowed HTML tags separated by a comma.***/
        $text = strip_tags($text, $allowed_tags);
        
        $excerpt_word_count = 55; /*** MODIFY THIS. change the excerpt word count to any integer you like.***/
        $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);
        
        $excerpt_end = '[...]'; /*** MODIFY THIS. change the excerpt endind to something else.***/
        $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);
        
        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if (count($words) > $excerpt_length) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }
    }
    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

//add a menu link in the plugin list
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');

function add_action_links($links) {
    $settings_link = '<a href="' . admin_url('tools.php?page=wppro-code-snippets-filters-actions') . '">' . __('Settings', 'wpproatoz-code-snippets') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Register the submenu in "Tools"
add_action('admin_menu', 'wpproatoz_add_admin_menu');

function wpproatoz_add_admin_menu() {
    add_submenu_page(
        'tools.php', // Parent menu (Tools)
        __('WPPro Code Snippets', 'wpproatoz-code-snippets'), // Page title
        __('WPPro Code Snippets', 'wpproatoz-code-snippets'), // Menu title
        'manage_options', // Capability
        'wppro-code-snippets-filters-actions', // Menu slug
        'wpproatoz_settings_page' // Callback function
    );
}

function wpproatoz_settings_page() {
    // Handle export request
    if (isset($_POST['wpproatoz_export']) && check_admin_referer('wpproatoz_settings_action', 'wpproatoz_settings_nonce')) {
        $options = get_option('wpproatoz_hooks');
        $export_data = json_encode($options);
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('WpPro Code Snippets Filters & Actions', 'wpproatoz-code-snippets'); ?></h1>
            <p><?php _e('Copy this settings export:', 'wpproatoz-code-snippets'); ?></p>
            <textarea rows="10" cols="50" readonly><?php echo esc_textarea($export_data); ?></textarea>
        </div>
        <?php
        return;
    }

    // Handle import request
    if (isset($_POST['wpproatoz_import']) && isset($_POST['wpproatoz_import_data']) && 
        check_admin_referer('wpproatoz_settings_action', 'wpproatoz_settings_nonce')) {
        $import_data = json_decode(stripslashes($_POST['wpproatoz_import_data']), true);
        if ($import_data !== null) {
            update_option('wpproatoz_hooks', $import_data);
            echo '<div class="notice notice-success"><p>' . __('Settings imported successfully!', 'wpproatoz-code-snippets') . '</p></div>';
        }
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('WpPro Code Snippets Filters & Actions', 'wpproatoz-code-snippets'); ?></h1>
        <form method="post" action="options.php">
            <?php
            wp_nonce_field('wpproatoz_settings_action', 'wpproatoz_settings_nonce');
            settings_fields('wpproatoz_settings_group');
            do_settings_sections('wppro-code-snippets-filters-actions');
            submit_button();
            ?>
        </form>
        <form method="post">
            <?php wp_nonce_field('wpproatoz_settings_action', 'wpproatoz_settings_nonce'); ?>
            <h2><?php _e('Export/Import Settings', 'wpproatoz-code-snippets'); ?></h2>
            <p><input type="submit" name="wpproatoz_export" class="button" value="<?php esc_attr_e('Export Settings', 'wpproatoz-code-snippets'); ?>"></p>
            <p><?php _e('Paste settings to import:', 'wpproatoz-code-snippets'); ?></p>
            <textarea name="wpproatoz_import_data" rows="5" cols="50"></textarea>
            <p><input type="submit" name="wpproatoz_import" class="button" value="<?php esc_attr_e('Import Settings', 'wpproatoz-code-snippets'); ?>"></p>
        </form>
    </div>
    <?php
}
            
// Register settings
add_action('admin_init', 'wpproatoz_register_settings');

function wpproatoz_register_settings() {
    register_setting('wpproatoz_settings_group', 'wpproatoz_hooks');
    register_setting('wpproatoz_settings_group', 'wpproatoz_debug_mode', array('sanitize_callback' => 'sanitize_text_field'));

    add_settings_section(
        'wpproatoz_main_section',
        __('Manage Filters & Actions', 'wpproatoz-code-snippets'),
        'wpproatoz_section_callback',
        'wppro-code-snippets-filters-actions'
    );

    add_settings_field(
        'ele_disable_page_title',
        __('Enable Hide elementor Page Title Filter.', 'wpproatoz-code-snippets'),
        'wpproatoz_toggle_field_callback',
        'wppro-code-snippets-filters-actions',
        'wpproatoz_main_section',
        ['id' => 'ele_disable_page_title']
    );

    add_settings_field(
        'pre_handle_404',
        __('Enable Elementor Load More Display Fix Filter', 'wpproatoz-code-snippets'),
        'wpproatoz_toggle_field_callback',
        'wppro-code-snippets-filters-actions',
        'wpproatoz_main_section',
        ['id' => 'pre_handle_404']
    );

    add_settings_field(
        'custom_wp_trim_excerpt',
        __('Enable Preserve Excerpt Formatting Filter', 'wpproatoz-code-snippets'),
        'wpproatoz_toggle_field_callback',
        'wppro-code-snippets-filters-actions',
        'wpproatoz_main_section',
        ['id' => 'custom_wp_trim_excerpt']
    );

    add_settings_field(
        'add_hide_featured_image_meta_box',
        __('Enable Hide featured images on a post by post basis Action.', 'wpproatoz-code-snippets'),
        'wpproatoz_toggle_field_callback',
        'wppro-code-snippets-filters-actions',
        'wpproatoz_main_section',
        ['id' => 'add_hide_featured_image_meta_box']
    );

    add_settings_field(
        'custom_css_hide_featured_image',
        __('Enable Add custom CSS to hide the featured image Action.', 'wpproatoz-code-snippets'),
        'wpproatoz_toggle_field_callback',
        'wppro-code-snippets-filters-actions',
        'wpproatoz_main_section',
        ['id' => 'custom_css_hide_featured_image']
    );

    add_settings_field(
        'wpproatoz_debug_mode',
        __('Enable Debug Mode', 'wpproatoz-code-snippets'),
        'wpproatoz_debug_toggle_callback',
        'wppro-code-snippets-filters-actions',
        'wpproatoz_main_section'
    );
}

function wpproatoz_section_callback() {
    ?>
    <p><?php echo esc_html__('Select the filters and actions you want to enable.', 'wpproatoz-code-snippets');?></p>
    <p><input type="checkbox" id="wpproatoz_toggle_all" onclick="jQuery('.wpproatoz-feature input').prop('checked', this.checked)">
    <label for="wpproatoz_toggle_all"><?php _e('Toggle All Features', 'wpproatoz-code-snippets'); ?></label></p>
    <?php
}

function wpproatoz_toggle_field_callback($args) {
    $options = get_option('wpproatoz_hooks');
    $checked = isset($options[$args['id']]) ? 'checked' : '';
    echo "<input type='checkbox' name='wpproatoz_hooks[{$args['id']}]' value='1' {$checked} class='wpproatoz-feature' />";
}

function wpproatoz_debug_toggle_callback() {
    $debug_mode = get_option('wpproatoz_debug_mode', 'off');
    $checked = ($debug_mode === 'on') ? 'checked' : '';
    echo "<input type='checkbox' name='wpproatoz_debug_mode' value='on' {$checked} />";
    echo "<p class='description'>" . __('Enable to log debug information to WordPress debug.log', 'wpproatoz-code-snippets') . "</p>";
}

// Apply Hooks Based on User Settings with Performance Optimization
add_action('init', 'wpproatoz_hooks');
function wpproatoz_hooks() {
    $options = get_option('wpproatoz_hooks');
    $debug_mode = get_option('wpproatoz_debug_mode', 'off');
    
    // Only load Elementor-related hooks if Elementor is active
    if (isset($options['ele_disable_page_title']) && class_exists('Elementor\Plugin')) {
        add_filter('hello_elementor_page_title', 'ele_disable_page_title');
        if ($debug_mode === 'on') {
            error_log('WPProAtoZ: Elementor page title filter enabled');
        }
    }
    
    if (isset($options['pre_handle_404']) && class_exists('Elementor\Plugin')) {
        add_filter('pre_handle_404', 'pre_handle_404', 10, 2);
        if ($debug_mode === 'on') {
            error_log('WPProAtoZ: Elementor load more fix filter enabled');
        }
    }
    
    // Only load meta box in admin
    if (isset($options['add_hide_featured_image_meta_box']) && is_admin()) {
        add_action('add_meta_boxes', 'add_hide_featured_image_meta_box');
        if ($debug_mode === 'on') {
            error_log('WPProAtoZ: Hide featured image meta box enabled');
        }
    }
    
    // Only load excerpt filter when needed
    if (isset($options['custom_wp_trim_excerpt']) && (is_home() || is_archive() || is_single())) {
        remove_filter('get_the_excerpt', 'wp_trim_excerpt');
        add_filter('get_the_excerpt', 'custom_wp_trim_excerpt');
        if ($debug_mode === 'on') {
            error_log('WPProAtoZ: Custom excerpt filter enabled');
        }
    }
    
    // Only load CSS on single posts
    if (isset($options['custom_css_hide_featured_image']) && is_single()) {
        add_action('wp_head', 'custom_css_hide_featured_image');
        if ($debug_mode === 'on') {
            error_log('WPProAtoZ: Custom CSS for featured image enabled');
        }
    }
}

// Enqueue jQuery for toggle all feature
add_action('admin_enqueue_scripts', 'wpproatoz_enqueue_scripts');
function wpproatoz_enqueue_scripts($hook) {
    if ($hook !== 'tools_page_wppro-code-snippets-filters-actions') {
        return;
    }
    wp_enqueue_script('jquery');
}

// Cleanup on uninstall
register_uninstall_hook(__FILE__, 'wpproatoz_uninstall');
function wpproatoz_uninstall() {
    delete_option('wpproatoz_hooks');
    delete_option('wpproatoz_debug_mode');
    // Clean up post meta
    global $wpdb;
    $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = 'hide_featured_image'");
}

// Load text domain for translations
add_action('plugins_loaded', 'wpproatoz_load_textdomain');
function wpproatoz_load_textdomain() {
    load_plugin_textdomain('wpproatoz-code-snippets', false, dirname(plugin_basename(__FILE__)) . '/languages');
}