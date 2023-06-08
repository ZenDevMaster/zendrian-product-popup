<?php

// Add "size chart" link above quantity/add to cart buttons
add_action('woocommerce_before_add_to_cart_button', 'zendrian_product_popup_size_chart');
function zendrian_product_popup_size_chart()
{
    global $product;

    $zpp_enabled = get_post_meta($product->get_id(), 'zendrian_product_popup_enable', true);

    if ($zpp_enabled === '1') {
        $zpp_link_text = get_post_meta($product->get_id(), 'zendrian_product_popup_link_text', true);
        if (empty($zpp_link_text)) {
            $zpp_link_text = get_option('zendrian_product_popup_link_text', 'Size Chart');
        }

        echo '<a href="#" class="zpp-size-chart" data-product_id="' . $product->get_id() . '">' . esc_html($zpp_link_text) . '</a>';
    }
}
// Add meta box for product popup settings
add_action('add_meta_boxes', 'zendrian_product_popup_meta_box');
function zendrian_product_popup_meta_box()
{
    add_meta_box('zendrian_product_popup', 'Product Popup Settings', 'zendrian_product_popup_meta_box_callback', 'product', 'side', 'default');
}

// Meta box callback
function zendrian_product_popup_meta_box_callback($post)
{
    wp_nonce_field('zendrian_product_popup_save_data', 'zendrian_product_popup_nonce');

    $zpp_enabled = get_post_meta($post->ID, 'zendrian_product_popup_enable', true);
    $zpp_text = get_post_meta($post->ID, 'zendrian_product_popup_link_text', true);
    $zpp_content = get_post_meta($post->ID, 'zendrian_product_popup_content', true);

    ?>
    <p>
        <input type="checkbox" name="zendrian_product_popup_enable" id="zendrian_product_popup_enable" <?php checked($zpp_enabled, '1'); ?> value="1">
        <label for="zendrian_product_popup_enable">Enable Product Popup</label>
    </p>
    <p>
        <label for="zendrian_product_popup_link_text">Link Text:</label>
        <input type="text" name="zendrian_product_popup_link_text" id="zendrian_product_popup_link_text" value="<?php echo esc_attr($zpp_text); ?>">
    </p>
    <p>
        <label for="zendrian_product_popup_content">Popup Content:</label>
        <textarea name="zendrian_product_popup_content" id="zendrian_product_popup_content"><?php echo esc_textarea($zpp_content); ?></textarea>
    </p>
    <?php
}

// Save product popup settings
add_action('save_post', 'zendrian_product_popup_save_data');
function zendrian_product_popup_save_data($post_id)
{
    // Check if the current request is valid

    if (!isset($_POST['zendrian_product_popup_nonce'])) {
        return $post_id;
    }
    if (!wp_verify_nonce($_POST['zendrian_product_popup_nonce'], 'zendrian_product_popup_save_data')) {
        return $post_id;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    if ('product' !== get_post_type($post_id)) {
        return $post_id;
    }

    // Save product popup settings

    if (isset($_POST['zendrian_product_popup_enable'])) {
        update_post_meta($post_id, 'zendrian_product_popup_enable', '1');
    } else {
        delete_post_meta($post_id, 'zendrian_product_popup_enable');
    }
    
    if (isset($_POST['zendrian_product_popup_link_text'])) {
        update_post_meta($post_id, 'zendrian_product_popup_link_text', sanitize_text_field($_POST['zendrian_product_popup_link_text']));
    } else {
        delete_post_meta($post_id, 'zendrian_product_popup_link_text');
    }

    if (isset($_POST['zendrian_product_popup_content'])) {
        update_post_meta($post_id, 'zendrian_product_popup_content', wp_kses_post($_POST['zendrian_product_popup_content']));
    } else {
        delete_post_meta($post_id, 'zendrian_product_popup_content');
    }
}

// Add hidden div at the end of the product page with modal content
add_action('woocommerce_after_single_product', 'zendrian_product_popup_content_div');
function zendrian_product_popup_content_div()
{
    global $product;

    $zpp_content = get_post_meta($product->get_id(), 'zendrian_product_popup_content', true);

    if (!empty($zpp_content)) {
        $zpp_css = get_option('zendrian_product_popup_content_css', '');
        // Use do_shortcode to render the shortcode in the modal content
        echo '<div id="zpp-content-' . $product->get_id() . '" style="display:none;' . esc_attr($zpp_css) . '">' . wpautop(wp_kses_post(do_shortcode($zpp_content))) . '</div>';
    }
}

?>