<?php
/*
 * Plugin Name: Klesick Farms
 * Description: Contains Everything Klesick
 * Author: tobinfekkes, peterlama
 * Author URI: http://tobinfekkes.com
 * Version: 2.0
 * WC Tested up to: 3.8
 * WC requires at least: 3.7
*/
$var = 'hello';
$$var = 'hello world';

define( 'KF_PLUGIN_FILE', __FILE__ );
define( 'KF_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ));
define( 'KF_PLUGIN_URL', plugins_url( 'klesick-farms' ) );
define( 'KF_PLUGIN_NAME', 'klesick_farms' );
// define( 'KF_PLUGIN_URL', plugins_url( KF_PLUGIN_NAME ) );
// For template redirects
$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

// sad attempt at autoloader....gets files fine...but doesn't load them...?
// global $plugin_path;
// // Load files in /classes directory
// $classes = array_diff(scandir($plugin_path.'/classes'), array('..', '.'));
// $class_names = array();
// foreach ($classes as $class) {
// 	// Remove the ".php" from the end of the file name to create the class name
// 	$class_names[] = substr($class, 0, -4);
// 	// Require that class file name
// 	include_once('classes/'.$class);
// }

// global $plugin_path;
// global $class_names;
//
// foreach ($class_names as $class) {
// 	// Remove the "KF_" from the beginning of the file name, to create the class name
// 	// @TODO clean this up, for some reason the files are not included as classes when variable is used. Might just have to hard code class names
// 	$this_class = strtolower(substr($class, 3));
//     $this->{$this_class}    	= new $class();
// }

require_once('vendor/autoload.php');

if ( ! class_exists( 'Klesick_Farms' ) ) {
	// Instantiate the parent class
	class Klesick_Farms {
		public $prefix;
		public $prefix_l;

		public function __construct() {
			$this->prefix = "KF_";
			$this->prefix_l = strtolower($this->prefix);

			$this->includes();
			$this->init_hooks();

			$this->custom_post_types = array(
				"Menus" 			=> array("singular" => "Menu", 			"supports" 	=> array('title')),
				"Boxes" 			=> array("singular" => "Box", 			"supports" 	=> array('title', 'thumbnail', 'excerpt','page-attributes')),
				"Drivers" 			=> array("singular" => "Driver", 		"supports" 	=> array('title', 'thumbnail',)),
				"Routes" 			=> array("singular" => "Route", 		"supports" 	=> array('title')),
				"Vehicles" 			=> array("singular" => "Vehicle", 		"supports" 	=> array('title')),
				"Vendors" 			=> array("singular" => "Vendor", 		"supports" 	=> array('title', 'thumbnail', 'editor')),
				"Notifications" 	=> array("singular" => "Notification", 	"supports" 	=> array('title', 'thumbnail', 'editor', 'excerpt', 'revisions')),
				"Affiliates" 		=> array("singular" => "Affiliate", 	"supports" 	=> array('title', 'thumbnail', 'editor', 'excerpt')),
			);

			function r($content = '', $title = '', $log = true) {

				$response = print_r($content, 1);

				if ($log) {
					error_log($title . $response);
				}else {
					echo "<pre>".$title." ".$response."</pre>";
				}
			}
		}


		public function includes() {

			include_once('classes/KF_Account.php');
			include_once('classes/KF_Activity.php');
			include_once('classes/KF_Address.php');
			include_once('classes/KF_Affiliate.php');
			include_once('classes/KF_Ajax.php');
			include_once('classes/KF_Box.php');
			include_once('classes/KF_Cron.php');
			include_once('classes/KF_Checkout.php');
			include_once('classes/KF_Customer.php');
			include_once('classes/KF_Delivery.php');
			include_once('classes/KF_Driver.php');
			include_once('classes/KF_Install.php');
			include_once('classes/KF_Infusionsoft.php');
            include_once('classes/KF_Lock.php');
			include_once('classes/KF_Menu.php');
			include_once('classes/KF_Notes.php');
			include_once('classes/KF_Notifications.php');
			include_once('classes/KF_Payment.php');
			include_once('classes/KF_Product.php');
			include_once('classes/KF_Packing.php');
			include_once('classes/KF_Referral.php');
			include_once('classes/KF_Route.php');
			include_once('classes/KF_Settings.php');
			include_once('classes/KF_Twilio.php');
			include_once('classes/KF_Vehicle.php');
			include_once('classes/KF_Vendor.php');
			include_once('classes/KF_Workwave.php');
			include_once('classes/KF_Admin_Dashboard.php');
			include_once('classes/KF_Reports.php');
			include_once('classes/KF_Coupon.php');

			$this->account 		= new KF_Account();
			$this->activity 	= new KF_Activity();
			$this->address 		= new KF_Address();
			$this->affiliate	= new KF_Affiliate();
			$this->ajax 		= new KF_Ajax();
			$this->box 			= new KF_Box();
			$this->cron 		= new KF_Cron();
			$this->checkout 	= new KF_Checkout();
			$this->customer 	= new KF_Customer();
			$this->delivery 	= new KF_Delivery();
			$this->driver 		= new KF_Driver();
			$this->infusionsoft	= new KF_Infusionsoft();
			$this->install		= new KF_Install();
			$this->menu 		= new KF_Menu();
			$this->notes 		= new KF_Notes();
			$this->notifications= new KF_Notifications();
			$this->payment 		= new KF_Payment();
			$this->product 		= new KF_Product();
			$this->packing 		= new KF_Packing();
			$this->referral 	= new KF_Referral();
			$this->reports      = new KF_Reports();
			$this->route 		= new KF_Route();
			$this->settings		= new KF_Settings();
			$this->twilio 		= new KF_Twilio();
			$this->vehicle 		= new KF_Vehicle();
			$this->vendor 		= new KF_Vendor();
			$this->workwave 	= new KF_Workwave();
			$admin_dashboard	= new KF_Admin_Dashboard(); // no need to save the instance since everything is hooked on init
			$kf_coupon          = new KF_Coupon();
		}

		private function init_hooks() {
			// Register Install Script
			register_activation_hook( __FILE__, array('KF_Install', 'kf_create_tables' ) );

            // after wordpress is initialized
            add_action( 'init', array( $this, 'include_template_functions' ), 10 );

			// Register Custom Post Types
            add_action( 'init', array( $this, 'register_custom_post_types' ), 10 );

			// after woocommerce is loaded
			add_action( 'woocommerce_init', array( $this, 'woocommerce_loaded' ) );

            // load plugin settings
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Register admin menu items
			add_action( 'admin_menu', array( $this, 'customer_menu_pages') );

			KF_Product::init_hooks();
		}

		function register_custom_post_types() {

			foreach ($this->custom_post_types as $plural => $post_type_options) {
				// Clear array
				$labels = $args = array();
				// Singular
				$singular = $post_type_options['singular'];
				// Lowercase Post Type
				$post_type = 'kf_' . strtolower($singular);
				// Initiate Labels
				$labels = array(
					'name'               => _x( $plural, 'post type general name', $post_type ),
					'singular_name'      => _x( $singular, 'post type singular name', $post_type ),
					'menu_name'          => _x( $singular, 'admin '. $singular, $post_type ),
					'name_admin_bar'     => _x( $singular, 'add new on admin bar', $post_type ),
					'add_new'            => _x( 'Add New', $singular, $post_type ),
					'add_new_item'       => __( 'Add New '.$singular, $post_type ),
					'new_item'           => __( 'New '.$singular, $post_type ),
					'edit_item'          => __( 'Edit ' .$singular, $post_type ),
					'view_item'          => __( 'View '.$singular, $post_type ),
					'all_items'          => __( 'All '.$plural, $post_type ),
					'search_items'       => __( 'Search '.$plural, $post_type ),
					'parent_item_colon'  => __( 'Parent '.$plural, $post_type ),
					'not_found'          => __( 'No '.$plural.' found.', $post_type ),
					'not_found_in_trash' => __( 'No '.$plural.' found in Trash.', $post_type )
				);

				$args = array(
					// enable Gutenberg on custom post type
					// 'show_in_rest' 		 => true,
					'labels'             => $labels,
					'public'             => false,
					'publicly_queryable' => false,
					'show_ui'            => true,
					'show_in_menu'       => false,
					'query_var'          => true,
					'rewrite'            => array( 'slug' => $post_type ),
					'capability_type'    => 'page',
					'has_archive'        => true,
					'hierarchical'       => false,
					'menu_position'      => null,
					'supports'           => $post_type_options['supports'],
				);
				// Register Custom Post Type
				register_post_type( $post_type, $args );
			}
		}

		function customer_menu_pages() {
			// Parent Menu Item
			add_menu_page( 'Klesick Farms', 'Klesick Farms', 'manage_options', 'customer', 'customer_admin_page', 'dashicons-admin-users', 0);
			// Duplicate parent menu item for top-level submenu
			add_submenu_page('customer', 'Customers', 'Customers', 'manage_options', 'customer', 'customer_admin_page');
			add_submenu_page('customer', '','', 'manage_options', 'edit-customer', 'edit_customer_admin_page');
			// Add secondary submenus
			add_submenu_page('customer', 'Reports',  'Reports',  'manage_options', 'kf_reports',  'kf_admin_reports');
			add_submenu_page('customer', 'Packing',  'Packing',  'manage_options', 'kf_packing',  'kf_admin_packing');
			add_submenu_page('customer', 'Notes', 	 'Notes',    'manage_options', 'kf_notes',    'kf_admin_notes');
			add_submenu_page('customer', 'Settings', 'Settings', 'manage_options', 'kf_settings', 'kf_admin_settings');
			add_submenu_page('customer', 'Payments', 'Payments', 'manage_options', 'kf_payments', 'kf_admin_payments');

			foreach ($this->custom_post_types as $plural => $array) {
				$post_type = 'kf_' . strtolower($array['singular']);
				add_submenu_page('customer', $plural, $plural, 'manage_options', 'edit.php?post_type='.$post_type);
			}
		}

        public function woocommerce_loaded() {
			// This requires WooCommerce to be loaded, so it has to be here, rather than above with the other includes
			include_once('classes/KF_Order.php');
			$this->order = new KF_Order();

			/**
			 * Override WooCommerce Template Files
			 */
			function kf_woocommerce_locate_template( $template, $template_name, $template_path ) {

				$my_plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

			  	$_template = $template;
			  	if ( ! $template_path ) $template_path = WC()->template_url;
			  	$plugin_path  = $my_plugin_path . '/templates/';


			  	// Modification: Get the template from this plug-in, if it exists - THIS IS PRIORITY
			  	if ( file_exists( $plugin_path . $template_name ) )
			    	$template = $plugin_path . $template_name;

				// Look within passed path within the theme if template is not in plugin - this is second priority
				if (! file_exists( $plugin_path . $template_name ) )
			  	$template = locate_template(
				    array(
				      	$template_path . $template_name,
				      	$template_name
				    )
				);

			  	// Use default template
			  	if ( ! $template )
			    	$template = $_template;

			  	// Return what we found
			 	return $template;
			}

			add_filter( 'woocommerce_locate_template', 'kf_woocommerce_locate_template', 80, 3 );

			$this->includes = array(

		// load everywhere includes
				'all' => array(
					'klesick_js' => array(
						'type' => 'script',
						'location' => 'local',
						'source' => str_replace(ABSPATH, '/', plugin_dir_path( __FILE__ ). 'js/scripts.js'),
						'deps' => array('jquery')
					),
					'klesick_css' => array(
						'type' => 'style',
						'location' => 'local',
						'source' => str_replace(ABSPATH, '/', plugin_dir_path( __FILE__ ).'css/klesick.css'),
					),
					'admin_klesick_css' => array(
						'type' => 'style',
						'location' => 'local',
						'source' => str_replace(ABSPATH, '/', plugin_dir_path( __FILE__ ).'admin/css/admin.css'),
					),
					'selectWoo' => array(
						'type' => 'script',
						'location' => 'local',
						// 'source' => WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js',
						'source' => str_replace(ABSPATH, '/', WC()->plugin_path() . '/assets/js/selectWoo/selectWoo.full.min.js'),
						'deps' => array('jquery')
					),
					'google_maps_js' => array(
						'type' => 'script',
						'location' => 'remote',
						'source' => str_replace(ABSPATH, '/', sprintf('//maps.googleapis.com/maps/api/js?key=%s&libraries=%s', $this->settings->settings['google_live_key'], 'geometry,places,drawing')),
						'deps' => array('jquery')
					),
				),

		// frontend includes
				'frontend' => array(
					'scroll_to' => array(
						'type' => 'script',
						'location' => 'local',
						'source' => str_replace(ABSPATH, '/', plugin_dir_path( __FILE__ ). 'js/jquery.scrollTo.min.js'),
						'deps' => array('jquery')
					),
				),
		// admin includes
				'backend' => array(
					'datatable_css_combined' => array(
						'type' => 'style',
						'location' => 'remote',
						'source' => '//cdn.datatables.net/v/dt/dt-1.10.16/b-1.5.1/kt-2.3.2/r-2.2.1/rr-1.2.3/sl-1.2.5/datatables.min.css',
					),
					'datatable_js_combined' => array(
						'type' => 'script',
						'location' => 'remote',
						'source' => '//cdn.datatables.net/v/dt/dt-1.10.16/b-1.5.1/kt-2.3.2/r-2.2.1/rr-1.2.3/sl-1.2.5/datatables.min.js',
						'deps' => array('jquery')
					),
					'datatable_editor_js' => array(
						'type' => 'script',
						'location' => 'local',
						'source' => str_replace(ABSPATH, '/', plugin_dir_path( __FILE__ ).'editor/js/dataTables.editor.min.js'),
						'deps' => array('jquery')
					),
				)
			);

			function kf_enqueue($handle, $include) {
				// Use local file source to check last_modified date to use as version number for cache-busting
				$version = ($include['location'] == 'local' ) ? date("ymd-H.i.s", filemtime( str_replace('//', '/', ABSPATH .$include['source']))) : NULL;
				if ($include['type'] == 'script') {
					wp_enqueue_script($handle, $include['source'], $include['deps'] ?? array(), $version );
				}else {
					wp_enqueue_style($handle, $include['source'], $include['deps'] ?? array(), $version );
				}
			}

			// Enqueue scripts/styles for both front and backend
			foreach ($this->includes['all'] as $handle => $include) {
				kf_enqueue( $handle, $include );
			}

			// Enqueue scripts/styles for frontend
            add_action( 'wp_enqueue_scripts', function() {
				wp_enqueue_style( 'dashicons' );
				foreach ($this->includes['frontend'] as $handle => $include) {
					kf_enqueue( $handle, $include );
				}
			});

			// Enqueue scripts/styles for admin
            add_action( 'admin_enqueue_scripts', function() {
				foreach ($this->includes['backend'] as $handle => $include) {
					kf_enqueue( $handle, $include );
				}
			});

			wp_localize_script(
                'klesick_js',
                KF_PLUGIN_NAME.'_url',
                KF_PLUGIN_URL
            );
        }

		public function include_template_functions() {

			register_post_status( 'wc-orphan', array(
				'label'                     => 'Orphan',
				'public'                    => false,
				'internal'					=> true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => false,
				'show_in_admin_status_list' => false,
				)
			);
			register_post_status( 'wc-draft', array(
				'label'                     => 'Draft',
				'public'                    => true,
				'internal'					=> true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => false,
				'show_in_admin_status_list' => false,
				)
			);

			register_post_status( 'wc-temp', array(
				'label'                     => 'Temp',
				'public'                    => true,
				'internal'					=> true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => false,
				'show_in_admin_status_list' => false,
				)
			);

			register_post_status( 'wc-routed', array(
				'label'                     => 'Routed',
				'public'                    => true,
				'internal'					=> true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Routed <span class="count">(%s)</span>', 'Routed <span class="count">(%s)</span>' )
				)
			);
			register_post_status( 'wc-delivered', array(
				'label'                     => 'Delivered',
				'public'                    => false,
				'internal'					=> true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Delivered <span class="count">(%s)</span>', 'Delivered <span class="count">(%s)</span>' )
				)
			);

			require_once( 'kf-template.php' );
			require_once( 'kf-utilities.php' );
			require_once( 'kf-shortcodes.php' );
		}

		public function register_settings() {
			register_setting('kf_settings_group', 'kf_settings');
		}
    }
}

$GLOBALS['Klesick_Farms'] = new Klesick_Farms();

?>