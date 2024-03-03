<?php
/**
 * Plugin Name: My Form Plugin
 * Description: A simple plugin to create a form and save its data to the database.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Plugin code goes here.
function my_form_plugin_shortcode() {
    ob_start(); ?>

    <form id="myForm">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <input type="submit" value="Submit">
    </form>
    <div id="formResponse"></div>

    <?php
    return ob_get_clean();
}
add_shortcode('my_form', 'my_form_plugin_shortcode');


function my_form_plugin_scripts() {
    wp_enqueue_script('my-form-plugin-js', plugin_dir_url(__FILE__) . 'js/my-form.js', array('jquery'), null, true);
    wp_localize_script('my-form-plugin-js', 'myFormAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'my_form_plugin_scripts');



function submit_my_form() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';

 // Assuming 'data' is the serialized form data passed via AJAX
 if (isset($_POST['data'])) {
    parse_str($_POST['data'], $formData);
    
    // Now, you can safely access $formData['name'], $formData['email'], etc.
    if (isset($formData['name']) && isset($formData['email'])) {
        $name = sanitize_text_field($formData['name']);
        $email = sanitize_email($formData['email']);
        $wpdb->insert($table_name, array('name' => $name, 'email' => $email));

        echo 'Thank you for your submission!';
    } else {
        echo 'Please complete form';
    }
} 

wp_die(); // Close the ajax request
    
   
}

add_action('wp_ajax_submit_my_form', 'submit_my_form');
add_action('wp_ajax_nopriv_submit_my_form', 'submit_my_form');



function my_form_plugin_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'my_form_plugin_activate');

function my_form_plugin_deactivate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';

    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}
register_deactivation_hook(__FILE__, 'my_form_plugin_deactivate');



add_action('admin_menu', 'my_custom_plugin_menu');

function my_custom_plugin_menu() {
    add_menu_page(
        __('My Form Plugin', 'my-custom-plugin'), // Page title
        __('Form Plugin', 'my-custom-plugin'), // Menu title
        'manage_options', // Capability required to see this menu item
        'my-custom-plugin', // Menu slug
        'my_custom_plugin_page', // Function to call to render the menu page
        'dashicons-admin-generic', // Icon URL (optional)
        6 // Position (optional)
    );
}

function my_custom_plugin_page() {
    echo '<div class="wrap">';
    echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
    echo '<p>' . esc_html__('Short code for form is [my_form]', 'my-custom-plugin') . '</p>';
    echo '</div>';
}
