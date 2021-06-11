<?php

/**
 * Original author: Boxcar Studio
 *
 * Description: Theme as delivered to LTD Global on 4/16/2021.
 * Comment: theme functions seem to be missing the appropriate prefixes.
 *
 * https://docs.woocommerce.com/document/conditional-tags/
 */


// Setting a CONSTANT for the plugin dir path
define('THIS_THEME', get_stylesheet_directory());

require THIS_THEME . '/includes/formstack.php';





add_action( 'wp_enqueue_scripts', 'highscope_enqueue_styles', 1000);

// Redirect on login to Membership Account page
add_filter( 'login_redirect', 'highscope_on_login_redirect', 10, 3 );

add_action('login_form_register', 'custom_login_form_register');

// Remove error for username, only show error for email only.
add_filter('registration_errors', 'custom_registration_errors', 10, 3);

/** form stack **/
add_shortcode('formstack-keep-abreast', 'formstack_keep_abreast_function');
add_shortcode('formstack-annual-giving', 'formstack_annual_giving_function');
add_shortcode('formstack-tickets', 'formstack_tickets_function');
add_shortcode('formstack-sponsors', 'formstack_sponsors_function');
add_shortcode('formstack-auction-donations', 'formstack_auction_donations_function');

add_action('admin_head', 'myprefix_admin_js');

/* Using email address as username for signups */
add_action('login_head', 'custom_login_head');

// Ensure that Divi Builder framework is loaded - required for some post types when using Divi Builder plugin
add_filter('et_divi_role_editor_page', 'myprefix_load_builder_on_all_page_types');

add_action('login_head', 'custom_login_page');

add_action( 'wp', 'divi_child_theme_setup', 9999 );

/**
 * Change the breadcrumb separator
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );

/* WooCommerce image sizing */
add_theme_support( 'woocommerce', array('single_image_width' => 200) );
add_filter('et_builder_post_types', 'myprefix_add_post_types');
add_action('add_meta_boxes', 'myprefix_add_meta_boxes');



// https://developer.wordpress.org/reference/functions/wp_nav_menu/
// https://developer.wordpress.org/reference/functions/get_template_part/
register_nav_menus(
	array(
		'store-nav' => __('Store Nav', 'highscope')
	)
);








function highscope_on_login_redirect( $url, $query, $user ) {
	return '/membership-account';
}

function wcc_change_breadcrumb_delimiter( $defaults ) {
	$defaults['delimiter'] = '<span class="addSpace">&#9632;</span>';
	return $defaults;
}





// Changed to echo - jbernal, 2021-5-25
function custom_login_head() {
	
	$string = file_get_contents(THIS_THEME . '/includes/head.html');
	echo $string;
}



function custom_registration_errors($wp_error, $sanitized_user_login, $user_email){
    if(isset($wp_error->errors['empty_username'])){
        unset($wp_error->errors['empty_username']);
    }

    if(isset($wp_error->errors['username_exists'])){
        unset($wp_error->errors['username_exists']);
    }
    return $wp_error;
}



/* end of email as username */
function custom_login_form_register(){
    if(isset($_POST['user_login']) && isset($_POST['user_email']) && !empty($_POST['user_email'])){
        $_POST['user_login'] = $_POST['user_email'];
    }
}


/* Enable Divi Builder on all post types with an editor box */
function myprefix_add_post_types($post_types) {
	foreach(get_post_types() as $pt) {
		if (!in_array($pt, $post_types) and post_type_supports($pt, 'editor')) {
			$post_types[] = $pt;
		}
	} 
	return $post_types;
}


/* Add Divi Custom Post Settings box */
function myprefix_add_meta_boxes() {
	foreach(get_post_types() as $pt) {
		if (post_type_supports($pt, 'editor') and function_exists('et_single_settings_meta_box')) {
			add_meta_box('et_settings_meta_box', __('Divi Custom Post Settings', 'Divi'), 'et_single_settings_meta_box', $pt, 'side', 'high');
		}
	} 
}


/* Ensure Divi Builder appears in correct location */
function myprefix_admin_js() { 
	$s = get_current_screen();
	if(!empty($s->post_type) and $s->post_type!='page' and $s->post_type!='post')
	{ 
	
	}
}



function myprefix_load_builder_on_all_page_types($page) { 
	return isset($_GET['page'])?$_GET['page']:$page; 
}


function custom_login_page() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/login/custom-login-styles.css" />';
}

function divi_child_theme_setup() {
    if ( ! class_exists('ET_Builder_Module') ) {
        return;
    }
    get_template_part( 'custom-modules/cfwpm' );
    $cfwpm = new Custom_ET_Builder_Module_Login();
    remove_shortcode( 'et_pb_login' );   
    add_shortcode( 'et_pb_login', array($cfwpm, '_shortcode_callback') );
}



// Visual Guide to Checkout Page
// https://www.businessbloomer.com/woocommerce-visual-hook-guide-checkout-page/

// https://docs.woocommerce.com/document/disable-the-default-stylesheet/
// add_filter( 'woocommerce_enqueue_styles', '__return_false' );

function highscope_enqueue_styles() { 

	$basedir = get_stylesheet_directory_uri()."/styles";
	$woodir = get_stylesheet_directory_uri()."/woocommerce/styles";
	

	// Optional, *organized CSS files to be loaded.
	// Currently these are not being loaded and there
	// is a monolithic CSS file :/
	$theme = array(
		// 'home' => 'home.css',
		// 'main' => 'main.css',
		// 'structure' => 'structure.css',
		// 'sidebar' => 'sidebar.css',
		// 'header' => 'header.css',
		// 'menu' => 'menu.css',
		// 'menu-accordion' => 'menu-accordion.css',
		// 'post' => 'post.css',
		// 'footer' => 'footer.css',
		// 'overlay' => 'overlay.css'
	);
	
	$woocommerce = array(
		"highscope-cart" => "cart.css",
		"highscope-checkout" => "checkout.css"
	);
	
	
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

	$dependencies = array(
		"woocommerce-general",
		"woocommerce-layout",
		"woocommerce-smallscreen"
	);
	
	foreach($woocommerce as $id => $uri) {
		$path = $woodir . "/" . $uri;
		wp_enqueue_style($id, $path, $dependencies);
	}
}







