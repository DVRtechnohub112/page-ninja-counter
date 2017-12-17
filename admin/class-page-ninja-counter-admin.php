<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/rishishah
 * @since      1.0.0
 *
 * @package    Page_Ninja_Counter
 * @subpackage Page_Ninja_Counter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Page_Ninja_Counter
 * @subpackage Page_Ninja_Counter/admin
 * @author     Rishi Shah <rishi41194@gmail.com>
 */
class Page_Ninja_Counter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Page_Ninja_Counter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Page_Ninja_Counter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/page-ninja-counter-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Page_Ninja_Counter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Page_Ninja_Counter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/page-ninja-counter-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create admin menu for setting page.
	 *
	 * @since    1.0.0
	 */
	public function pnc_setting_page() {
		add_menu_page( 'PNC Setting Page', 'PNC Setting Page', 'manage_options', 'pnc-setting-page.php', 'pnc_menu_setting_page', 'dashicons-admin-generic', 200  );

		function pnc_menu_setting_page() {
			?>

			<?php
		}
	}

	/**
	 * Insert count data to DB table.
	 *
	 * @since    1.0.0
	 */
	public function pnc_add_page_count(){

		global $post;

		$page_id = $post->ID; // Get page id.
		$user_agent = $_SERVER['HTTP_USER_AGENT']; // Get user agent.
		// Get User Agent of user.
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		$count = 1; // Set count to 1.
		// Get post publish date.
		$date = get_the_time( 'Y-m-d h-i-a', $page_id );
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://www.geoplugin.net/php.gp?ip=.".$ip_address,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"postman-token: 65407178-859e-2482-84e8-85a4f68a3b52"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		$final_data = unserialize($response );
	
		$country = $final_data['geoplugin_countryName'];
		$post_status = get_post_status( $page_id );
		
		if( $post_status === "publish" && !empty( $country ) ) {
			global $wpdb;
			$wpdb->insert('wp_pnc_count', array(
				'page_id' => $page_id,
				'count' => $count,
				'ip_address' => $ip_address,
				'user_agent' => $user_agent,
				'country_name' => $country,
				'status' => $post_status,
				'date' => $date,
				'modified_date' => $date,
			));
		}
	}
}
