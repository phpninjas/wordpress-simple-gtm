<?php
/*
Plugin Name: Google Tag Manager for Wordpress
Version: 0.1
Plugin URI: https://github.com/phpninjas/wordpress_simple_gtm
Description: Simple google tag manager, no bollocks
Author: James Turner
Author URI: https://github.com/phpninjas/

WC requires at least: 2.6
WC tested up to: 3.4.5
*/

namespace phpninjas\gtm;

class GTM
{

    const OPTION_GROUP = 'gtm';
    const ADMINSLUG = 'gtm_settings';
    const OPTION_NAME_CONTAINER_ID = 'containerId';

    function init() {
    }

    static public function template($template, $args) {
        extract($args);
        ob_start();
        include($template);
        return ob_get_clean();
    }

    static public function add_gtm_script() {
        if (false !== $containerId = get_option(self::OPTION_NAME_CONTAINER_ID)) {
            echo self::template("js/gtm.js", [self::OPTION_NAME_CONTAINER_ID => $containerId]);
        };
    }

    static public function admin_init() {
        register_setting(self::OPTION_GROUP, self::OPTION_NAME_CONTAINER_ID, ['type' => 'string']);

        add_settings_section(
            self::OPTION_GROUP,
            __('General'),
            function () {
            },
            self::ADMINSLUG
        );

        add_settings_field(
            self::OPTION_NAME_CONTAINER_ID,
            __('Google Tag Manager ID'),
            __NAMESPACE__ . '\GTM::show_input_field',
            self::ADMINSLUG,
            self::OPTION_GROUP,
            array(
                "label" => self::OPTION_NAME_CONTAINER_ID,
                "value" => get_option(self::OPTION_NAME_CONTAINER_ID)
            )
        );
    }

    static public function show_input_field($args) {
        ?>
        <input name="<?php echo $args['label'] ?>" type="text" value="<?php echo $args['value'] ?>"/>
        <?php
    }


}

$show_admin_page = function() {
    echo GTM::template('html/admin.phtml', ['optionGroup' => GTM::OPTION_GROUP, 'page' => GTM::ADMINSLUG]);
};

$add_admin_menu = function()use($show_admin_page) {
    add_options_page(
        __('GTM'),
        __('GTM'),
        'manage_options',
        GTM::ADMINSLUG,
        $show_admin_page
    );
};

add_action('wp_head', __NAMESPACE__ . '\GTM::add_gtm_script', 1, 0);
add_action('plugins_loaded', __NAMESPACE__ . '\GTM::init');
add_action('admin_init', __NAMESPACE__ . '\GTM::admin_init');
add_action('admin_menu', $add_admin_menu);