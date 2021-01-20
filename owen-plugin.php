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
      $this->includes();
      $this->init_hooks();
    }

    function includes(){
      require_once("classes/install.php");
    }

    function init_hooks(){
      register_activation_hook(__FILE__,array("OP_Install",'install_tables'));
      add_action('admin_menu', array($this, 'customer_menu_pages'));
      add_shortcode('piano_shortcode', array($this, "piano_shortcode")); 

    }

    function customer_menu_pages(){
      add_menu_page( 'Piano', 'Piano', 'manage_options', 'piano', array($this, 'customer_admin_page'), 'dashicons-format-audio', 0);
    }
    
    function piano_shortcode() {
      wp_enqueue_script('piano_js', OP_PIANO_PLUGIN_PATH."/main_script.js", ['jquery']);
      wp_localize_script('piano_js', 'owen_plugin_path', OP_PIANO_PLUGIN_PATH);
      wp_localize_script('piano_js', 'importhash', $_GET["song"]);
      wp_localize_script('piano_js', 'importremainder', $_GET["r"]);
      wp_enqueue_style('piano_css', OP_PIANO_PLUGIN_PATH."/main_style.css");
      $piano = file_get_contents("https://owenpalmer.com/wp-content/plugins/owen-plugin/content.html");
      return $piano;
    }
    
    function customer_admin_page() {
      
    }

  }
};

$GLOBALS['Owen_Plugin'] = new Owen_Plugin();

?>
