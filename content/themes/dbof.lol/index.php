<?php

if(is_404()) {
    /**
     * This is a bit of a hack...
     * In functions.php we have a plugins_url filter (tp_plugins_url) which works on the frontend.
     * But it doesn't work in the admin area. So to make sure plugins use the correct plugins folder,
     * we have to redirect the browser to the right destination.
     */
    if(strstr($_SERVER['REQUEST_URI'], LOL__WORDPRESS_DIR_NAME.'/wp-content')) {
        $request_uri = lol__update_content_path($_SERVER['REQUEST_URI']);
        wp_redirect($request_uri, 301);
        exit;
    }
}

if (!class_exists('Timber')) {
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
    return;
}

$context = Timber::get_context();
Timber::render('default.twig', $context);
