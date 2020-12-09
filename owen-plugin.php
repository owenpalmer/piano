<?php
/*
* Plugin Name: owen-plugin
* Description: Piano
*/

define( 'OP_PIANO_PLUGIN_PATH', untrailingslashit( plugin_dir_url( __FILE__ ) ));

define( 'OP_PIANO_PLUGIN_FILE', __FILE__ );
define( 'OP_PIANO_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ));
define( 'OP_PIANO_PLUGIN_URL', plugins_url( 'OP_PIANO_PLUGIN_NAME', __FILE__ ) );
define( 'OP_PIANO_PLUGIN_NAME', 'owen-plugin');
// define( 'KF_PLUGIN_URL', plugins_url( KF_PLUGIN_NAME ) );
// For template redirects

$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
add_action( 'admin_menu', 'customer_menu_pages');

function customer_menu_pages(){
  add_menu_page( 'Piano', 'Piano', 'manage_options', 'piano', 'customer_admin_page', 'dashicons-format-audio', 0);
}

add_shortcode('piano_shortcode', "piano_shortcode"); 

function piano_shortcode() {
 

}

function customer_admin_page() {
  wp_enqueue_script('piano_js', OP_PIANO_PLUGIN_PATH . "/piano_project/main_script.js", ['jquery']);
  wp_localize_script('piano_js', 'something', OP_PIANO_PLUGIN_PATH);
  wp_enqueue_style('piano_css', OP_PIANO_PLUGIN_PATH . "/piano_project/main_style.css");

  $piano = '
    <header>DIGITAL PIANO</header>

    <div id="piano_keys"></div></br>

    <div>
      </br>
      <button id="playsong">Play Song</button>
      <button id="addrow">Add Row</button></br></br>
      <button id="faster">Faster</button>
      <button id="slower">Slower</button>
      <var id="speed">Speed:1</var>
    </div></br>

    <div id="piano_roll"></div>

    <div id="filltest"></div>
  ';

echo $piano;

echo plugins_url('kjhgfd', __FILE__);
};

?>
