<?php

// Add settings page under the Settings menu
add_action('admin_menu', 'zendrian_product_popup_admin_menu');
function zendrian_product_popup_admin_menu()
{
    add_options_page('Zendrian Product Popup', 'Zendrian Product Popup', 'manage_options', 'zendrian-product-popup', 'zendrian_product_popup_settings_page');
}

// Settings page template
function zendrian_product_popup_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Zendrian Product Popup</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('zendrian_product_popup');
            do_settings_sections('zendrian-product-popup');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings, sections, and fields
add_action('admin_init', 'zendrian_product_popup_settings_init');
function zendrian_product_popup_settings_init()
{
    register_setting('zendrian_product_popup', 'zendrian_product_popup_link_text');
    register_setting('zendrian_product_popup', 'zendrian_product_popup_content_css');

    add_settings_section('zendrian_product_popup_main', 'Main Settings', null, 'zendrian-product-popup');

    add_settings_field('zendrian_product_popup_link_text', 'Link Text', 'zendrian_product_popup_link_text_callback', 'zendrian-product-popup', 'zendrian_product_popup_main');
    add_settings_field('zendrian_product_popup_content_css', 'Content CSS', 'zendrian_product_popup_content_css_callback', 'zendrian-product-popup', 'zendrian_product_popup_main');
}

// Link text settings field callback
function zendrian_product_popup_link_text_callback()
{
    $link_text = get_option('zendrian_product_popup_link_text', '');
    echo '<input type="text" name="zendrian_product_popup_link_text" value="' . esc_attr($link_text) . '">';
}

// Content CSS settings field callback
function zendrian_product_popup_content_css_callback()
{
    $content_css = get_option('zendrian_product_popup_content_css', '');
    echo '<textarea name="zendrian_product_popup_content_css">' . esc_textarea($content_css) . '</textarea>';
}

?>