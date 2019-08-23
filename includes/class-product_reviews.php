<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       remlost.eu/animals
 * @since      1.0.0
 *
 * @package    Product_reviews
 * @subpackage Product_reviews/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Product_reviews
 * @subpackage Product_reviews/includes
 * @author     Tillmann Weimer <tillmann1970@gmail.com>
 */
class Product_reviews {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Product_reviews_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PRODUCT_REVIEWS_VERSION' ) ) {
			$this->version = PRODUCT_REVIEWS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'product_reviews';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->register_filter_the_content();

		$this->add_cpt();
		$this->add_ct();

		$this->init_acf();


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Product_reviews_Loader. Orchestrates the hooks of the plugin.
	 * - Product_reviews_i18n. Defines internationalization functionality.
	 * - Product_reviews_Admin. Defines all hooks for the admin area.
	 * - Product_reviews_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product_reviews-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product_reviews-i18n.php';

		/**
		 * Including ACF
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/acf/acf.php';
		
		/**
		 * Including CPT
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . //'includes/cpt/';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-product_reviews-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-product_reviews-public.php';

		$this->loader = new Product_reviews_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Product_reviews_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Product_reviews_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Product_reviews_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Product_reviews_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Product_reviews_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register a function to filter the content
	 */
	public function register_filter_the_content() {
		add_filter('the_content', [$this, 'filter_the_content']);
	}

	/**
	 * Functon for filtering the content
	 */
	public function filter_the_content($content) {
		//if posttype is pr_product
		if(get_post_type() === 'pr_product') {
			
			//find taxonomy pr_product_type for current product
			$products = get_the_term_list(get_the_ID(), 'pr_product_type', 'Product types: ', '.');

			//Append div with terms if any
			//var_dump($products);
			$content .= '<div class="pr-product-type">' . $products . '</div>';

			if(function_exists('get_field')) {
				$price = get_field('price');

				$content .= '<div class="product-details">';
				$content .= '<h1><strong></strong>' . __('Product Info ', 'product_reviews') . '</strong></h1>';
				$content .= '<p>' . __('Price: ', 'product_reviews') . $price . '</p>';
				//show only if there are submittet data
				if($products !== false) {
					$content .= '<span class="products">' . __('Products: ', 'product_reviews') . '</span' . $products .= '<br>';
				}
			}
			//return the modified content
			return $content;

		}
		
		if(get_post_type() === 'pr_review') {
			
			//Append div with terms if any
			$content .= '<div class="pr-review">' . $reviews . '</div>';

			if(function_exists('get_field')) {
				$rating = get_field('rating');
				$product_name = get_the_title();

				$content .= '<div class="product-details">';
				$content .= '<h1><strong></strong>' . __('Review Info ', 'product_reviews') . '</strong></h1>';
				$content .= '<p>' . __('Rating: ', 'product_reviews') . $rating . '</p>';
				$content .= '<p>' . __('Product: ', 'product_reviews') . $product_name . '</p>';
				//show only if there are submittet data
				// if($products !== false) {
				// 	$content .= '<span class="products">' . __('Products: ', 'product_reviews') . '</span' . $products .= '<br>';
				// }
			}
			//return the modified content
			return $content;

		}
		//return the unmodified content
		return $content;
	}

	/**
	 * Add functions to be run through the init hook
	 */
	public function add_cpt() {
		//Add hook for registration of CPT
		add_action('init', [$this, 'register_cpts']);
	}

	public function add_ct() { 
		//Add hook for registration of CT
		add_action('init', [$this, 'register_cts']);
		//Add hook for registration of ACF

	}

	public function register_cpts() {
		
		/**
		 * Post Type: Products.
		 */

		$labels = array(
			"name" => __( "Products", "twentysixteen" ),
			"singular_name" => __( "Product", "twentysixteen" ),
		);

		$args = array(
			"label" => __( "Products", "twentysixteen" ),
			"labels" => $labels,
			"description" => "",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"delete_with_user" => false,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"rewrite" => array( "slug" => "pr_product", "with_front" => 	true ),
			"query_var" => true,
			"menu_icon" => "dashicons-products",
			"supports" => array( "title", "editor", "thumbnail", "excerpt",	 "custom-fields" ),
			"taxonomies" => array( "pr_product_type" ),
		);

		register_post_type( "pr_product", $args );
	

	//add_action( 'init', 'cptui_register_my_cpts_pr_product' );

		//Post type Product Review

		//function cptui_register_my_cpts_pr_review() {

	/**
	 * Post Type: Reviews.
	 */

	$labels = array(
		"name" => __( "Reviews", "twentysixteen" ),
		"singular_name" => __( "Review", "twentysixteen" ),
	);

		$args = array(
			"label" => __( "Reviews", "twentysixteen" ),
			"labels" => $labels,
			"description" => "",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"delete_with_user" => false,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"rewrite" => array( "slug" => "pr_review", "with_front" => 	true ),
			"query_var" => true,
			"menu_icon" => "dashicons-welcome-write-blog",
			"supports" => array( "title", "editor", "thumbnail" ),
		);

		register_post_type( "pr_review", $args );
	//}

	add_action( 'init', 'cptui_register_my_cpts_pr_review' );


	}
	

	public function init_acf(){
			//Add filter to fix ACF assests URL
			add_filter('acf/settings/url', function(){
				return plugin_dir_url(__FILE__) . 'acf/';
			});

		//Add field group Product Details

		if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5d5faa8248d5b',
			'title' => 'Product Details',
			'fields' => array(
				array(
					'key' => 'field_5d5faa9452d27',
					'label' => 'Price',
					'name' => 'price',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '$',
					'min' => '',
					'max' => '',
					'step' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'pr_product',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));

		endif;

		//Add field group Review Details

		if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5d5fabd15f6ad',
			'title' => 'Review Details',
			'fields' => array(
				array(
					'key' => 'field_5d5fabe578fa1',
					'label' => 'Rating',
					'name' => 'rating',
					'type' => 'range',
					'instructions' => 'Here you can give a rating to a 	product',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'min' => '',
					'max' => 5,
					'step' => '',
					'prepend' => '',
					'append' => 'points',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'pr_review',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			));

	endif;


	
	}

	//Register Custom Taxonomies
	public function register_cts() {

		/**
		 * Taxonomy: Product types.
		 */

		$labels = array(
			"name" => __( "Product types", "twentysixteen" ),
			"singular_name" => __( "Product type", "twentysixteen" ),
		);

		$args = array(
			"label" => __( "Product types", "twentysixteen" ),
			"labels" => $labels,
			"public" => true,
			"publicly_queryable" => true,
			"hierarchical" => true,
			"show_ui" => true,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"query_var" => true,
			"rewrite" => array( 'slug' => 'pr_product_type', 'with_front' 	=> true, ),
			"show_admin_column" => false,
			"show_in_rest" => true,
			"rest_base" => "pr_product_type",
			"rest_controller_class" => "WP_REST_Terms_Controller",
			"show_in_quick_edit" => false,
			);
		register_taxonomy( "pr_product_type", array( "pr_product" ), $args 	);

add_action( 'init', 'cptui_register_my_taxes_pr_product_type' );

	}

}