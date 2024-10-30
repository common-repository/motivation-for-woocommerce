<?php
/**
 * Plugin Name: Motivation for WooCommerce
 * Plugin URI:  https://wordpress.org/plugins/woo-motivation
 * Description: Motivate your customers for desired actions with combination of advanced notices, discounts and gifts.
 * Author: Ivan Chernyakov
 * Author URI: https://businessupwebsite.com
 * Version: 1.0.0
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: woo-motivation
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main Class
 */
if ( ! class_exists( 'Woo_Motivation' ) ) :

	class Woo_Motivation {

		/**
		 * Instance of this class.
		 */
		protected static $instance = null;

		public function __construct() {			
			if ( is_admin()){
				add_action( 'init', array( $this, 'register_motivation_post_type' ) );	
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );	
			}
			//Notices
			add_action( 'woocommerce_init',  array( $this, 'load_cart_notices' ), 10, 1); 
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Check WooCommerce activation
			add_action( 'admin_init', array( $this, 'woo_motivation_woocommerce_active' ) );
		}


		/**
		 * Check WooCommerce activation function
		 */
		public function woo_motivation_woocommerce_active() {
			if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				add_action( 'admin_notices', array( $this, 'woo_motivation_error' ) );

				deactivate_plugins( plugin_basename( __FILE__ ) ); 

				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}
		}

		/**
		 * Error activation
		 */
		public function woo_motivation_error(){
			?>
			<div class="error"><p>Sorry, but <b>WooCommerce Motivation</b> requires <b>WooCommerce</b> to be installed and active.</p></div>
			<?php
		}

		/**
		 * Include scripts and styles.
		 */ 
		public function enqueue_scripts() {
			if ( is_checkout() || is_cart() ) {
				wp_enqueue_style( 'motivation-update-css', plugin_dir_url( __FILE__ ) . 'assets/css/main.css', false );
			}
		}

		/**
		 * Include admin scripts and styles.
		 */ 
		public function enqueue_admin_scripts() {
				wp_enqueue_script( 'motivation-admin-js', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', false );
				wp_enqueue_style( 'motivation-admin-css', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', false );
		}

		/**
		 * Return an instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
	     * Motivation Post type.
	     */
		public function register_motivation_post_type() {
			include_once( 'admin/woo-class-motivation-post-type.php' );
		}

		/**
	     * Output the Order review table for the checkout.
	     */
		public function load_cart_notices() {
			include_once( 'includes/woo-class-motivation-advanced-notices.php' );		
		}
	}

	/**
	 * Install plugin default options.
	 */
	add_action( 'plugins_loaded', array('Woo_Motivation', 'get_instance'));

endif;