<?php
function my_theme_enqueue_styles() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


// Redirect on login to Membership Account page
add_filter( 'login_redirect', function( $url, $query, $user ) {
	return '/membership-account';
}, 10, 3 );

//Custom formstack shortcodes
//[formstack-keep-abreast]
function formstack_keep_abreast_function() {
     return '<script type="text/javascript" src="https://HighScope.formstack.com/forms/js.php/keep_abreast"></script><noscript><a href="https://HighScope.formstack.com/forms/keep_abreast" title="Online Form">Online Form - Keep Abreast of Anniversary Activities & Events</a></noscript><div style="text-align:right; font-size:x-small;"></div>';
}
add_shortcode('formstack-keep-abreast', 'formstack_keep_abreast_function');

//[formstack-annual-giving]
function formstack_annual_giving_function() {
     return '<script type="text/javascript" src="https://HighScope.formstack.com/forms/js.php/annual_giving"></script><noscript><a href="https://HighScope.formstack.com/forms/annual_giving" title="Online Form">Online Form - Annual Giving </a></noscript>';
}
add_shortcode('formstack-annual-giving', 'formstack_annual_giving_function');

//[formstack-tickets]
function formstack_tickets_function() {
     return '<script type="text/javascript" src="https://HighScope.formstack.com/forms/js.php/tickets"></script><noscript><a href="https://HighScope.formstack.com/forms/tickets" title="Online Form">Online Form - Gala Tickets Order Form</a></noscript><div style="text-align:right; font-size:x-small;"></div>';
}
add_shortcode('formstack-tickets', 'formstack_tickets_function');

//[formstack-sponsors]
function formstack_sponsors_function() {
     return '<script type="text/javascript" src="https://HighScope.formstack.com/forms/js.php/sponsors"></script><noscript><a href="https://HighScope.formstack.com/forms/sponsors" title="Online Form">Online Form - Gala Sponsorship Options</a></noscript><div style="text-align:right; font-size:x-small;"></div>';
}
add_shortcode('formstack-sponsors', 'formstack_sponsors_function');

//[formstack-auction-donations]
function formstack_auction_donations_function() {
     return '<script type="text/javascript" src="https://HighScope.formstack.com/forms/js.php/auction_donations"></script><noscript><a href="https://HighScope.formstack.com/forms/auction_donations" title="Online Form">Online Form - Gala Auction Donations</a></noscript><div style="text-align:right; font-size:x-small;"></div>';
}
add_shortcode('formstack-auction-donations', 'formstack_auction_donations_function');


/* Using email address as username for signups */
add_action('login_head', function(){
?>
    <style>
        #registerform > p:first-child{
            display:none;
        }
    </style>

    <script type="text/javascript" src="<?php echo site_url('/wp-includes/js/jquery/jquery.js'); ?>"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#registerform > p:first-child').css('display', 'none');
        });
    </script>
<?php
});
//Remove error for username, only show error for email only.
add_filter('registration_errors', function($wp_error, $sanitized_user_login, $user_email){
    if(isset($wp_error->errors['empty_username'])){
        unset($wp_error->errors['empty_username']);
    }

    if(isset($wp_error->errors['username_exists'])){
        unset($wp_error->errors['username_exists']);
    }
    return $wp_error;
}, 10, 3);
add_action('login_form_register', function(){
    if(isset($_POST['user_login']) && isset($_POST['user_email']) && !empty($_POST['user_email'])){
        $_POST['user_login'] = $_POST['user_email'];
    }
});

/* end of email as username */

/* woocommerce image sizing */
add_theme_support( 'woocommerce', array(
'single_image_width' => 200,
) );

/* end image sizing */


/* Enable Divi Builder on all post types with an editor box */
function myprefix_add_post_types($post_types) {
	foreach(get_post_types() as $pt) {
		if (!in_array($pt, $post_types) and post_type_supports($pt, 'editor')) {
			$post_types[] = $pt;
		}
	} 
	return $post_types;
}
add_filter('et_builder_post_types', 'myprefix_add_post_types');

/* Add Divi Custom Post Settings box */
function myprefix_add_meta_boxes() {
	foreach(get_post_types() as $pt) {
		if (post_type_supports($pt, 'editor') and function_exists('et_single_settings_meta_box')) {
			add_meta_box('et_settings_meta_box', __('Divi Custom Post Settings', 'Divi'), 'et_single_settings_meta_box', $pt, 'side', 'high');
		}
	} 
}
add_action('add_meta_boxes', 'myprefix_add_meta_boxes');

/* Ensure Divi Builder appears in correct location */
function myprefix_admin_js() { 
	$s = get_current_screen();
	if(!empty($s->post_type) and $s->post_type!='page' and $s->post_type!='post') { 
?>

<?php
	}
}
add_action('admin_head', 'myprefix_admin_js');

// Ensure that Divi Builder framework is loaded - required for some post types when using Divi Builder plugin
add_filter('et_divi_role_editor_page', 'myprefix_load_builder_on_all_page_types');
function myprefix_load_builder_on_all_page_types($page) { 
	return isset($_GET['page'])?$_GET['page']:$page; 
}


function custom_login_page() {
echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/login/custom-login-styles.css" />';
}
add_action('login_head', 'custom_login_page');


function divi_child_theme_setup() {
    if ( ! class_exists('ET_Builder_Module') ) {
        return;
    }
    get_template_part( 'custom-modules/cfwpm' );
    $cfwpm = new Custom_ET_Builder_Module_Login();
    remove_shortcode( 'et_pb_login' );   
    add_shortcode( 'et_pb_login', array($cfwpm, '_shortcode_callback') );
}
add_action( 'wp', 'divi_child_theme_setup', 9999 );


/**
 * Change the breadcrumb separator
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
function wcc_change_breadcrumb_delimiter( $defaults ) {
	$defaults['delimiter'] = '<span class="addSpace">&#9632;</span>';
	return $defaults;
}

?>
