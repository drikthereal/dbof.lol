<?php

if (!class_exists('Timber')) {
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
    return;
}

$template = array('index.twig');
$context = Timber::get_context();

$is_404 = is_404();

if($is_404 || isset($_GET['is_404'])) {
    /**
     * This is a bit of a hack...
     * In functions.php we have a plugins_url filter (tp_plugins_url) which works on the frontend.
     * But it doesn't work in the admin area. So to make sure plugins use the correct plugins folder,
     * we have to redirect the browser to the right destination.
     */
    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode('?', $uri, 2);
    if(strstr($uri[0], LOL__WORDPRESS_DIR_NAME.'/wp-content')) {
        $request_uri = lol__update_content_path($uri[0]);
        $request_uri = rtrim($request_uri, '/');
        if(!empty($uri[1])) {
            $request_uri .= '?' . $uri[1];
        }
        wp_redirect($request_uri, 301);
        exit;
    } elseif($is_404) {
        array_unshift($template, '404.twig');
    }
}

if(is_single()) {
    $context['post'] = new TimberPost();
    array_unshift($template, $context['post']->post_type . '-single.twig');
} elseif(is_post_type_archive()) {
    array_unshift($template, get_post_type() . '.twig');
} elseif(is_page()) {
    $context['page'] = new TimberPost();
    array_unshift($template, 'page.twig');
} elseif(is_tag()) {
    $context['tag'] = new TimberTerm();
    array_unshift($template, 'tag.twig');
}

Timber::render($template, $context);
