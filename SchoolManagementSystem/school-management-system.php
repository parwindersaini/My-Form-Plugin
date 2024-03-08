<?php
/*
* Plugin Name: School Management
* Version: 0.1
* Author: parwinder singh
*/

// Step 1: Add an Admin Menu Page
function student_form_menu() {
    add_menu_page(
        'Student Form',
        'Student Form',
        'manage_options',
        'custom-admin-form',
        'student_form_page'
    );
}
add_action('admin_menu', 'student_form_menu');

// Step 2: Callback function to display the admin page and form
function student_form_page() {
    ?>
   
        <h1>Student Form</h1>
        <form id="custom-admin-form">
            <label >Student Name</label>
            <input type="text"  name="name" >
            <label >Student Email</label>
            <input type="email"  name="email">
            <input type="submit"  value="Submit">
        </form>
        <div id="custom-admin-response"></div>
   
    <?php
}

// Step 3: Enqueue jQuery and AJAX Script
function custom_admin_form_enqueue_scripts($hook) {
    if ($hook != 'toplevel_page_custom-admin-form') {
        return;
    }
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-admin-ajax-script', plugin_dir_url(__FILE__) . 'js/custom-admin-ajax-script.js', array('jquery'), '1.0', true);
    wp_localize_script('custom-admin-ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('admin_enqueue_scripts', 'custom_admin_form_enqueue_scripts');

// Step 4: Handle AJAX Request
function handle_custom_admin_form_submission() {
    if (isset($_POST['data'])) {
        parse_str($_POST['data'], $formData);
    
        if ($formData['name']!="" && $formData['email']!="") {
            $response = array(
                'success' => true, 
                'message' => 'Thank you '.$formData['name'].'  your email is '.$formData['email'] // Optional message
            );
        }else{
            $response = array(
                'success' => true, 
                'message' => 'Please fill all fields' // Optional message
            );
        }
    }
    wp_send_json($response);
}
add_action('wp_ajax_custom_admin_form_submit', 'handle_custom_admin_form_submission');

