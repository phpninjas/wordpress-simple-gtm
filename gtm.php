<?php
/*
Plugin Name: Google Tag Manager for Wordpress
Version: 1.0
Plugin URI: https://github.com/phpninjas/wordpress-simple-gtm
Description: Simple google tag manager, no bollocks
Author: James Turner
Author URI: https://github.com/phpninjas/
*/

namespace phpninjas\gtm;

class GTM {
    const OPTION_GROUP = 'gtm';
    const ADMINSLUG = 'gtm_settings';
    const OPTION_NAME_CONTAINER_ID = 'containerId';
}

$bodyOpen = function () {
    if (false !== $containerId = get_option(GTM::OPTION_NAME_CONTAINER_ID)) {
        include('html/no-script.phtml');
    }
};

$wpHead = function () {
    if (false !== $containerId = get_option(GTM::OPTION_NAME_CONTAINER_ID)) {
        include("js/gtm.js");
    };
};

$adminInit = function () {
    register_setting(GTM::OPTION_GROUP, GTM::OPTION_NAME_CONTAINER_ID, ['type' => 'string']);
};

$adminMenu = function () {
    add_options_page(
        __('GTM'),
        __('GTM'),
        'manage_options',
        GTM::ADMINSLUG,
        function () {
            $optionGroup = GTM::OPTION_GROUP;
            $page = GTM::ADMINSLUG;
            $containerName = GTM::OPTION_NAME_CONTAINER_ID;
            include('html/admin.phtml');
        }
    );
};


add_action('wp_head', $wpHead, 1, 0);
add_action('body_open', $bodyOpen);

if (is_admin()) {
    add_action('admin_init', $adminInit);
    add_action('admin_menu', $adminMenu);
}
