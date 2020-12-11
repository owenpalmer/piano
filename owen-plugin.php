<?php
/*
* Plugin Name: owen-plugin
* Description: Piano
*/
define( 'OP_PIANO_PLUGIN_PATH', untrailingslashit( plugin_dir_url( __FILE__ ) ));
add_action( 'admin_menu', 'customer_menu_pages');
// add_action('admin_enqueue_scripts', 'piano');


function customer_menu_pages(){
  add_menu_page( 'Piano', 'Piano', 'manage_options', 'piano', 'customer_admin_page', 'dashicons-format-audio', 0);
}

add_shortcode('piano_shortcode', "piano_shortcode"); 

function piano_shortcode(){
  wp_enqueue_script('piano_js', OP_PIANO_PLUGIN_PATH . "/piano_project/main_script.js", ['jquery']);
  wp_localize_script('piano_js', 'something', OP_PIANO_PLUGIN_PATH);
  wp_enqueue_style('piano_css', OP_PIANO_PLUGIN_PATH . "/piano_project/main_style.css");
  require 'index.php';
  error_log('hola');
  // $content = "<h1>DIGITAL PIANO</h1>
  // <div id='piano_keys'>
  // </div>";
  // return $content;
}

function customer_admin_page() {
  ?>
  hello worlld 
  <?php
  // do_shortcode('piano_shortcode');
}



?>
