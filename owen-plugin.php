<?php
/*
* Plugin Name: owen-plugin
* Description: Piano
*/

use function WP_CLI_Login\init_server_from_request;

define( 'OP_PIANO_PLUGIN_FILE', __FILE__ );
define( 'OP_PIANO_PLUGIN_NAME', 'owen-plugin');
define( 'OP_PIANO_PLUGIN_PATH', untrailingslashit( plugins_url(OP_PIANO_PLUGIN_NAME) ));
define( 'OP_PIANO_PLUGIN_URL', plugins_url(OP_PIANO_PLUGIN_NAME));

$plugin_path = untrailingslashit( plugins_url("owen-plugin") );

if (! class_exists('Owen_Plugin')){
  class Owen_Plugin {

    function __construct() {
      $this->init_hooks();
    }

    function init_hooks(){
      add_action('admin_menu', array($this, 'customer_menu_pages'));
      add_shortcode('piano_shortcode', array($this, "piano_shortcode")); 
    }
    
    function customer_menu_pages(){
      add_menu_page( 'Piano', 'Piano', 'manage_options', 'piano', array($this, 'customer_admin_page'), 'dashicons-format-audio', 0);
    }
    
    function piano_shortcode() {
      wp_enqueue_script('piano_js', OP_PIANO_PLUGIN_PATH."/main_script.js", ['jquery']);
      wp_localize_script('piano_js', 'owen_plugin_path', OP_PIANO_PLUGIN_PATH);
      wp_enqueue_style('piano_css', OP_PIANO_PLUGIN_PATH."/main_style.css");
      $piano = '
      <p>DIGITAL PIANO</p>
      
      <div id="piano_keys"></div><br>
      
      <div>
      <br>
          <button id="playsong">Play Song</button>
          <button id="addrow">Add Row</button><br><br>
          <button id="faster">Faster</button>
          <button id="slower">Slower</button>
          <var id="speed">Speed:1</var>
        </div><br>
    
        <div id="piano_roll"></div>
    
        <div id="filltest"></div>
      ';
      return $piano;
    }
    
    function customer_admin_page() {
      
    }
    
  }
};

$GLOBALS['Owen_Plugin'] = new Owen_Plugin();

?>
