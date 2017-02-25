<?php

/*
 * Display a notification in the admin when Timber is not activated.
 */
if (!class_exists('Timber')) {
    add_action(
        'admin_notices', function () {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="'.esc_url(admin_url('plugins.php#timber')).'">'.esc_url(admin_url('plugins.php')).'</a></p></div>';
        }
    );

    return;
}

/**
 * Updates the path to the correct value.
 *
 * @param [string] $old_path
 * @return [string]
 */
function lol__update_content_path($old_path) {
    return str_replace(LOL__WORDPRESS_DIR_NAME.'/wp-content', LOL__CONTENT_DIR_NAME, $old_path);
}

/**
 * Make sure plugins_url() returns the proper url string.
 *
 * @param [string] $url
 * @param [string] $path
 * @param [string] $plugin
 * @return [string]
 */
function lol__plugins_url($url, $path, $plugin) {
    return lol__update_content_path($url);
}
add_filter('plugins_url', 'lol__plugins_url', 10, 3);

/**
 * Use the correct upload dir.
 *
 * @param [type] $param [description]
 * @return [type] [description]
 */
function lol__upload_dir($param) {
    $param['baseurl'] = lol__update_content_path($param['baseurl']);
    $param['url'] = lol__update_content_path($param['url']);
    return $param;
}
add_filter('upload_dir', 'lol__upload_dir');

/**
 * get_stylesheet_directory_uri() has to return the correct path.
 *
 * @param [string] $url
 * @return [string]
 */
function lol__stylesheet_directory_uri($url) {
    return lol__update_content_path($url);
}
add_filter('stylesheet_directory_uri', 'lol__stylesheet_directory_uri');

// Only store 5 revisions / post
function lol__wp_revisions_to_keep($num, $post) {
    return 5;
}
add_filter('wp_revisions_to_keep', 'lol__wp_revisions_to_keep', 10, 2);

// Featured image support
function lol__after_setup_theme() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'lol__after_setup_theme');



// TIMBER

// Extend timber context
function lol__timber_context($context) {
    // Menus by location
    $nav_menu_locations = get_nav_menu_locations();
    foreach($nav_menu_locations as $location_slug => $id) {
        $context['menu'][$location_slug] = new TimberMenu($id);
    }
    return $context;
}
add_filter('timber_context', 'lol__timber_context');

// Set templates folder
Timber::$dirname = array('layouts', 'templates');
