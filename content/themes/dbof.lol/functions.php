<?php
/**
 * Updates the path to the correct value.
 *
 * @param [string] $old_path
 * @return [string]
 */
function tp_update_content_path($old_path) {
    return str_replace(TP_WORDPRESS_DIR_NAME.'/wp-content', TP_CONTENT_DIR_NAME, $old_path);
}

/**
 * Make sure plugins_url() returns the proper url string.
 *
 * @param [string] $url
 * @param [string] $path
 * @param [string] $plugin
 * @return [string]
 */
function tp_plugins_url($url, $path, $plugin) {
    return tp_update_content_path($url);
}
add_filter('plugins_url', 'tp_plugins_url', 10, 3);

/**
 * Use the correct upload dir.
 *
 * @param [type] $param [description]
 * @return [type] [description]
 */
function tp_upload_dir($param) {
    $param['baseurl'] = tp_update_content_path($param['baseurl']);
    $param['url'] = tp_update_content_path($param['url']);
    return $param;
}
add_filter('upload_dir', 'tp_upload_dir');

/**
 * get_stylesheet_directory_uri() has to return the correct path.
 *
 * @param [string] $url
 * @return [string]
 */
function tp_stylesheet_directory_uri($url) {
    return tp_update_content_path($url);
}
add_filter('stylesheet_directory_uri', 'tp_stylesheet_directory_uri');
